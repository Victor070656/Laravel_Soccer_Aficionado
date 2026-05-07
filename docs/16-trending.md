# Trending Page Concepts - Soccer Aficionado

## Trending Page Layout

### Breaking News Ticker (Top of Page)
```
┌─────────────────────────────────────────────────┐
│  🔴 BREAKING (red badge)  Football News               │
│  "Arsenal signs new striker for £50m!"               │
│  Posted by John Doe · 10m ago · ♥ 45            │
└─────────────────────────────────────────────────┘
```

### Trending Hashtags (Main Section)
```
┌─────────────────────────────────────────────────┐
│  # Trending Hashtags                             │
│  ┌─────────────────────────────────────┐  │
│  │ #ArtetaOut [HOT]              200 posts │  │
│  │ #HalaMadrid              180 posts │  │
│  │ #ChelseaVsArsenal [Rising]  85 posts │  │
│  │ #Messi                   150 posts │  │
│  │ #VAR                     90 posts │  │
│  └─────────────────────────────────────┘  │
└─────────────────────────────────────────────────┘
```

### Viral Fan Debates
```
┌─────────────────────────────────────────────────┐
│  💬 Viral Fan Debates                           │
│  ┌─────────────────────────────────────┐  │
│  │ 👤 John [Tactical] 2h ago              │  │
│  │ "Arteta's tactics are outdated. Here's     │  │
│  │ why..." [Read More]                      │  │
│  │ ♥ 120  💬 45 (Trending)                │  │
│  └─────────────────────────────────────┘  │
└─────────────────────────────────────────────────┘
```

### Fast-Rising Conversations
```
┌─────────────────────────────────────────────────┐
│  🚀 Fast-Rising Conversations                    │
│  ┌─────────────────────────────────────┐  │
│  │ "Just saw the lineups! No Saka? ⚽"    │  │
│  │ Posted 30m ago · ♥ 25  💬 12          │  │
│  │ [Join Conversation →]                  │  │
│  └─────────────────────────────────────┘  │
└─────────────────────────────────────────────────┘
```

### Sidebar Modules

#### Most Discussed Clubs
```
┌─────────────────────────────────────────────────┐
│ ⚽ Most Discussed Clubs                       │
│  1. Arsenal - 200 discussions                │
│  2. Chelsea - 180 discussions                 │
│  3. Barcelona - 150 discussions             │
│  4. Real Madrid - 120 discussions            │
│  5. Liverpool - 100 discussions            │
└─────────────────────────────────────────────────┘
```

#### Trending Players
```
┌─────────────────────────────────────────────────┐
│ ⚔ Trending Players                           │
│  1. Bukayo Saka (Arsenal) - 85 posts      │
│  2. Lionel Messi (Inter Miami) - 150 posts │
│  3. Erling Haaland (City) - 95 posts      │
│  4. Cole Palmer (Chelsea) - 60 posts      │
│  5. Vinicius Jr. (Real Madrid) - 70 posts │
└─────────────────────────────────────────────────┘
```

#### Most Active Match Rooms
```
┌─────────────────────────────────────────────────┐
│ 🔴 Most Active Match Rooms                    │
│  🔴 Arsenal vs Chelsea - 125 comments      │
│  🔴 Barcelona vs Real Madrid - 98 comments  │
│  🔴 Man United vs Liverpool - 67 comments  │
└─────────────────────────────────────────────────┘
```

## Hashtag Click-Through Flow
1. User clicks "#ArtetaOut" on Trending page
2. Redirected to filtered feed: `feed?hashtag=ArtetaOut`
3. Sees all posts containing "#ArtetaOut"
4. Can join conversation by posting with that hashtag

## Trending Badge Types
- **HOT**: 300+ posts in 24 hours (red badge)
- **RISING**: 100-299 posts in 6 hours (Pitch Green badge)
- **NORMAL**: Less than 100 posts (no badge)

## Mobile Trending (Single Column)
1. **Breaking News**: Full-width card at top
2. **Trending Hashtags**: List view with post counts
3. **Viral Debates**: 2-3 posts with type badges
4. **Fast-Rising**: Scrollable horizontal cards
5. **Sidebar Modules**: Move to bottom sections
