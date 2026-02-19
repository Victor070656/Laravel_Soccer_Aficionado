Build a **fully featured football fan engagement web application** called **Soccer Aficionado** using **Laravel (latest version)** with an already scaffolded starter kit. The Laravel setup already includes authentication and REST API endpoints for mobile applications. The system must be scalable, secure, modular, and production-ready.

The application should function as a **global social platform for football fans** combining live match data, fan interaction, community features, and gamification.

**Football data (clubs, matches, competitions, players, and standings) is sourced from an external API (TheSportsDB).** These entities are not manually created via CRUD forms — instead, the admin panel provides tools to browse, search, and sync API data into the local database.

---

## **Core Technical Requirements**

### Framework & Architecture

- Use Laravel latest version.
- Use Laravel starter kit (authentication already scaffolded).
- Maintain clean architecture and modular structure.
- Use MVC pattern.
- Build API-first architecture for mobile integration.
- Use Laravel Sanctum or token-based API authentication.
- Use MySQL or PostgreSQL database.
- Use TailwindCSS for modern UI.
- Ensure scalable and maintainable code structure.
- Follow Laravel best practices and conventions.

---

## **User Roles & Permissions**

Implement role-based access control:

### 1. Admin

- Manage users
- Browse and sync clubs, competitions, and matches from external API
- Moderate posts and comments
- View platform analytics
- Manage reports and violations

### 2. Registered Users

- Manage profile
- Select favorite clubs
- Create posts
- Comment and interact
- Vote and participate in polls
- Join fan communities

---

## **Core Features**

### Authentication & User System

- Registration and login
- Profile management
- Profile image upload
- Favorite club selection
- Follow/unfollow users
- Role-based permissions

---

### Football Data System (External API — TheSportsDB)

All football data is fetched from **TheSportsDB API** (free tier). No manual CRUD for clubs, matches, or competitions.

- Browse clubs, competitions, matches, players, and standings via API
- Sync selected data into local database for use with communities, polls, and social features
- Live match status handling via API
- Team squad data from API
- League standings from API
- Search and filter API data from the admin panel

Local database stores synced copies of:

- Clubs (synced from API)
- Players (synced from API)
- Matches (synced from API)
- Competitions (configured league IDs mapped to API)
- Match events

---

### Social Platform Features

- User posts (text + media)
- Comment system
- Likes/reactions
- Share posts
- User mentions
- Follow system
- Activity feed
- Trending posts

---

### Fan Communities

- Club-based fan communities
- Join/leave communities
- Community discussion boards
- Community moderators
- Community posts

---

### Voting & Polling System

- Player of the match voting
- Match prediction polls
- Goal of the week voting
- Time-limited polls
- Results analytics

---

### Gamification System

- Points system for user activity
- Achievement badges
- Leaderboards
- User rankings

---

### Notification System

- Real-time notifications
- Match reminders
- Interaction alerts
- Community updates

---

### Reporting & Moderation

- Report posts or users
- Content moderation dashboard
- Ban or suspend users
- Content approval workflow

---

### Search & Discovery

- Search users, clubs, matches, communities
- Trending content
- Recommendation logic (basic)

---

### API Requirements (for Mobile App)

Provide RESTful endpoints for:

- Authentication
- User profiles
- Matches and live scores
- Posts and comments
- Communities
- Notifications
- Voting system

Use consistent JSON response format and pagination.

---

## **Admin Dashboard**

Build a comprehensive admin panel:

- User management
- Match browsing & sync from API
- Club browsing & sync from API
- Competition browsing (API-sourced)
- Content moderation
- Reports handling
- Analytics overview

---

## **Database Design Requirements**

Design normalized relational schema for:

- Users
- Roles
- Clubs
- Players
- Competitions
- Matches
- Match events
- Posts
- Comments
- Communities
- Votes
- Notifications
- Leaderboards
- Reports

Ensure indexing and scalability.

---

## **UI/UX Requirements**

