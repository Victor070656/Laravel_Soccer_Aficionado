# Product Architecture - Soccer Aficionado

## Overview
Soccer Aficionado is a premium football-first social ecosystem built as a "digital stadium for football fans."

## Core Architecture Layers

### 1. Presentation Layer (UI/UX)
- **Laravel + Livewire**: Server-side rendered components with real-time capabilities
- **Tailwind CSS v4**: Custom `@theme` block with Digital Stadium design system
- **Flux UI**: Component library for consistent UI elements
- **Glassmorphism**: 15-20% opacity overlays, blur effects, Pitch Green (#00ff41) glows

### 2. Application Layer
- **Controllers**: Handle HTTP requests, API integrations (FootballApiService)
- **Livewire Components**: Real-time interactive features (polling + WebSocket ready)
- **Services**: GamificationService, NotificationService, FootballApiService

### 3. Data Layer
- **Models**: User, Post, Community, Club, Player, MatchComment, MatchAction, Badge
- **Migrations**: Track all schema changes for football identity fields, match interactions
- **Pivot Tables**: User-Club (favorites), User-Community (membership), User-Badge (achievements)

## Feature Modules

### Profile System (Heart of Platform)
- Football identity card with club badge overlay
- Fan ranking: New Fan → Rising → Active → Die-hard → Super → Elite → Legendary
- Matchday streaks, engagement scores, achievements/badges

### Home Feed (Football-First Social)
- 8 content types: banter, match_reaction, goal_reaction, tactical_opinion, player_comparison, meme, breaking_news, matchday_discussion
- Real-time polling (30s) with `wire:poll`
- Quick reactions (♥, 🔥, 💚, 😂, 😱) on hover

### Trending Section (Twitter/X-like)
- Auto-detected hashtags from post bodies
- Most discussed clubs, trending players, viral debates
- Most active match rooms, breaking news, fast-rising conversations
- Real-time polling (60s)

### Match Rooms (Live Banter)
- Real-time comment streaming (3s polling)
- Emoji reactions (7 types) with emoji storm detection
- Heat Meter (CALM → LOW → MEDIUM → HIGH → EXTREME)
- Fan Momentum indicators (home vs away support tracking)

### Club Communities
- Per-club ecosystem with location-based visibility (Country/State/Region)
- Fan discussions, matchday chats, polls, tactical debates
- Community moderator tools

### Gamification & Retention
- Points system (post_created=10, comment_created=5, like_received=2, etc.)
- Club loyalty badges, debate leaderboards, prediction rewards
- Matchday streaks, top fan rankings, community XP

## User Flow
1. **Onboarding**: Register → Select favorite clubs → Choose football personality → Get fan ranking
2. **Daily Loop**: Open app → Check live matches → Read feed → React/comment → Check trending → Visit profile
3. **Matchday**: Join match room → Live comments → Emoji reactions → Heat meter rises → Emoji storm triggers

## Technical Stack
- **Backend**: Laravel 12.x, PHP 8.2+
- **Frontend**: Livewire 3.x, Tailwind CSS 4.x, Flux UI
- **Database**: MySQL/PostgreSQL (users, posts, communities, matches, comments, reactions, badges)
- **Real-time**: Livewire polling (3-60s) + WebSocket ready (Laravel Echo + Pusher)
- **API**: Football API (fixtures, lineups, events, statistics, standings)
