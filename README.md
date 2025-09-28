# INVENTO MULTI-TENANT SYSTEM

Our system is designed to be a multi-tenant application, where each house owner acts as a tenant. All the core resources, like buildings, flats, tenants, bills, and so on, are linked to a house owner’s unique ID. To ensure data security, authorisation is enforced at the repository or query level. This means that non-admin users can only access data that belongs to their specific tenant. Admins, on the other hand, have full access to all the data for all their tenants.

## OPTIMIZATION, QUERIES, AND DESIGN DECISIONS

-   Data Isolation: Instead of setting up separate databases or schemas for each tenant, the system uses a single shared schema. All major tables have a foreign key called house_owner_id that connects them to the shared schema. This makes it easy to migrate, report, and scale the system.
-   Query Enforcement:
    -   The repository method employs a WHERE clause (`where(‘house_owner_id’, $userId)`) filter when the current user lacks administrative privileges.
    -   This ensures row-level isolation, thereby preventing inadvertent data leaks.
    -   In situations where filters are mandatory (such as custom search or overdue bills), explicit overriding of query conditions is employed.
-   Indexes & Constraints:
    -   Composite unique keys are applied where appropriate (e.g., (house_owner_id, name) for bill categories, (flat_id, month, bill_category_id) for bills) to prevent duplicate entries per tenant.
    -   Foreign keys ensure cascading deletes (e.g., deleting a house owner removes all dependent data).
-   Date Handling for Bills:
    -   The month is stored as a string ("F Y", e.g., "May 2022") to ensure we know the year in which the month's bill was created for.
    -   Queries for overdue bills convert this into a valid date (Carbon::createFromFormat) and compare using the last day of the month.
    -   Grouping is done by flat_id, and a computed total_due is attached to each group for quick reporting.
-   Pagination:
    -   Custom grouping (by flat_id) required manual pagination using Laravel’s LengthAwarePaginator.
    -   This balances performance while still returning structured results with totals.
-   Design Trade-Offs:
    -   I chose a shared schema over subdomain per tenant for simplicity and easier aggregation.
    -   Storing month as a formatted string is human-readable but requires parsing; an alternative would be storing a date column for faster queries.
    -   Services (e.g., EmailService, LogService) wrap Laravel’s core features to keep code clean, testable, and consistent with dependency injection.

## PRE-REQUISITE FOR SETUP

-   Docker desktop
-   Web browser
-   Terminal (git bash)

## HOW TO SETUP

-   Make sure your docker desktop is up and running
-   Launch you terminal and navigate to your working directory

```bash
cd ./working_dir
```

-   Clone repository

```bash
git clone https://github.com/degod/invento-multitenant-system.git
```

-   Move into the project directory

```bash
cd invento-multitenant-system/
```

-   Copy env.example into .env

```bash
cp .env.example .env
```

-   Build app using docker

```bash
docker compose up -d --build
```

-   Log in to docker container bash

```bash
docker compose exec app bash
```

-   Install composer

```bash
composer install
```

-   Create an application key

```bash
php artisan key:generate
```

-   Run database migration and seeder

```bash
php artisan migrate:fresh --seed
```

-   To access application, visit
    `http://localhost:9090`

-   To Login as "Super Admin":
    `U:  admin@invento.test`
    `P:  admin123`

-   To Login as any House Owner:
    `U:  select from the list of users [under user management]`
    `P:  password`

-   To access application's database, visit
    `http://localhost:9091`

-   To access application's mailhost, visit
    `http://localhost:8025`

This application is also deployed for random demo and can be accessed using the URL below:

**[Click Here](http://invento.getenjoyment.net/)**
