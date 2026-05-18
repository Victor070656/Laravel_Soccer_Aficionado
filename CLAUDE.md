- Exception - Internal Server Error

Flux component [icon.] does not exist.

PHP 8.4.11
Laravel 12.51.0
127.0.0.1:8000

## Stack Trace

0 - resources/views/flux/icon/index.blade.php:12
1 - vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php:123
2 - vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php:124
3 - vendor/laravel/framework/src/Illuminate/View/Engines/PhpEngine.php:57
4 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:22
5 - vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php:76
6 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:10
7 - vendor/laravel/framework/src/Illuminate/View/View.php:208
8 - vendor/laravel/framework/src/Illuminate/View/View.php:191
9 - vendor/laravel/framework/src/Illuminate/View/View.php:160
10 - vendor/laravel/framework/src/Illuminate/View/Concerns/ManagesComponents.php:103
11 - resources/views/components/bottom-nav.blade.php:8
12 - vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php:123
13 - vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php:124
14 - vendor/laravel/framework/src/Illuminate/View/Engines/PhpEngine.php:57
15 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:22
16 - vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php:76
17 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:10
18 - vendor/laravel/framework/src/Illuminate/View/View.php:208
19 - vendor/laravel/framework/src/Illuminate/View/View.php:191
20 - vendor/laravel/framework/src/Illuminate/View/View.php:160
21 - vendor/laravel/framework/src/Illuminate/View/Concerns/ManagesComponents.php:103
22 - resources/views/layouts/app/sidebar.blade.php:168
23 - vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php:123
24 - vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php:124
25 - vendor/laravel/framework/src/Illuminate/View/Engines/PhpEngine.php:57
26 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:22
27 - vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php:76
28 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:10
29 - vendor/laravel/framework/src/Illuminate/View/View.php:208
30 - vendor/laravel/framework/src/Illuminate/View/View.php:191
31 - vendor/laravel/framework/src/Illuminate/View/View.php:160
32 - vendor/laravel/framework/src/Illuminate/View/Concerns/ManagesComponents.php:103
33 - resources/views/layouts/app.blade.php:3
34 - vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php:123
35 - vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php:124
36 - vendor/laravel/framework/src/Illuminate/View/Engines/PhpEngine.php:57
37 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:22
38 - vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php:76
39 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:10
40 - vendor/laravel/framework/src/Illuminate/View/View.php:208
41 - vendor/laravel/framework/src/Illuminate/View/View.php:191
42 - vendor/laravel/framework/src/Illuminate/View/View.php:160
43 - vendor/laravel/framework/src/Illuminate/View/Concerns/ManagesComponents.php:103
44 - resources/views/dashboard.blade.php:212
45 - vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php:123
46 - vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php:124
47 - vendor/laravel/framework/src/Illuminate/View/Engines/PhpEngine.php:57
48 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:22
49 - vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php:76
50 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:10
51 - vendor/laravel/framework/src/Illuminate/View/View.php:208
52 - vendor/laravel/framework/src/Illuminate/View/View.php:191
53 - vendor/laravel/framework/src/Illuminate/View/View.php:160
54 - vendor/laravel/framework/src/Illuminate/Http/Response.php:78
55 - vendor/laravel/framework/src/Illuminate/Http/Response.php:34
56 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:939
57 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:906
58 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:821
59 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
60 - vendor/laravel/framework/src/Illuminate/Auth/Middleware/EnsureEmailIsVerified.php:41
61 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
62 - app/Http/Middleware/EnsureUserIsNotBanned.php:35
63 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
64 - vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php:50
65 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
66 - vendor/laravel/framework/src/Illuminate/Auth/Middleware/Authenticate.php:63
67 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
68 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/VerifyCsrfToken.php:87
69 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
70 - vendor/laravel/framework/src/Illuminate/View/Middleware/ShareErrorsFromSession.php:48
71 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
72 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:120
73 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:63
74 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
75 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/AddQueuedCookiesToResponse.php:36
76 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
77 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/EncryptCookies.php:74
78 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
79 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
80 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:821
81 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:800
82 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:764
83 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:753
84 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:200
85 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
86 - vendor/livewire/livewire/src/Features/SupportDisablingBackButtonCache/DisableBackButtonCacheMiddleware.php:19
87 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
88 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php:21
89 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php:31
90 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
91 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php:21
92 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php:51
93 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
94 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePostSize.php:27
95 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
96 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php:109
97 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
98 - vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php:61
99 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
100 - vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php:58
101 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
102 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/InvokeDeferredCallbacks.php:22
103 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
104 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePathEncoding.php:26
105 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
106 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
107 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:175
108 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:144
109 - vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1220
110 - public/index.php:20
111 - vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php:23

## Request

GET /dashboard

## Headers

