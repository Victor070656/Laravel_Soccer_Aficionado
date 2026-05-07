# Homepage Layout Concepts - Soccer Aficionado

## Homepage (Landing Page for Guests)

### Hero Section
```
┌─────────────────────────────────────────────────┐
│  🏟 Stadium Glow Background (animated orbs)     │
│                                              │
│         ⚽ SOCCER AFICIONADO                 │
│    "The Digital Stadium for Football Fans"          │
│                                              │
│    [Live Matches] [Explore Communities]         │
│    [Sign Up - Get Started]                   │
└─────────────────────────────────────────────────┘
```

### Live Matches Ticker (Auto-Scrolling)
```
┌─────────────────────────────────────────────────┐
│  🔴 LIVE MATCHES                            │
│  ► Arsenal 2-1 Chelsea (72') ► Barcelona 0-0 Real │
│    Madrid (HT) ► Man United 3-2 Liverpool (90+3') ►  │
└─────────────────────────────────────────────────┘
```

### Features Grid (3x2)
```
┌──────────┬──────────┬──────────┐
│ ⚽ Feed  │ 🔥 Trending│ 👥 Communities│
│ Football │ Hashtags │ Per-Club  │
│ Banter   │ Debates  │ Ecosystem  │
│ [Explore] │ [Explore] │ [Explore]    │
├──────────┼──────────┼──────────┤
│ 🏟 Match   │ 🏆 Profile │ 🎖️ Gamifi- │
│ Rooms    │ Identity │ cation    │
│ Live      │ Card     │ Points,   │
│ Banter    │ Ranking  │ Badges   │
│ [Explore] │ [Explore] │ [Explore]    │
└──────────┴──────────┴──────────┘
```

## Homepage (For Logged-In Users - Redirect to /feed)

### Quick Stats Bar
```
┌─────────────────────────────────────────────────┐
│  👤 John Doe                    │
│  Points: 550 | Badges: 8 | Rank: Active Fan │
│  [Complete Profile →]                               │
└─────────────────────────────────────────────────┘
```

### Your Feed Preview (3 Latest Posts)
```
┌─────────────────────────────────────────────────┐
│  📰 Your Feed (Latest)                       │
│  ┌─────────────────────────────────────┐  │
│  │ 👤 Mike [ARS]: "Great goal! ⚽"     │  │
│  │ ♥ 12  💬 3                          │  │
│  └─────────────────────────────────────┘  │
│  [View All →]                                    │
└─────────────────────────────────────────────────┘
```

### Quick Actions (2x2 Grid)
```
┌────────────────┬────────────────┐
│ 🔥 Trending │ 👥 Match    │
│ See what's  │ Join live   │
│ happening    │ banter     │
├────────────────┼────────────────┤
│ 🏆 Leader-  │ 📊 Active  │
│ board       │ Polls     │
│ Who's top? │ Vote now!  │
└────────────────┴────────────────┘
```

## Mobile Homepage (Bottom Nav Shown)

### Tab Bar (Fixed Bottom)
```
┌─────────────────────────────────────────────────┐
│  🏠 Home  │ 🔥 Trend  │ 👥 Comm. │ 🏟 Rooms │ 👤 You │
│ (Active)│           │           │ (3 Live) │        │
└─────────────────────────────────────────────────┘
```

### Scrollable Feed (Single Column)
1. **Welcome Card**: Points/badges summary
2. **Live Matches Widget**: 2-3 live match cards
3. **Latest Posts**: 3-5 feed posts
4. **Active Polls**: 1-2 polls
5. **Trending Topics**: 3-5 hashtags

## Design Tokens for Homepage
- **Hero Background**: Stadium gradient (#131313 to #0a0f1e)
- **Glass Cards**: 15% opacity, blur(16px), Pitch Green border glow
- **CTA Buttons**: `bg-primary-container` (#00ff41) with dark text
- **Live Indicators**: Pulse animation + badge-live component
- **Stats**: Large numbers (text-headline-md) with Pitch Green color
