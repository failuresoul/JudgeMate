<div align="center">

# ⚖️ JudgeMate

**A full-stack Competitive Programming Judge Platform built with Laravel 12**

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

*Submit code. Get judged. Compete. Climb the leaderboard.*

</div>

---

## 📋 Table of Contents

1. [Overview](#-overview)
2. [Features](#-features)
3. [Tech Stack](#-tech-stack)
4. [System Architecture](#-system-architecture)
5. [User Roles](#-user-roles)
6. [Getting Started](#-getting-started)
7. [Running Migrations & Seeders](#-running-migrations--seeders)
8. [Queue Worker (JudgeSubmission)](#-queue-worker-judgesubmission)
9. [Task Scheduler](#-task-scheduler)
10. [Route Reference](#-route-reference)
11. [Deployment Guide](#-deployment-guide)
12. [Project Structure](#-project-structure)
13. [Database Schema](#-database-schema)
14. [External Integrations](#-external-integrations)

---

## 🌟 Overview

**JudgeMate** is a self-hosted online judge platform where contestants submit C++, Python, or Java code solutions to algorithmic problems. The platform judges submissions in real time using a background queue worker, handles contest creation and management, generates PDF scoreboards, syncs upcoming contests from Codeforces / AtCoder / CodeChef / LeetCode via an external API, and provides an admin analytics dashboard — all protected by a robust role-based access control system.

---

## ✨ Features

| Category | Feature |
|---|---|
| **Auth** | Registration with admin approval workflow, login/logout, password reset |
| **Roles** | Four-tier RBAC: Guest → Contestant → ProblemSetter → Admin (via Spatie Laravel Permission) |
| **Problems** | Create/edit/publish problems with statement, I/O format, constraints, difficulty, and tags |
| **Test Cases** | Per-problem hidden/visible test cases managed by ProblemSetters |
| **Submissions** | Code submission in C++, Python, Java; async judging via Laravel Queue |
| **Judge Engine** | Real code execution (shell_exec) against test cases; reports AC / WA / TLE / CE |
| **Contests** | Full contest lifecycle: create → admin-approve → register → join → submit → scoreboard |
| **Scoreboard** | Live ICPC-style scoreboard with penalty time; PDF download via DomPDF |
| **Badges** | Automatic badge awards: First AC, Speed Demon, Problem Slayer |
| **Leaderboard** | Global public leaderboard ranked by accepted submissions |
| **Notifications** | Real-time database notifications when a submission verdict is ready |
| **Admin Dashboard** | Live analytics: verdict charts (Chart.js), top-5 problems, metric cards |
| **External Contests** | Fetches upcoming contests from Kontests API; cached and auto-refreshed every 10 min |
| **Scheduler** | Laravel scheduler runs `contests:refresh-external` every 10 min and `contest:update-status` every minute |

---

## 🛠 Tech Stack

| Layer | Technology |
|---|---|
| **Framework** | Laravel 12.x |
| **Language** | PHP 8.2+ |
| **Database** | MySQL 8.0 |
| **Queue Backend** | Database queue (configurable to Redis) |
| **Cache** | Database cache (configurable to Redis) |
| **Session** | Database sessions |
| **Auth** | Laravel Breeze (Blade stack) |
| **RBAC** | Spatie Laravel Permission v6 |
| **PDF** | barryvdh/laravel-dompdf v3 |
| **Frontend** | Blade + Vite + Vanilla CSS (TailwindCSS) |
| **Charts** | Chart.js v4 (CDN) |
| **Languages Judged** | C++ (g++/MinGW), Python (3.x), Java (JDK 17) |
| **External API** | Kontests.net (aggregates Codeforces, AtCoder, CodeChef, LeetCode) |

---

## 🏗 System Architecture

```
Browser
   │
   ▼
[Laravel Router] ──► [Middleware Stack]
                        │  auth (Breeze)
                        │  approved (CheckApproved)
                        │  role:Admin / role:ProblemSetter (RoleMiddleware)
   │
   ▼
[Controllers]
   │
   ├── [SubmissionController.store()]
   │       └── JudgeSubmission::dispatch() ──► [jobs table]
   │
   ▼
[Queue Worker: php artisan queue:work]
   │
   └── [JudgeSubmission Job]
           ├── executeReal() ── shell_exec(g++ / python / java)
           ├── Compare output vs expected_output
           ├── Save verdict to submissions table
           ├── BadgeService::checkAndAward()
           └── User::notify(SubmissionProcessed)

[Scheduler: php artisan schedule:run (every minute)]
   ├── contests:refresh-external  → every 10 minutes
   └── contest:update-status      → every minute
```

---

## 👤 User Roles

| Role | Access Level | Default Redirect |
|---|---|---|
| **Guest** | Unauthenticated — can view public leaderboard and problems index | `/login` |
| **Contestant** | Approved registered user — can submit code, view own submissions, join/register contests | `/dashboard` |
| **ProblemSetter** | Creates and manages problems, test cases; views all submissions for their problems | `/judge` |
| **Admin** | Full platform control — user approval, contest approval, analytics dashboard | `/admin` |

> New registrations default to `status = pending` and must be approved by an Admin before gaining access.

---

## 🚀 Getting Started

### Prerequisites

- PHP 8.2+ with extensions: `pdo_mysql`, `mbstring`, `openssl`, `xml`, `curl`, `fileinfo`
- Composer 2.x
- Node.js 18+ and npm
- MySQL 8.0
- **Judge binaries on PATH** (or absolute paths in `JudgeSubmission.php`):
  - `g++` (MinGW on Windows: `C:\MinGW\bin\g++.exe`)
  - `python` / `python3` (Anaconda: `C:\Users\USER\anaconda3\python.exe`)
  - `javac` + `java` (JDK 17: `C:\Program Files\Amazon Corretto\jdk17.0.18_9\bin\`)

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/yourname/judgemate.git
cd judgemate

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies and build assets
npm install
npm run build          # or: npm run dev  (for hot-reload during development)

# 4. Configure environment
cp .env.example .env
php artisan key:generate

# 5. Edit .env — set DB credentials at minimum:
#    DB_DATABASE=judgemate
#    DB_USERNAME=root
#    DB_PASSWORD=yourpassword
#    QUEUE_CONNECTION=database   ← IMPORTANT: change from 'sync' for real judging
```

---

## 🗄 Running Migrations & Seeders

### Fresh database (recommended for demo/development)

```bash
# Wipe everything and re-run all migrations + all seeders in one step
php artisan migrate:fresh --seed
```

This runs all 13 migrations **in chronological order** and then calls `DatabaseSeeder`, which internally calls:

| Order | Seeder | What it seeds |
|---|---|---|
| 1 | `RoleSeeder` | Creates 4 roles: Guest, Contestant, ProblemSetter, Admin |
| 2 | *(inline)* | Default Admin user `admin@judgemate.test` / `password` |
| 3 | *(inline)* | Sample Contestant `contestant@judgemate.test` / `password` |
| 4 | *(inline)* | Sample Judge `judge@judgemate.test` / `password` |
| 5 | `ProblemSeeder` | Sample algorithmic problems with test cases |
| 6 | `BadgeSeeder` | 3 badges: First AC, Speed Demon, Problem Slayer |

### Re-seed without wiping (existing data)

```bash
php artisan db:seed
```

### Run a specific seeder only

```bash
php artisan db:seed --class=ProblemSeeder
php artisan db:seed --class=BadgeSeeder
```

### Demo Login Credentials

| Role | Email | Password |
|---|---|---|
| Admin | `admin@judgemate.test` | `password` |
| Contestant | `contestant@judgemate.test` | `password` |
| ProblemSetter | `judge@judgemate.test` | `password` |

---

## ⚙️ Queue Worker (JudgeSubmission)

The submission judge runs as an async queue job. **Without the worker running, code is never judged** (submissions stay in `pending` status forever).

> ⚠️ **Critical**: Your `.env` must have `QUEUE_CONNECTION=database` (not `sync`) for async judging to work.

### Start the worker (development)

```bash
# Basic worker — processes all jobs, exits when queue is empty
php artisan queue:work

# Recommended for development: stay alive, retry once, 120-second timeout per job
php artisan queue:work --tries=3 --timeout=120 --sleep=3

# With queue listen (re-reads code on each job — useful during development)
php artisan queue:listen --tries=3 --timeout=120
```

### Key flags explained

| Flag | Meaning | Recommended Value |
|---|---|---|
| `--tries=3` | Max attempts before a job is moved to `failed_jobs` | `3` |
| `--timeout=120` | Seconds before a job process is killed (should be > PHP execution time) | `120` |
| `--sleep=3` | Seconds to sleep when the queue is empty | `3` |
| `--queue=default` | Process a specific named queue | `default` |
| `--max-jobs=500` | Restart worker after processing N jobs (prevents memory leaks) | `500` |
| `--max-time=3600` | Restart worker after N seconds | `3600` |

### Production worker (via Supervisor)

```ini
# /etc/supervisor/conf.d/judgemate-worker.conf
[program:judgemate-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/judgemate/artisan queue:work --tries=3 --timeout=120 --max-jobs=500 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/judgemate/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start judgemate-worker:*
```

### View / retry failed jobs

```bash
php artisan queue:failed           # list all failed jobs
php artisan queue:retry all        # retry all failed jobs
php artisan queue:retry {id}       # retry a specific failed job
php artisan queue:flush            # delete all failed jobs
```

---

## 🕐 Task Scheduler

JudgeMate uses the Laravel scheduler (defined in `bootstrap/app.php`) to run two commands automatically:

| Command | Schedule | Purpose |
|---|---|---|
| `contests:refresh-external` | Every 10 minutes | Clears and re-caches upcoming contests from Kontests API |
| `contest:update-status` | Every minute | Updates active/inactive status of contests |

### Testing the scheduler locally

#### Option A — Single tick (run whatever is due right now)

```bash
php artisan schedule:run
```

#### Option B — Continuously (like a real cron, fires every minute in your terminal)

```bash
php artisan schedule:work
```

> `schedule:work` is the Laravel 10+ built-in replacement for running a cron job locally. It polls every minute automatically — no real cron setup needed.

#### Option C — Force a specific command right now (bypass schedule timing)

```bash
# Manually fire the external contests refresh
php artisan contests:refresh-external

# Check the schedule list and when each command fires next
php artisan schedule:list
```

### Production cron setup

Add **one single cron entry** on the server. Laravel's scheduler takes care of everything else:

```cron
* * * * * cd /var/www/judgemate && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🗺 Route Reference

### 🌍 Public Routes (no middleware)

| Method | URI | Name | Description |
|---|---|---|---|
| `GET` | `/` | — | Redirects to `/login` |
| `GET` | `/pending` | `auth.pending` | Account pending approval page |
| `GET` | `/leaderboard` | `leaderboard` | Global public leaderboard |
| `GET` | `/problems` | `problems.index` | Browse all published problems |
| `GET` | `/problems/{problem}` | `problems.show` | View a single problem |

### 🔐 Authenticated + Approved Users (`auth`, `approved`)

| Method | URI | Name | Description |
|---|---|---|---|
| `GET` | `/dashboard` | `dashboard` | Home dashboard (also requires `verified`) |
| `GET` | `/profile` | `profile.edit` | Edit own profile |
| `PATCH` | `/profile` | `profile.update` | Update profile |
| `DELETE` | `/profile` | `profile.destroy` | Delete account |
| `GET` | `/profile/show/{user?}` | `profile.show` | View any user's public profile |
| `GET` | `/submissions` | `submissions.index` | View submissions (role-filtered) |
| `GET` | `/problems/{problem}/submit` | `problems.submit` | Code submission form |
| `POST` | `/problems/{problem}/submissions` | `problems.submissions.store` | Submit code |
| `GET` | `/submissions/{submission}/status` | `submissions.status` | Poll submission verdict (JSON) |
| `GET` | `/notifications/unread-count` | `notifications.unread-count` | Unread notification count (JSON) |
| `POST` | `/notifications/mark-read` | `notifications.mark-read` | Mark notifications as read |
| `GET` | `/contests` | `contests.index` | Browse contests |
| `GET` | `/contests/create` | `contests.create` | New contest form |
| `POST` | `/contests` | `contests.store` | Create a contest |
| `GET` | `/contests/{contest}` | `contests.show` | View contest detail |
| `GET` | `/contests/{contest}/edit` | `contests.edit` | Edit contest |
| `PUT/PATCH` | `/contests/{contest}` | `contests.update` | Update contest |
| `DELETE` | `/contests/{contest}` | `contests.destroy` | Delete contest |
| `POST` | `/contests/{contest}/register` | `contests.register` | Register for a contest |
| `POST` | `/contests/{contest}/join` | `contests.join` | Join an active contest |
| `GET` | `/contests/{contest}/scoreboard` | `contests.scoreboard` | View live scoreboard |
| `GET` | `/contests/{contest}/scoreboard/data` | `contests.scoreboard.data` | Scoreboard JSON (throttled) |
| `GET` | `/contests/{contest}/scoreboard/pdf` | `contests.scoreboard.pdf` | Download scoreboard PDF |

### 🔧 ProblemSetter-Only (`auth`, `approved`, `role:ProblemSetter`)

| Method | URI | Name | Description |
|---|---|---|---|
| `GET` | `/judge` | `judge.dashboard` | ProblemSetter dashboard |
| `GET` | `/judge/test-cases` | `judge.test-cases.index` | List all test cases |
| `GET` | `/judge/problems/{problem}/test-cases` | `judge.test-cases.show` | Test cases for a problem |
| `GET` | `/problems/create` | `problems.create` | New problem form |
| `POST` | `/problems` | `problems.store` | Create problem |
| `GET` | `/problems/{problem}/edit` | `problems.edit` | Edit problem |
| `PUT/PATCH` | `/problems/{problem}` | `problems.update` | Update problem |
| `DELETE` | `/problems/{problem}` | `problems.destroy` | Delete problem |
| `POST` | `/problems/{problem}/test-cases` | `problems.test-cases.store` | Add test case |
| `DELETE` | `/test-cases/{test_case}` | `test-cases.destroy` | Delete test case |

### 🛡 Admin-Only (`auth`, `approved`, `role:Admin`)

| Method | URI | Name | Description |
|---|---|---|---|
| `GET` | `/admin` | `admin.dashboard` | Admin analytics dashboard |
| `GET` | `/admin/users` | `admin.users.index` | User management list |
| `POST` | `/admin/users/{user}/approve` | `admin.users.approve` | Approve a pending user |
| `POST` | `/admin/users/{user}/reject` | `admin.users.reject` | Reject a pending user |
| `POST` | `/contests/{contest}/approve` | `contests.approve` | Approve a contest (nested in auth group + role:Admin) |

### 🔑 Auth Routes (Laravel Breeze — `routes/auth.php`)

| Method | URI | Name | Description |
|---|---|---|---|
| `GET` | `/login` | `login` | Login form |
| `POST` | `/login` | — | Authenticate user |
| `GET` | `/register` | `register` | Registration form |
| `POST` | `/register` | — | Create account (sets status=pending) |
| `POST` | `/logout` | `logout` | Log out |
| `GET` | `/forgot-password` | `password.request` | Forgot password form |
| `POST` | `/forgot-password` | `password.email` | Send reset link |
| `GET` | `/reset-password/{token}` | `password.reset` | Reset password form |
| `POST` | `/reset-password` | `password.update` | Update password |

---

## 🚢 Deployment Guide

### 1. Environment Setup

```bash
# In production .env:
APP_NAME="JudgeMate"
APP_ENV=production        # Disables debug output, enables production optimizations
APP_KEY=base64:...        # NEVER change this after launch
APP_DEBUG=false           # CRITICAL: must be false in production
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=judgemate_prod
DB_USERNAME=judgemate_user
DB_PASSWORD=strong_random_password

QUEUE_CONNECTION=database  # or 'redis' for better performance
CACHE_STORE=database       # or 'redis'
SESSION_DRIVER=database    # or 'redis'

LOG_CHANNEL=daily
LOG_LEVEL=error            # Only log errors, not debug info
```

### 2. Install Dependencies (production mode)

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

### 3. Run Migrations

```bash
# First-time setup
php artisan migrate --force

# Or fresh deploy (WARNING: wipes all data)
php artisan migrate:fresh --seed --force
```

### 4. Cache Everything

Run these after every deployment to maximise performance:

```bash
# Cache compiled config (reads all config/*.php into one file)
php artisan config:cache

# Cache all route definitions
php artisan route:cache

# Cache compiled Blade views
php artisan view:cache

# Cache Eloquent model meta (Laravel 11+)
php artisan model:cache   # if using Scout or OpCache

# One-liner: cache config + routes + views together
php artisan optimize
```

### 5. Clear Cache (when re-deploying)

```bash
php artisan optimize:clear   # clears config, route, view, event caches
php artisan cache:clear      # clears application cache (database/Redis)
```

### 6. External Codeforces / Contest API Cache Duration

The `ExternalContestService` currently caches with:

```php
Cache::remember('all_external_contests', 600, ...)  // 10 minutes
```

**Production recommendation:** Increase to **30–60 minutes** (1800–3600 seconds) to reduce API call frequency and respect Kontests API rate limits:

```php
// In ExternalContestService.php — change TTL for production:
Cache::remember('all_external_contests', env('EXTERNAL_CONTEST_CACHE_TTL', 600), ...)
```

Then set in `.env`:
```env
# Local dev: 600 (10 min) — fresh data frequently
# Production: 1800 (30 min) or 3600 (1 hour) — reduce API load
EXTERNAL_CONTEST_CACHE_TTL=1800
```

### 7. File Permissions

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 8. Queue Worker in Production

Use **Supervisor** (see the [Queue Worker section](#️-queue-worker-judgesubmission)) to keep the worker alive. Never rely on `queue:listen` in production.

### 9. Final Pre-Demo Checklist

```
[ ] php artisan migrate:fresh --seed          ← fresh database with demo data
[ ] QUEUE_CONNECTION=database in .env         ← async judging enabled
[ ] php artisan queue:work --tries=3          ← worker running in a separate terminal
[ ] php artisan schedule:work                 ← scheduler running in a separate terminal
[ ] php artisan config:cache && route:cache   ← caches warmed
[ ] storage/app/submissions directory writable← judge writes temp files here
[ ] g++ / python / java accessible on PATH    ← judge binaries work
[ ] Login with admin@judgemate.test / password
[ ] Approve a new registration to test workflow
[ ] Submit a solution and verify it gets judged
[ ] Check /admin analytics dashboard
```

---

## 📁 Project Structure

```
judgemate/
├── app/
│   ├── Console/Commands/
│   │   └── RefreshExternalContests.php   # Artisan command for contest sync
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AdminController.php          # Analytics dashboard
│   │   │   │   └── UserManagementController.php # Approve/reject users
│   │   │   ├── Auth/                            # Breeze auth controllers
│   │   │   ├── Judge/
│   │   │   │   └── JudgeController.php          # ProblemSetter dashboard
│   │   │   ├── ContestController.php
│   │   │   ├── HomeController.php
│   │   │   ├── LeaderboardController.php
│   │   │   ├── NotificationController.php
│   │   │   ├── ProblemController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── ScoreboardController.php
│   │   │   ├── SubmissionController.php
│   │   │   └── TestCaseController.php
│   │   └── Middleware/
│   │       ├── CheckApproved.php          # Blocks pending/rejected users
│   │       └── RoleMiddleware.php         # role:Admin / role:ProblemSetter
│   ├── Jobs/
│   │   └── JudgeSubmission.php           # Async code execution job
│   ├── Models/
│   │   ├── Badge.php
│   │   ├── Contest.php
│   │   ├── Problem.php
│   │   ├── Submission.php
│   │   ├── Tag.php
│   │   ├── TestCase.php
│   │   └── User.php
│   ├── Notifications/
│   │   └── SubmissionProcessed.php       # DB notification on verdict
│   └── Services/
│       ├── BadgeService.php              # Badge award logic
│       └── ExternalContestService.php    # Kontests API + cache
│
├── bootstrap/
│   └── app.php                           # Middleware aliases + scheduler
│
├── database/
│   ├── migrations/                       # 13 timestamped migration files
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── BadgeSeeder.php
│       ├── ProblemSeeder.php
│       └── RoleSeeder.php
│
├── resources/views/
│   ├── admin/dashboard.blade.php         # Analytics dashboard (Chart.js)
│   ├── contests/
│   ├── layouts/
│   │   ├── admin.blade.php
│   │   ├── app.blade.php
│   │   ├── guest.blade.php
│   │   └── judge.blade.php
│   ├── leaderboard/
│   ├── problems/
│   └── submissions/
│
├── routes/
│   ├── web.php                           # All HTTP routes
│   ├── auth.php                          # Breeze auth routes
│   └── console.php                       # Artisan console routes
│
├── storage/app/submissions/              # Temp files created during judging
├── .env                                  # Environment config
├── composer.json
└── vite.config.js
```

---

## 🗃 Database Schema

> See the separate **ER Diagram** and **Schema Diagram** files in the project wiki / docs folder.

### Tables Summary

| Table | Purpose |
|---|---|
| `users` | All platform users with `status` (pending/approved/rejected) |
| `sessions` | Database-backed HTTP sessions |
| `password_reset_tokens` | Password reset flow |
| `roles` | Spatie role definitions (Guest, Contestant, ProblemSetter, Admin) |
| `model_has_roles` | Pivot — User ↔ Role assignment |
| `permissions` | Spatie permission definitions (unused currently) |
| `model_has_permissions` | Pivot — model ↔ permission |
| `role_has_permissions` | Pivot — Role ↔ permission |
| `problems` | Problem definitions with difficulty, slug, publishing flag |
| `test_cases` | Input/expected_output pairs per problem; `is_hidden` flag |
| `tags` | Problem topic tags |
| `problem_tag` | Pivot — Problem ↔ Tag |
| `submissions` | Code submissions with verdict enum and language |
| `notifications` | Laravel database notifications (UUID PK, polymorphic) |
| `contests` | Contest definitions with approval and active flags |
| `contest_problems` | Pivot — Contest ↔ Problem with alphabetical label (A, B, C…) |
| `contest_participants` | Pivot — Contest ↔ User with `joined_at` timestamp |
| `badges` | Badge definitions (name, description, icon_class) |
| `user_badges` | Pivot — User ↔ Badge with `awarded_at` timestamp |
| `jobs` | Laravel queue jobs table |
| `job_batches` | Laravel job batch table |
| `failed_jobs` | Failed job records for inspection/retry |
| `cache` | Database-backed application cache |

---

## 🌐 External Integrations

### Kontests API

- **Endpoint:** `https://kontests.net/api/v1/all`
- **Purpose:** Fetches upcoming/running contests from Codeforces, AtCoder, CodeChef, LeetCode
- **Cache Key:** `all_external_contests`
- **Cache TTL:** 600 seconds (10 min) — configurable via `EXTERNAL_CONTEST_CACHE_TTL`
- **Fallback:** If the API times out or fails, a hardcoded set of fallback contests is shown (guarantees the UI never appears broken)
- **Refresh Command:** `php artisan contests:refresh-external`
- **Auto-Refresh:** Via Laravel scheduler every 10 minutes

### DomPDF

- **Package:** `barryvdh/laravel-dompdf`
- **Purpose:** Generates a styled PDF of the contest scoreboard
- **Route:** `GET /contests/{contest}/scoreboard/pdf`

---

## 📄 License

This project is open-sourced under the [MIT license](LICENSE).

---

<div align="center">
Built with ❤️ using Laravel 12 &nbsp;|&nbsp; JudgeMate © 2026
</div>
