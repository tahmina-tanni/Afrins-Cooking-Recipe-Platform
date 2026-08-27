# 🍳 Afrin's Cooking Recipe Platform

A full-stack web-based recipe management platform where users can explore, share, and manage cooking recipes. The platform includes a dedicated Admin Panel for managing users, recipes, categories, and platform data.



## 📌 Project Overview

Afrin's Cooking is a recipe sharing and management system designed to provide users with an easy way to discover cooking recipes and share their own recipes.

The system provides two types of users:

- 👤 Normal User
- 👑 Admin

Users can browse recipes, while administrators can manage the entire platform through a secure admin dashboard.



# ✨ Features

## 👤 User Features

- User registration and login
- Browse available recipes
- View recipe details
- Explore recipes by categories
- View recipe images
- User-based recipe management




## 👑 Admin Panel Features

### Dashboard

- Total users count
- Total recipes count
- Total categories count
- Total reviews count
- Newsletter subscriber count


### User Management

- View all registered users
- View user information
- Change user role:
  - User → Admin
  - Admin → User
- Protected admin account


### Recipe Management

- View all recipes
- See recipe author information
- View recipe categories
- Feature / Unfeature recipes
- Delete recipes


### Category Management

- View all categories
- Add new category
- Upload category image
- Delete category
- Manage category data




# 🛠️ Technologies Used

## Frontend

- HTML5
- CSS3
- JavaScript
- Tailwind CSS


## Backend

- PHP


## Database

- MySQL


## Development Environment

- XAMPP
- Apache Server
- phpMyAdmin




# 🗂️ Project Structure


afrin_cooking/

│
├── admin/
│ ├── index.php
│ ├── users.php
│ ├── recipes.php
│ ├── categories.php
│ ├── add_category.php
│ ├── delete_category.php
│ ├── update_user_role.php
│ └── delete_recipe.php
│
├── api/
│ ├── check_login.php
│
├── config/
│ └── database.php
│
├── uploads/
│ ├── recipes/
│ └── categories/
│
├── assets/
│
├── index.html
│
└── README.md


---

# 🗄️ Database Design

Database Name:


afrin_cooking


Main Tables:

### Users

Stores user account information.


id
name
email
password
role
created_at



### Recipes

Stores recipe information.


id
title
description
ingredients
steps
image
user_id
category_id
featured
created_at



### Categories

Stores recipe categories.


id
name
image
created_at



### Reviews

Stores recipe reviews.


id
recipe_id
user_id
rating
comment
created_at



### Newsletter

Stores subscriber information.


id
email
subscribed_at





# ⚙️ Installation Guide

## Step 1: Install XAMPP

Download and install XAMPP.

Start:

- Apache
- MySQL




## Step 2: Clone Repository

```bash
git clone https://github.com/your-username/afrin-cooking.git
Step 3: Move Project

Copy the project folder into:

C:\xampp\htdocs\

Example:

C:\xampp\htdocs\afrin_cooking
Step 4: Create Database

Open:

http://localhost/phpmyadmin

Create database:

afrin_cooking

Import the provided SQL file.

Step 5: Configure Database

Open:

config/database.php

Update:

DB_HOST = localhost
DB_USER = root
DB_PASS = ''
DB_NAME = afrin_cooking
Step 6: Run Project

Open:

http://localhost/afrin_cooking/

Admin Panel:

http://localhost/afrin_cooking/admin/
🔐 User Roles
Normal User

Can:

Browse recipes
View recipe details
Use platform features
Admin

Can:

Manage users
Manage recipes
Manage categories
Control platform content
📸 Screenshots

Add screenshots here:

Homepage
Recipe Page
Admin Dashboard
User Management
Recipe Management
Category Management
🚀 Future Improvements
Recipe search system
Recipe filtering
User profile
Recipe editing
Review moderation
Better image management
Notification system
👨‍💻 Developer

Developed by:

Tahmina Tanni

Software Engineering Student

📄 License

This project is developed for academic and educational purposes.