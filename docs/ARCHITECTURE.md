# EcoSprout architecture

EcoSprout uses a conventional server-rendered PHP application with shared session authorization and PDO-based MySQL access. It is not a microservices application.

## System overview

~~~mermaid
flowchart TD
    Browser["Customer / Staff / Administrator browser"] --> PHP["PHP pages + shared includes"]
    PHP --> Auth["Sessions and role checks"]
    PHP --> Catalog["Plant catalog and workshop workflows"]
    PHP --> Cart["Cart and order workflow"]
    PHP --> Admin["Staff and administrative actions"]
    Catalog --> PDO["PDO prepared statements"]
    Cart --> PDO
    Admin --> PDO
    PDO --> MySQL[("MySQL: users, plants, orders, workshops and queries")]
~~~

## Key source boundaries

| Source location | Responsibility |
| --- | --- |
| auth/ | Sign-in, customer registration and sign-out |
| customer/ | Catalog, cart, orders, workshop registration and queries |
| staff/ | Catalog editing, workshops and customer query responses |
| admin/ | Users, dashboard and reporting |
| includes/ | Navigation, shared helpers and session logic |
| config/db.php | PDO connection using optional private environment variables |
| database/ecosprout.sql | Schema and safe portfolio fixtures |

## Typical transaction

1. An authenticated customer browses stock and adds an item to the cart.
2. The application maintains session context and obtains product and availability information using PDO.
3. An order operation writes database rows and associates them with the current account.
4. Staff views and admin reporting use the same relational database.

This summary represents source-level structure. Exact field validation and stock updates must be checked in the individual PHP handlers before any production usage.

## Trade-offs and operational limitations

- Simple PHP pages and PDO allow fast iteration, but domain logic is coupled to individual routes rather than isolated services.
- Session/role checks exist; this does not establish a production security assessment.
- Original source contained seeded person/contact/order records. The public SQL replaces identity fixtures and removes transactional records.
- Demo users have known passwords and must only be used on disposable local databases.
- External image URLs are dependencies; offline display and image reuse licensing are unverified.
