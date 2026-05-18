# Soccer Aficionado - Digital Stadium Design System

**Version**: 1.0  
**Last Updated**: 2026-05-18  
**Brand**: The Digital Stadium — A premium, dark-mode-first football social platform evoking the visceral energy of floodlit matches.

---

## 1. Design Philosophy

Soccer Aficionado is not a news app. It's a **social ecosystem where football identity is the primary currency**. The design system reflects this by creating an environment that feels:

- **Immersive**: Glassmorphic layers suggest depth and luminescence, evoking the glow of stadium floodlights
- **Elite**: High-contrast elements and precision typography reinforce a premium, professional aesthetic
- **High-Energy**: Bold colors, dynamic animations, and responsive interactions mirror the excitement of live football
- **Community-First**: Generous spacing and visibility prioritize fan voices, debates, and tribal loyalty

The visual language combines **Glassmorphism** with **High-Contrast/Bold** elements, creating a "HUD-like" interface that appears as semi-transparent layers over an infinite pitch.

---

## 2. Color Palette: "Floodlit Pitch"

### Core Surface Colors

| Name | Hex | Usage | Notes |
|------|-----|-------|-------|
| **Deep Charcoal** | `#131313` | Primary background, surface base | Main application background; warmth without pure black |
| **Midnight Navy** | `#0A0C10` | Secondary background, depth, shadows | Used for nested/recessed areas; creates depth hierarchy |
| **Surface Bright** | `#393939` | Elevated surfaces, hover states | Lighter surface for component elevation |
| **Surface Container** | `#201f1f` | Cards, containers | Intermediate elevation surface |
| **Inverse Surface** | `#e5e2e1` | Text on dark backgrounds | High-contrast text color (near white) |

### Action & Signal Colors

| Name | Hex | Usage | Notes |
|------|-----|-------|-------|
| **Pitch Green** | `#00FF41` | Primary actions, success, highlights | Neon green for high-energy CTAs; reserved for critical elements |
| **Pitch Green Dim** | `#00e639` | Secondary green states, subtle signals | Tone-down of primary green for less critical actions |
| **Stadium White** | `#F5F5F5` | Text, borders, secondary UI | Crisp, stark contrast for maximum legibility |
| **Error** | `#ffb4ab` | Error states, warnings | Muted red for error feedback |

### Semantic Colors

| Name | Hex | Usage | Notes |
|------|-----|-------|-------|
| **Primary Container** | `#00ff41` | Active/primary button fill | Filled button background |
| **On Primary** | `#003907` | Text on primary button | High contrast text over pitch green |
| **Secondary** | `#c6c6c7` | Secondary actions, disabled states | Neutral secondary color |
| **Tertiary** | `#faf8ff` | Accent highlights, badges | Light purple for tertiary accents |

### Custom Tailwind Implementation

Add to `tailwind.config.js`:

```javascript
colors: {
  stadium: {
    charcoal: '#131313',
    navy: '#0A0C10',
    white: '#F5F5F5',
    green: '#00FF41',
    'green-dim': '#00e639',
    surface: {
      bright: '#393939',
      container: '#201f1f',
      'container-high': '#2a2a2a',
    },
    error: '#ffb4ab',
  }
}
```

---

## 3. Typography System

### Font Pairings

**Headlines & Display**: **Inter**
- Aggressive, athletic grotesque designed for high-impact headlines
- Use heavy weights (Bold 700 to Black 900)
- Tight letter-spacing to emulate stadium scoreboards
- Best for: Page titles, section headers, stat callouts

**Body & UI Labels**: **Lexend**
- Athletic, open-geometric design optimized for high-speed reading
- Excellent for dense data environments (stats, league tables)
- Maintains "active" personality while ensuring legibility
- Best for: Body text, labels, UI copy, dense information

### Type Scale

