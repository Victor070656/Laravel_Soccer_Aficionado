# Suggested Color Palette - Soccer Aficionado

## Digital Stadium Palette (Implemented in Tailwind v4)

### Background & Surface Colors
| Variable | Hex | Usage |
|-----------|-----|-------|
| `--color-background` | `#131313` | Deep Charcoal - Page backgrounds |
| `--color-surface` | `#131313` | Same as background |
| `--color-surface-dim` | `#131313` | Lowest elevation |
| `--color-surface-container-lowest` | `#0e0e0e` | Deepest container |
| `--color-surface-container-low` | `#1c1b1b` | Low elevation |
| `--color-surface-container` | `#201f1f` | Default cards |
| `--color-surface-container-high` | `#2a2a2a` | Hover states |
| `--color-surface-container-highest` | `#353534` | Highest elevation |
| `--color-surface-variant` | `#353534` | Alternative surface |

### Text & Foreground Colors
| Variable | Hex | Usage |
|-----------|-----|-------|
| `--color-on-background` | `#e5e2e1` | Stadium White - Primary text |
| `--color-on-surface` | `#e5e2e1` | Text on surfaces |
| `--color-on-surface-variant` | `#b9ccb2` | Secondary text (muted) |
| `--color-inverse-surface` | `#e5e2e1` | Inverse surface text |
| `--color-inverse-on-surface` | `#313030` | Dark text on light |

### Primary & Action Colors (Pitch Green)
| Variable | Hex | Usage |
|-----------|-----|-------|
| `--color-primary` | `#ebffe2` | Light green - Text on green |
| `--color-on-primary` | `#003907` | Dark text on primary |
| `--color-primary-container` | `#00ff41` | **Pitch Green** - Actions, glows |
| `--color-on-primary-container` | `#007117` | Dark text on green |
| `--color-primary-fixed` | `#72ff70` | Fixed primary variant |
| `--color-primary-fixed-dim` | `#00e639` | Dimmed primary |
| `--color-surface-tint` | `#00e639` | Surface tint (focus rings) |

### Secondary Colors
| Variable | Hex | Usage |
|-----------|-----|-------|
| `--color-secondary` | `#c6c6c7` | Stadium White variant |
| `--color-on-secondary` | `#2f3131` | Dark text on secondary |
| `--color-secondary-container` | `#454747` | Secondary containers |
| `--color-secondary-fixed` | `#e2e2e2` | Fixed secondary |
| `--color-secondary-fixed-dim` | `#c6c6c7` | Dimmed secondary |

### Tertiary Colors
| Variable | Hex | Usage |
|-----------|-----|-------|
| `--color-tertiary` | `#faf8ff` | Light purple tint |
| `--color-on-tertiary` | `#2b3040` | Dark text on tertiary |
| `--color-tertiary-container` | `#d8dcf2` | Tertiary containers |
| `--color-tertiary-fixed` | `#dee1f7` | Fixed tertiary |
| `--color-tertiary-fixed-dim` | `#c2c6db` | Dimmed tertiary |

### Status & Outline Colors
| Variable | Hex | Usage |
|-----------|-----|-------|
| `--color-error` | `#ffb4ab` | Red - EXTREME heat, errors |
| `--color-on-error` | `#690005` | Dark text on error |
| `--color-error-container` | `#93000a` | Error containers |
| `--color-outline` | `#84967e` | Green-tint borders |
| `--color-outline-variant` | `#3b4b37` | Muted borders |

## Stadium Glow Effects
- **Pitch Green Glow**: `box-shadow: 0 0 20px rgba(0, 255, 65, 0.3)`
- **Live Pulse**: `box-shadow: 0 0 40px rgba(0, 255, 65, 0.6)` (50% opacity)
- **Floodlight**: `background: radial-gradient(circle, rgba(0,255,65,0.1) 0%, transparent 70%)`

## Club Color Usage Guidelines
- **Primary Identity**: Use user's club colors subtly (logo, badge only)
- **Backgrounds**: Always use Digital Stadium palette (#131313, #201f1f)
- **Actions**: Always Pitch Green (#00ff41) for CTAs
- **Balance**: Club colors ≤ 10% of UI, Stadium palette ≥ 90%

## Dark Mode Dominance
- **Background**: Always dark (#131313)
- **Cards**: Glassmorphism with 15-20% white opacity
- **Text**: Stadium White (#e5e2e1) for legibility
- **Accents**: Pitch Green (#00ff41) for premium feel
