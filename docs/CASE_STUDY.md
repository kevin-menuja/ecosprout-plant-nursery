# EcoSprout — engineering case study

**Problem modeled:** A nursery needing plant discovery, order handling, workshops and staff operations.

**What is implemented:** Public plant browsing and informational pages, PHP session-based customer login, product ordering and workshop registration, a staff console for inventory/workshop content, and an administrator dashboard.

**Engineering tools:** PHP, HTML/CSS, JavaScript and MySQL via PDO prepared statements.

## Key design choices

- A single relational schema links users, plants, orders, workshop registrations, catalog services and contact queries.
- Separate customer, staff and admin directories make role ownership visible in the source.
- Shared includes contain session and layout utilities.
- PDO is configured with native prepared statements and strict error handling.
- Local configuration can be injected via environment variables rather than modifying the checked-in database connector.

## Published-source security measures

The personal portfolio copy excludes the original SQL customer records, orders, queries and workshop registrations. It replaces original user records with three fictional account fixtures and preserves plant, workshop and service catalog examples. All published accounts use example.test emails and demonstration-only passwords.

## Verification and remaining gaps

**Completed:** PHP 8.5.2 syntax checks on 28 source files, plus source inspection for database schema and role directories.

**Not claimed:** database integration test, browser E2E test, live payments, scalable inventory control or deployed production acceptance. UI images rely on externally hosted assets.

## Learning outcome

The project demonstrates server-rendered CRUD features and relational workflows in PHP. An engineering review would prioritize stronger service separation, server-side authorization tests, inventory concurrency, CSRF coverage verification, and proper deployment secrets and logging before commercial use.
