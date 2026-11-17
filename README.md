# EcoSprout — Plant Nursery and Gardening Services

**EcoSprout Plant Nursery** is a PHP/MySQL web application that models a local plant nursery and gardening service in Sri Lanka. The project includes a plant catalog, cart/order workflows, gardening workshops, customer queries, staff tools, and administrative reporting.

> **Portfolio demonstration, not a live nursery or payment service.** All public database users are fictional examples. The published SQL excludes the original account, order, contact and transaction records.

## Features

| Area | Implementation in source |
| --- | --- |
| Customer | Browse plants, add items to cart, view orders, register for workshops, send queries |
| Nursery staff | Manage plants and stock, manage workshops, respond to customer queries |
| Administrator | Manage users, view system dashboard and reports |
| Access | PHP sessions, role checks and password hashing |
| Persistence | MySQL schema with synthetic local demonstration data |

## Stack

PHP 8.x, MySQL/MariaDB, PDO prepared statements, HTML/CSS, Bootstrap, JavaScript.

## Run locally

**Prerequisites:** PHP 8.3+ with PDO MySQL, Apache or the PHP built-in server, and MySQL 8+/MariaDB.

1. Create a disposable local database by importing **database/ecosprout.sql**. It includes a complete schema, plant/service examples and synthetic users.
2. Configure MySQL locally. The default development settings use host localhost, database ecosprout and user root, with no password. Override these privately with environment variables **ECOSPROUT_DB_HOST**, **ECOSPROUT_DB_NAME**, **ECOSPROUT_DB_USER**, **ECOSPROUT_DB_PASS**. Do not commit private credentials.
3. From the project root, start a local PHP web server:

~~~bash
php -S 127.0.0.1:8080
~~~

4. Open http://127.0.0.1:8080 in your browser. Apache/WAMP/MAMP also works when the document root points to the project folder.

### Synthetic local-only demo accounts

| Role | Email | Password |
| --- | --- | --- |
| Administrator | admin@example.test | DemoAdmin123! |
| Staff | staff@example.test | DemoStaff123! |
| Customer | customer@example.test | DemoCustomer123! |

These published credentials are intentionally **not private**. Never deploy the demo accounts or sample database on an internet-accessible service. Use fresh accounts and secure environment secrets for any nonlocal testing.

## Reproduce verification

~~~bash
find . -type f -name '*.php' -print0 | xargs -0 -n1 php -l
~~~

The curated source passed syntax validation of **28 PHP files** using PHP 8.5.2 on macOS. This does **not** establish end-to-end database or browser acceptance.

## Documentation

- [Architecture and workflows](docs/ARCHITECTURE.md)
- [Engineering case study and limitations](docs/CASE_STUDY.md)

## Scope and attribution

This repository is a portfolio source snapshot curated from the project's original ZIP. Build artifacts, private configs, original contact and transactional records were excluded. Product pictures in the UI are sourced from external image URLs; availability and third-party reuse rights have not been independently verified.

This is a demonstrator of role-based PHP application development, not evidence of an operating commercial nursery or a live payment gateway.