| Role | Font | Size | Weight | Line Height | Letter Spacing | Usage |
|------|------|------|--------|-------------|---|----------|
| **Display XL** | Inter | 64px | 900 | 1.1 (70px) | -0.04em | Hero headlines, splash screens |
| **Headline LG** | Inter | 32px | 800 | 1.2 (38px) | -0.02em | Page titles, major sections |
| **Headline MD** | Inter | 24px | 700 | 1.3 (31px) | 0 | Section headers, card titles |
| **Body LG** | Lexend | 18px | 400 | 1.6 (29px) | 0 | Large body text, lead paragraphs |
| **Body MD** | Lexend | 16px | 400 | 1.5 (24px) | 0 | Standard body copy, descriptions |
| **Body SM** | Lexend | 14px | 400 | 1.5 (21px) | 0 | Supporting text, captions |
| **Label Bold** | Lexend | 14px | 600 | 1.2 (17px) | 0 | Button text, strong labels |
| **Label Regular** | Lexend | 14px | 500 | 1.2 (17px) | 0 | Form labels, metadata |
| **Label SM** | Lexend | 12px | 500 | 1.2 (14px) | 0 | Tags, badges, hints |

### Tailwind Implementation

Add to `tailwind.config.js`:

```javascript
fontFamily: {
  display: ['Inter', 'sans-serif'],
  body: ['Lexend', 'sans-serif'],
},
fontSize: {
  'display-xl': ['64px', { lineHeight: '1.1', letterSpacing: '-0.04em', fontWeight: '900' }],
  'headline-lg': ['32px', { lineHeight: '1.2', letterSpacing: '-0.02em', fontWeight: '800' }],
  'headline-md': ['24px', { lineHeight: '1.3', fontWeight: '700' }],
  'body-lg': ['18px', { lineHeight: '1.6' }],
  'body-md': ['16px', { lineHeight: '1.5' }],
  'body-sm': ['14px', { lineHeight: '1.5' }],
  'label-bold': ['14px', { lineHeight: '1.2', fontWeight: '600' }],
  'label': ['14px', { lineHeight: '1.2', fontWeight: '500' }],
  'label-sm': ['12px', { lineHeight: '1.2', fontWeight: '500' }],
}
```

---

## 4. Spacing & Layout System

### Spacing Rhythm (8px Base Unit)

| Token | Value | Usage |
|-------|-------|-------|
| `xs` | 4px | Tight micro-spacing within components |
| `sm` | 12px | Internal padding, small gaps |
| `md` | 24px | Component padding, standard gaps |
| `lg` | 40px | Section margins, large gaps |
| `xl` | 64px | Major section spacing, vast gaps |
| `gutter` | 16px | Grid/container gutters |

### Grid & Container

- **Desktop**: 12-column grid with generous gutters (16-40px)
- **Tablet**: 6-column grid, adaptive spacing
- **Mobile**: Single-column with 16px edge padding
- **Container max-width**: 1440px (desktop), fluid on mobile

### Rounded Corners

| Token | Value | Usage |
|-------|-------|-------|
| `sm` | 0.125rem (2px) | Subtle rounding on minimal UI elements |
| `default` | 0.25rem (4px) | Standard rounding for cards, buttons |
| `md` | 0.375rem (6px) | Slightly more prominent rounding |
| `lg` | 0.5rem (8px) | Buttons, interactive chips |
| `xl` | 0.75rem (12px) | Large cards, elevated surfaces |
| `full` | 9999px | Fully rounded (pills, circles) |

---

## 5. Elevation & Glassmorphism

The design uses **Glassmorphism** instead of traditional drop shadows to create depth hierarchy while maintaining the "HUD" aesthetic.

### Layer Structure

