# Simple Task Manager (To-Do App)

A simple PHP task manager application with user authentication and per-user task management.  
Built with **HTML**, **PHP**, **MySQL (PDO)**, and **Bootstrap**.

---

## ✨ Features

- User authentication (Sign up, Login, Logout)  
  - Passwords hashed using `password_hash()` and verified with `password_verify()`.
- Task management:
  - Add, edit, delete tasks
  - Mark tasks as complete/incomplete
  - Each task belongs only to the logged-in user
- Database access via **PDO prepared statements** (SQL injection safe)
- Input sanitization (`sanitize()` helper) and XSS prevention
- Basic UI styling with **Bootstrap 5**
- Project structure separated into `config/`, `includes/`, `public/`, `sql/`

---

## 🗂️ Project Structure

```
simple-todo-app/
│
├── config/
│   └── db.php              # Database connection (PDO)
│
├── includes/
│   ├── auth.php            # Session helpers
│   ├── functions.php       # Sanitization helper
│   └── csrf.php            # CSRF protection helpers (bonus)
│
├── public/
│   ├── index.php           # Landing page
│   ├── register.php        # Sign up
│   ├── login.php           # Login
│   ├── logout.php          # Logout
│   ├── tasks.php           # Main task manager (CRUD)
│   ├── api.php             # AJAX endpoint for task actions (bonus)
│   └── assets/style.css    # Custom styling
│
└── sql/
    └── schema.sql          # Database schema (users & tasks)
```

---

## ⚙️ Requirements

- **PHP** 7.4+ (with PDO extension enabled)
- **MySQL** 5.7+ or MariaDB
- A local server (Apache/Nginx) or PHP built-in server

---

## 🚀 Setup Instructions

1. **Clone or extract project**
   ```bash
   git clone https://github.com/nafeesniloy/simple-todo-app.git
   cd simple-todo-app
   ```

2. **Create the database**
   ```sql
   CREATE DATABASE todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   USE todo_app;
   SOURCE sql/schema.sql;
   ```

3. **Configure DB connection**  
   Open `config/db.php` and update:
   ```php
   $host = "localhost";
   $db   = "todo_app";
   $user = "your_mysql_username";
   $pass = "your_mysql_password";
   ```

4. **Run the application**
   - Using PHP’s built-in server:
     ```bash
     php -S localhost:8000 -t public
     ```
   - Or configure Apache/Nginx with `public/` as the document root.

5. **Access in browser**  
   Visit [http://localhost:8000](http://localhost:8000) and register a new account.

---

## 🔒 Security Notes

- All passwords are stored securely using `password_hash()`.
- Inputs are sanitized before database insertion and output.
- SQL injection protected with **prepared statements**.
- CSRF protection added (`includes/csrf.php`) for all forms and API requests.
- Note: `csrf.php` and `api.php` are **not meant to be accessed directly**.  
  - `csrf.php` is included internally by other scripts.  
  - `api.php` only responds to AJAX calls with a valid CSRF token.

---

## 📌 Roadmap / Bonus Features

- ✅ AJAX for task actions (add/edit/delete without reload)
- ✅ CSRF protection
- Password policy enforcement (min. length, complexity)
- Flash messages for better feedback

---

## 📜 License
MIT License

Copyright (c) 2025 S.M. Nafees Hossain Niloy

This project is released under the MIT License.
