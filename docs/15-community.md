# Community Page Concepts - Soccer Aficionado

## Communities Listing Page

### Location Filter Tabs
```
┌─────────────────────────────────────────────────┐
│  👥 Communities Header (glass-card)                 │
│  [All Communities] [Global] [By Country] [By State] │
└─────────────────────────────────────────────────┘
```

### Search Bar
```
┌─────────────────────────────────────────────────┐
│  🔍 "Search communities..." (full-width input)      │
└─────────────────────────────────────────────────┘
```

### Community Cards Grid (3-Column Desktop, 1-Column Mobile)
```
┌──────────────────┬──────────────────┬──────────────────┐
│ ┌────────────┐ │ ┌────────────┐ │ ┌────────────┐ │
│ │ [Club Logo] │ │ [Club Logo] │ │ [Club Logo] │ │
│ │            │ │            │ │            │ │
│ │Arsenal    │ │Chelsea    │ │Barcelona  │ │
│ │Community  │ │Community  │ │Community  │ │
│ │            │ │            │ │            │ │
│ │5,000      │ │3,200      │ │8,100      │ │
│ │Members    │ │Members    │ │Members    │ │
│ │120 Posts  │ │85 Posts   │ │200 Posts  │ │
│ │📍 Nigeria  │ │📍 UK      │ │📍 Spain    │ │
│ └────────────┘ │ └────────────┘ │ └────────────┘ │
└──────────────────┴──────────────────┴──────────────────┘
```

## Individual Community Page

### Community Header (Glass Card with Banner)
```
┌─────────────────────────────────────────────────┐
│  [Banner Image - Opacity 20%]                     │
├─────────────────────────────────────────────────┤
│  ┌────────┐  Arsenal Community              │
│  │ [Logo] │  ⚽ Arsenal FC                     │
│  └────────┘  📍 Nigeria, Lagos                  │
│             Members: 5,000 | Posts: 120         │
│             [Join Community] (if not member)        │
│             [Leave Community] (if member)         │
└─────────────────────────────────────────────────┘
```

### Fan Discussions (Main Feed)
```
┌─────────────────────────────────────────────────┐
│  💬 Discussions (Feed within community)            │
│  ┌─────────────────────────────────────┐  │
│  │ 👤 John [Moderator] 5m ago           │  │
│  │ "What a match! Saka was incredible ⚽" │  │
│  │ ♥ 15  💬 5                         │  │
│  └─────────────────────────────────────┘  │
│  ... (more community posts)                    │
└─────────────────────────────────────────────────┘
```

### Sidebar (Desktop) / Bottom Section (Mobile)
```
┌─────────────────────────────────────────────────┐
│ Top Members                                    │
│  👤 John [Moderator] - 50 posts            │
│  👤 Sarah [Member] - 35 posts               │
│  👤 Mike [Member] - 28 posts                │
├─────────────────────────────────────────────────┤
│ Community Stats                                │
│  Total Members: 5,000                        │
│  Total Posts: 120                           │
│  Club: Arsenal FC                           │
└─────────────────────────────────────────────────┘
```

## Location-Based Visibility

### Hierarchy Structure
```
Global Communities
├── Continent: Africa
│   ├── Country: Nigeria
│   │   ├── State: Lagos
│   │   │   └── "Arsenal Fans in Lagos"
│   │   └── State: Abuja
│   │       └── "Arsenal Fans in Abuja"
│   └── Country: Ghana
│       └── "Arsenal Fans in Ghana"
├── Continent: Europe
│   ├── Country: UK
│   │   └── "Arsenal Fans in London"
│   └── Country: Spain
│       └── "Barcelona Fans in Madrid"
└── Global
    └── "Arsenal Global Community"
```

### Location Filter Logic
- **All Communities**: Shows all communities user is member of
- **Global**: Communities without specific country/state
- **By Country**: Filter by `country` field (e.g., "Nigeria")
- **By State**: Filter by `state` field (e.g., "Lagos")

## Community Moderator Tools (Future)
- **Pin Post**: Stick important announcements to top
- **Remove Member**: Handle rule violations
- **Edit Community**: Update description, rules, banner
- **View Reports**: See reported posts/comments
