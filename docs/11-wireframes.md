# Wireframe Structure Suggestions - Soccer Aficionado

## Page Layout Templates

### 1. Profile Page (Football Identity Card)
```
┌─────────────────────────────────────────────────┐
│  🏟 Stadium Glow Background (blurred orbs)     │
├─────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────┐  │
│  │ Glass Card (glass-card)                 │  │
│  │ ┌──────┐    ┌──────────────┐ │  │
│  │ │Avatar│    │ Profile Info    │ │  │
│  │ │ +Club │    │ - Name         │ │  │
│  │ │Badge │    │ - @username    │ │  │
│  │ └──────┘    │ - Fan Rank Badge│ │  │
│  │              │ - Stats (Pts, Fans)│ │  │
│  │              └──────────────┘ │  │
│  └─────────────────────────────────────┘  │
│                                              │
│  ┌─────────┐  ┌─────────┐  ┌─────────┐ │
│  │Fan Rank │  │Achieve-  │  │Top Comm.│ │
│  │(Progress│  │ments  │  │(Club List)│ │
│  │ Bar)    │  │(Badge   │  │          │ │
│  └─────────┘  │Grid)    │  └─────────┘ │
│                  └─────────┘              │
└─────────────────────────────────────────────────┘
```

### 2. Home Feed (Football-First Social)
```
┌─────────────────────────────────────────────────┐
│  📰 Welcome Banner (glass-card, gradient)      │
├─────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────┐  │
│  │ Create Post (glass-card)                 │  │
│  │ [Banter] [Match Rx] [Goal] [Tactical] │  │
│  │ "Share your banter..." [Post ⚽]       │  │
│  └─────────────────────────────────────┘  │
│                                              │
│  ┌─────────────────────────────────────┐  │
│  │ Feed Post (glass-card)                   │  │
│  │ 👤 User Name [ARS] 2m ago          │  │
│  │ "Great match today! ⚽"                 │  │
│  │ ♥ 5  💬 2  🔗 1  [🔥 😂 💚] │  │
│  └─────────────────────────────────────┘  │
│  ... (more posts)                            │
└─────────────────────────────────────────────────┘
```

### 3. Trending Page (Twitter/X-like)
```
┌─────────────────────────────────────────────────┐
│  🔥 Trending Header (glass-card)             │
├─────────────────────────────────────────────────┤
│  Main Feed           │ Sidebar              │
│  ┌──────────┐     │ ┌──────────┐      │
│  │Breaking   │     │ │Most       │      │
│  │News Ticker│     │ │Discussed  │      │
│  │(Red Accent)│     │ │Clubs     │      │
│  └──────────┘     │ │(Top 5)   │      │
│  ┌──────────┐     │ ├──────────┤      │
│  │#Hashtags  │     │ │Trending   │      │
│  │(#ArtetaOut│     │ │Players    │      │
│  │ 200 posts│     │ │(Top 5)   │      │
│  └──────────┘     │ └──────────┘      │
│  ┌──────────┐                            │
│  │Viral     │                            │
│  │Debates   │                            │
│  │(Tactical  │                            │
│  │ Opinion) │                            │
│  └──────────┘                            │
└─────────────────────────────────────────────────┘
```

### 4. Match Room (Live Banter)
```
┌─────────────────────────────────────────────────┐
│  Match Header (glass-card + Heat Bar)        │
│  🏟 Arsenal 2-1 Chelsea 🔥 LIVE (Pulse)  │
│  [===== Heat Meter (EXTREME) =====]     │
│  Home Momentum: ████░░░░ Away        │
├─────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────┐  │
│  │ "⚽ Goal!" [Post]                     │  │
│  │ Quick Reactions: [⚽] [🔥] [💚]... │  │
│  └─────────────────────────────────────┘  │
│                                              │
│  ┌─────────────────────────────────────┐  │
│  │ Live Comments (scrollable)             │  │
│  │ 👤 John [ARS] 1m: "Come on!!! ⚽" │  │
│  │ 👤 Sarah [CHE] 1m: "Unlucky 😱"    │  │
│  │ 👤 Mike [ARS] 2m: "YYEEE 🎉"      │  │
│  └─────────────────────────────────────┘  │
│                                              │
│  Sidebar: Match Events | Reactions | Stats  │
└─────────────────────────────────────────────────┘
```

### 5. Communities (Club Ecosystem)
```
┌─────────────────────────────────────────────────┐
│  👥 Communities Header (glass-card)         │
│  [All] [Global] [By Country] [By State]  │
│  🔍 "Search communities..."                  │
├─────────────────────────────────────────────────┤
│  ┌──────────┐  ┌──────────┐  ┌──────────┐ │
│  │Arsenal   │  │Chelsea   │  │Barcelona│ │
│  │Community│  │Community│  │Community│ │
│  │5,000    │  │3,200    │  │8,100    │ │
│  │Members  │  │Members  │  │Members  │ │
│  └──────────┘  └──────────┘  └──────────┘ │
│                                              │
│  Sidebar: Popular Clubs (for creating        │
│            new communities)                    │
└─────────────────────────────────────────────────┘
```

## Wireframe Grid System
- **Desktop**: 12-column grid, max-w-7xl, centered
- **Tablet**: 6-column grid, max-w-4xl
- **Mobile**: Single column, px-4 padding

## Component Spacing
- **Major Sections**: `mb-6` (24px gap)
- **Cards**: `p-5` (20px padding)
- **Card Gaps**: `space-y-4` (16px between cards)
- **Inner Elements**: `gap-3` (12px between elements)
