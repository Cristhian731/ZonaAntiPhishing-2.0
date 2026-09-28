# Zona AntiPhishing 2.0

## Database

zona_antiphishing

## Version

1.0

## Description

Educational platform focused on phishing awareness, simulations, quizzes, cybersecurity learning, and digital protection.

---

## Status

Active Development

Current Phase:
Sprint 3 Planning

Completed:

- Sprint 1 (Learning Core)
- Sprint 2 (Quiz System)

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

Tracks lesson completion and learning progress.

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

# Database Diagram

```text
COURSES
   │
   ▼
LESSONS
   │
   ├──────────────► USER_PROGRESS ◄──── USERS
   │
   ▼
QUIZZES ◄───────── QUIZ_ATTEMPTS ◄──── USERS
   │
   ▼
QUIZ_QUESTIONS
   │
   ▼
QUIZ_OPTIONS
```

---

# Future Development

## Sprint 3 - Phishing Simulations

Planned Tables:

- simulations
- simulation_scenarios
- simulation_attempts

Purpose:

Provide realistic phishing training scenarios where users can practice identifying suspicious emails, messages, websites, and social engineering techniques.

---

## Future Features

- XP System
- Badges
- Certificates
- URL Analyzer
- Dashboard Analytics
- Privacy Management
- Audit Logs
- Administrative Panel
