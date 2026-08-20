-- ============================================================
-- Forces Academy LMS — Complete Database Schema
-- Run this ENTIRE file in phpMyAdmin (SQL tab) on a fresh
-- database named `forces_academy_lms`, or paste it into
-- InfinityFree's phpMyAdmin to set everything up from scratch.
--
-- Tables are created in dependency order (parent tables first,
-- so foreign keys never fail).
-- ============================================================

-- ------------------------------------------------------------
-- 1. STUDENTS
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    roll_number VARCHAR(50) NOT NULL UNIQUE,
    class VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- 2. ADMINS
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample admin login:
--   Email:    admin@forcesacademy.com
--   Password: Admin123!
INSERT INTO admins (full_name, email, password) VALUES
('System Administrator', 'admin@forcesacademy.com', '$2b$10$DD.cIMk6C1OeB6DVBX2Q3eUZnMSAyrQfq8JcADUsEKUNGqQohZ8EC');

-- ------------------------------------------------------------
-- 3. COURSES
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(150) NOT NULL,
    description TEXT,
    teacher_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO courses (course_name, description, teacher_name) VALUES
('Introduction to Programming', 'Fundamentals of programming logic using C and Python, covering variables, loops, and functions.', 'Maj. Ahsan Raza'),
('Data Structures & Algorithms', 'Core data structures — arrays, linked lists, trees, graphs — and algorithmic problem solving.', 'Capt. Sana Khalid'),
('Web Development Fundamentals', 'HTML, CSS, PHP, and MySQL basics for building dynamic, database-driven websites.', 'Lt. Bilal Ahmed'),
('Military History & Strategy', 'Study of key historical battles, strategic doctrine, and leadership case studies.', 'Col. Imran Sheikh');

-- ------------------------------------------------------------
-- 4. NOTICES
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    posted_by VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO notices (title, content, posted_by, created_at) VALUES
('Mid-Term Exam Schedule Released', 'The mid-term examination schedule has been posted on the main notice board. All cadets must confirm their exam slots by Friday.', 'Admin Office', NOW() - INTERVAL 1 DAY),
('Parade Ground Maintenance Notice', 'The parade ground will be closed for maintenance this weekend. Physical training sessions will be relocated to the indoor hall.', 'Admin Office', NOW() - INTERVAL 3 DAY),
('New Library Hours', 'The academy library will now remain open until 9 PM on weekdays to support exam preparation.', 'Admin Office', NOW() - INTERVAL 5 DAY),
('Guest Lecture: Cybersecurity in Defense', 'A guest lecture on cybersecurity fundamentals in defense systems will be held next Tuesday in the main auditorium.', 'Admin Office', NOW() - INTERVAL 7 DAY);

-- ------------------------------------------------------------
-- 5. ASSIGNMENTS (depends on courses)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    course_id INT NOT NULL,
    due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

INSERT INTO assignments (title, description, course_id, due_date) VALUES
('Assignment 1: Variables & Loops', 'Write 5 small C programs demonstrating variables, loops, and conditional logic.', (SELECT id FROM courses WHERE course_name = 'Introduction to Programming' LIMIT 1), DATE_ADD(CURDATE(), INTERVAL 5 DAY)),
('Assignment 2: Linked List Implementation', 'Implement a singly linked list with insert, delete, and search operations.', (SELECT id FROM courses WHERE course_name = 'Data Structures & Algorithms' LIMIT 1), DATE_ADD(CURDATE(), INTERVAL 7 DAY)),
('Assignment 3: Build a Login Form', 'Create a simple HTML/CSS/PHP login form with basic validation.', (SELECT id FROM courses WHERE course_name = 'Web Development Fundamentals' LIMIT 1), DATE_ADD(CURDATE(), INTERVAL 3 DAY)),
('Assignment 4: Battle Case Study', 'Submit a 2-page write-up analyzing the strategy of a historical battle of your choice.', (SELECT id FROM courses WHERE course_name = 'Military History & Strategy' LIMIT 1), DATE_ADD(CURDATE(), INTERVAL 10 DAY));

-- ------------------------------------------------------------
-- 6. SUBMISSIONS (depends on assignments + students)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assignment_id INT NOT NULL,
    student_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('submitted', 'graded') DEFAULT 'submitted',
    FOREIGN KEY (assignment_id) REFERENCES assignments(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);
-- No sample rows here — submissions are created naturally when a real
-- student uploads a file, since file_path must point to a real file.

-- ------------------------------------------------------------
-- 7. RESULTS (depends on students + courses)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    subject VARCHAR(100) NOT NULL,
    marks INT NOT NULL,
    total_marks INT NOT NULL,
    grade VARCHAR(5) NOT NULL,
    exam_type VARCHAR(50) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);
-- No sample rows here — student_id must match a REAL registered student.
-- After registering a test student, insert results manually, e.g.:
--
-- INSERT INTO results (student_id, course_id, subject, marks, total_marks, grade, exam_type) VALUES
-- (1, (SELECT id FROM courses WHERE course_name = 'Introduction to Programming' LIMIT 1), 'Programming Fundamentals', 42, 50, 'A', 'Mid-Term');

-- ------------------------------------------------------------
-- 8. TIMETABLE
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS timetable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class VARCHAR(100) NOT NULL,
    day VARCHAR(20) NOT NULL,
    time_slot VARCHAR(50) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    teacher VARCHAR(100) NOT NULL
);

INSERT INTO timetable (class, day, time_slot, subject, teacher) VALUES
('BSCS', 'Monday', '09:00 AM - 10:00 AM', 'Introduction to Programming', 'Maj. Ahsan Raza'),
('BSCS', 'Monday', '10:00 AM - 11:00 AM', 'Data Structures & Algorithms', 'Capt. Sana Khalid'),
('BSCS', 'Wednesday', '11:00 AM - 12:00 PM', 'Web Development Fundamentals', 'Lt. Bilal Ahmed'),
('BSCS', 'Friday', '01:00 PM - 02:00 PM', 'Military History & Strategy', 'Col. Imran Sheikh');

-- ------------------------------------------------------------
-- 9. FEES (depends on students)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS fees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    due_date DATE NOT NULL,
    paid_date DATE NULL,
    status ENUM('pending', 'paid', 'overdue') NOT NULL DEFAULT 'pending',
    description VARCHAR(255),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);
-- No sample rows here either — student_id must match a REAL registered student.
-- After registering a test student, insert fees manually, e.g.:
--
-- INSERT INTO fees (student_id, amount, due_date, description, status) VALUES
-- (1, 45000.00, DATE_ADD(CURDATE(), INTERVAL 10 DAY), 'Semester Fee - Fall 2026', 'pending');

-- ============================================================
-- DONE. Tables created in this file:
--   students, admins, courses, notices, assignments,
--   submissions, results, timetable, fees
--
-- Next steps:
--   1. Register a real student through register.php
--   2. Use that student's real id for any results/fees sample rows
--   3. Log in as admin (see credentials above) to manage everything else
-- ============================================================
