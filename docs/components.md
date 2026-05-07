# Component System Ideas - Soccer Aficionado

## Core Component Library (Built with Tailwind + Flux)

### 1. Glass Card (`.glass-card`)
**Usage**: Primary container for all content sections
**Implementation**:
```html
<div class="glass-card rounded-xl p-6 relative overflow-hidden">
    <!-- Stadium Glow Background -->
    <div class="absolute -top-20 -right-20 h-40 w-40 rounded-full bg-primary-container/10 blur-3xl"></div>
    <!-- Content -->
    <div class="relative z-10">
        <!-- Your content here -->
    </div>
</div>
```

### 2. Badge Live (`.badge-live`)
**Usage**: Live indicators, hot topics, breaking news
**Implementation**:
```html
<span class="badge-live">LIVE</span>
<!-- Pitch Green background, dark text, pulse animation -->
```

### 3. Gradient Text (`.gradient-text`)
**Usage**: Headlines, brand names
**Implementation**:
```html
<h1 class="gradient-text">Soccer Aficionado</h1>
<!-- Pitch Green gradient (primary-container to primary-fixed) -->
```

### 4. Text Neon (`.text-neon`)
**Usage**: Important numbers, live scores
**Implementation**:
```html
<div class="text-neon text-4xl">2 - 1</div>
<!-- Text shadow with Pitch Green glow -->
```

### 5. Pulse Glow Animation (`.animate-pulse-glow`)
**Usage**: Live match indicators, active polls
**Implementation**:
```html
<span class="relative flex h-3 w-3">
    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-container opacity-75"></span>
    <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-container"></span>
</span>
```

### 6. Stadium Glow Background
**Usage**: Major pages (profile, feed, trending, match rooms)
**Implementation**:
```html
<div class="fixed inset-0 pointer-events-none overflow-hidden">
    <div class="absolute top-0 left-1/4 h-96 w-96 rounded-full bg-primary-container/5 blur-3xl"></div>
    <div class="absolute bottom-0 right-1/4 h-96 w-96 rounded-full bg-secondary/5 blur-3xl"></div>
</div>
```

## Football-Specific Components

### 7. Match Header
```html
<div class="glass-card rounded-xl p-6">
    <!-- Teams + Score -->
    <div class="flex items-center justify-between">
        <div class="text-center">
            <img src="arsenal-logo.png" class="h-16 w-16 mx-auto">
            <div class="text-sm font-bold">Arsenal</div>
        </div>
        <div class="text-center">
            <div class="text-4xl font-black text-primary-container">2 - 1</div>
            <div class="text-xs text-primary-container">72' LIVE</div>
        </div>
        <div class="text-center">
            <img src="chelsea-logo.png" class="h-16 w-16 mx-auto">
            <div class="text-sm font-bold">Chelsea</div>
        </div>
    </div>
    <!-- Heat Meter -->
    <div class="mt-4 h-2 bg-surface-container-high rounded-full overflow-hidden">
        <div class="h-full bg-primary-container rounded-full" style="width: 75%"></div>
    </div>
</div>
```

### 8. Fan Momentum Bar
```html
<div class="h-3 rounded-full overflow-hidden bg-surface-container-high">
    <div class="bg-primary-container/80 h-full" style="width: 60%"></div>
    <div class="bg-secondary/80 h-full" style="width: 40%"></div>
</div>
```

### 9. Heat Meter
```html
<div class="flex items-center gap-2">
    <div class="flex-1 h-2 rounded-full bg-error animate-pulse"> <!-- EXTREME -->
    <span class="text-xs font-bold text-error">EXTREME (35)</span>
</div>
```

### 10. Comment Card
```html
<div class="glass-card rounded-xl p-4 hover:bg-surface-container/50 transition-colors">
    <div class="flex items-start gap-3">
        <img src="avatar.jpg" class="h-10 w-10 rounded-lg object-cover">
        <div class="flex-1">
            <div>
                <span class="font-bold text-on-surface">John Doe</span>
                <span class="text-xs px-1.5 py-0.5 rounded-full bg-primary-container/20 text-primary-container">[ARS]</span>
                <span class="text-xs text-on-surface-variant">2m ago</span>
            </div>
            <p class="mt-1 text-on-surface">Great goal! ⚽</p>
            <div class="mt-2 flex gap-4 text-xs text-on-surface-variant">
                <span>♥ 5</span>
                <span>💬 2</span>
            </div>
        </div>
    </div>
</div>
```

### 11. Quick Reaction Buttons
```html
<div class="flex gap-2">
    <button class="px-3 py-2 rounded-lg bg-surface-container-high hover:scale-110 transition-transform">
        ⚽ <span class="text-xs">Goal</span>
    </button>
    <!-- Repeat for 🔥, 💚, 😂, etc. -->
</div>
```

## Component Utilities
- **Cards**: Always use `glass-card` class
- **Buttons**: `bg-primary-container` for primary, `bg-surface-container-high` for secondary
- **Text**: `text-on-surface` for primary, `text-on-surface-variant` for secondary
- **Borders**: `border-outline-variant/40` for subtle separation
- **Spacing**: `p-4` (16px) for cards, `gap-3` (12px) for elements
