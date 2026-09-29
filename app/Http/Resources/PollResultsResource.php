<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PollResultsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        
        $totalVotes = $this->options->sum('votes_count');
        $isExpired = $this->resource->isExpired();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'slug' => $this->slug,
            'expires_at' => $this->expires_at->toIso8601String(),
            'is_expired' => $isExpired,
            'status' => $isExpired ? 'closed' : 'open',
            'seconds_remaining' => $isExpired ? 0 : (int) Carbon::now()->diffInSeconds($this->expires_at),
            'total_votes' => $totalVotes,
            'options' => $this->options->map(function ($option) use ($totalVotes) {
                return [
                    'id' => $option->id,
                    'text' => $option->text,
                    'votes' => $option->votes_count,
                    'percentage' => $totalVotes > 0
                        ? round(($option->votes_count / $totalVotes) * 100, 1)
                        : 0.0,
                ];
            }),
        ];
    }
}