<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="260" alt="Laravel Logo">
</p>

<h1 align="center">Automate Chat With Laravel</h1>

<p align="center">
  A real-time one-to-one chat application built with <b>Laravel 12</b>, <b>Livewire 3</b>, and <b>Laravel Reverb</b> — featuring instant messaging, typing indicators, file/image/video attachments, reply threading, and a live chat sidebar, all without a page reload.
</p>

---

## 📋 Table of Contents

- [About the Project](#about-the-project)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Prerequisites](#prerequisites)
- [Installation & Setup](#installation--setup)
- [Environment Variables](#environment-variables)
- [Running the Application](#running-the-application)
- [Database Schema](#database-schema)
- [How Real-Time Chat Works](#how-real-time-chat-works)
- [Routes](#routes)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)

---

## About the Project

**Automate Chat With Laravel** is a private messaging system where authenticated users can chat with each other in real time. It uses **Livewire** for reactive, server-driven UI components and **Laravel Reverb** (Laravel's first-party WebSocket server) for broadcasting events like new messages and typing status instantly to the receiver — no polling required.

## Features

- 🔐 **Authentication** — Custom Livewire-based Login & Register components (with username availability check on the fly)
- 💬 **Real-time 1-to-1 messaging** using Laravel Reverb + Laravel Echo (Pusher-protocol compatible)
- ⌨️ **"User is typing…" indicator**, broadcast live to the other participant
- 📎 **Attachments support** — send images, videos, or files (up to 10MB) alongside text
- ↩️ **Reply-to-message** threading (`reply_to_id` self-reference on the `chats` table)
- 🔎 **Search users** to start a new conversation or filter your chat list
- 🗂️ **Chat sidebar** showing every conversation with its latest message
- ✅ **Delivery/read status tracking** (`pending`, `sent`, `delivered`, `read`, `failed`)
- 🧵 **Soft deletes** for both users and chat messages
- 🔔 Toast/flash notifications via `php-flasher/flasher-laravel`
- 🎨 Styled with **Tailwind CSS v4** and bundled via **Vite**

## Tech Stack

| Layer | Technology |
|---|---|
| Backend Framework | Laravel 12 (PHP ^8.2) |
| Reactive UI | Livewire 3 |
| Real-time / WebSockets | Laravel Reverb, Laravel Echo, Pusher-js |
| Frontend Build | Vite, Tailwind CSS v4 |
| Notifications | php-flasher/flasher-laravel |
| Database (default) | SQLite (configurable to MySQL/Postgres) |
| Testing | PHPUnit |

## Project Structure

Key folders relevant to the chat feature:

```
app/
├── Events/
│   ├── MessageSentEvent.php     # Broadcasts a new chat message to the receiver's private channel
│   └── UserTyping.php           # Broadcasts a "typing" event to the receiver
├── Livewire/
│   ├── AuthComponent/
│   │   ├── Login.php
│   │   └── RegisterCompoment.php
│   ├── Chat/
│   │   └── ChatList.php         # Main chat page: sidebar, message list, send/receive logic
│   └── Components/User/Partials/
│       ├── ChatBox.php          # Standalone chat box component (with file uploads)
│       ├── ChatSideBar.php
│       ├── Header.php / Footer.php / Sidebar.php
└── Models/
    ├── Chat.php                 # sender(), receiver(), replyTo() relationships
    └── User.php                 # latestMessage(), unreadMessages() relationships

routes/
├── web.php                      # /, /login, /register, /chat (auth-protected)
└── channels.php                 # Private channel authorization for broadcasting

database/migrations/
├── ..._create_chats_table.php
├── ..._add_field_user_table.php
└── ..._add_username_users_table.php

resources/js/echo.js             # Laravel Echo client configured for Reverb
resources/views/livewire/...     # Blade views for each Livewire component
```

## Prerequisites

Make sure you have the following installed before starting:

- **PHP** ≥ 8.2 with common extensions (`mbstring`, `openssl`, `pdo`, `sqlite3` or your DB driver)
- **Composer** ≥ 2.x
- **Node.js** ≥ 18 and **npm**
- **SQLite** (default) or **MySQL/PostgreSQL** if you prefer another driver
- **Git**

## Installation & Setup

### 1. Clone the repository

```bash
git clone https://github.com/AbulQasim123/Automate-Chat-With-Laravel.git
cd Automate-Chat-With-Laravel
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install JavaScript dependencies

```bash
npm install
```

### 4. Create your environment file

```bash
cp .env.example .env
```

### 5. Generate the application key

```bash
php artisan key:generate
```

### 6. Configure the database

The project defaults to **SQLite**. Create the database file:

```bash
touch database/database.sqlite
```

(On Windows, simply create an empty `database/database.sqlite` file manually.)

If you'd rather use MySQL/PostgreSQL, update the `DB_*` variables in `.env` accordingly (see [Environment Variables](#environment-variables)).

### 7. Run migrations

```bash
php artisan migrate
```

This creates the `users`, `chats`, `cache`, `jobs`, and related tables (including the custom profile fields, `username`, and soft-delete columns added via later migrations).

### 8. Set up Laravel Reverb

Reverb powers the real-time broadcasting. Publish its config and generate app credentials:

```bash
php artisan install:broadcasting
```

This will guide you through setting `REVERB_APP_ID`, `REVERB_APP_KEY`, and `REVERB_APP_SECRET` in your `.env` (add them manually if the command doesn't prompt — see below).

### 9. Build frontend assets

```bash
npm run build
```
or, for development with hot reload:
```bash
npm run dev
```

## Environment Variables

The default `.env.example` only ships with core Laravel variables. For the real-time chat to work, add these **Reverb** and **Vite/Echo** variables to your `.env`:

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=8080

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

> ⚠️ **Important:** By default `.env.example` sets `BROADCAST_CONNECTION=log`, which only logs events instead of broadcasting them over WebSockets. You must change this to `reverb` for real-time chat and typing indicators to actually work in the browser.

## Running the Application

You'll typically need **three processes** running at once (in separate terminals), or use the combined script below.

**Option A — one command (recommended for local dev):**

```bash
composer run dev
```
This runs the PHP server, queue listener, log viewer (`pail`), and Vite dev server concurrently.

**Option B — run each service manually:**

```bash
# Terminal 1 — Laravel app server
php artisan serve

# Terminal 2 — Reverb WebSocket server
php artisan reverb:start

# Terminal 3 — Vite dev server (asset compilation)
npm run dev

# Terminal 4 (optional) — Queue worker, if you queue any jobs
php artisan queue:listen
```

Then open **http://localhost:8000** in your browser, register a couple of user accounts, log in with each in separate browser sessions (or incognito), and start chatting at **`/chat`**.

## Database Schema

### `chats` table
| Column | Type | Notes |
|---|---|---|
| `sender_id` / `receiver_id` | unsignedBigInteger | FK → `users.id` |
| `message` | text, nullable | |
| `attachment` | string, nullable | Storage path for uploaded file |
| `message_type` | enum | `text`, `image`, `video`, `file`, `system` |
| `reply_to_id` | unsignedBigInteger, nullable | Self-referencing FK for replies |
| `status` | enum | `pending`, `sent`, `delivered`, `read`, `failed` |
| `is_read` / `is_deleted` | boolean | |
| `delivered_at` / `read_at` / `sent_at` | timestamp, nullable | |
| `meta` | json, nullable | |
| `deleted_at` | timestamp (soft deletes) | |

### `users` table (extended)
Adds `username` (unique), profile fields (`phone`, `photo`, `gender`, `dob`, `address`, `city`, `state`, `country`, `zip_code`), `status`, two-factor fields, `tenant_id`, login audit fields, `api_token`, `settings` (json), and soft deletes on top of Laravel's defaults.

## How Real-Time Chat Works

1. A logged-in user opens `/chat`, which loads the `ChatList` Livewire component.
2. The sidebar lists users the current user has previously chatted with (via `loadUsers()`), showing each one's `latestMessage`.
3. Selecting a user (`selectUser`) loads the full message thread between the two participants (`loadMessages`).
4. Sending a message (`sendMessage`) saves a `Chat` record and immediately dispatches `MessageSentEvent`, which broadcasts over a **private channel** named `chat-channel.{receiver_id}` (authorized in `routes/channels.php`).
5. The receiver's browser — subscribed to that same private channel via Laravel Echo (`resources/js/echo.js`) — receives the event instantly and Livewire's `listenMessage()` prepends it to their message list, with **no page refresh**.
6. While typing, `userTyping()` broadcasts a lightweight `UserTyping` event (`toOthers()`, so the sender doesn't see their own typing indicator) to notify the other participant.

## Routes

| Method | URI | Component | Middleware |
|---|---|---|---|
| GET | `/` | `welcome` view | — |
| GET | `/login` | `Login` (Livewire) | — |
| GET | `/register` | `RegisterCompoment` (Livewire) | — |
| GET | `/chat` | `ChatList` (Livewire) | `auth` |

## Troubleshooting

- **Messages not appearing in real time?** Confirm `BROADCAST_CONNECTION=reverb` in `.env` and that `php artisan reverb:start` is running.
- **"Class not found" errors after pulling changes:** run `composer dump-autoload`.
- **Frontend not updating / styles missing:** run `npm run build` (or keep `npm run dev` running) and clear cached views with `php artisan view:clear`.
- **Private channel authorization failing:** make sure you're logged in as the correct user in each browser session — `routes/channels.php` restricts each `chat-channel.{id}` to the matching authenticated user.
- **SQLite errors on migrate:** confirm `database/database.sqlite` exists and `DB_CONNECTION=sqlite` is set.

## Contributing

Contributions, issues, and feature requests are welcome. Feel free to fork the repo and open a pull request.

## License

This project is built on the Laravel framework, which is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
