# Padel Court Booking System

This is my Web Development 1 final project. It is a custom PHP MVC app where users can register, log in, check court availability, and book padel courts. Admins can manage courts, timeslots, and bookings.

## Run with Docker

From a clean clone in the project root:

1. docker-compose up --build
2. Open app: http://localhost:8080
3. Open phpMyAdmin: http://localhost:8081

Docker files used:

- docker-compose.yml
- Dockerfile

## Database and setup notes

- Required export file in project root: padel_booking.sql
- Docker auto-init file used by MySQL container: db/schema.sql (mounted through docker-compose.yml)
- padel_booking.sql is the root export copy for submission; db/schema.sql is the file Docker runs during container initialization.
- Main tables: users, courts, timeslots, bookings

If you already had an old Docker volume before the slot_date update, see troubleshooting below.

## Demo login credentials

These demo accounts are seeded in db/schema.sql and padel_booking.sql.

- Admin
	- Email: admin@padel.local
	- Password: admin123
- User
	- Email: user@padel.local
	- Password: user123

## Architecture and coding patterns

- MVC routing entry point: public/index.php
- Controllers coordinate request flow: src/Controllers
- Models represent entities: src/Models
- Repositories handle SQL data access: src/Repositories
- Services contain business logic between controllers and repositories: src/Services
- CSRF utility class for form protection: src/Framework/Csrf.php

Examples:

- Auth flow and password hashing: src/Services/AuthService.php
- Booking and availability repository queries: src/Repositories/BookingRepository.php
- Timeslot repository with date-based logic: src/Repositories/TimeslotRepository.php

## GDPR notes

Implemented in this project:

- Minimal personal data usage for account + booking features only
- Passwords are hashed (not stored in plain text)
- Session-based login with secure cookie settings
- Prepared statements for DB operations

Code references:

- src/Services/AuthService.php
- public/index.php
- src/Repositories/UserRepository.php
- src/Repositories/BookingRepository.php
- src/Repositories/Repository.php

## WCAG / accessibility notes

Implemented in this project:

- Page language is set in layout
- Semantic navigation/header structure
- Labels on form fields
- Bootstrap responsive layout and keyboard-focusable controls
- Availability section communicates loading/error/empty states clearly

Code references:

- src/Views/partials/header.php
- src/Views/auth/login.php
- src/Views/auth/register.php
- src/Views/courts/get.php
- public/css/app.css

## Troubleshooting

If you get SQL errors after schema changes (for example unknown column slot_date), your old Docker volume probably still has the old table structure.

Use a full reset:

1. docker compose down -v
2. docker compose up --build

This recreates the MySQL volume and re-runs db/schema.sql from scratch.
