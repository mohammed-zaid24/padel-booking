
# Padel Court Booking System

Custom PHP MVC application for managing padel court reservations.

## Features
- User registration & login
- Real-time availability (JavaScript + JSON API)
- Booking & cancellation
- Admin panel (courts, timeslots, bookings)
- Secure PDO prepared statements
- Role-based authorization
- Bootstrap UI

## Tech Stack
- PHP (custom MVC)
- MySQL
- Docker
- Bootstrap 5
- JavaScript fetch API

## Accessibility (WCAG)

This project aims to align with WCAG 2.1 considerations where applicable:

- **Page language**: Root `<html lang="en">` is set so assistive technologies use the correct language. See `src/Views/partials/header.php`.
- **Semantic structure**: Navigation is wrapped in `<nav>`, content in `<header>` / main content areas. Same file.
- **Form labels**: All form inputs have associated `<label>` elements (e.g. login, register, court date picker). See `src/Views/auth/login.php`, `src/Views/auth/register.php`, and `src/Views/courts/get.php`.
- **Contrast and focus**: Bootstrap 5 is used for consistent colour contrast and focus styles. Buttons and links are keyboard-focusable.
- **Dynamic content**: The availability list in `src/Views/courts/get.php` is updated via JavaScript; the date picker has a visible label and the loading/error states are communicated in the same region.

## GDPR considerations

- **Data collected**: Name, email, and a hashed password for account creation; booking data (court, date, timeslot) linked to the user. See `src/Repositories/UserRepository.php` and `src/Repositories/BookingRepository.php`.
- **Purpose**: Account management and court booking only. No analytics or third-party tracking in this codebase.
- **Sessions**: Login state is stored in server-side sessions with secure cookie settings (httponly, samesite). See `public/index.php` (session configuration) and `src/Services/AuthService.php` (what is stored in session).
- **Passwords**: Stored only as hashes via `password_hash()`; see `src/Services/AuthService.php`.

For a production deployment you would additionally document retention periods, legal basis, and user rights (access, rectification, erasure) and implement them where required.