- Clean, modern, football-themed interface
- Mobile-first responsive design
- Fast loading pages
- Accessible design
- Consistent component styling
- Dark/light mode support

---

## **Performance & Security**

- Input validation
- CSRF protection
- Authorization policies
- Rate limiting
- Optimized queries
- Caching where appropriate
- File upload protection

---

## **Deliverables**

- Full Laravel project structure
- Database migrations
- Models and relationships
- Controllers and services
- API routes and web routes
- Blade views
- Admin dashboard
- Seeders for demo data
- Documentation for setup and deployment

---

---

# Mobile Application — Soccer Aficionado

Build a **fully featured cross-platform mobile application** for the **Soccer Aficionado** platform using **React Native with Expo (latest SDK)**. The mobile app consumes the existing Laravel REST API documented below and delivers the complete fan experience — live match data, social interactions, community features, gamification, and notifications — natively on iOS and Android.

---

## **Core Technical Requirements**

### Framework & Tooling

- Use **React Native** with **Expo (latest SDK, managed workflow)**.
- Use **Expo Router** (file-based routing) for navigation.
- Use **TypeScript** throughout the entire codebase.
- Use **NativeWind** (TailwindCSS for React Native) for styling.
- Use **Zustand** for global state management (auth, user, notifications).
- Use **TanStack React Query** for server state, caching, pagination, and background refetch.
- Use **Axios** for HTTP requests with interceptors for auth token injection and error handling.
- Use **Expo SecureStore** for storing authentication tokens securely.
- Use **Expo Image** for optimized image loading and caching.
- Use **Expo Notifications** for push notification support.
- Use **React Hook Form + Zod** for form handling and validation.
- Use **date-fns** for date/time formatting and relative timestamps.
- Follow a clean, modular folder structure with separation of concerns.

---

## **API Base Configuration**

The backend is a Laravel application exposing a versioned REST API. All endpoints are prefixed with `/api/v1`.

### Authentication

- Token-based authentication via **Laravel Sanctum**.
- On login/register, the API returns a `token` field. Store it securely with Expo SecureStore.
- Attach the token to every authenticated request via an Axios interceptor: `Authorization: Bearer <token>`.
- Handle `401 Unauthorized` responses globally by clearing the token and redirecting to the login screen.

### Response Format

All API responses follow a consistent JSON envelope:

```json
// Success
{
  "success": true,
  "message": "Success",
  "data": { ... }
}

// Error
{
  "success": false,
  "message": "Error description",
  "errors": { ... }
}
```

- Always read data from `response.data.data`.
- Paginated responses include Laravel's standard pagination: `current_page`, `last_page`, `per_page`, `total`, `data` (the array of items).
- Use TanStack React Query's `useInfiniteQuery` for infinite scroll on paginated endpoints.

---

## **API Endpoints Reference**

### Public Endpoints (No Authentication Required)

| Method | Endpoint             | Description                                                                               |
| ------ | -------------------- | ----------------------------------------------------------------------------------------- |
| `POST` | `/register`          | Register a new user. Body: `{ name, username, email, password, password_confirmation }`   |
| `POST` | `/login`             | Login. Body: `{ email, password }`. Returns `{ user, token }`                             |
| `GET`  | `/matches`           | List matches. Query: `?status=&league=&date=&page=`                                       |
| `GET`  | `/matches/live`      | Get currently live matches                                                                |
| `GET`  | `/matches/{id}`      | Get single match with events, lineups, statistics                                         |
| `GET`  | `/clubs`             | List clubs. Query: `?search=&country=&page=`                                              |
| `GET`  | `/clubs/{id}`        | Get club detail with squad, recent & upcoming matches                                     |
| `GET`  | `/competitions`      | List all competitions with season info                                                    |
| `GET`  | `/competitions/{id}` | Get competition detail with standings, fixtures, teams. Query: `?season=`                 |
| `GET`  | `/posts`             | List approved posts (paginated)                                                           |
| `GET`  | `/posts/{id}`        | Get single post with comments                                                             |
| `GET`  | `/communities`       | List active communities. Query: `?search=`                                                |
| `GET`  | `/communities/{id}`  | Get community detail with posts                                                           |
| `GET`  | `/polls`             | List active polls (paginated)                                                             |
| `GET`  | `/polls/{id}`        | Get poll with options and user vote status                                                |
| `GET`  | `/users/{username}`  | Get user profile with posts                                                               |
| `GET`  | `/search`            | Search across all content. Query: `?q=&type=` (all/users/clubs/communities/posts/matches) |
| `GET`  | `/leaderboard`       | Get top 50 users by points                                                                |

