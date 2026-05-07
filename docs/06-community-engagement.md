# Community Engagement Strategy - Soccer Aficionado

## Engagement Pillars

### 1. Tribal Loyalty (Club Identity)
**Strategy**: Make fans feel proud of their club affiliation
- **Club Badge Overlay**: Profile pictures show primary club badge
- **Club Loyalty Badges**: "Arsenal Fan", "Chelsea Die-hard", etc.
- **Location-Based Communities**: "Arsenal Fans in Lagos", "Barcelona Fans in Nigeria"
- **Matchday Unity**: During matches, fans unite in club-colored rooms

**Implementation**:
- Users select favorite clubs during onboarding
- Primary club badge displays on profile, comments, posts
- Location hierarchy: Global > Continent > Country > State/Region
- Club-specific achievements (50 posts = "Club Loyalty" badge)

### 2. Real-Time Emotion (Matchday Experience)
**Strategy**: Capture the raw emotion of football in digital form
- **Live Banter Rooms**: Fans react instantly to goals, cards, subs
- **Emoji Storms**: When 3+ fans react in 5 seconds, animation triggers
- **Heat Meter**: Visual representation of room energy (CALM → EXTREME)
- **Fan Momentum**: Home vs Away support tracking with dynamic bar

**Implementation**:
- 3-second polling for live comments
- 7 emoji reaction types (⚽, 🔥, 💚, 😂, 😡, 🎉, 👏)
- Color-coded heat levels (Pitch Green = HIGH, Red = EXTREME)
- WebSocket ready for instant updates

### 3. Recognition & Status (Gamification)
**Strategy**: Make fans feel recognized for their contributions
- **Fan Rankings**: New → Rising → Active → Die-hard → Super → Elite → Legendary
- **Achievement Badges**: "Debate Won", "Club Loyalty", "Matchday Streak"
- **Leaderboards**: Top fans by points, debates won, predictions correct
- **Points System**: Post (10), Comment (5), Like Received (2), Vote (3)

**Implementation**:
- GamificationService awards points per action
- Badge qualification checks on each point award
- Profile shows ranking with visual progress bar
- Community contributions highlighted on profile

### 4. Content Virality (Trending)
**Strategy**: Surface the most engaging football conversations
- **Hashtag Trends**: Auto-detect #hashtags from post bodies
- **Viral Debates**: Highlight posts with 50+ likes/comments in 2 hours
- **Fast-Rising**: Posts in last 6 hours with rapid engagement
- **Breaking News**: Pinned posts + breaking_news content type

**Implementation**:
- 60-second polling for trending updates
- Scope Trending() in Post model (last 7 days, order by engagement)
- Hashtag extraction from post bodies via regex
- One-click join conversation flow

### 5. Daily Habit Formation (Retention)
**Strategy**: Make opening the app a daily ritual
- **Matchday Reminders**: Push notifications 15 mins before kickoff
- **Morning Feed**: "Good morning, [Name]! 3 live matches today."
- **Evening Trending**: "Join the debate: #ArtetaOut is trending!"
- **Weekly Recap**: "You earned 50 points this week! Check your ranking."

**Implementation**:
- Scheduled jobs for match reminders
- Daily digest email with top trending topics
- Profile progress tracking (streak count, points this week)
- Notification preferences in settings

## Community Tiers

### Casual Fans (0-99 points)
- Can: Read feed, view trending, join 3 communities
- Cannot: Create polls, post memes, access VIP match rooms
- Goal: Reach 100 points to unlock "Rising Fan"

### Active Fans (100-999 points)
- Can: Create all content types, join unlimited communities, access match rooms
- Cannot: Moderate communities, access leaderboards
- Goal: Reach 1,000 points to unlock "Die-hard Fan"

### Die-Hard Fans (1,000-4,999 points)
- Can: Moderate communities, appear on leaderboards, get club ambassador status
- Cannot: Access admin panel, manage platform-wide features
- Goal: Reach 5,000 points to unlock "Super Fan"

### Super Fans (5,000-9,999 points)
- Can: Club ambassador badge, priority support, beta features
- Goal: Reach 10,000 points to unlock "Elite Fan"

### Elite/Legendary Fans (10,000+ points)
- Can: Platform ambassadors, direct feedback to product team
- Recognition: Special profile frame, "Legendary Fan" ranking
