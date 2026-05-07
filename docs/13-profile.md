# Profile Page Concepts - Soccer Aficionado

## Profile Layout (Football Identity Card)

### Main Profile Card (Glassmorphism)
```
┌─────────────────────────────────────────────────┐
│  🏟 Stadium Glow (top-right + bottom-left orbs)   │
├─────────────────────────────────────────────────┤
│  ┌──────┐                                      │
│  │Avatar│  John Doe          [✨ Elite Fan]    │
│  │ +    │  @johndoe                             │
│  │Club  │  📍 Nigeria, Lagos                    │
│  │Badge │  ⚽ Arsenal Fan                      │
│  └──────┘                                      │
│                                                  │
│  Points: 5,500 | Followers: 120 | Following: 85 │
│  Posts: 45                                    │
│                                                  │
│  [✏️ Edit Profile] (if own) / [+ Follow] (if not)│
└─────────────────────────────────────────────────┘
```

### Bio Section
```
┌─────────────────────────────────────────────────┐
│  📝 Bio                                          │
│  "Passionate Arsenal fan. Love tactical debates.   │
│   Always at the Emirates on matchdays! ⚽"        │
└─────────────────────────────────────────────────┘
```

### Football Identity Details (2-Column Grid)
```
┌──────────────────────────┬──────────────────────────┐
│ ⚽ Favorite Player       │ 🎓 Favorite Coach          │
│ [Player Photo]          │ [Academic Cap]            │
│ Bukayo Saka            │ Mikel Arteta            │
│ Arsenal FC              │ Arsenal FC              │
└──────────────────────────┴──────────────────────────┘
```

### Fan Ranking Section
```
┌─────────────────────────────────────────────────┐
│ 🏆 Fan Ranking                                   │
│                                                  │
│         "ELITE FAN" (text-display-xl, glow)       │
│         5,500 Points                         │
│                                                  │
│ [======Progress Bar (55% to Legendary)======]│
│         5,500 / 10,000 to Legendary Fan      │
└─────────────────────────────────────────────────┘
```

### Achievements/Badges Grid
```
┌─────────────────────────────────────────────────┐
│ 🏅 Achievements                                │
│  [⚽ Club Fan] [🔥 Match Streak 7] [💬 Debater] │
│  [🎖️ Loyalty] [👑 Ambassador] [🏆 Top 10]    │
└─────────────────────────────────────────────────┘
```

### Recent Posts & Comments
```
┌─────────────────────────────────────────────────┐
│ 📰 Recent Posts                                  │
│  ┌─────────────────────────────────────┐  │
│  │ "Great match today! ⚽"              │  │
│  │ ♥ 12  💬 3                    │  │
│  └─────────────────────────────────────┘  │
│  [View All Posts →]                            │
└─────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────┐
│ 💬 Recent Comments                                │
│  ┌─────────────────────────────────────┐  │
│  │ On "Title": "I disagree..."        │  │
│  │ 2m ago                           │  │
│  └─────────────────────────────────────┘  │
└─────────────────────────────────────────────────┘
```

### Top Communities
```
┌─────────────────────────────────────────────────┐
│ 👥 Top Communities                                │
│  ┌─────────────────────────────────────┐  │
│  │ [Logo] Arsenal Community [Moderator] │  │
│  │ 50 posts, 5,000 members           │  │
│  └─────────────────────────────────────┘  │
└─────────────────────────────────────────────────┘
```

## Profile Features

### Edit Profile Form (Modal/Page)
- **Avatar Upload**: Drag-and-drop with Pitch Green border
- **Favorite Clubs**: Select up to 3, mark one as primary
- **Favorite Player**: Searchable dropdown (uses Players table)
- **Favorite Coach**: Text input (free-form)
- **Football Personality**: Radio buttons (Flame, Analyst, Banter King, etc.)
- **Country/State**: Dropdown (ISO codes) + text input
- **Bio**: Textarea, 280 chars max

### Public Profile (View-Only for Others)
- All sections visible
- Follow/Unfollow button (if not own profile)
- Message button (future feature)
- Report user (safety feature)

## Mobile Profile (Single Column)
1. **Profile Card**: Avatar + Club Badge, Stats Row
2. **Fan Ranking**: Large display with progress bar
3. **Identity Details**: Full-width sections
4. **Achievements**: 3-column badge grid
5. **Recent Activity**: Tabs [Posts | Comments]
6. **Communities**: List view with role badges
