# Feed Behavior Concepts - Soccer Aficionado

## Feed Types

### 1. Home Feed (Football-First Social)
**Content Types** (8 total):
- **banter** (💬): General football banter, jokes, reactions
- **match_reaction** (⚽): Reactions to specific matches
- **goal_reaction** (🥅): Specific goal celebrations/reactions
- **tactical_opinion** (📋): Tactical analysis, formations, manager decisions
- **player_comparison** (⚔): Comparing player stats, abilities
- **meme** (😂): Football memes with images
- **breaking_news** (📰): Important football news
- **matchday_discussion** (🔥): General matchday conversations

**Sorting**: Latest first (default), Trending (engagement), Following (posts from followed users)

**Polling**: 30 seconds via `wire:poll.30s`

### 2. Trending Feed (Twitter/X-like)
**Modules**:
- **Breaking News Ticker**: Pinned posts + breaking_news type
- **Hashtag Trends**: Auto-detected from `#hashtags` in post bodies
- **Viral Debates**: Posts with 50+ likes/comments in 2 hours
- **Fast-Rising**: Posts in last 6 hours with rapid engagement
- **Most Discussed Clubs**: Clubs with highest post counts
- **Trending Players**: Players mentioned most in posts
- **Active Match Rooms**: Rooms with most comments in 24h

**Polling**: 60 seconds via `wire:poll.60s`

### 3. Community Feed (Club-Specific)
**Content**: Only posts tagged with that community
**Sorting**: Latest, Most Liked, Most Discussed
**Special**: Pinned moderator posts, community announcements

### 4. Profile Feed (User-Specific)
**Content**: User's posts + comments
**Display**: Split view [Posts | Comments] tabs
**Special**: Public view shows only approved content

## Engagement Loops

### Quick Reactions (On Hover)
1. User hovers over post → Emoji bar slides up instantly
2. Clicks ⚽ → Reaction recorded + animation plays
3. Counnt updates: "♥ 12" → "♥ 13"
4. If 3+ same emoji in 5s → Emoji Storm triggers

### Comment Flow
1. Click on post → Redirected to `posts.show`
2. Scroll to comments section
3. Type comment → "Post Comment"
4. Comment appears with live animation
5. Receive notification if someone replies

### Share Flow
1. Click share button → Copies to clipboard OR opens share dialog
2. Points awarded (+5 for share_created)
3. Share count increments on original post

## Real-Time Behavior

### Live Feed Updates
- **Polling Intervals**:
  - Home Feed: 30s
  - Trending: 60s
  - Match Rooms: 3s
  - Comments: 3s
- **WebSocket Ready**: `echo:feed,PostCreated`, `echo:trending,NewPost`

### New Content Animation
- **Fade In**: New posts slide in from bottom (translateY 10px → 0)
- **Pulse**: New comments get subtle pulse on appearance
- **Shimmer**: Loading skeleton while poll fetches new data

## Filtering & Sorting

### Content Type Filter
```
[All] [Banter] [Match Reaction] [Goal] [Tactical] [Player] [Meme] [News]
```
**Behavior**: Click filter → Feed reloads with only that content type

### Hashtag Filter
1. Click "#ArtetaOut" on trending page
2. Feed filters to posts containing that hashtag
3. URL updates to `feed?hashtag=ArtetaOut`
4. User can "Join Conversation" by posting with same hashtag

### Date Range Filter
- **Today**: Posts from last 24 hours
- **This Week**: Posts from last 7 days
- **This Month**: Posts from last 30 days
- **All Time**: All posts (default)

## Mobile Feed Behavior
- **Pull to Refresh**: Triggers poll immediately (future feature)
- **Infinite Scroll**: Load more posts when reaching bottom (paginate 20)
- **Sticky Header**: Post type selector stays visible while scrolling
- **Snap Scroll**: Comments in match rooms snap to bottom on new message
