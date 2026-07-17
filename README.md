<div align="center">
    <img src="public/logo.svg" alt="JudgeMate Logo" width="120" height="120">
    <h1>JudgeMate</h1>
    <p>A modern, robust, and beautiful Competitive Programming Judging Platform built on Laravel.</p>
</div>

---

## 🚀 Overview

**JudgeMate** is an advanced platform for hosting programming contests, managing algorithmic problem sets, and automatically evaluating user submissions. Whether you're a school, university, or a coding bootcamp, JudgeMate provides a sleek interface and powerful tools to run your programming competitions effortlessly.

## ✨ Key Features

### 🧑‍💻 For Contestants
* **Beautiful Dashboard:** Track your progress, submissions, and contest history.
* **Live Leaderboards:** Real-time contest standings with dynamic PDF exports.
* **Inspiration Feed:** A dedicated space to read tips, tutorials, and inspiration posts (with GIFs/Images) shared by our expert Judges.
* **Day & Night Mode:** A seamless, aesthetically pleasing UI that adapts to your preferred lighting.

### ⚖️ For Judges (Problem Setters)
* **Problem Management:** Create, edit, and publish challenging problems with full Markdown support.
* **Test Case Sandbox:** Add hidden and visible test cases to thoroughly evaluate solutions.
* **Blogging & Inspiration:** Write insightful posts and share GIFs/images to the "Inspiration" feed to help guide contestants.

### 🛡️ For Administrators
* **User Management:** Approve or reject new users, ensuring your platform remains secure.
* **Content Moderation:** Review and approve blog posts submitted by Judges before they go live on the Inspiration feed.
* **Contest Administration:** Oversee all active and upcoming contests.

## 🛠️ Tech Stack

- **Backend:** [Laravel 11](https://laravel.com/) (PHP)
- **Frontend UI:** Blade Templates, [Tailwind CSS](https://tailwindcss.com/), Alpine.js
- **Asset Bundling:** [Vite](https://vitejs.dev/)
- **Database:** MySQL / SQLite

## 📦 Installation & Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/JudgeMate.git
   cd JudgeMate
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Make sure to configure your database settings in the `.env` file.*

4. **Run Migrations & Seeders**
   ```bash
   php artisan migrate --seed
   ```

5. **Link Storage (For Blog Images/GIFs)**
   ```bash
   php artisan storage:link
   ```

6. **Compile Assets & Run Server**
   ```bash
   npm run build
   php artisan serve
   ```
   *Visit `http://localhost:8000` to see your platform in action!*

## 💡 About the Inspiration Feed
The **Inspiration** section is a unique feature of JudgeMate that bridges the gap between expert Problem Setters and Contestants. Judges can write rich blog posts, attach helpful images or funny GIFs, and submit them for review. Once an Admin clicks **Approve**, the post goes live on the Contestant's Inspiration dashboard!

---
*Built with ❤️ for the Competitive Programming Community.*
