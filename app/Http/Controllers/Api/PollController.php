<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePollRequest;
use App\Http\Requests\StoreVoteRequest;
use App\Http\Resources\PollResource;
use App\Http\Resources\PollResultsResource;
use App\Http\Traits\ApiResponses;
use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PollController extends Controller
{
    use ApiResponses;


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePollRequest $request): JsonResponse
    {
        $data = $request->validated();

        
        $expiresAt = Carbon::parse($data['expires_at'])->setTimezone(config('app.timezone'));

        
        $poll = DB::transaction(function () use ($request, $data, $expiresAt) {
            $poll = $request->user()->polls()->create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'slug' => $this->generateUniqueSlug(),
                'expires_at' => $expiresAt,
            ]);

            foreach ($data['options'] as $text) {
                $poll->options()->create(['text' => $text]);
            }

            return $poll;
        });

        $poll->load('options');

        return $this->success('Poll created.', new PollResource($poll), 201);
    }

    private function generateUniqueSlug(): string
    {
        do {
            $slug = Str::lower(Str::random(8));
        } while (Poll::where('slug', $slug)->exists());

        return $slug;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug): JsonResponse
    {
        $poll = Poll::where('slug', $slug)
            ->with(['options' => function ($query) {
                $query->withCount('votes');
            }])
            ->first();

        if (! $poll) {
            return $this->error('Poll not found.', null, 404);
        }

        return $this->success('Poll retrieved.', new PollResultsResource($poll));
    }

    public function vote(StoreVoteRequest $request, string $slug): JsonResponse
    {
        $poll = Poll::where('slug', $slug)->with('options')->first();

        if (! $poll) {
            return $this->error('Poll not found.', null, 404);
        }

        if ($poll->isExpired()) {
            return $this->error('This poll has closed and is no longer accepting votes.', null, 403);
        }

        $optionId = $request->validated()['poll_option_id'];
        $optionBelongsToPoll = $poll->options->contains('id', $optionId);

        if (! $optionBelongsToPoll) {
            return $this->error('That option does not belong to this poll.', null, 422);
        }

        $voterKey = $request->user()
            ? 'user:'.$request->user()->id
            : 'guest:'.hash('sha256', $request->ip().'|'.$request->userAgent());

        try {
            Vote::create([
                'poll_id' => $poll->id,
                'poll_option_id' => $optionId,
                'user_id' => $request->user()?->id,
                'voter_key' => $voterKey,
                'ip_address' => $request->ip(),
            ]);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return $this->error('You have already voted on this poll.', null, 409);
            }

            throw $e;
        }

        $poll = Poll::where('slug', $slug)
            ->with(['options' => function ($query) {
                $query->withCount('votes');
            }])
            ->first();

        return $this->success('Vote recorded.', new PollResultsResource($poll), 201);
    }

    public function stream(string $slug): StreamedResponse
    {
        $poll = Poll::where('slug', $slug)->first();

        if (! $poll) {
            abort(404, 'Poll not found.');
        }

        return response()->stream(function () use ($poll) {
            set_time_limit(0);
            ignore_user_abort(false);

            $lastVoteCount = null;

            while (true) {
                if (connection_aborted()) {
                    break;
                }

                $freshPoll = Poll::where('slug', $poll->slug)
                    ->with(['options' => function ($query) {
                        $query->withCount('votes');
                    }])
                    ->first();

                $currentVoteCount = $freshPoll->options->sum('votes_count');

                if ($currentVoteCount !== $lastVoteCount) {
                    $payload = (new PollResultsResource($freshPoll))->response()->getData(true);

                    echo "event: results\n";
                    echo 'data: '.json_encode($payload['data'])."\n\n";

                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();

                    $lastVoteCount = $currentVoteCount;
                }

                if ($freshPoll->isExpired()) {
                    break;
                }

                sleep(2); 
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }


}
