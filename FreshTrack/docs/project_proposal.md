# FreshTrack Project Proposal

## Title
FreshTrack - Smart Food Inventory & Expiry Monitoring System

## Problem Statement
Many food inventory systems only store product names and quantities, but they do not help track batches, expiry dates, supplier history, waste, or donation opportunities. FreshTrack solves this by storing structured inventory data and providing database-driven reports for stock, expiry, and consumption priority.

## Objectives
- Maintain food inventory with batch-level tracking
- Monitor expiry dates and identify items that need attention
- Support supplier, purchase, waste, and donation records
- Provide analytical views for dashboard and reports
- Demonstrate core database topics learned in the lab

## Scope
The website will work as a management system for food items, similar to a small inventory platform. Users can search, sort, filter, and view products and batches, while the database stores the business rules and reporting logic.

## Database Modules
1. User management
2. Category and product management
3. Supplier and purchase history
4. Batch and expiry tracking
5. Inventory movement logging
6. Waste and donation records
7. Alerts and reporting views

## Lab Coverage Plan
### Lab-02: DDL and DML
Create the schema and insert sample records for all main tables.

### Lab-03: Constraints
Apply primary keys, foreign keys, unique rules, not null rules, defaults, and checks.

### Lab-04: SELECT and Aggregate Functions
Write queries for counts, sums, grouped summaries, low-stock items, and expiry counts.

### Lab-05: Subquery, Set Operations, and Views
Use subqueries for expiring items, set operations for status comparisons, and views for dashboard data.

### Lab-06: Join Operation
Join products, batches, suppliers, purchases, and alerts for reporting pages.

### Lab-07: PL/SQL
Create procedures for adding batches and marking waste or donation, plus triggers for stock validation and alert generation.

## Deliverables
- Database schema
- Sample data set
- Query collection
- Views for dashboard
- PL/SQL procedures and triggers
- Simple website interface built on top of the database

## Expected Result
The final project should show both the business workflow and the database skills learned in class, with enough SQL variety to demonstrate DDL, DML, constraints, joins, views, subqueries, and PL/SQL.