1. **Base Layer** (Layer 0): Solid Deep Charcoal (#131313) or Midnight Navy (#0A0C10)
2. **Surface Layer** (Layer 1): Semi-transparent Stadium White (15-20% opacity) with background blur (12-20px)
3. **Border Layer**: 1px stroke with gradient (Stadium White at 20% to Transparent)
4. **Glow Layer**: Soft outer glow for active/priority elements (Pitch Green at 10% spread)

### Glassmorphic Card

```css
.glass-card {
  background: rgba(245, 245, 245, 0.15);
  backdrop-filter: blur(16px);
  border: 1px solid rgba(245, 245, 245, 0.2);
  border-image: linear-gradient(to right, rgba(245, 245, 245, 0.2), transparent) 1;
  border-radius: 0.5rem;
}

.glass-card.active {
  box-shadow: 0 0 24px rgba(0, 255, 65, 0.1);
}
```

### Floodlight Glow (for active/priority)

```css
.floodlight-glow {
  box-shadow: 0 0 16px rgba(0, 255, 65, 0.15), inset 0 0 8px rgba(0, 255, 65, 0.05);
}

.floodlight-glow:hover {
  box-shadow: 0 0 24px rgba(0, 255, 65, 0.2), inset 0 0 8px rgba(0, 255, 65, 0.08);
}
```

---

## 6. Component Guidelines

### Buttons

**Primary Button** (Pitch Green Fill)
- Background: `#00FF41`
- Text: `#003907` (black)
- Padding: `12px 24px` (md label-bold)
- Border Radius: `0.5rem` (lg)
- Gradient: Subtle top-down gradient (lighten 10% at top)
- Focus: Inner Pitch Green glow + outer glow
- Hover: Brighten by 10%

```jsx
<button className="px-6 py-3 bg-stadium-green text-on-primary font-label-bold rounded-lg hover:bg-stadium-green hover:brightness-110 focus:ring-2 focus:ring-stadium-green focus:ring-offset-2 focus:ring-offset-stadium-charcoal transition-all">
  Primary Action
</button>
```

**Secondary Button** (Stadium White Stroke)
- Background: Transparent + glassmorphic
- Border: 1px Stadium White
- Text: Stadium White
- Padding: `12px 24px`
- Focus: Pitch Green border + glow

```jsx
<button className="px-6 py-3 border border-stadium-white bg-white/10 backdrop-blur-lg text-stadium-white rounded-lg hover:bg-white/15 focus:border-stadium-green focus:ring-2 focus:ring-stadium-green transition-all">
  Secondary Action
</button>
```

### Cards

**Glass Card (Standard)**
- Glassmorphic background + borders (see Glassmorphism section)
- Padding: `24px` (md)
- Internal spacing: `12px` (sm) between elements
- No shadow — rely on border + transparency for hierarchy

**Live Match Card** (with pulse)
- Same as Glass Card, plus:
- Border: Pitch Green with animation pulse
- Pulse animation: 2s ease-in-out infinite

```jsx
<div className="bg-white/15 backdrop-blur-lg border border-stadium-green rounded-lg p-6 animate-pulse">
  {/* Live content */}
</div>
```

### Input Fields

- Background: Recessed well (Midnight Navy or darker)
- Border: 1px Stadium White (bottom only by default)
- Padding: `12px 16px`
- Focus: Border changes to Pitch Green with faint glow beneath
- Placeholder: Stadium White at 40% opacity

```jsx
<input className="w-full bg-stadium-navy border-b border-stadium-white px-4 py-3 text-stadium-white placeholder:text-white/40 focus:border-b-2 focus:border-stadium-green focus:outline-none transition-colors" />
```

### Badges & Labels

- Background: Glassmorphic (Stadium White 10% opacity)
- Text: Stadium White or Pitch Green (for active states)
- Border: 1px Stadium White or Pitch Green
- Padding: `6px 12px` (sm)
- Border Radius: `full` (pill)

---

## 7. Animations & Interactions

### Motion Principles

- **Duration**: 200-300ms for micro-interactions, 500-800ms for transitions
- **Easing**: `ease-in-out` for smooth, athletic feel
- **Restraint**: Avoid over-animation; reserve motion for feedback and state changes

### Core Animations

| Animation | Duration | Easing | Usage |
|-----------|----------|--------|-------|
| **Fade In** | 300ms | ease-out | Content loading, modal entry |
| **Slide Up** | 300ms | ease-out | Feed items, notifications |
| **Pulse Glow** | 2s | ease-in-out | Live events, active elements |
| **Bounce** | 600ms | ease-in-out | Achievement badges, goal reactions |
| **Shimmer** | 2s | ease-in-out | Loading placeholders, skeleton screens |
| **Rotate** | 2s | linear | Loading spinners |

### Tailwind Animation Config

```javascript
animation: {
  'pulse-glow': 'pulse-glow 2s ease-in-out infinite',
  'slide-up': 'slide-up 300ms ease-out',
  'bounce-in': 'bounce-in 600ms ease-in-out',
  'shimmer': 'shimmer 2s ease-in-out infinite',
}

keyframes: {
  'pulse-glow': {
    '0%, 100%': { opacity: '1', boxShadow: '0 0 8px rgba(0, 255, 65, 0.1)' },
    '50%': { opacity: '0.8', boxShadow: '0 0 16px rgba(0, 255, 65, 0.2)' },
  },
  'slide-up': {
    'from': { opacity: '0', transform: 'translateY(16px)' },
    'to': { opacity: '1', transform: 'translateY(0)' },
  },
  'bounce-in': {
    '0%': { transform: 'scale(0.8)', opacity: '0' },
    '50%': { transform: 'scale(1.05)' },
    '100%': { transform: 'scale(1)', opacity: '1' },
  },
  'shimmer': {
    '0%': { backgroundPosition: '-1000px 0' },
    '100%': { backgroundPosition: '1000px 0' },
  },
}
```

---

## 8. Component Library

### Existing & Planned Components

#### Navigation
- **Bottom Navigation** (5 tabs: Home, Trending, Communities, Match Rooms, Profile)
- **Navbar** (Top header with logo, search, user menu)
- **Breadcrumbs** (Navigation trail)

#### Content Display
- **Feed Card** (Post/banter container with reactions)
- **Match Card** (Live match info + engagement)
- **Club Badge** (Club loyalty badge)
- **Player Card** (Player profile mini)
- **Leaderboard Item** (User ranking row)

#### Forms & Input
- **Text Input** (Search, form fields)
- **Toggle** (On/Off switches)
- **Chip Group** (Multi-select tags)
- **Textarea** (Comment input)

#### Feedback
- **Toast Notification** (Success, error, info)
- **Modal** (Overlay dialog)
- **Loading Spinner** (Animated loader)
- **Skeleton Screen** (Content placeholder)

#### Data Visualization
- **Pitch Visualizer** (Tactical top-down view)
- **Live Score Ticker** (Scrolling scoreboard)
- **Stat Bar** (Progress bars, metrics)
- **Sentiment Meter** (Community mood visualization)

---

## 9. Accessibility & Contrast

### WCAG 2.1 Compliance

- **Contrast Ratio**: Aim for 4.5:1 minimum for body text, 3:1 for large text
- **Color Blindness**: Don't rely on color alone; use icons, patterns, or text labels
- **Focus States**: Always provide visible focus indicators (Pitch Green ring)
- **Motion**: Respect `prefers-reduced-motion` setting
- **Typography**: Maintain line height ≥ 1.5 for readability

### Dark Mode Considerations

- Stadium colors already optimize for dark mode
- Stadium White (near white) at 100% on Deep Charcoal passes WCAG AAA
- Pitch Green at full saturation passes WCAG AA for medium text
- All interactive elements must have visible focus states

---

## 10. Design Tokens Reference

### Quick Implementation Checklist

- [ ] Install fonts: Inter (Lexend optional if using Google Fonts)
- [ ] Update `tailwind.config.js` with color, font, and animation configs
- [ ] Create Tailwind utility classes for `.glass-card`, `.floodlight-glow`, etc.
- [ ] Build component library in `resources/views/components/`
- [ ] Create Blade components for buttons, cards, inputs, badges
- [ ] Test all components in dark mode with accessibility tools (axe, Lighthouse)
- [ ] Document usage in Storybook or Blade component explorer

---

## 11. Migration Path from Current Design

### Phase 1: Foundation (Immediate)
1. Update Tailwind config with new colors and typography
2. Create design tokens CSS/Tailwind utilities
3. Build core button and card components

### Phase 2: Components (Week 1)
4. Redesign input fields, badges, labels
5. Update icons to thin-stroke football theme
6. Build navigation components

### Phase 3: Pages (Week 2)
7. Redesign home feed with new cards
8. Update profile identity card
9. Redesign trending section
10. Update match room UI

### Phase 4: Polish (Week 3)
11. Fine-tune animations and micro-interactions
12. Comprehensive accessibility audit
13. Performance optimization
14. Deploy with A/B testing

---

## 12. Resources & References

- **Design Inspiration**: Glassmorphism (uxdesign.cc), Dark Mode UX (nielsennormangroup.com)
- **Component Library**: Headless UI, Radix UI (patterns)
- **Color Tool**: Coolors.co, Color Blind Simulator (Coblis)
- **Typography**: Inter GitHub (rsms/inter), Lexend on Google Fonts
- **Animation**: Framer Motion, Tailwind's built-in animations

---

**Questions? Issues?** Contact the design systems team or create an issue in the repo.  
**Last Updated**: 2026-05-18 | **Version**: 1.0
