# Forces Academy LMS

A full-stack Learning Management System built for a military-academy-themed institution — students manage their courses, assignments, results, timetable, and fees, while admins run the entire academy through a dedicated control panel.

**Live Site:** [tehseenproject.ct.ws](https://tehseenproject.ct.ws/login.php) 
**Admin Panel:** [tehseenproject.ct.ws/admin/login.php](https://tehseenproject.ct.ws/admin/login.php)

---

## Screenshots



| | |
|---|---|
| ![Student Dashboard](./screenshots/dashboard.png) | ![Assignments](./screenshots/assignments.png) |
| ![Admin Panel](./screenshots/admin-dashboard.png) | ![Manage Students](./screenshots/manage-students.png) |
| ![Mobile View](./screenshots/mobile-view.jpeg) | |

---

## Tech Stack

- **Backend:** PHP (procedural, mysqli with prepared statements)
- **Database:** MySQL
- **Frontend:** HTML5, custom CSS design system, Bootstrap 5
- **JavaScript:** Vanilla JS (no framework) — mobile navigation, password visibility toggle
- **Fonts:** Oswald, Inter, JetBrains Mono (Google Fonts) 
- **Hosting:** InfinityFree (free PHP + MySQL hosting)
- **Version Control:** Git & GitHub

---

## Features

### Student Portal
- Secure registration and login with hashed passwords (`password_hash` / `password_verify`)
- Dashboard with live stats — total courses, pending assignments (with overdue flagging), and latest notice
- Course listing pulled directly from the database
- Notice board with title search
- Assignments page — view all assignments, submit files (PDF/image) with type and size validation, submitted status tracking
- Results page — view personal grades only, with a print-friendly "Print Results" view
- Weekly timetable filtered to the student's own class
- Fee records with total pending amount shown prominently
- Editable profile (name, email) and secure password change
- Fully responsive — tested on mobile

### Admin Panel
- Completely separate login and session system from the student portal
- Dashboard with academy-wide stats (students, courses, assignments, notices)
- Manage Students — search by name, email, or roll number; view details; delete with confirmation
- Manage Courses — add, edit, delete
- Manage Assignments — add, edit, delete, linked to courses
- Post Notices — add and delete, instantly visible on the student side
- Upload Results — per student, per course, with subject/marks/grade/exam type
- Manage Timetable — add class schedules by day and time slot
- Manage Fees — add fee records per student, mark as paid, track overdue status

### Security & Validation
- All database queries use prepared statements (SQL injection protection)
- Passwords hashed, never stored in plain text
- Session-based access control on every protected page — unauthorized access redirects to login
- File uploads validated by both extension and real MIME type, saved with unique filenames
- Uploads folder protected with `.htaccess` to block script execution
- Server-side and client-side form validation (email format, password length)

---

## How to Run Locally

1. **Install a local PHP server** — [XAMPP](https://www.apachefriends.org/) (Windows/Mac/Linux) or [Laragon](https://laragon.org/) (Windows).

2. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/forces-academy-lms.git
   ```
   Place the folder inside your server's web root (e.g. `htdocs/` for XAMPP).

3. **Create the database:**
   - Start Apache and MySQL from your XAMPP/Laragon control panel
   - Open phpMyAdmin (`http://localhost/phpmyadmin`)
   - Create a new database named `forces_academy_lms`
   - Import the SQL files from the `/database` folder

4. **Configure the database connection:**
   Open `config/db.php` and set your local credentials:
   ```php
   $host     = "localhost";
   $user     = "root";
   $password = "";
   $database = "forces_academy_lms";
   ```

5. **Run the project:**
   Visit `http://localhost/forces-academy-lms/` in your browser. Register a new student account, or create an admin account directly in the `admins` table via phpMyAdmin (passwords must be hashed).

---

## Project Structure

```
/                    → student-facing pages
/admin/              → admin panel (separate login/session)
/admin/actions/      → admin CRUD handlers (insert/update/delete)
/includes/           → shared student-side PHP (auth check, sidebar)
/admin/includes/     → shared admin-side PHP
/config/db.php       → database connection
/css/style.css       → complete design system
/js/main.js          → mobile nav + password toggle
/uploads/            → student assignment submissions
```

---

## Built By

**Tehseen Sughra** | Code Saviours SI-26 | 2026