### Authenticated Endpoints (Bearer Token Required)

| Method   | Endpoint                      | Description                                                                                             |
| -------- | ----------------------------- | ------------------------------------------------------------------------------------------------------- |
| `POST`   | `/logout`                     | Logout and revoke token                                                                                 |
| `GET`    | `/user`                       | Get current authenticated user with roles, badges, favorite clubs                                       |
| `PUT`    | `/user`                       | Update profile. Body: `{ name?, bio?, country?, timezone?, favorite_clubs?[], primary_club_id? }`       |
| `GET`    | `/dashboard`                  | Get personalized dashboard: feed, live matches, upcoming matches, polls, trending, favorite clubs       |
| `GET`    | `/feed`                       | Get personalized activity feed (paginated)                                                              |
| `POST`   | `/posts`                      | Create post. Multipart: `{ body, community_id?, media[]? }`                                             |
| `PUT`    | `/posts/{id}`                 | Update post. Body: `{ body }`                                                                           |
| `DELETE` | `/posts/{id}`                 | Delete post                                                                                             |
| `POST`   | `/posts/{id}/like`            | Toggle like on a post                                                                                   |
| `POST`   | `/posts/{id}/comment`         | Add comment. Body: `{ body, parent_id? }`                                                               |
| `POST`   | `/posts/{id}/share`           | Share a post. Body: `{ comment? }`                                                                      |
| `POST`   | `/posts/{id}/pin`             | Toggle pin on a post (admin only)                                                                       |
| `DELETE` | `/comments/{id}`              | Delete a comment                                                                                        |
| `POST`   | `/communities`                | Create community. Multipart: `{ name, description?, rules?, club_id?, avatar?, banner? }`               |
| `POST`   | `/communities/{id}/join`      | Join a community                                                                                        |
| `POST`   | `/communities/{id}/leave`     | Leave a community                                                                                       |
| `POST`   | `/polls`                      | Create poll. Body: `{ title, description?, type, match_id?, closes_at?, options[]{label, player_id?} }` |
| `POST`   | `/polls/{id}/vote`            | Vote on a poll. Body: `{ poll_option_id }`                                                              |
| `POST`   | `/users/{id}/follow`          | Toggle follow/unfollow a user                                                                           |
| `POST`   | `/reports`                    | Report content. Body: `{ reportable_type (post/comment/user), reportable_id, reason, description? }`    |
| `GET`    | `/notifications`              | List notifications (paginated)                                                                          |
| `GET`    | `/notifications/unread-count` | Get unread notification count                                                                           |
| `POST`   | `/notifications/{id}/read`    | Mark single notification as read                                                                        |
| `POST`   | `/notifications/read-all`     | Mark all notifications as read                                                                          |

---

## **Project Structure**

