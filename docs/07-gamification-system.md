# Gamification System - Soccer Aficionado

## Points System

### Action Point Values
| Action | Points | Notes |
|--------|--------|-------|
| Post Created | 10 | Any content type |
| Comment Created | 5 | On any post |
| Like Received | 2 | When others like your content |
| Vote Cast | 3 | On polls |
| Share Created | 5 | Sharing posts externally |
| Community Joined | 5 | First time joining |
| Follow Gained | 2 | When others follow you |
| Match Prediction Correct | 20 | Predict match outcome |
| Debate Won | 15 | Your debate trends |
| Club Loyalty Milestone | 50 | 50 posts for one club |
| Matchday Check-in | 5 | Post during live match |

### Points Ranking Tiers
| Ranking | Points Required | Color | Badge |
|---------|-----------------|-------|-------|
| New Fan | 0-99 | outline-variant | 👶 |
| Rising Fan | 100-499 | on-surface-variant | 🌱 |
| Active Fan | 500-999 | secondary | ⚽ |
| Die-hard Fan | 1,000-4,999 | on-surface | 🔥 |
| Super Fan | 5,000-9,999 | tertiary-fixed-dim | 🏆 |
| Elite Fan | 10,000-49,999 | primary-container | 👑 |
| Legendary Fan | 50,000+ | error (red glow) | 👑💎 |

## Achievement Badges

### Club Loyalty Badges
- **Club Fan**: Join your first club community
- **Club Loyalist**: 10 posts in one club community
- **Club Ambassador**: 50 posts + 500 points in one club
- **Club Legend**: 100+ posts, 1,000+ points, moderator status

### Engagement Badges
- **First Post**: Create your first post
- **First Comment**: Comment on someone's post
- **Getting Liked**: Receive 10 likes on your content
- **Viral Creator**: Post reaches 100+ likes
- **Debate Winner**: Your tactical opinion trends

### Matchday Badges
- **Matchday Streak 3**: Post during 3 consecutive matches
- **Matchday Streak 7**: Post during 7 consecutive matches
- **Emoji Storm Starter**: Trigger an emoji storm (3+ reactions in 5s)
- **Heat Meter Master**: Be in a room when it reaches "EXTREME"

### Community Badges
- **Community Builder**: Create a new community
- **Moderator**: Become a community moderator
- **Welcome Wagon**: Comment on 10 new member introductions

## Leaderboards

### Top Fan Rankings
- **All-Time Points**: Users with highest total points
- **This Week**: Points earned in last 7 days
- **Matchday Kings**: Most active during live matches
- **Debate Champions**: Most debates won (trending posts)

### Club-Specific Leaderboards
- **Arsenal Top Fans**: Points earned by Arsenal fans
- **Chelsea Die-Hards**: Most active Chelsea supporters
- **Barcelona Legends**: Top Barcelona community members

## Implementation (GamificationService)

### Points Award Logic
```php
protected array $pointsMap = [
    'post_created' => 10,
    'comment_created' => 5,
    'like_received' => 2,
    'vote_cast' => 3,
    'share_created' => 5,
    'community_joined' => 5,
    'follow_gained' => 2,
    'match_prediction_correct' => 20,
    'debate_won' => 15,
    'club_loyalty_milestone' => 50,
    'matchday_checkin' => 5,
];
```

### Badge Qualification
- Badges check: Points required + Specific criteria (posts_count, comments_count, etc.)
- Automatic award on point gain via `GamificationService::checkBadges()`
- Badge display: Icon + name + earned date on profile

### Leaderboard Query
```php
User::where('is_banned', false)
    ->orderByDesc('points')
    ->take(20)
    ->get(['id', 'name', 'username', 'avatar', 'points']);
```
