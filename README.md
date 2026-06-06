# Freshtrack-food-inventory-system

FreshTrack - Smart Food Inventory & Expiry Monitoring System

## Project Goal
FreshTrack is a database-driven website project for managing food inventory, batches, expiry dates, supplier purchases, waste, and donations. The website should feel like a product management system with search, sort, filter, and view features, while the main academic focus stays on the database design, SQL, views, and PL/SQL logic.

## What This Project Covers
- User and inventory management
- Food category and batch tracking
- Expiry date monitoring and alerts
- Consume-first recommendation for near-expiry items
- Food waste and expired item tracking
- Supplier and purchase history management
- Donation management for near-expiry items
- Dashboard analytics for inventory and expiry status

## Lab Topic Mapping
- Lab-02 DDL and DML: create tables and insert sample records
- Lab-03 Constraints: primary key, foreign key, unique, not null, check, default
- Lab-04 SELECT and aggregate functions: filters, counts, sums, grouping, sorting
- Lab-05 Subquery, set operations, and views: expiring items, alerts, inventory summaries
- Lab-06 Join operation: product, batch, supplier, and purchase reports
- Lab-07 PL/SQL: procedures and triggers for stock updates and alerts

## Suggested Folder Structure
- `database/`: design notes and entity overview
- `docs/`: proposal and written project explanation
- `sql/01_create_tables.sql`: DDL for the database schema
- `sql/02_insert_data.sql`: sample data for testing and demonstration
- `sql/03_queries.sql`: lab queries using SELECT, joins, aggregates, subqueries, and set operations
- `sql/04_views.sql`: reusable views for dashboard and reporting
- `sql/05_procedures_triggers.sql`: PL/SQL procedures and triggers
- `sql/schema.sql`: master reference for execution order
- `frontend/`: static dashboard prototype with search, filter, and sorting UI

## Core Database Idea
The project is built around these main entities:
- Users
- Categories
- Products
- Suppliers
- Purchases
- Batches
- Inventory movements
- Donations
- Waste records
- Alerts

## Website Features To Show In Screenshots Or Demo
- Product list with search and category filter
- Batch details with expiry date and stock quantity
- Expiry dashboard showing items expiring soon
- Consume-first recommendation list
- Purchase and supplier history
- Donation and waste tracking history
- Responsive dashboard UI with analytics cards and quick actions

## Recommended Build Order
1. Finalize entities and relationships.
2. Create tables with constraints.
3. Insert sample data.
4. Write joins, aggregates, subqueries, and set operations.
5. Create views for dashboard pages.
6. Add PL/SQL procedures and triggers.
7. Build the website interface on top of the database.

## Presentation Tip
If your teacher focuses mostly on the database, keep the website simple but functional, then explain how each screen is backed by a table, a view, or a procedure.

## Frontend (Static Prototype)

I added a simple static homepage prototype under the `frontend/` folder to demonstrate how the database-backed UI could look.

- Open the homepage: `frontend/index.html` in your browser.
- Quick local preview (from the `frontend` folder):

```
python -m http.server 8000
# then open http://localhost:8000 in your browser
```

Files added:
- `frontend/index.html` — Dashboard-style homepage
- `frontend/styles.css` — Styles used by the prototype
- `frontend/app.js` — Small interactive script with sample data

Next step: wire the frontend to your Laravel backend or fetch the SQL view endpoints when you have an API ready.