```
app/
├── (auth)/                     # Auth screens (login, register, forgot-password)
│   ├── _layout.tsx
│   ├── login.tsx
│   ├── register.tsx
│   └── forgot-password.tsx
├── (tabs)/                     # Main app tab navigator
│   ├── _layout.tsx             # Tab bar configuration
│   ├── index.tsx               # Home / Dashboard
│   ├── matches.tsx             # Matches hub
│   ├── communities.tsx         # Communities hub
│   ├── notifications.tsx       # Notifications
│   └── profile.tsx             # Current user profile
├── matches/
│   ├── [id].tsx                # Match detail
│   └── live.tsx                # Live matches
├── clubs/
│   ├── index.tsx               # Browse clubs
│   └── [id].tsx                # Club detail
├── competitions/
│   ├── index.tsx               # Browse competitions
│   └── [id].tsx                # Competition detail with standings
├── posts/
│   ├── [id].tsx                # Post detail with comments
│   ├── create.tsx              # Create post
│   └── edit.tsx                # Edit post
├── communities/
│   ├── [id].tsx                # Community detail with posts
│   └── create.tsx              # Create community
├── polls/
│   ├── index.tsx               # Browse polls
│   ├── [id].tsx                # Poll detail and voting
│   └── create.tsx              # Create poll
├── users/
│   └── [username].tsx          # User profile
├── search.tsx                  # Global search
├── leaderboard.tsx             # Leaderboard
├── settings/
│   ├── index.tsx               # Settings hub
│   ├── profile.tsx             # Edit profile
│   ├── clubs.tsx               # Manage favorite clubs
│   └── password.tsx            # Change password
├── _layout.tsx                 # Root layout (auth guard, providers)
└── +not-found.tsx
components/
├── ui/                         # Reusable UI primitives (Button, Input, Card, Avatar, Badge, Modal, etc.)
├── posts/                      # PostCard, PostForm, CommentItem, CommentForm, LikeButton, ShareButton
├── matches/                    # MatchCard, LiveScoreCard, MatchEventItem, LineupView, StandingsTable
├── communities/                # CommunityCard, MemberBadge
├── polls/                      # PollCard, PollOptionItem, VoteButton
├── notifications/              # NotificationItem
└── common/                     # Header, EmptyState, ErrorState, LoadingSpinner, InfiniteList, SearchBar
lib/
├── api.ts                      # Axios instance with base URL, interceptors, token injection
├── auth.ts                     # Auth helpers: getToken, setToken, clearToken (SecureStore)
├── queryClient.ts              # TanStack Query client configuration
└── utils.ts                    # Formatting, date helpers, constants
hooks/
├── useAuth.ts                  # Auth state and actions (login, register, logout, refresh user)
├── usePosts.ts                 # Posts queries and mutations (list, feed, create, update, delete, like, comment, share)
├── useMatches.ts               # Matches queries (list, live, detail)
├── useClubs.ts                 # Clubs queries (list, detail)
├── useCompetitions.ts          # Competitions queries (list, detail with season)
├── useCommunities.ts           # Communities queries and mutations (list, detail, create, join, leave)
├── usePolls.ts                 # Polls queries and mutations (list, detail, create, vote)
├── useNotifications.ts         # Notifications queries and mutations (list, unread count, mark read)
├── useSearch.ts                # Search query with debounce
├── useLeaderboard.ts           # Leaderboard query
└── useProfile.ts               # User profile query, follow mutation
stores/
├── authStore.ts                # Zustand store: user, token, isAuthenticated, login/logout actions
└── notificationStore.ts        # Zustand store: unread count, real-time badge updates
types/
├── api.ts                      # ApiResponse<T>, PaginatedResponse<T>, ApiError
├── auth.ts                     # User, LoginRequest, RegisterRequest, AuthResponse
├── post.ts                     # Post, Comment, Like, Share
├── match.ts                    # Match, MatchEvent, Lineup, Statistic
├── club.ts                     # Club, Player, Squad
├── competition.ts              # Competition, Standing, Season
├── community.ts                # Community, CommunityMember
├── poll.ts                     # Poll, PollOption, Vote
├── notification.ts             # Notification
└── common.ts                   # Pagination, SearchResult
constants/
├── colors.ts                   # Football-themed color palette
├── config.ts                   # API_BASE_URL, pagination sizes, timeouts
└── icons.ts                    # Icon name mappings
```

---

## **App Screens & Features**

### Authentication Flow

