# Design System Direction - Soccer Aficionado

## Digital Stadium Design Guide

### Color Palette (Tailwind v4 `@theme` Block)
```css
--color-background: #131313;        /* Deep Charcoal - Core surface */
--color-on-background: #e5e2e1;     /* Stadium White - Text */
--color-surface: #131313;             /* Same as background */
--color-surface-container: #201f1f;      /* Slightly lighter for cards */
--color-surface-container-high: #2a2a2a; /* Hover states */
--color-on-surface: #e5e2e1;          /* Primary text */
--color-on-surface-variant: #b9ccb2;   /* Secondary text */
--color-primary: #ebffe2;              /* Light green for text on green */
--color-primary-container: #00ff41;      /* Pitch Green - Actions, glows */
--color-on-primary-container: #003907;   /* Dark text on green */
--color-secondary: #c6c6c7;           /* Stadium White variant */
--color-tertiary-fixed-dim: #c2c6db; /* Purple tint for accents */
--color-error: #ffb4ab;               /* Red for heat EXTREME */
--color-outline: #84967e;              /* Green-tint borders */
```

### Typography (Inter + Lexend)
```css
--font-sans: "Inter", ui-sans-serif, system-ui, sans-serif;  /* Headlines */
--font-display: "Lexend", ui-sans-serif, system-ui, sans-serif; /* Body */

/* Custom text sizes with object syntax */
--text-display-xl: 64px {
    font-family: var(--font-sans);
    font-weight: 900;
    line-height: 1.1;
    letter-spacing: -0.04em;
}

--text-headline-lg: 32px {
    font-family: var(--font-sans);
    font-weight: 800;
    line-height: 1.2;
    letter-spacing: -0.02em;
}

--text-body-md: 16px {
    font-family: var(--font-display);
    font-weight: 400;
    line-height: 1.5;
}
```

### Spacing (8px Base Unit)
```css
--spacing-base: 8px;    /* Core unit */
--spacing-xs: 4px;       /* Tight packing */
--spacing-sm: 12px;      /* Compact gaps */
--spacing-md: 24px;      /* Section spacing */
--spacing-lg: 40px;      /* Major sections */
--spacing-xl: 64px;      /* Page margins */
--spacing-gutter: 16px;   /* Grid gutters */
```

### Border Radius (Soft Precision)
```css
--radius-sm: 0.125rem;   /* 2px - Micro elements */
--radius: 0.25rem;       /* 4px - Default */
--radius-md: 0.375rem;   /* 6px - Cards */
--radius-lg: 0.5rem;     /* 8px - Buttons */
--radius-xl: 0.75rem;   /* 12px - Large cards */
--radius-full: 9999px;    /* Full pill (rarely used) */
```

## Glassmorphism Components

### Glass Card Base
```css
.glass-card {
    background: rgba(255, 255, 255, 0.15);  /* 15% opacity */
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid transparent;
    position: relative;
}

/* Metallic edge effect */
.glass-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    border-radius: inherit;
    padding: 1px;
    background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, transparent 100%);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    pointer-events: none;
}
```

### Pitch Green Glow (Floodlight Effect)
```css
.text-neon {
    text-shadow:
        0 0 10px rgba(0, 255, 65, 0.75),
        0 0 24px rgba(0, 255, 65, 0.25);
}

.badge-live {
    background: var(--color-primary-container);
    color: var(--color-on-primary-container);
    box-shadow: 0 0 18px rgba(0, 255, 65, 0.28);
}
```

## Stadium Glow Backgrounds
```css
/* Used on major pages (profile, feed, trending, match rooms) */
<div class="fixed inset-0 pointer-events-none overflow-hidden">
    <div class="absolute top-0 left-1/4 h-96 w-96 rounded-full bg-primary-container/5 blur-3xl"></div>
    <div class="absolute bottom-0 right-1/4 h-96 w-96 rounded-full bg-secondary/5 blur-3xl"></div>
</div>
```

## Animation Guidelines

### Pulse Glow (Live Indicators)
```css
@keyframes pulse-glow {
    0%, 100% { box-shadow: 0 0 20px rgba(0, 255, 65, 0.3); }
    50% { box-shadow: 0 0 40px rgba(0, 255, 65, 0.6); }
}
```

### Emoji Storm (Floating Animations)
```css
@keyframes float {
    0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
    10% { opacity: 0.5; }
    90% { opacity: 0.5; }
    100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
}
```

## Component Utilities
- `.glass-card`: Glassmorphism card base
- `.badge-live`: Live indicator with Pitch Green glow
- `.gradient-text`: Pitch Green gradient text
- `.text-neon`: Pitch Green text glow
- `.pulse-glow`: Animated live pulse
