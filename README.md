# Tribbbal Internship Calendar

A PHP and MySQL web application for replacing the internship schedule spreadsheet with a clean, searchable, and easy-to-manage calendar system.

This workspace now includes the full application structure, reusable PHP helpers, a responsive front end, and an SQL export with seeded internship-day records for immediate import.

## Project Summary

This project stores internship schedule data in MySQL and presents it in a modern calendar/timeline interface. An administrator can add, edit, delete, search, and view internship events by week and day.

The goal is to turn the Excel-based schedule into a production-style PHP application with:

- A structured database
- Reusable PHP includes
- A responsive UI
- CRUD functionality
- A documented set of PHP built-in functions used in the project

## Core Features

- Home page showing all weeks
- Calendar/timeline view
- Event details page
- Add event form
- Edit event form
- Delete event action with confirmation
- Search by event title
- Dashboard with summary stats
- Responsive layout for mobile and desktop

## Recommended Folder Structure

```text
internship-calendar/
│
├── index.php
├── calendar.php
├── add-event.php
├── edit-event.php
├── delete-event.php
├── event.php
├── search.php
├── dashboard.php
│
├── config/
│   └── database.php
│
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── functions.php
│
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── script.js
│   └── images/
│
├── sql/
│   └── internship_calendar.sql
│
└── README.md
```

## Database Design

### Database Name

```sql
internship_calendar
```

### Main Table

```sql
calendar_events
```

### Suggested Columns

| Field | Type | Purpose |
|---|---|---|
| id | INT, Primary Key | Unique event identifier |
| week | INT | Internship week number |
| day | VARCHAR(20) | Day name |
| title | VARCHAR(255) | Event title |
| description | TEXT | Full event description |
| success_criteria | TEXT | What should be achieved |
| traps | TEXT | Watch-outs or risks |
| event_date | DATE | Actual calendar date |
| created_at | TIMESTAMP | Record creation time |

## Pages and What They Do

### `index.php`

The landing page. Displays all 8 weeks in modern cards and gives a quick overview of the internship calendar.

### `calendar.php`

Shows the events in a premium calendar or timeline layout for easier browsing.

### `event.php`

Displays a single event in detail, including title, description, success criteria, traps, and date.

### `add-event.php`

Provides a form for inserting a new event into MySQL.

### `edit-event.php`

Loads an existing event and allows the administrator to update it.

### `delete-event.php`

Deletes an event after confirmation.

### `search.php`

Searches calendar events by title.

### `dashboard.php`

Displays summary statistics such as total weeks, total days, total events, current week, completed events, and upcoming events.

## PHP Functions Used

The project should use at least 15 PHP built-in functions. The table below documents the selected functions, what they do, and where they fit in the application.

| PHP Function | What It Does | Where It Is Used |
|---|---|---|
| `mysqli_connect()` | Opens a connection to the MySQL database | `config/database.php` |
| `mysqli_query()` | Sends SQL queries to the database | CRUD actions and data loading |
| `mysqli_fetch_assoc()` | Returns one database row as an associative array | Listing events and dashboard data |
| `mysqli_num_rows()` | Counts rows returned from a query | Search results and totals |
| `htmlspecialchars()` | Escapes special HTML characters to prevent XSS | Outputting event titles and descriptions |
| `trim()` | Removes extra spaces from user input | Form processing before saving data |
| `isset()` | Checks whether a variable or form field exists | Form submission and edit checks |
| `empty()` | Checks whether a value is blank | Validation for required fields |
| `date()` | Formats the current date or a timestamp | Dashboard, event display, and timestamps |
| `strtotime()` | Converts a date string into a Unix timestamp | Sorting and comparing event dates |
| `array_map()` | Applies a callback to each item in an array | Transforming event lists for display |
| `implode()` | Joins array items into a string | Building readable summaries or filters |
| `explode()` | Splits a string into an array | Parsing comma-separated values or tags |
| `header()` | Sends HTTP headers, such as redirects | After add, edit, or delete actions |
| `json_encode()` | Converts PHP data into JSON | Optional AJAX responses or dynamic UI updates |

## Why These Functions Matter

These 15 functions cover the full flow of the project:

- `mysqli_connect()` and `mysqli_query()` handle the database layer.
- `mysqli_fetch_assoc()` and `mysqli_num_rows()` help read and count event data.
- `htmlspecialchars()`, `trim()`, `isset()`, and `empty()` protect and validate form input.
- `date()` and `strtotime()` make date handling reliable.
- `array_map()`, `implode()`, and `explode()` help process lists and formatted values.
- `header()` supports clean redirects after CRUD actions.
- `json_encode()` is useful if any part of the interface uses AJAX or dynamic scripts.

## Sample Workflow

1. Import the Excel data into `calendar_events`.
2. Load the schedule on `index.php`.
3. Open a week or day in `calendar.php`.
4. Click an event to view full details in `event.php`.
5. Use `add-event.php` or `edit-event.php` to manage records.
6. Use `delete-event.php` to remove an event safely.
7. Use `search.php` to find event titles quickly.
8. Review totals and progress on `dashboard.php`.

## Setup Instructions

### 1. Install Requirements

- PHP
- MySQL
- Apache or a local PHP server such as XAMPP, WAMP, or Laragon

### 2. Create the Database

Run the database setup script first, then import the main schema/data file.

```sql
CREATE DATABASE internship_calendar;
```

You can create the application user and privileges with:

```bash
sudo mysql < sql/setup-user.sql
```

Then import the table schema and sample records:

```bash
mysql -u internship_app -p internship_calendar < sql/internship_calendar.sql
```

### 3. Configure Database Connection

The project is preconfigured for:

- host: `127.0.0.1`
- user: `internship_app`
- password: `InternshipApp123!`
- database: `internship_calendar`

If you choose a different password, update `config/database.php` to match.

### 4. Import the Excel Data

Import `sql/internship_calendar.sql` into MySQL. It creates the database, creates the table, and inserts 40 sample internship-day records. If you have the original Excel file, you can replace the sample rows with the real schedule data.

### 5. Run the Project

Place the project inside your web server directory and open `index.php` in the browser.

## Evaluation Checklist

- Database created correctly
- Excel data imported into MySQL
- Home page shows all weeks
- Calendar view is clean and responsive
- Event details page works
- Add, edit, and delete actions work
- Search works
- Dashboard stats are correct
- README includes the 15 PHP functions and their purposes

## Deliverables

- Source code
- SQL export file
- README documentation
- Screenshots of:
  - Home page
  - Calendar view
  - Event details page
  - Dashboard

## Presentation Notes

Be ready to explain:

- Your project structure
- Your database schema
- How CRUD works
- The 15 PHP functions you used and why
- Challenges you faced
- What you would improve with more time

## Final Note

This README is written to match the assignment requirements and to serve as a submission-ready project guide. If you later add the actual PHP files, this document already describes how the application should be built and which PHP functions it should use.
