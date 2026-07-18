<div align="center">
    <h1>👨‍💻 JudgeMate</h1>
    <p><strong>A modern, high-performance Competitive Programming Judging Platform built with Laravel, Tailwind CSS, and Alpine.js.</strong></p>
    <p>
        <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=flat-square&logo=laravel" alt="Laravel">
        <img src="https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=flat-square&logo=tailwind-css" alt="Tailwind">
        <img src="https://img.shields.io/badge/Alpine.js-3.0-8BC0D0?style=flat-square&logo=alpine.js" alt="Alpine">
    </p>
</div>

---

## 🚀 Overview

**JudgeMate** is a sleek, highly-polished platform for hosting programming contests, managing algorithmic problem sets, and tracking contestant submissions. It features an incredibly fast UI, seamless Light/Dark mode transitions, asynchronous UI elements, and a robust role-based architecture.

Whether you're running a school competition or practicing classic CP algorithms, JudgeMate provides an unmatched developer and user experience.

---

## ✨ Key Features

### 🧑‍💻 For Contestants
* **Beautiful Dashboard:** Track your problem-solving statistics asynchronously.
* **Realistic Problemset:** Sort and filter classic CP problems by difficulty. Problems feature genuine Markdown statements, complex math constraints, and realistic sample I/O.
* **Live Leaderboards:** View real-time contest standings with PDF export capabilities.
* **Inspiration Feed:** Read tips, tutorials, and inspiration posts shared by our expert Judges.
* **Day & Night Mode:** A seamless, aesthetically pleasing UI that remembers your preferences.

### ⚖️ For Judges (Problem Setters)
* **Problem Management:** Create, edit, and publish challenging problems with full Markdown support. View a personalized dashboard of *only* the problems you've created.
* **Test Case Sandbox:** Add hidden and visible test cases to thoroughly evaluate solutions.
* **Blogging & Inspiration:** Write insightful posts with images/GIFs to help guide contestants.

### 🛡️ For Administrators
* **User Management:** Approve, reject, or ban users to ensure platform security.
* **Content Moderation:** Review and approve blog posts submitted by Judges before they go live on the Contestants' Inspiration feed.
* **Global Access:** Admins have unrestricted viewing access to all problems, contests, and hidden test cases.

---

## 🛠️ Tech Stack

- **Backend:** [Laravel 11](https://laravel.com/) (PHP 8.2+)
- **Frontend UI:** Blade Templates, [Tailwind CSS](https://tailwindcss.com/) (v3.4), [Alpine.js](https://alpinejs.dev/)
- **Asset Bundling:** [Vite](https://vitejs.dev/)
- **Database:** MySQL / SQLite
- **Icons:** Heroicons

---

## 📦 Installation & Local Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/JudgeMate.git
   cd JudgeMate
   ```

2. **Install PHP and Node Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Configure your database settings (e.g. `DB_CONNECTION=sqlite`) in the `.env` file.*

4. **Run Migrations & Seeders**
   ```bash
   # This will build the DB and inject all Mock Users and Realistic CP Problems!
   php artisan migrate:fresh --seed
   php artisan db:seed --class=MockDataSeeder
   ```

5. **Link Storage**
   *(Required for displaying Blog/Inspiration Images & GIFs)*
   ```bash
   php artisan storage:link
   ```

6. **Compile Assets & Run Server**
   ```bash
   npm run build
   php artisan serve
   ```
   *Visit `http://localhost:8000` to see your platform in action!*

---

## 🔑 Demo Login Credentials

The `MockDataSeeder` provisions the database with realistic classic CP problems and specific user accounts for testing. **The password for all accounts is `password`.**

### 🛡️ Admin Account
- **Email:** `admin@judgemate.test`

### ⚖️ Judge (Problem Setter) Accounts
Use any of these emails to log in as a Judge and manage problem sets:
- `rakin@judgemate.test`
- `wajih@judgemate.test`
- `farhad@judgemate.test`
- `shadik@judgemate.test`
- `sadid@judgemate.test`

### 🧑‍💻 Contestant Accounts
Use any of these emails to log in as a standard contestant, view problems, and browse the Inspiration feed:
- `toki@judgemate.test`
- `alif1@judgemate.test`
- `masum@judgemate.test`
- `ruhan@judgemate.test`
- `torikul@judgemate.test`
- `siyam@judgemate.test`
- `alok@judgemate.test`
- `sazzad@judgemate.test`
- `alif2@judgemate.test`
- `saikat@judgemate.test`

---
*Built with ❤️ for the Competitive Programming Community.*
