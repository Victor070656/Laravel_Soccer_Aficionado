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
