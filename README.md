# VOYARA TRAVEL BOOKING SYSTEM
### Project Documentation & Important Updates

---

## Project Structure
A professional overview of where everything lives.

```text
voyara/
├── .htaccess                  # Apache URL rewriting + security headers
├── database.sql               # Full MySQL schema + seed data
├── seed.php                   # Database seeder (for local testing)
├── config/
│   ├── app.php                # App constants, paths, debug mode
│   ├── database.php           # DB credentials
│   └── bootstrap.php          # Autoloader, session, helpers
├── app/
│   ├── controllers/           # Business Logic
│   ├── models/                # Database Queries & Models
│   └── views/                 # UI Templates (PHP/HTML)
├── public/
│   └── index.php              # Main entry point (Router)
├── assets/
│   ├── css/                   # Stylesheets
│   └── js/                    # JavaScript logic
└── uploads/                   # User images/receipts storage
```
---
##  Errors Fixed in Project:

While reviewing the codebase, I found and fixed a few basic errors:

### 1. ROOT_PATH Error
When running the application, there was an error saying `ROOT_PATH is already defined`. I added a check in the configuration file to prevent this:
```php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__ . '/../..');
}
```

### 2. Header & Footer Bug
The PHP closing tags were missing in the header and footer files, which caused HTML to appear incorrectly and break the design. I added the missing closing tags to fix this.

---

## Changes Made:

1.  **New Homepage:** I replaced the old design with a modern, premium design that looks more professional.
2.  **Real Data Integration:** The homepage now uses real data from the database instead of fake test data.
3.  **Local Seeder:** I added `seed.php` to help with testing. It fills the database with test data locally. (Never use this on the live website.)

---

## Hosting Details
For details and guide on hosting refer to [Hosting.md](Hosting.md)

---

## Contact & Feedback
*   **Developer:** Kiran Khadka
*   **Email:** [therealkiranda@gmail.com](mailto:therealkiranda@gmail.com)
*   **Co-developer:** Utsav Pokharel (Utsav-56)
