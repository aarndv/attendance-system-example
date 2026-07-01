# Student Attendance System (Example Repo)

This repository serves as a **minimal, fully working prototype** of the Student Attendance System built using basic HTML, CSS, procedural PHP, and MySQL. 

The purpose of this repository is to demonstrate how a development team can collaborate simultaneously without causing code overwrites or architecture breakdowns. By adhering to a strict layout of **predefined file names, global hooks, and input/output contracts ("ports")**, each member can develop their assigned features on an isolated Git branch and merge them cleanly via Pull Requests.

---

## Project Architecture & File Structure

The repository uses a strict **Single Responsibility** file layout. Every file name is explicitly predefined so no two members edit the same file simultaneously:

```text
student-attendance-system/
│
├── config/
│   └── db.php              <-- Member 1 (Database Configuration Hook)
│
├── assets/
│   └── pixel-logo.png      <-- Member 5 (Visual Asset / Pixel Art)
│
├── css/
│   └── style.css           <-- Member 5 (Global Stylesheet Theme)
│
├── database.sql            <-- Member 1 (Schema & Initial Admin Seed)
├── index.php               <-- Member 1 (Login UI & Security Logic)
├── logout.php              <-- Member 1 (Session Destroyer)
├── dashboard.php           <-- Member 3 (Main Data Matrix View / Read Loop)
├── add_attendance.php      <-- Member 2 (Insert Processing Logic / Create)
├── edit_attendance.php     <-- Member 4 (Update Processing Logic & Sub-UI)
└── delete_attendance.php   <-- Member 4 (Delete Processing Logic)
```

---

## Team Structure & Role Assignments

To maximize efficiency and maintain clean code isolation, project tasks are distributed across 5 specialized engineering roles, with the **Database Setup & Session Controller** serving as the Project Leader.

### 1. Project Leader: Fullstack Architecture & Authentication (Member 1)
* **Technical Domain:** Core DevOps, Database Administration (DBA), and Backend Security.
* **Key Deliverables:** `database.sql` (schema layout), `config/db.php` (global PDO hook), `index.php` (login controller), and `logout.php`.
* **Responsibilities:** Establish the core database tables and provide the global session authentication check (`$_SESSION['user']`) that locks or unlocks system views based on user authentication status.

### 2. Backend Engineering - Data Insertion (Member 2)
* **Technical Domain:** Backend Development / Data Engineering (Create Operation).
* **Key Deliverables:** `add_attendance.php` (backend processing script).
* **Responsibilities:** Capture data emitted from the dashboard forms, execute strict validation routines to ensure operational eligibility, and safely append new attendance records into the database.

### 3. Frontend Integration & Data Visualization (Member 3)
* **Technical Domain:** Frontend Development / UI Engineering (Read Operation).
* **Key Deliverables:** `dashboard.php` (main application matrix view).
* **Responsibilities:** Construct the central data grid, query MySQL to loop through and display attendance records, render system notifications, and house the input forms and action triggers.

### 4. Backend Engineering - Data Manipulation (Member 4)
* **Technical Domain:** Backend Development / Logic Engineering (Update/Delete Operations).
* **Key Deliverables:** `delete_attendance.php` and `edit_attendance.php`.
* **Responsibilities:** Read unique record identifiers passed through URL string query parameters to securely delete data matrices, or generate dedicated sub-interfaces to process attendance status modifications.

### 5. UI/UX Design & Asset Pipeline (Member 5)
* **Technical Domain:** Frontend Styling, Graphic Design, and Theme Systems.
* **Key Deliverables:** `css/style.css` and custom pixel art assets inside the `/assets/` directory.
* **Responsibilities:** Design the overarching look and feel of the retro pixel art interface. Provide uniform stylesheet rules that hook directly into classes like `.attendance-table` or contextual indicators (`.status-present`, `.status-absent`).

---

## The "Ports" & Communication Contracts

Members can develop and test features independently. As long as individual files respect the following predefined data pipelines, the components will connect seamlessly:

### A. The Database Contract (Leader ↔ All Backend Files)
* Every script requiring database access must execute `require_once 'config/db.php';`.
* The database connection is exposed globally as a PDO instance named **`$conn`**.

### B. The Create Contract (Member 3 Dashboard ↔ Member 2 Input Handler)
* The HTML form in `dashboard.php` must use `method="POST"` and target `action="add_attendance.php"`.
* The processing script expects exactly four payload variables inside the `$_POST` superglobal:
    * `$_POST['student_id']` (String)
    * `$_POST['student_name']` (String)
    * `$_POST['status']` (Enum: 'Present', 'Absent', 'Tardy')
    * `$_POST['log_date']` (Date string: YYYY-MM-DD)

### C. The Delete/Update Contract (Member 3 Dashboard ↔ Member 4 Manipulators)
* To delete a record, the table interface provides an anchor tag linking to: `delete_attendance.php?id=X` (where X represents the unique table row ID). The backend captures this via `$_GET['id']`.
* To edit a record, the interface links to `edit_attendance.php?id=X`. The modification script loads that specific row into an update view, then updates the status using a form targeting `edit_attendance.php` via `$_POST['id']` and `$_POST['status']`.

### D. The UI Styling Contract (Member 5 Artist ↔ Member 1, 3, 4 Frontends)
* All rendered pages must include the global stylesheet using `<link rel="stylesheet" href="css/style.css">`.
* Developers must implement the agreed-upon CSS class hooks within the HTML markup:
    * Main table wrapper: `<table class="attendance-table">`
    * Form submission buttons: `<button class="btn-pixel">`
    * Dynamic attendance status badges: `<span class="status-badge status-present">` (or `status-absent`, `status-tardy`).

---

## Instructional Setup for Team Members

1. **Clone the example repository** onto the local local environment to inspect the bare-minimum code implementations.
2. **Import the `database.sql` schema** into a local MySQL instance (via XAMPP / phpMyAdmin) to instantly populate the system with test database accounts and records.
3. **Run the application locally** to observe how clicking "Delete" or submitting the "Log Attendance" form passes data smoothly between separate backend files without relying on a single, unmanageable code script.
4. Use these structural files as technical foundations. When beginning work on the main project repository, create the assigned feature branch, duplicate the structural blueprints, and begin scaling up the features, pixel art integrations, and UI elements.
