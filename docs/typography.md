# Typography Direction - Soccer Aficionado

## Font Families

### Inter (Headlines & Display)
- **Usage**: Display text, headlines, statistics, numbers
- **Weights**: 900 (Black), 800 (ExtraBold), 700 (Bold)
- **Letter Spacing**: -0.04em (display-xl), -0.02em (headline-lg)
- **Personality**: "Condensed grotesque" - Athletic, aggressive, like stadium scoreboards

### Lexend (Body & UI)
- **Usage**: Body text, UI labels, form inputs, captions
- **Weights**: 400 (Regular), 500 (Medium), 600 (Semibold)
- **Personality**: "Athletic open-geometric" - High legibility for data-heavy environments

## Typography Scale (Tailwind v4 `@theme` Object Syntax)

### Display & Headlines
```css
--text-display-xl: 64px {
    font-family: var(--font-sans);  /* Inter */
    font-weight: 900;               /* Black */
    line-height: 1.1;
    letter-spacing: -0.04em;     /* Tight for impact */
}

--text-headline-lg: 32px {
    font-family: var(--font-sans);  /* Inter */
    font-weight: 800;               /* ExtraBold */
    line-height: 1.2;
    letter-spacing: -0.02em;     /* Tight for scoreboard feel */
}

--text-headline-md: 24px {
    font-family: var(--font-sans);  /* Inter */
    font-weight: 700;               /* Bold */
    line-height: 1.3;
}
```

### Body Text
```css
--text-body-lg: 18px {
    font-family: var(--font-display);  /* Lexend */
    font-weight: 400;                   /* Regular */
    line-height: 1.6;
}

--text-body-md: 16px {
    font-family: var(--font-display);  /* Lexend */
    font-weight: 400;
    line-height: 1.5;
}
```

### UI Labels
```css
--text-label-bold: 14px {
    font-family: var(--font-display);  /* Lexend */
    font-weight: 600;                   /* Semibold */
    line-height: 1.2;
}

--text-label-sm: 12px {
    font-family: var(--font-display);  /* Lexend */
    font-weight: 500;                   /* Medium */
    line-height: 1.2;
}
```

## Uppercase Styling (Professional Sports Aesthetic)
- **Labels**: `text-label-bold uppercase tracking-wider`
- **Secondary Buttons**: `uppercase tracking-[0.28em]` (like "LIVE", "HOT", "NEW")
- **Navigation**: `uppercase` for all tab labels

## Number Styles (Scoreboards)
- **Match Scores**: `text-display-xl` (64px, Inter 900, tight spacing)
- **Points**: `text-headline-md` (24px, Inter 700)
- **Stats**: `text-body-md` (16px, Lexend 400, tabular-nums)

## Usage Examples

### Profile Name
```html
<h1 class="text-headline-lg text-on-surface">John Doe</h1>
<!-- Inter 32px, 800 weight, -0.02em spacing -->
```

### Fan Ranking (Large Display)
```html
<div class="text-display-xl text-primary-container">ELITE FAN</div>
<!-- Inter 64px, 900 weight, -0.04em spacing, glow text -->
```

### Body Text
```html
<p class="text-body-md text-on-surface leading-relaxed">Great match today!</p>
<!-- Lexend 16px, 400 weight, 1.5 line-height -->
```

### UI Label (Badge)
```html
<span class="text-label-bold text-on-surface-variant uppercase tracking-wider">Live</span>
<!-- Lexend 14px, 600 weight, uppercase -->
```
