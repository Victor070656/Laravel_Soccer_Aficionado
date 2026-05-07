# Recommended Animations/Interactions - Soccer Aficionado

## Core Animation Principles
- **Stadium-Inspired**: Mimic floodlights, crowd energy, pitch atmosphere
- **Performance-First**: Use CSS animations, avoid JavaScript-heavy effects
- **Purposeful**: Every animation must enhance the football experience

## Animation Catalog

### 1. Pulse Glow (Live Indicators)
**Usage**: Live match badges, active polls, online users
**CSS**:
```css
@keyframes pulse-glow {
    0%, 100% { box-shadow: 0 0 20px rgba(0, 255, 65, 0.3); }
    50% { box-shadow: 0 0 40px rgba(0, 255, 65, 0.6); }
}
```
**Implementation**: Add `animate-pulse-glow` class to live indicators

### 2. Emoji Storm (Fan Excitement)
**Usage**: When 3+ fans react with same emoji in 5 seconds
**CSS**:
```css
@keyframes float {
    0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
    10% { opacity: 0.5; }
    90% { opacity: 0.5; }
    100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
}
```
**Implementation**: Fixed overlay with 5-10 floating emojis, random positions

### 3. Bounce Subtle (Interactive Elements)
**Usage**: Emoji reaction buttons, post type selector
**CSS**:
```css
@keyframes bounce-subtle {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}
```
**Implementation**: Add `hover:animate-bounce-subtle` on interactive elements

### 4. Fade In (New Content)
**Usage**: New comments appearing in match rooms
**CSS**:
```css
@keyframes fade-in {
    0% { opacity: 0; transform: translateY(10px); }
    100% { opacity: 1; transform: translateY(0); }
}
```
**Implementation**: New comments get `animate-fade-in` class

### 5. Shimmer (Loading States)
**Usage**: Loading skeleton for feed, profile, communities
**CSS**:
```css
@keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
}
.skeleton {
    background: linear-gradient(90deg, #2a2a2a 0%, #3a3a3a 50%, #2a2a2a 100%);
    background-size: 1000px 100%;
    animation: shimmer 2s infinite linear;
}
```

### 6. Heat Meter Pulse (Room Activity)
**Usage**: Match room heat meter bar
**Logic**: Color transitions based on activity count
- CALM: `bg-outline-variant` (gray)
- LOW: `bg-secondary` (light gray)
- MEDIUM: `bg-tertiary-fixed-dim` (purple)
- HIGH: `bg-primary-container` (Pitch Green)
- EXTREME: `bg-error` (red) with pulse animation

### 7. Scale on Hover (Interactive Cards)
**Usage**: Post cards, community cards, trending topics
**Implementation**: Add `hover:scale-[1.02]` or `hover:scale-105` classes

### 8. Crowd Noise (Pitch Visualizer)
**Concept**: Stadium-style animated lines representing crowd energy
**Implementation** (Future):
```css
.pitch-visualizer {
    background: repeating-linear-gradient(
        90deg,
        transparent,
        transparent 2px,
        rgba(0, 255, 65, 0.3) 2px,
        rgba(0, 255, 65, 0.3) 4px
    );
    animation: pitch-wave 0.5s infinite linear;
}
```

## Interaction Patterns

### Quick Reactions (Hover to Reveal)
**Desktop**: Hover over comment → Emoji bar slides up
**Mobile**: Tap reaction button → Emoji bar expands
**Animation**: `transition-all duration-200`

### Live Score Update
**Trigger**: Goal scored in match room
**Animation**: Score number scales up (1.5x) then back to normal
**Duration**: 300ms with ease-out

### Fan Momentum Bar
**Trigger**: New comment posted supporting home/away
**Animation**: Width transitions smoothly (500ms) to new percentage
**Color**: Gradient from `primary-container` (home) to `secondary` (away)

## Performance Guidelines
- **Limit Animations**: Max 3-5 simultaneous animations per page
- **Use GPU**: Prefer `transform` and `opacity` (GPU-accelerated)
- **Fallback**: `prefers-reduced-motion` media query disables animations
- **Cleanup**: Remove animation classes when element leaves DOM
