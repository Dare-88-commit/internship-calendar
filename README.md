# Tribbbal Internship Calendar

A PHP and MySQL web application for replacing the internship schedule spreadsheet with a clean, searchable, and easy-to-manage calendar system.

This workspace now includes a triBBBal-style route/controller layer, reusable PHP helpers, `.phtml` templates, an `xhr` endpoint, and an SQL export with seeded internship-day records for immediate import.

## Project Summary

This project stores internship schedule data in MySQL and presents it in a modern calendar/timeline interface. An administrator can add, edit, delete, search, and view internship events by week and day.

The goal is to turn the Excel-based schedule into a production-style PHP feature that matches the triBBBal collaboration pattern:

- A structured database
- Route/controller based rendering
- Reusable `.phtml` includes and partials
- An `xhr` endpoint for interactive updates
- A responsive Wondertag-aligned UI
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
├── requests.php
├── sources/
│   ├── internship_calendar.php
│   ├── internship_calendar_dashboard.php
│   ├── internship_calendar_event.php
│   ├── internship_calendar_add_event.php
│   ├── internship_calendar_edit_event.php
│   └── internship_calendar_delete_event.php
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
├── xhr/
│   └── internship_calendar.php
│
├── themes/
│   └── wondertag/
│       ├── layout/
│       │   ├── container.phtml
│       │   └── internship-calendar/
│       │       ├── content.phtml
│       │       ├── dashboard.phtml
│       │       ├── event.phtml
│       │       └── includes/
│       │           ├── hero.phtml
│       │           ├── filters.phtml
│       │           ├── stats-strip.phtml
│       │           ├── event-card.phtml
│       │           ├── event-row.phtml
│       │           ├── empty-state.phtml
│       │           └── event-modal.phtml
│       ├── javascript/
│       │   └── internship-calendar.js
│       └── stylesheet/
│           └── internship-calendar.css
│
├── sql/
│   └── internship_calendar.sql
│
└── README.md
```

## Render Flow

The new feature follows the collaboration brief flow:

`route/controller -> source data preparation -> Wo_LoadPage() -> .phtml template -> shared includes/partials -> jQuery/AJAX -> requests.php -> xhr handler -> JSON/HTML response`

Primary routes:

- `/internship-calendar`
- `/internship-calendar/dashboard`
- `/internship-calendar/event/{id}`

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

Front controller. Reads `link1`, loads the correct `sources/*.php` controller, and renders the shared container.

### `sources/internship_calendar.php`

Prepares the calendar home data and loads the `content.phtml` view.

### `sources/internship_calendar_dashboard.php`

Prepares the dashboard data and loads the `dashboard.phtml` view.

### `sources/internship_calendar_event.php`

Loads a single event by ID and renders the `event.phtml` view.

### `sources/internship_calendar_add_event.php`

Loads the add-event form and handles the save action.

### `sources/internship_calendar_edit_event.php`

Loads the edit-event form and handles update submissions.

### `sources/internship_calendar_delete_event.php`

Loads the delete confirmation screen and handles deletion.

### `requests.php`

Routes AJAX calls into `xhr/internship_calendar.php`.

### Legacy root files

The old root-level pages now redirect to the canonical triBBBal-style routes. They remain only for backward compatibility and are no longer the primary entry points.

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

## Date Handling

The app now uses the `Africa/Lagos` timezone explicitly and validates dates in strict `Y-m-d` format so the current week, progress counts, and formatted dates stay consistent across machines. The schedule seed now starts Week 1 on `2026-06-29`, which keeps the internship calendar aligned with the corrected timeline.

## Sample Workflow

1. Import the Excel data into `calendar_events`.
2. Open the feature through `/internship-calendar` or `index.php?link1=internship-calendar`.
3. Browse the schedule from the Wondertag-style calendar view.
4. Use the shared search and week filters to update the event cards without a full reload.
5. Open a single event through `/internship-calendar/event/{id}` or the dashboard route.
6. Use the canonical add, edit, and delete routes under `index.php?link1=internship-calendar-add`, `index.php?link1=internship-calendar-edit`, and `index.php?link1=internship-calendar-delete`.
7. Review totals and progress on `/internship-calendar/dashboard`.

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
