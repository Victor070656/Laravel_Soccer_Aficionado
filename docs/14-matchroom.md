# Match Room Concepts - Soccer Aficionado

## Match Room Layout (Live Banter)

### Match Header (Glass Card + Heat Bar)
```
┌─────────────────────────────────────────────────┐
│  🏟 Stadium Glow Background                     │
├─────────────────────────────────────────────────┤
│  ┌────────┐  ┌──────────┐  ┌────────┐     │
│  │Arsenal│  │  2 - 1   │  │Chelsea │     │
│  │  [Logo]│  │(72') LIVE│  │  [Logo]│     │
│  └────────┘  └──────────┘  └────────┘     │
│                                                  │
│  [===== Heat Meter (EXTREME - Red Pulse) =====] │
│  Home Momentum: ██████░░░░ Away (60/40)   │
└─────────────────────────────────────────────────┘
```

### Comment Input Section
```
┌─────────────────────────────────────────────────┐
│  💬 Share Your Reaction...                       │
│  "⚽ Come on Arsenal! YYYEE!"               │
│  [48/280]              [Post ⚽]           │
└─────────────────────────────────────────────────┘
```

### Emoji Reaction Buttons
```
┌─────────────────────────────────────────────────┐
│  🔥 Quick Reactions                               │
│  [⚽ Goal] [🔥 Fire] [💚 Love] [😂 LOL] [😡 Angry] │
│  [🎉 Party] [👏 Clap] [😱 Shock]             │
└─────────────────────────────────────────────────┘
```

### Live Comments Feed (Scrollable)
```
┌─────────────────────────────────────────────────┐
│  ┌─────────────────────────────────────┐  │
│  │ 👤 John [ARS] 1m ago                   │  │
│  │ "COME ONNNN!!! ⚽⚽⚽"             │  │
│  │                ♥ 5  💬 2              │  │
│  └─────────────────────────────────────┘  │
│                                                  │
│  ┌─────────────────────────────────────┐  │
│  │ 👤 Sarah [CHE] 2m ago                 │  │
│  │ "Unlucky with that offside 😱"           │  │
│  │                ♥ 2  💬 1              │  │
│  └─────────────────────────────────────┘  │
│  ... (more comments streaming in)              │
└─────────────────────────────────────────────────┘
```

### Match Events Ticker (Right Sidebar or Bottom)
```
┌─────────────────────────────────────────────────┐
│ ⚽ Match Events                                 │
│  45' ⚽ Goal! Saka (Arsenal)                 │
│  52' 🟥 Yellow Card (Chelsea player)             │
│  67' 🔄 Substitution (Arsenal)                  │
│  72' ⚽ Goal! Havertz (Arsenal)              │
└─────────────────────────────────────────────────┘
```

### Live Reactions Summary
```
┌─────────────────────────────────────────────────┐
│ 🔥 Live Reactions                               │
│  ⚽ x45  🔥 x32  💚 x28  😂 x15  😡 x8      │
└─────────────────────────────────────────────────┘
```

### Quick Stats
```
┌─────────────────────────────────────────────────┐
│ Quick Stats                                    │
│  Total Comments: 125                          │
│  Total Reactions: 230                         │
│  Heat Level: EXTREME                          │
└─────────────────────────────────────────────────┘
```

## Emoji Storm Overlay (Floating Animation)
```
┌─────────────────────────────────────────────────┐
│  ⚽ (floating up, left 20%)                      │
│                   🔥 (floating up, left 60%)      │
│                        💚 (floating up, right 30%) │
│  ⚽ (floating up, right 70%)                      │
│           😂 (floating up, left 40%)             │
└─────────────────────────────────────────────────┘
```

## Heat Meter Visualization
- **CALM**: Gray (#84967e), 0-4 activities/min
- **LOW**: Light Gray (#c6c6c7), 5-9 activities/min
- **MEDIUM**: Purple (#c2c6db), 10-19 activities/min
- **HIGH**: Pitch Green (#00ff41), 20-29 activities/min
- **EXTREME**: Red (#ffb4ab), 30+ activities/min + pulse animation

## Fan Momentum Bar
- **Container**: `bg-surface-container-high`, full width, h-3 (12px)
- **Home Section**: `bg-primary-container` (Pitch Green)
- **Away Section**: `bg-secondary` (Light Gray)
- **Transition**: `transition-all duration-500` for smooth updates
- **Tooltips**: Hover to see exact counts (e.g., "Arsenal: 75 comments")

## Mobile Match Room (Single Column)
1. **Match Header**: Teams + Score + Live Pulse
2. **Heat Meter + Momentum**: Full-width bars
3. **Comment Input**: Sticky at top or bottom
4. **Quick Reactions**: Horizontal scrollable row
5. **Live Comments**: Full-width, auto-scroll to bottom
6. **Match Events**: Collapsible section
