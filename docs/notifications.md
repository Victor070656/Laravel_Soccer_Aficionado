# Notification Concepts - Soccer Aficionado

## Notification Types

### 1. Match Reminders
**Trigger**: 15 minutes before match kickoff
**Content**: "⚽ Arsenal vs Chelsea kicks off in 15 minutes! [Join Room]"
**Priority**: High (sends push notification)

### 2. Engagement Notifications
- **Like Received**: "♥ John liked your post: 'Great goal!'""
- **Comment Received**: "💬 Sarah commented: 'I disagree!'""
- **Follow Gained**: "➕ Mike started following you"
- **Post Trending**: "📰 Your debate is now trending! #ArtetaOut"

### 3. Gamification Notifications
- **Points Earned**: "✨ +10 points for posting"
- **Badge Earned**: "🏅 You earned 'Club Fan' badge!"
- **Ranking Improved**: "🏆 You're now an 'Active Fan'!"
- **Streak Milestone**: "🔥 7-day matchday streak! Keep it up!"

### 4. Community Notifications
- **New Member**: "👤 John joined Arsenal Community"
- **Pinned Post**: "📌 Moderator pinned: 'Matchday Thread'"
- **Report**: "⚠️ Your post was reported (under review)"

## Notification Display

### Notification Center (Dropdown/Bell Icon)
```
┌─────────────────────────────────────────────────┐
│ 🔔 Notifications (Glass Card)                     │
│                                              │
│ ✨ +10 points for posting                  2m │
│ 🏅 You earned 'Club Fan' badge!           5m │
│ ♥ John liked your post                      10m │
│ 💬 Sarah commented: "Great match!"         15m │
│                                              │
│ [View All Notifications →]                     │
└─────────────────────────────────────────────────┘
```

### Notification Badge (Bell Icon)
- **Red Circle**: Unread count (max 9, then "9+")
- **Pulse Animation**: If 3+ unread notifications
- **Position**: Top-right of bell icon

## Notification Preferences
- **Push Notifications** (Mobile): Match reminders, trending alerts
- **Email Digest**: Daily/weekly summary of top notifications
- **In-App**: All notifications appear in dropdown
- **Sound**: Optional chime for live match events

## Real-Time Notifications
- **WebSocket Channel**: `echo:notification.{userId},NotificationReceived`
- **Behavior**: Instant popup (toast) + badge count increment
- **Sound**: Optional "crowd cheer" sound for goal events

## Notification Categories
| Category | Icon | Priority | Default |
|-----------|------|----------|---------|
| Match Reminders | ⚽ | High | On |
| Engagement | ♥/💬 | Medium | On |
| Gamification | 🏅/🏆 | Low | On |
| Community | 👥 | Medium | On |
| Reports | ⚠️ | High | On |
