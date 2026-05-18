/**
 * Soccer Aficionado - Icon System Reference
 * Google Material Symbols with football-friendly aliases
 * Last Updated: 2026-05-18
 * 
 * All icons are available via the <x-icon name="..." /> component
 * Uses Google Material Symbols API
 */

// ============================================================================
// ICON USAGE
// ============================================================================

/*
 * Basic usage (outlined):
 *   <x-icon name="home" />
 *   <x-icon name="pitch" />
 *   <x-icon name="match-room" />
 * 
 * With size options:
 *   <x-icon name="home" size="xs" />      <!-- Extra small -->
 *   <x-icon name="home" size="sm" />      <!-- Small -->
 *   <x-icon name="home" size="md" />      <!-- Medium (default) -->
 *   <x-icon name="home" size="lg" />      <!-- Large -->
 *   <x-icon name="home" size="xl" />      <!-- Extra large -->
 * 
 * With filled variant:
 *   <x-icon name="home" filled />
 *   <x-icon name="heart" filled />
 * 
 * With custom styling:
 *   <x-icon name="home" class="text-primary-container" />
 *   <x-icon name="trophy" class="text-success" />
 */

// ============================================================================
// FOOTBALL-THEMED ICONS
// ============================================================================

const FOOTBALL_ICONS = {
  // Navigation
  home: 'home',                  // Home/Dashboard
  'match-room': 'sports_soccer', // Match rooms/Live games
  communities: 'groups',         // Communities/Clubs
  trending: 'trending_up',       // Trending topics
  profile: 'person',             // User profile
  
  // Football Specific
  pitch: 'sports_soccer',        // Football pitch/field
  ball: 'sports_soccer',         // Football ball
  jersey: 'sports_jersey',       // Player jersey
  trophy: 'sports_trophy',       // Trophy/Achievement
  whistle: 'sports_bar',         // Referee whistle
  team: 'groups',                // Team/squad
  player: 'person',              // Player profile
  coach: 'school',               // Coach/Manager
  flag: 'flag',                  // Country flag
  
  // Actions
  search: 'search',              // Search
  filter: 'tune',                // Filter
  sort: 'sort',                  // Sort
  edit: 'edit',                  // Edit
  delete: 'delete',              // Delete
  add: 'add',                    // Add/Create
  download: 'download',          // Download
  upload: 'upload',              // Upload
  
  // Engagement
  heart: 'favorite',             // Like/Love reaction
  comment: 'chat_bubble',        // Comment
  share: 'share',                // Share
  
  // UI Controls
  settings: 'settings',          // Settings
  bell: 'notifications',         // Notifications
  menu: 'menu',                  // Menu
  close: 'close',                // Close
  back: 'arrow_back',            // Back
  forward: 'arrow_forward',      // Forward
  more: 'more_vert',             // More options (vertical)
  
  // Navigation
  clock: 'schedule',             // Time/Schedule
  calendar: 'event',             // Calendar/Date
  location: 'location_on',       // Location/Venue
  link: 'link',                  // Link
  external: 'open_in_new',       // External link
  
  // Status
  star: 'star',                  // Star/Rating
  verified: 'verified',          // Verified badge
  success: 'check_circle',       // Success state
  error: 'error',                // Error state
  warning: 'warning',            // Warning state
  info: 'info',                  // Info state
  help: 'help',                  // Help
  
  // View Options
  'view-list': 'view_list',      // List view
  'view-grid': 'grid_view',      // Grid view
};

// ============================================================================
// SIZE VARIANTS
// ============================================================================

const ICON_SIZES = {
  xs: {
    class: 'text-xs leading-none',
    usage: 'Inline badges, small indicators',
  },
  sm: {
    class: 'text-sm leading-none',
    usage: 'Form inputs, mini badges',
  },
  md: {
    class: 'text-lg leading-none',
    usage: 'Navigation tabs, standard UI (DEFAULT)',
  },
  lg: {
    class: 'text-2xl leading-none',
    usage: 'Page headers, featured sections',
  },
  xl: {
    class: 'text-3xl leading-none',
    usage: 'Hero sections, large displays',
  },
};

// ============================================================================
// ICON STYLES
// ============================================================================

/*
 * Outlined (default):
 *   <x-icon name="home" />
 *   └─ Thin stroke, minimal fill
 *   └─ Use for: Standard UI, navigation, actions
 * 
 * Filled:
 *   <x-icon name="home" filled />
 *   └─ Solid fill
 *   └─ Use for: Active states, emphasis, alerts
 */

// ============================================================================
// COLOR VARIATIONS
// ============================================================================

/*
 * Using Tailwind classes:
 *   <x-icon name="heart" class="text-primary-container" />
 *   <x-icon name="trophy" class="text-success" />
 *   <x-icon name="error" class="text-error" />
 *   <x-icon name="warning" class="text-on-surface-variant" />
 * 
 * Available color classes:
 *   • text-primary-container   (Pitch Green - actions)
 *   • text-on-surface          (Stadium White - default)
 *   • text-on-surface-variant  (Muted - secondary)
 *   • text-error               (Red - errors)
 *   • text-surface-tint        (Pitch Green dim)
 */

