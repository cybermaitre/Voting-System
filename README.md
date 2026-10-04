<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

API Documentation:

User Onboarding:
1. User registration: http://127.0.0.1:8000/api/register (Post)
Request=>{
  "name": "",
  "email": "",
  "password": ""
}
Response=>{
  "success": true,
  "message": "Account created.",
  "data": {
    "user": { "user details" },
    "token": ""
  }
}
2. User Login: http://127.0.0.1:8000/api/login (Post)
Request=>{
  "email": "",
  "password": ""
}
Response=>{
  "success": true,
  "message": "Logged In",
  "data": {
    "user": { "user details" },
    "token": ""
  }
}
3.  User Logout: http://127.0.0.1:8000/api/logout (Post)
Request=>{token}
Response=>{ "success": true, "message": "Logged out." }

Poll:
4. Create poll: http://127.0.0.1:8000/api/polls (Post)
Request=>{
    "title":"Presidential Vote",
    "description":"Vote for your 2027 president",
    "expires_at": "2026-09-28 12:00:00",
    "options":[
        "Tinubu", "Obi" ,"Makinde" , "Atiku" , "Abiola"
    ]
}
Response=>{
  "success": true,
  "message": "Poll created.",
  "data": {
    "id": 1,
    "title": "Presidential Vote",
    "description": "Vote for your 2027 president",
    "slug": "k3x9a2mq",
    "share_url": "http://127.0.0.1:8000/api/polls/k3x9a2mq",
    "expires_at": "",
    "created_at": "",
    "options": [
      { "id": 1, "text": "Tinubu" },
      { "id": 2, "text": "Obi" },
      { "id": 3, "text": "Makinde" },
      { "id": 4, "text": "Atiku" },
      {"id": 5, "text": "Abiola" }
    ]
  }
}

Votes:
5. Voting http://127.0.0.1:8000/api/polls/{slug}/vote (Post)
Request=>{
"poll_option_id": 
}
Response=>{
  "success": true,
  "message": "Poll retrieved.",
  "data": {
    "id": 1,
    "title": "",
    "description": "",
    "slug": {slug},
    "expires_at": "",
    "is_expired": false,
    "status": "open",
    "seconds_remaining": ,
    "total_votes": ,
    "options": [
      { "id": 1, "text": "", "votes": , "percentage":  },
      { "id": 2, "text": "", "votes": , "percentage":  },
    ]
  }
}
6. Checking vote: http://127.0.0.1:8000/api/polls/{slug}
Request=>{}
Response=>{"success": true,
  "message": "Poll retrieved.",
  "data": {
    "id": 1,
    "title": "",
    "description": "",
    "slug": {slug},
    "expires_at": "",
    "is_expired": false,
    "status": "open",
    "seconds_remaining": ,
    "total_votes": ,
    "options": [
      { "id": 1, "text": "", "votes": , "percentage":  },
      { "id": 2, "text": "", "votes": , "percentage":  },
    ]
  }
}
7. Live stream: http://127.0.0.1:8000/api/polls/{slug}/stream
Request=>{}
Response=>{Vote details}



## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