* **host**: 127.0.0.1:8000
* **connection**: keep-alive
* **pragma**: no-cache
* **cache-control**: no-cache
* **sec-ch-ua**: "Chromium";v="146", "Not-A.Brand";v="24", "Google Chrome";v="146"
* **sec-ch-ua-mobile**: ?0
* **sec-ch-ua-platform**: "Linux"
* **upgrade-insecure-requests**: 1
* **user-agent**: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
* **sec-fetch-site**: same-origin
* **sec-fetch-mode**: navigate
* **sec-fetch-user**: ?1
* **sec-fetch-dest**: document
* **referer**: http://127.0.0.1:8000/register
* **accept-encoding**: gzip, deflate, br, zstd
* **accept-language**: en-US,en;q=0.9
* **cookie**: XSRF-TOKEN=eyJpdiI6InExVXJ4VUtWV1lodjdqTzlsWWpld3c9PSIsInZhbHVlIjoieGx3RThleGVzVUM2K3Y4bUU1V0R1MVY5R3VTd2JvWkxEMnEvR25STWNEVUt2Z3pkTnY0K0VOUEtiWXJEM3lzVGtFeU83Tmw3VExQdmFuVEdOUGFiRFZxLy9BYVYzckVJT3VwQlZZdDV3NFBwWDJhMzU5VzhJTi9UdGdjYlpyeWQiLCJtYWMiOiI2ZGI4ZWU0ZGVjMWI2NTk2YjRlMWMxZDdjN2RmNjRiMjZiZjZjN2ViODg2NTYwNmE1ZWQ2YWVhMjg2NTQ4NWMwIiwidGFnIjoiIn0%3D; laravel-session=eyJpdiI6IjFodVRBYklBN0w2MnVBMnRWQUc4NXc9PSIsInZhbHVlIjoiSjhXaUMvREtxTHpMQjhvcWdQQmlrVEkxUkxGbGhzQ2RLRGRzN09UdWx0VG9kM2xoU0ZqbXZJaDRKNi9xMSsrUUVKdFBNczFoekJsOEpsRUMwRTc0Z2piYVZSN09uOGNiUjI3UENZRjlwS1h4SndCSzlnUnkwTkdCUmt5Rld4UXMiLCJtYWMiOiJjZjE4YThkZWEyNzQyZTE1NmEyYTZhNzFkNjVhYmI2NmM0ZDgyZTkyODBjODQ3NjYwZWY4ZjJiNTJjMTFhOWI5IiwidGFnIjoiIn0%3D

## Route Context

controller: App\Http\Controllers\DashboardController
route name: dashboard
middleware: web, auth, verified

## Route Parameters

No route parameter data available.

## Database Queries

* mysql - select * from `sessions` where `id` = 'vHftzFQZNV0yY76SyG9u29DmncDn8Ftf3JdoG7If' limit 1 (2.78 ms)
* mysql - select * from `users` where `id` = 2 limit 1 (1.41 ms)
* mysql - select `clubs`.*, `club_user`.`user_id` as `pivot_user_id`, `club_user`.`club_id` as `pivot_club_id`, `club_user`.`is_primary` as `pivot_is_primary`, `club_user`.`created_at` as `pivot_created_at`, `club_user`.`updated_at` as `pivot_updated_at` from `clubs` inner join `club_user` on `clubs`.`id` = `club_user`.`club_id` where `club_user`.`user_id` in (2) (1.47 ms)
* mysql - select count(*) as aggregate from `posts` where `is_approved` = 1 and `is_approved` = 1 and (`user_id` in (select `users`.`id` from `users` inner join `follows` on `users`.`id` = `follows`.`following_id` where `follows`.`follower_id` = 2) or `user_id` = 2) and `posts`.`deleted_at` is null (1.55 ms)
* mysql - select * from `polls` where `is_active` = 1 and (`closes_at` is null or `closes_at` > '2026-05-14 09:27:09') order by `created_at` desc limit 3 (1.36 ms)
* mysql - select `posts`.*, (select count(*) from `likes` where `posts`.`id` = `likes`.`likeable_id` and `likes`.`likeable_type` = 'App\Models\Post') as `likes_count`, (select count(*) from `comments` where `posts`.`id` = `comments`.`post_id` and `parent_id` is null and `comments`.`deleted_at` is null) as `comments_count`, (select count(*) from `shares` where `posts`.`id` = `shares`.`post_id`) as `shares_count` from `posts` where `is_approved` = 1 and `created_at` >= '2026-05-07 09:27:09' and `posts`.`deleted_at` is null order by `likes_count` desc, `comments_count` desc limit 5 (2.5 ms)
* mysql - select count(*) as aggregate from `badges` inner join `badge_user` on `badges`.`id` = `badge_user`.`badge_id` where `badge_user`.`user_id` = 2 (1.4 ms)
* mysql - select * from `ads` where `is_active` = 1 and (`starts_at` is null or `starts_at` <= '2026-05-14 09:27:09') and (`ends_at` is null or `ends_at` > '2026-05-14 09:27:09') and `placement` = 'sidebar' order by RAND() limit 1 (1.83 ms)
* mysql - select exists(select * from `roles` inner join `role_user` on `roles`.`id` = `role_user`.`role_id` where `role_user`.`user_id` = 2 and `slug` = 'admin') as `exists` (1.23 ms)
* mysql - select * from `cache` where `key` in ('laravel-cache-tsdb:live') (1.17 ms)