// ============================================================================
// COMPONENT EXAMPLES
// ============================================================================

/*
 * BOTTOM NAVIGATION (5-tab mobile-first)
 * <x-bottom-nav-redesign active-tab="home" />
 * 
 * Renders:
 *   [Home Icon]    [Trending Icon]    [Communities Icon]    [Match Icon]    [Profile Icon]
 *   Home           Trending           Communities           Matches         Profile
 * 
 * Each tab shows:
 *   • Outlined icon when inactive
 *   • Filled icon when active
 *   • Text label below icon
 *   • Color changes to Pitch Green (#00FF41) when active
 * 
 * Usage in layout:
 *   <x-bottom-nav-redesign active-tab="home" />
 *   {/* Page content above */}
 */

// ============================================================================
// IMPLEMENTING IN TEMPLATES
// ============================================================================

/*
 * In a feed item:
 *   <div class="flex gap-2">
 *     <x-icon name="heart" class="text-on-surface-variant" />
 *     <span>123 likes</span>
 *   </div>
 * 
 * In a button:
 *   <x-button-primary class="flex items-center gap-2">
 *     <x-icon name="add" />
 *     Create Post
 *   </x-button-primary>
 * 
 * In navigation:
 *   <a href="/search" class="flex items-center gap-2">
 *     <x-icon name="search" />
 *     Search
 *   </a>
 * 
 * Active indicator:
 *   <div class="relative">
 *     <x-icon name="match-room" size="lg" />
 *     @if($liveMatches > 0)
 *       <span class="absolute top-0 right-0 w-2 h-2 bg-error rounded-full"></span>
 *     @endif
 *   </div>
 */

// ============================================================================
// ACCESSIBILITY
// ============================================================================

/*
 * Icons include:
 *   • role="img" for semantic meaning
 *   • aria-hidden="true" for decorative icons
 *   • Paired with text labels in critical UI (buttons, nav, etc)
 * 
 * Best practices:
 *   ✓ Always pair decorative icons with visible text labels in navigation
 *   ✓ Use filled icons for active/selected states
 *   ✓ Use consistent sizing throughout sections
 *   ✓ Test icon contrast (should be 4.5:1 minimum)
 *   ✓ Provide aria-label for icon-only buttons
 *
 * Example with aria-label:
 *   <button aria-label="Delete post">
 *     <x-icon name="delete" />
 *   </button>
 */

// ============================================================================
// THEMING & CUSTOMIZATION
// ============================================================================

/*
 * Default colors (using CSS custom properties):
 *   Outlined icons:   var(--color-on-surface-variant)
 *   Active icons:     var(--color-primary-container)
 *   Error icons:      var(--color-error)
 * 
 * To customize globally, override in CSS:
 *   .material-symbols-outlined {
 *     color: var(--color-primary-container);
 *   }
 * 
 * To customize per-instance:
 *   <x-icon name="home" class="text-primary-container" />
 */

// ============================================================================
// AVAILABLE MATERIAL SYMBOLS
// ============================================================================

/*
 * Full list of 2500+ Material Symbols available at:
 * https://fonts.google.com/icons
 * 
 * Variant names can be used directly if not in the football alias map:
 *   <x-icon name="sports_basketball" />
 *   <x-icon name="sports_cricket" />
 *   <x-icon name="sports_tennis" />
 * 
 * Or add to FOOTBALL_ICONS map for shorter names:
 *   basketball: 'sports_basketball',
 *   cricket: 'sports_cricket',
 */

// ============================================================================
// MIGRATION GUIDE
// ============================================================================

/*
 * If replacing existing icons:
 * 
 * OLD:
 *   <i class="material-symbols-outlined">home</i>
 * 
 * NEW:
 *   <x-icon name="home" />
 * 
 * OLD with sizing:
 *   <i class="material-symbols-outlined text-2xl">sports_soccer</i>
 * 
 * NEW with sizing:
 *   <x-icon name="pitch" size="lg" />
 * 
 * OLD with active state:
 *   <i class="material-symbols-filled text-primary-container">favorite</i>
 * 
 * NEW with active state:
 *   <x-icon name="heart" filled class="text-primary-container" />
 */

// ============================================================================
// TESTING CHECKLIST
// ============================================================================

const TESTING_CHECKLIST = [
  '✓ Icon component renders correctly',
  '✓ Size variants apply proper classes',
  '✓ Filled variant shows correctly',
  '✓ Colors apply with Tailwind classes',
  '✓ Accessibility attributes present (role, aria-hidden)',
  '✓ Bottom nav shows correct active states',
  '✓ Icons scale properly on mobile/desktop',
  '✓ Contrast ratios meet WCAG AA (4.5:1)',
  '✓ Icon library integrated into component system',
  '✓ Football-themed aliases working',
];

// ============================================================================
// NEXT: Implement in actual pages
// ============================================================================

export {
  FOOTBALL_ICONS,
  ICON_SIZES,
  TESTING_CHECKLIST,
};
