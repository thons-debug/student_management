-- =========================================
-- STUDENT MANAGEMENT SYSTEM DATABASE
-- FINAL COMPLETE DB SETUP
-- =========================================

CREATE DATABASE IF NOT EXISTS student_management;

USE student_management;

-- =========================================
-- USERS TABLE
-- =========================================

CREATE TABLE users (

    id INT PRIMARY KEY AUTO_INCREMENT,

    username VARCHAR(100) UNIQUE NOT NULL,

    email VARCHAR(150) UNIQUE,

    password VARCHAR(255) NOT NULL,

    role ENUM('admin','student') DEFAULT 'student',

    status ENUM(
        'pending',
        'approved',
        'rejected'
    ) DEFAULT 'pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- COURSES TABLE
-- =========================================

CREATE TABLE courses (

    id INT PRIMARY KEY AUTO_INCREMENT,

    course_name VARCHAR(150) NOT NULL,

    course_code VARCHAR(50) UNIQUE NOT NULL,

    duration VARCHAR(100),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- STUDENTS TABLE
-- =========================================

CREATE TABLE students (

    id INT PRIMARY KEY AUTO_INCREMENT,

    user_id INT UNIQUE,

    course_id INT NULL,

    name VARCHAR(150) NOT NULL,

    roll_number VARCHAR(100) UNIQUE NOT NULL,

    email VARCHAR(150),

    phone VARCHAR(20),

    address TEXT,

    dob DATE,

    gender ENUM(
        'male',
        'female',
        'other'
    ),

    admission_date DATE,

    photo VARCHAR(255) DEFAULT 'default.png',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE,

    FOREIGN KEY (course_id)
    REFERENCES courses(id)
    ON DELETE SET NULL
);

-- =========================================
-- ATTENDANCE TABLE
-- =========================================

CREATE TABLE attendance (

    id INT PRIMARY KEY AUTO_INCREMENT,

    student_id INT NOT NULL,

    attendance_date DATE NOT NULL,

    status ENUM(
        'present',
        'absent'
    ) DEFAULT 'present',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (student_id)
    REFERENCES students(id)
    ON DELETE CASCADE
);

-- =========================================
-- FEES TABLE
-- =========================================

CREATE TABLE fees (

    id INT PRIMARY KEY AUTO_INCREMENT,

    student_id INT NOT NULL,

    amount DECIMAL(10,2) NOT NULL,

    payment_date DATE NULL,

    status ENUM(
        'paid',
        'pending',
        'unpaid'
    ) DEFAULT 'pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (student_id)
    REFERENCES students(id)
    ON DELETE CASCADE
);

-- =========================================
-- NOTICES TABLE
-- =========================================

CREATE TABLE notices (

    id INT PRIMARY KEY AUTO_INCREMENT,

    title VARCHAR(255) NOT NULL,

    message TEXT NOT NULL,

    notice_date DATE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- RESULTS TABLE
-- =========================================

CREATE TABLE results (

    id INT PRIMARY KEY AUTO_INCREMENT,

    student_id INT NOT NULL,

    subject_name VARCHAR(150) NOT NULL,

    marks INT NOT NULL,

    grade VARCHAR(10),

    status ENUM(
        'pass',
        'fail'
    ) DEFAULT 'pass',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (student_id)
    REFERENCES students(id)
    ON DELETE CASCADE
);

-- =========================================
-- TIMETABLE TABLE
-- =========================================

CREATE TABLE timetable (

    id INT PRIMARY KEY AUTO_INCREMENT,

    day_name VARCHAR(50),

    subject_name VARCHAR(150),

    faculty_name VARCHAR(150),

    class_time VARCHAR(100),

    room_no VARCHAR(50),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- DEFAULT ADMIN ACCOUNT
-- PASSWORD: admin123
-- =========================================

INSERT INTO users (

    username,
    email,
    password,
    role,
    status

)

VALUES(

    'admin',

    'admin@gmail.com',

    '$2y$10$8M6M5v0Q6fL2n8uYkQ2f8eG5b7v7SxP5v0kVfB0yS2qzT7x6YQx4K',

    'admin',

    'approved'
);

-- =========================================
-- SAMPLE COURSES
-- =========================================

INSERT INTO courses (

    course_name,
    course_code,
    duration

)

VALUES

('BCA','BCA101','3 Years'),

('MCA','MCA201','2 Years'),

('Data Science','DS301','1 Year'),

('Full Stack Development','FSD401','6 Months');

-- =========================================
-- SAMPLE NOTICES
-- =========================================

INSERT INTO notices (

    title,
    message,
    notice_date

)

VALUES

(
'Semester Exams',
'Semester examinations will begin next month.',
CURDATE()
),

(
'Attendance Notice',
'Minimum attendance should be above 75%.',
CURDATE()
),

(
'Fee Reminder',
'Fee payment deadline is 25th of this month.',
CURDATE()
);

-- =========================================
-- SAMPLE TIMETABLE
-- =========================================

INSERT INTO timetable (

    day_name,
    subject_name,
    faculty_name,
    class_time,
    room_no

)

VALUES

(
'Monday',
'PHP Programming',
'Mr. Rahul',
'10:00 AM - 11:30 AM',
'Lab 1'
),

(
'Tuesday',
'Database Management',
'Mrs. Sneha',
'11:00 AM - 12:30 PM',
'Room 203'
),

(
'Wednesday',
'Web Development',
'Mr. Arjun',
'09:30 AM - 11:00 AM',
'Lab 2'
);

-- =========================================
-- INDEXES FOR PERFORMANCE
-- =========================================

CREATE INDEX idx_student_roll
ON students(roll_number);

CREATE INDEX idx_attendance_student
ON attendance(student_id);

CREATE INDEX idx_fees_student
ON fees(student_id);

CREATE INDEX idx_results_student
ON results(student_id);

-- =========================================
-- DATABASE SETUP COMPLETED
-- =========================================