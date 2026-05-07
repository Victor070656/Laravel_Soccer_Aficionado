# Card Designs - Soccer Aficionado

## Card Design Principles
- **Glassmorphism**: 15-20% white opacity, blur(16px), metallic gradient border
- **Pitch Green Accents**: Buttons, glows, progress bars
- **Stadium Aesthetic**: Soft corners (0.25-0.75rem), no full pills
- **Dark Background**: Always #131313 or #201f1f surfaces

## Card Types

### 1. Profile Card (Football Identity)
```
┌─────────────────────────────────────────────────┐
│ Glass Card (glass-card, p-6)                     │
│ ┌──────┐    Name: John Doe                  │
│ │Avatar│    @johndoe                         │
│ │ 100px│    [✨ Elite Fan Badge]              │
│ │      │                                       │
│ │+Club│    Points: 5,500 | Followers: 120  │
│ │Badge│    [Edit Profile] [Share Profile]    │
│ └──────┘                                       │
└─────────────────────────────────────────────────┘
```
**Classes**: `glass-card rounded-xl p-6 relative overflow-hidden`
**Special**: Stadium glow orbs (absolute positioned, blur-3xl)

### 2. Feed Post Card
```
┌─────────────────────────────────────────────────┐
│ Glass Card (glass-card, p-5)                     │
│ ┌──────┐    John Doe [ARS] 2m ago            │
│ │Avatar│    "Great match today! ⚽"              │
│ │ 40px │                                       │
│ └──────┘                                       │
│ [Media Grid if images attached]                │
│ ♥ 12  💬 5  🔗 3  [🔥 😂 💚] (hover)   │
└─────────────────────────────────────────────────┘
```
**Classes**: `glass-card rounded-xl p-5 hover:bg-surface-container/50`
**Special**: Content type badge (banter, tactical, etc.)

### 3. Match Card (Live)
```
┌─────────────────────────────────────────────────┐
│ Glass Card (glass-card, p-6)                     │
│ ┌──────┐    ┌──────┐    ┌──────┐       │
│ │Arsenal│    │ 2 - 1│    │Chelsea│       │
│ │ 64x64 │    │(72') │    │ 64x64 │       │
│ └──────┘    │ LIVE │    └──────┘       │
│              └──────┘                       │
│ [===== Heat Meter =====]                      │
└─────────────────────────────────────────────────┘
```
**Classes**: `glass-card rounded-xl p-6 border-2 border-red-200/80`
**Special**: Animated pulse on "LIVE" text

### 4. Community Card
```
┌─────────────────────────────────────────────────┐
│ Glass Card (glass-card, p-4)                     │
│ ┌──────┐    Arsenal Community                  │
│ │Logo  │    ⚽ Arsenal FC                      │
│ │ 48x48│    Members: 5,000 | Posts: 120       │
│ └──────┘    📍 Nigeria, Lagos                  │
└─────────────────────────────────────────────────┘
```
**Classes**: `glass-card rounded-xl p-4 hover:scale-[1.02] transition-transform`
**Special**: Club logo, member count, location badge

### 5. Trending Hashtag Card
```
┌─────────────────────────────────────────────────┐
│ Glass Card (hover:bg-surface-container/30)           │
│ #ArtetaOut [HOT Badge]              200 posts  → │
│ #HalaMadrid                 180 posts  → │
│ #ChelseaVsArsenal [Rising Badge] 85 posts  → │
└─────────────────────────────────────────────────┘
```
**Classes**: `flex items-center justify-between p-3 rounded-lg hover:bg-surface-container/30`
**Special**: HOT/RISING badges, one-click join arrow

### 6. Achievement/Badge Card
```
┌──────────┬──────────┬──────────┐
│ ⚽ Club   │ 🔥 Match  │ 💬 Debater │
│ Fan      │ Streak  │          │
│ (Full     │ 7 Days  │ (Outline │
│  opacity) │ (Gold    │  style)  │
│          │  border) │          │
└──────────┴──────────┴──────────┘
```
**Classes**: `flex items-center gap-2 p-2 bg-surface-container/50 rounded-lg`
**Special**: Icon (32px), name (12px label-bold), earned date

### 7. Quick Stats Card
```
┌─────────────────────────────────────────────────┐
│ Glass Card (glass-card, p-4)                     │
│ Quick Stats                                    │
│ ┌────────┐   ┌────────┐   ┌────────┐   │
│ │ 550    │   │ 8      │   │ 120    │   │
│ │Points  │   │Badges  │   │Followers│   │
│ └────────┘   └────────┘   └────────┘   │
└─────────────────────────────────────────────────┘
```
**Classes**: `glass-card rounded-xl p-4`
**Special**: Large numbers (text-headline-md), label (text-label-sm)

## Card Spacing Standards
- **Padding**: `p-4` (16px) for small cards, `p-5` (20px) for medium, `p-6` (24px) for large
- **Gaps**: `gap-3` (12px) between elements, `space-y-4` (16px) between cards
- **Border Radius**: `rounded-xl` (12px) for cards, `rounded-lg` (8px) for buttons

## Card Hover States
- **Default**: `bg-surface-container/50` (slight highlight)
- **Interactive**: `hover:scale-[1.02] transition-transform` (slight scale)
- **Quick Reactions**: `opacity-0 hover:opacity-100` (fade in on hover)