- **Login Screen:** Email + password form. On success, store token, fetch user, navigate to dashboard.
- **Register Screen:** Name, username, email, password, confirm password. On success, auto-login.
- **Forgot Password Screen:** Email input, triggers password reset email via backend.
- **Auth Guard:** Root layout checks for token on mount. If no valid token, redirect to login. If token exists, fetch `/user` to validate. Handle expired tokens gracefully.

### Home / Dashboard (Tab 1)

- Displays personalized dashboard from `GET /dashboard`.
- **Live Matches Banner:** Horizontally scrollable live match cards at the top. Auto-refresh every 60 seconds.
- **Upcoming Matches:** Horizontal scroll of next 5 upcoming matches.
- **Activity Feed:** Infinite-scroll list of posts from followed users and joined communities. Pull-to-refresh.
- **Active Polls:** Up to 3 active polls with quick-vote capability.
- **Trending Posts:** Top 5 trending posts section.
- **Favorite Clubs:** Quick-access row of user's favorite club badges.

### Matches (Tab 2)

- **Match List:** Filterable by status (live/upcoming/finished), league, and date. Paginated with infinite scroll.
- **Live Matches:** Dedicated view with auto-refreshing scores (poll every 60s).
- **Match Detail:** Full match view — score, events timeline, lineups, statistics.

### Communities (Tab 3)

- **Community List:** Searchable list of active communities sorted by member count. Infinite scroll.
- **Community Detail:** Community info, member count, join/leave toggle, paginated community posts feed.
- **Create Community:** Form with name, description, rules, optional club association, avatar and banner image pickers.

### Notifications (Tab 4)

- **Notification List:** Paginated list with unread indicators. Pull-to-refresh.
- **Unread Badge:** Tab bar badge showing unread count from `GET /notifications/unread-count`. Poll every 30 seconds.
- **Mark as Read:** Tap notification to mark as read. "Mark all as read" button in header.
- **Deep Linking:** Tap notification to navigate to the relevant content (post, community, user profile).

### Profile (Tab 5)

- **Current User Profile:** Shows avatar, name, username, bio, points, badges, followers/following counts, favorite clubs, joined communities.
- **User Posts:** Paginated list of user's posts with infinite scroll.
- **Settings Access:** Gear icon navigating to settings screens.

### Post Detail

- Full post content with media viewer.
- Comments list with nested replies (threaded).
- Like, comment, and share action buttons.
- Report button in overflow menu.
- Post owner sees edit/delete options.

### Create / Edit Post

- Text input with @mention autocomplete (search users as they type after `@`).
- Image picker for media attachments (multi-select, up to 5 images).
- Optional community selector dropdown.
- Submit creates via `POST /posts` with multipart form data.

### Club Detail

- Club info: name, badge, stadium, founded year, country, colors.
- Squad list grouped by position (Goalkeeper, Defender, Midfielder, Forward).
- Recent results and upcoming fixtures.

### Competition Detail

- League info and season selector dropdown.
- Standings table with team logos, points, W/D/L, goal difference.
- Upcoming fixtures and recent results (current season only).
- Teams grid with club badges.

### Poll Detail & Voting

- Poll question, type badge, time remaining countdown.
- Options list with vote percentages shown as progress bars after voting.
- Vote button disabled after user has voted or poll is closed.
- Associated match info if applicable.

### Search

- Single search bar with type filter tabs (All, Users, Clubs, Communities, Posts, Matches).
- Debounced search input (300ms).
- Results displayed in categorized sections.

### Leaderboard

- Top 50 users ranked by points.
- Current user's rank highlighted.
- User cards showing avatar, name, points, and badges.

### Settings

- **Edit Profile:** Name, bio, country, timezone. Avatar upload via image picker.
- **Favorite Clubs:** Search and select clubs from the API. Mark one as primary.
- **Change Password:** Current password, new password, confirm.
- **Logout:** Confirm dialog, clear token, navigate to login.

---

## **UI/UX Requirements**

