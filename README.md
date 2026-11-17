# University-Internship-and-Career-Portal-System
# 🎓 University Internship and Career Portal System

## 📌 Project Overview

The **University Internship and Career Portal System** is a web-based platform designed to connect university students, employers, and university administrators through a centralized career management system.

The main purpose of this project is to simplify the internship and job application process by providing students with opportunities to explore career paths, apply for internships, and connect with potential employers.

The system also allows employers to publish internship and job vacancies, review applications, and identify suitable candidates. University administrators can manage students, employers, job opportunities, and overall portal activities.

This platform aims to improve communication between universities, students, and industries while making the internship and recruitment process more efficient, organized, and accessible.

---

## 🎯 Project Objectives

- Provide a centralized platform for internship and job opportunities.
- Help university students find suitable internships and employment opportunities.
- Allow employers to publish internship and job vacancies.
- Simplify the application and recruitment process.
- Enable students to create and manage their professional profiles.
- Allow employers to review applications and select suitable candidates.
- Provide administrators with tools to manage the entire system.
- Improve collaboration between universities and industries.

---

## 👥 Main User Roles

### 1. Student

Students can use the portal to explore career opportunities and manage their applications.

**Main Features:**
- Student registration and login.
- Create and update student profiles.
- Upload resumes and CVs.
- Browse available internships and job vacancies.
- Search and filter opportunities.
- View internship and job descriptions.
- Apply for internship and job opportunities.
- Track application status.
- View application history.
- Receive notifications about application updates.

### 2. Employer

Employers can use the system to find suitable university students for internships and job opportunities.

**Main Features:**
- Employer registration and login.
- Create and manage company profiles.
- Post internship opportunities.
- Publish job vacancies.
- Update and delete job postings.
- View student applications.
- Access student profiles and resumes.
- Shortlist suitable candidates.
- Update application statuses.
- Manage recruitment activities.

### 3. Administrator

The administrator manages and monitors the overall system.

**Main Features:**
- Secure administrator login.
- Manage student accounts.
- Manage employer accounts.
- Manage internship and job postings.
- View and manage applications.
- Monitor portal activities.
- Manage system information.
- Maintain platform security and data integrity.

---

## ⚙️ Core System Modules

| Module | Description |
|---|---|
| User Authentication | Registration, login and logout |
| Student Management | Student profiles and information |
| Employer Management | Company profiles and employer accounts |
| Internship Management | Create and manage internship opportunities |
| Job Management | Publish and manage job vacancies |
| Application Management | Submit and manage applications |
| Resume Management | Upload and manage student CVs |
| Search Management | Search and filter available opportunities |
| Notification Management | Notify users about important updates |
| Admin Management | Manage and monitor the portal |

---

## 🛠️ Technologies Used

The project is developed using modern web development technologies.

**Frontend**
- HTML5
- CSS3
- JavaScript
- Bootstrap

**Backend**
- PHP

**Database**
- MySQL

**Development Tools**
- Visual Studio Code
- XAMPP
- phpMyAdmin
- Git
- GitHub

*Note: Update this section if your team uses different technologies.*

---

## 📂 Project Structure

```text
University-Internship-and-Career-Portal-System/
│
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
│
├── config/
│   └── database.php
│
├── admin/
│   ├── dashboard.php
│   ├── manage_students.php
│   ├── manage_employers.php
│   └── manage_jobs.php
│
├── student/
│   ├── dashboard.php
│   ├── profile.php
│   ├── internships.php
│   ├── jobs.php
│   └── applications.php
│
├── employer/
│   ├── dashboard.php
│   ├── company_profile.php
│   ├── post_job.php
│   └── applications.php
│
├── uploads/
│   └── resumes/
│
├── index.php
├── login.php
├── register.php
├── logout.php
├── database.sql
└── README.md
```

*The structure above is a suggested organization. Adjust it according to the actual project files.*

---

## 🔄 System Workflow

1. Users visit the University Internship and Career Portal.
2. Students and employers register for accounts.
3. Registered users log in to their respective dashboards.
4. Students complete their profiles and upload resumes.
5. Employers create company profiles and publish opportunities.
6. Students browse and search available opportunities.
7. Students submit applications for suitable positions.
8. Employers review applications and shortlist candidates.
9. Employers update application statuses.
10. Students receive updates about their applications.
11. Administrators monitor and manage the platform.

---

## 🚀 Installation and Setup

Follow these steps to run the project locally.

### Step 1: Install Required Software

Install the following tools:

- XAMPP
- Visual Studio Code
- Git

### Step 2: Clone the Repository

Open your terminal and execute:

```bash
git clone https://github.com/your-username/University-Internship-and-Career-Portal-System.git
```

### Step 3: Move the Project

Copy the project folder into the XAMPP `htdocs` directory.

Example:

```text
C:\xampp\htdocs\University-Internship-and-Career-Portal-System
```

### Step 4: Start XAMPP

Open XAMPP Control Panel and start:

- Apache
- MySQL

### Step 5: Create the Database

1. Open phpMyAdmin.
2. Create a new database.
3. Import the `database.sql` file.
4. Verify that the database tables have been created.

### Step 6: Configure Database Connection

Update the database connection settings in the configuration file.

Example:

```php
<?php
$conn = new mysqli(
    "localhost",
    "root",
    "",
    "university_career_portal"
);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
```

### Step 7: Run the Project

Open your browser and visit:

```text
http://localhost/University-Internship-and-Career-Portal-System/
```

---

## 🔐 Security Features

The system aims to provide a secure environment through:

- Password hashing.
- User authentication.
- Role-based access control.
- Input validation.
- Secure database operations.
- Session management.
- Restricted access to administrative features.

---

## 🌟 Expected Benefits

**For Students**
- Easy access to internship and job opportunities.
- Improved career planning.
- Convenient online applications.
- Better connections with employers.

**For Employers**
- Easier recruitment process.
- Access to talented university students.
- Centralized application management.
- Reduced manual recruitment activities.

**For Universities**
- Better student career support.
- Improved university-industry connections.
- Centralized internship management.
- Easier monitoring of student career opportunities.

---

## 🔮 Future Enhancements

The following features can be considered for future development:

- Email and SMS notifications.
- AI-based internship recommendations.
- Advanced job matching system.
- Interview scheduling.
- Online interview integration.
- Application analytics and reports.
- Company verification system.
- Mobile-friendly application.
- Career guidance and learning resources.

---

## 👨‍💻 Team Project

This project is developed collaboratively as a university team project.

**Project Name:** University Internship and Career Portal System

**Project Type:** Web-Based Application

**Development Approach:** Team-Based Software Development

**Repository:** GitHub

### Team Contributions

Each team member contributes to different parts of the system, including frontend development, backend development, database design, testing, and documentation.

---

## 📌 Project Status

**Development Status:** In Progress

New features, improvements, bug fixes, and documentation updates will be added throughout the development process.

---

## 📄 License

This project is developed for educational and academic purposes as part of a university team project.

---

⭐ **University Internship and Career Portal System – Connecting Students with Career Opportunities.**

