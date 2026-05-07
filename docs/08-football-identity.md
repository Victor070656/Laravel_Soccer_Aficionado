# Football Identity System - Soccer Aficionado

## Identity Components

### 1. Profile Picture + Club Badge Overlay
**Visual**: User's avatar with small club badge (40x40px) positioned at bottom-right corner
**Purpose**: Instantly signal primary club affiliation
**Implementation**: HTML overlay with absolute positioning, 1px border

### 2. Favorite Player
**Fields**: `favorite_player_id` (foreign key to players table)
**Display**: Player photo (60x60px), name, club name
**User Flow**: Select during onboarding or edit in profile settings

### 3. Favorite Coach
**Fields**: `favorite_coach` (string)
**Display**: Academic cap icon, coach name
**Examples**: "Mikel Arteta", "Pep Guardiola", "Carlo Ancelotti"

### 4. Football Personality
**Fields**: `football_personality` (string)
**Options**:
- **The Flame**: Passionate, emotional fan (🔥)
- **The Analyst**: Tactical, data-driven fan (📋)
- **The Banter King**: Witty, humorous fan (😂)
- **The Loyalist**: Devoted, never-switch-sides fan (🛡️)
- **The Debater**: Loves tactical arguments (💬)

### 5. Location (Country/State)
**Fields**: `country` (2-char ISO code), `state` (100 chars)
**Display**: "📍 Nigeria, Lagos" or "📍 UK, London"
**Purpose**: Location-based community visibility

## Fan Ranking System

### Ranking Algorithm
```php
$points = $user->points ?? 0;

if ($points >= 10000) return 'Legendary Fan';      // 👑💎 Elite glow
if ($points >= 5000)  return 'Elite Fan';          // 👑 Primary container
if ($points >= 2500)  return 'Super Fan';           // 🏆 Tertiary fixed dim
if ($points >= 1000)  return 'Die-hard Fan';        // 🔥 On-surface
if ($points >= 500)   return 'Active Fan';          // ⚽ Secondary
if ($points >= 100)   return 'Rising Fan';         // 🌱 On-surface-variant
else                  return 'New Fan';             // 👶 Outline-variant
```

### Visual Progress Bar
- **Container**: `bg-surface-container-high` (dark gray track)
- **Fill**: `bg-primary-container` (Pitch Green) with transition
- **Width**: `(points / 100) * 100%` (caps at 100% for next tier)
- **Label**: "550/1000 points to Die-hard Fan"

## Matchday Activity

### Streak Tracking
**Logic**: Count consecutive days with match-related activity
```php
$streak = $user->activities()
    ->where('created_at', '>=', now()->subDays(30))
    ->whereIn('type', ['match_view', 'match_reaction', 'prediction'])
    ->get()
    ->groupBy(fn($a) => $a->created_at->toDateString())
    ->count();
```

### Engagement Score
**Formula**: `(posts * 5) + (comments * 2) + (likes * 1)`
**Display**: "Engagement: 340" on profile
**Purpose**: Measure overall platform contribution

## Achievements/Badges Display

### Badge Grid (Profile Section)
- **Layout**: Flex-wrap, 2-3 badges per row
- **Badge Card**: Icon (32px) + Name (12px label-bold)
- **Earned**: Full opacity with Pitch Green border
- **Locked**: 50% opacity with question mark overlay

### Top Community Contributions
**Query**: User's most active communities by post count
**Display**: Community logo + name + role badge ("Moderator", "Member")
**Limit**: Top 5 communities

## Club Loyalty Indicators

### Primary Club Detection
```php
$primaryClub = $user->favoriteClubs()
    ->wherePivot('is_primary', true)
    ->first() ?? $user->favoriteClubs()->first();
```

### Club Badge on Comments/Posts
**Display**: Small club badge (20x20px) next to username
**Example**: "John Doe [ARS] 2m ago"
**Purpose**: Instantly see which club a fan supports in discussions
