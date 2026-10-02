# Zona AntiPhishing 2.0

## Database

zona_antiphishing

## Version

1.0

## Description

Educational platform focused on phishing awareness, simulations, quizzes, cybersecurity learning, digital protection, and user progress tracking.

---

## Status

Active Development

Current Phase:

Sprint 9 Planning (Certificates)

Completed:

- Sprint 1 (Learning Core)
- Sprint 2 (Quiz System)
- Sprint 3 (Simulation Database)
- Sprint 4 (Authentication & Dashboard Foundation)
- Sprint 5 (Courses and Lessons Module)
- Sprint 6 (Quiz Engine & Progress Tracking)
- Sprint 7 (Simulation Engine)
- Sprint 8 (Profile Module)

---

# Sprint 1 - Learning Core

## Tables

### users

Stores user accounts, roles, authentication information and activity data.

### courses

Stores educational course information.

### lessons

Stores lessons belonging to courses.

### user_progress

Tracks lesson completion and user learning progression.

---

## Relationships

- lessons.course_id -> courses.id
- user_progress.user_id -> users.id
- user_progress.lesson_id -> lessons.id

---

# Sprint 2 - Quiz System

## Tables

### quizzes

Stores quizzes linked to lessons.

### quiz_questions

Stores quiz questions and explanations.

### quiz_options

Stores answer options and identifies correct answers.

### quiz_attempts

Stores quiz attempts, results, scores and completion status.

---

## Relationships

- quizzes.lesson_id -> lessons.id
- quiz_questions.quiz_id -> quizzes.id
- quiz_options.question_id -> quiz_questions.id
- quiz_attempts.user_id -> users.id
- quiz_attempts.quiz_id -> quizzes.id

---

# Sprint 3 - Simulation System

## Tables

### simulations

Stores simulation definitions used for phishing awareness training.

### simulation_scenarios

Stores simulation scenarios, expected responses and educational explanations.

### simulation_attempts

Stores user simulation attempts and results.

---

## Relationships

- simulation_scenarios.simulation_id -> simulations.id
- simulation_attempts.user_id -> users.id
- simulation_attempts.simulation_id -> simulations.id

---

# Database Diagram

```text
USERS
  │
  ├──────────────► USER_PROGRESS ◄──────── LESSONS ◄──────── COURSES
  │
  ├──────────────► QUIZ_ATTEMPTS ◄──────── QUIZZES
  │                                     │
  │                                     ▼
  │                              QUIZ_QUESTIONS
  │                                     │
  │                                     ▼
  │                              QUIZ_OPTIONS
  │
  └──────────────► SIMULATION_ATTEMPTS ◄──────── SIMULATIONS
                                                   │
                                                   ▼
                                         SIMULATION_SCENARIOS
```

---

# Implemented Application Modules

## Authentication

- User Registration
- User Login
- Session Management
- Logout
- Protected Routes

## Dashboard

Features:

- Courses Available
- Lessons Completed
- Quizzes Passed
- Simulations Passed
- Progress Percentage

Uses data from:

- courses
- user_progress
- quiz_attempts
- simulation_attempts

## Courses

Features:

- Course Catalog
- Course Details
- Lesson Listing

## Lessons

Features:

- Lesson Navigation
- Estimated Duration
- Learning Structure

## Quizzes

Features:

- Quiz Rendering
- Questions and Options
- Score Calculation
- Pass/Fail Evaluation
- Quiz Attempt Persistence

Tables:

- quizzes
- quiz_questions
- quiz_options
- quiz_attempts

## Progress Tracking

Features:

- Lesson Completion Tracking
- User Learning Progress
- Dashboard Metrics

Tables:

- user_progress

## Simulations

Features:

- Simulation Catalog
- Scenario Evaluation
- Phishing Identification
- Correct / Incorrect Feedback
- Simulation Attempt Persistence

Tables:

- simulations
- simulation_scenarios
- simulation_attempts

## Profile

Features:

- User Information
- Role Information
- Lessons Completed
- Quizzes Passed
- Simulations Passed
- Progress Overview
- Logout Access

---

# Current MVP Flow

```text
Register
    ↓
Login
    ↓
Dashboard
    ↓
Courses
    ↓
Course Details
    ↓
Lessons
    ↓
Quiz
    ↓
Quiz Result
    ↓
User Progress
    ↓
Simulations
    ↓
Simulation Result
    ↓
Profile
```

---

# Upcoming Features

## Sprint 9

- Certificates
- Certificate Verification

## Sprint 10

- XP System
- Levels
- User Badges
- Achievements

## Sprint 11

- URL Analyzer
- Dashboard Analytics
- Advanced Statistics

## Sprint 12

- Privacy Management
- Cookie Preferences
- Audit Logs
- Administrative Panel

---

# Technical Stack

Frontend

- HTML5
- CSS3
- Bootstrap 5
- JavaScript

Backend

- PHP 8
- PDO

Database

- MySQL / MariaDB

Version Control

- Git
- GitHub

---

# Project Status

Current MVP Status:

✅ Authentication

✅ Dashboard

✅ Courses

✅ Lessons

✅ Quiz Engine

✅ Progress Tracking

✅ Simulations

✅ Profile

Next Major Milestone:

🏆 Certificates Module
