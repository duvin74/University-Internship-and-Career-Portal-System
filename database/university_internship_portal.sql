
CREATE DATABASE IF NOT EXISTS university_internship_portal
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE university_internship_portal;

-- 1. USERS TABLE
CREATE TABLE IF NOT EXISTS Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Student', 'Company', 'Admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. STUDENT PROFILES TABLE
CREATE TABLE IF NOT EXISTS student_profiles (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    university VARCHAR(150),
    course VARCHAR(150),
    skills TEXT,
    cv_path VARCHAR(255),
    profile_photo VARCHAR(255) NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- 3. COMPANIES TABLE
CREATE TABLE IF NOT EXISTS companies (
    company_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    company_name VARCHAR(150) NOT NULL,
    website VARCHAR(255),
    description TEXT,
    verification_status ENUM('Pending', 'Verified', 'Rejected')
        DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- 4. INTERNSHIPS TABLE
CREATE TABLE IF NOT EXISTS internships (
    internship_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    requirements TEXT,
    deadline DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(company_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- 5. APPLICATIONS TABLE
CREATE TABLE IF NOT EXISTS applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    internship_id INT NOT NULL,
    student_id INT NOT NULL,
    application_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending', 'Accepted', 'Rejected', 'Withdrawn')
        DEFAULT 'Pending',
    UNIQUE KEY unique_student_application (internship_id, student_id),
    FOREIGN KEY (internship_id) REFERENCES internships(internship_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (student_id) REFERENCES student_profiles(student_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- 6. INTERVIEWS TABLE
CREATE TABLE IF NOT EXISTS interviews (
    interview_id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    interview_date DATETIME,
    meet_link VARCHAR(500),
    status VARCHAR(50) DEFAULT 'Scheduled',
    FOREIGN KEY (application_id) REFERENCES applications(application_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- 7. EXPERIENCES TABLE
CREATE TABLE IF NOT EXISTS experiences (
    experience_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    company_id INT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES student_profiles(student_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (company_id) REFERENCES companies(company_id)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- 8. COMPANY ACHIEVEMENTS TABLE
CREATE TABLE IF NOT EXISTS company_achievements (
    achievement_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    `date` DATE,
    FOREIGN KEY (company_id) REFERENCES companies(company_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- 9. REPORTS TABLE
CREATE TABLE IF NOT EXISTS reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    description TEXT NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- ADMIN LOGIN ACCOUNT
INSERT INTO Users (name, email, password, role)
SELECT
    'CareerConnect Admin',
    'admin@careerconnect.com',
    '$2y$12$30.mXncYqO6Z9omjUYWv..ov4bX1J1AinJfJyyrjYZKDgWowD36sK',
    'Admin'
WHERE NOT EXISTS (
    SELECT 1 FROM Users
    WHERE email = 'admin@careerconnect.com'
);