- **Football-themed design** with a green/dark primary palette. Accent colors for live events (red pulse), wins (green), draws (amber), losses (red).
- **Dark mode and light mode** support via NativeWind with `useColorScheme`.
- **Skeleton loading states** for all lists and detail screens (shimmer placeholders).
- **Pull-to-refresh** on all scrollable lists.
- **Haptic feedback** on interactive elements (like, vote, follow) via `expo-haptics`.
- **Smooth transitions** between screens using Expo Router's default animations.
- **Empty states** with illustrations and action prompts (e.g., "No posts yet — be the first!").
- **Error states** with retry buttons for failed API calls.
- **Toast notifications** for success/error feedback on mutations (post created, voted, etc.).
- **Bottom sheet modals** for comment composer, report form, and share options.
- **Responsive layout** that works on both phones and tablets.
- **Optimized image loading** with `expo-image` for progressive loading and disk caching.

---

## **State Management & Data Flow**

### Zustand Auth Store

```typescript
interface AuthState {
    user: User | null;
    token: string | null;
    isAuthenticated: boolean;
    isLoading: boolean;
    login: (email: string, password: string) => Promise<void>;
    register: (data: RegisterRequest) => Promise<void>;
    logout: () => Promise<void>;
    refreshUser: () => Promise<void>;
    setToken: (token: string) => void;
    clearAuth: () => void;
}
```

### TanStack React Query Patterns

- **Query Keys:** Namespaced arrays, e.g., `['posts', 'feed']`, `['matches', 'live']`, `['clubs', id]`.
- **Infinite Queries:** Use `useInfiniteQuery` with `getNextPageParam` reading `current_page` and `last_page` from the API response.
- **Mutations:** Use `useMutation` with `onSuccess` to invalidate related queries. E.g., after creating a post, invalidate `['posts']` and `['feed']`.
- **Optimistic Updates:** Apply optimistic updates for like toggles and follow toggles for instant UI feedback.
- **Stale Time:** Set `staleTime: 5 * 60 * 1000` (5 minutes) for stable data (clubs, competitions). Use `staleTime: 30 * 1000` (30 seconds) for live matches.
- **Refetch Intervals:** Auto-refetch live matches every 60 seconds and unread notification count every 30 seconds.

### Axios Configuration

```typescript
const api = axios.create({
    baseURL: API_BASE_URL + "/api/v1",
    timeout: 15000,
    headers: { Accept: "application/json" },
});

api.interceptors.request.use(async (config) => {
    const token = await SecureStore.getItemAsync("auth_token");
    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
});

api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            useAuthStore.getState().clearAuth();
        }
        return Promise.reject(error);
    },
);
```

---

## **Performance & Optimization**

- Use `React.memo` and `useCallback` to prevent unnecessary re-renders in list items.
- Use `FlashList` (from `@shopify/flash-list`) instead of `FlatList` for high-performance scrolling on all list screens.
- Implement image compression before upload using `expo-image-manipulator` (resize to max 1200px, quality 0.8).
- Debounce search input to avoid excessive API calls.
- Use `queryClient.prefetchQuery` to prefetch detail screens when a card is visible (e.g., prefetch match detail on match card render).
- Minimize bundle size by using tree-shakeable imports.

---

## **Error Handling**

- Global Axios error interceptor for 401 (auto-logout), 403 (show forbidden message), 422 (surface validation errors to forms), 429 (show rate limit message), 500 (show generic server error).
- Per-screen error boundaries using React Error Boundary with a "Something went wrong" UI and retry action.
- Network connectivity detection using `@react-native-community/netinfo` — show offline banner when disconnected.
- Form validation errors displayed inline next to the relevant fields.

---

## **Deliverables**

- Complete Expo project with TypeScript configuration
- File-based routing with Expo Router
- All screens matching the web application's feature set
- Reusable component library
- Custom hooks for every API domain
- Zustand stores for auth and notifications
- TanStack React Query integration with proper caching
- NativeWind (TailwindCSS) styling with dark/light mode
- Type definitions for all API contracts
- Environment configuration (.env) for API base URL
- App icon and splash screen assets
- EAS Build configuration for iOS and Android
