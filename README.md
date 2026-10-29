# BiteByte – recipe-sharing web app

BiteByte is a website where people can share recipes, browse other people's recipes and search and filter them. I built it with a partner as my graduation project (maturski rad) at Gimnazija Svetozar Markovic, Jagodina, in 2026. It was graded 5/5.

**Live demo:** _coming soon_

## Features

- Sign up and log in, with form validation in JavaScript and checks in PHP
- Browse all recipes, with search, sorting and filters (category, difficulty, tags, cooking time)
- Recipe details page with photo, cooking time, difficulty and tags
- Submit a recipe with a photo, and edit or delete your own recipes
- Support form and newsletter sign-up
- Responsive layout for desktop and mobile, with a carousel on the home page

## Built with

| Part | Technology |
| --- | --- |
| Frontend | HTML, CSS, JavaScript |
| Backend | PHP (PDO) |
| Database | MySQL / MariaDB |
| Local server | XAMPP |

**Who did what:** I wrote about 90% of the frontend (10 pages, form validation, carousel); my partner wrote most of the backend.

## How to run it locally

1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL**.
2. Clone this repo into `xampp/htdocs/`:
   ```
   git clone https://github.com/SannieBoi/BiteByte.git
   ```
3. Open phpMyAdmin (`http://localhost/phpmyadmin`), create a database named `bitebyte` and import `sql/bitebyte (2).sql`.
4. Open `http://localhost/BiteByte/php/index.php`.

The database connection settings are in `php/config.php` (default XAMPP user `root` with no password).

## Testing

I tested BiteByte manually after it was finished: 58 test cases and 17 bug reports are in **[bitebyte-qa](https://github.com/SannieBoi/bitebyte-qa)**.

## Project structure

```
css/    stylesheets for each page
html/   static pages (blog, contact, support)
img/    logo, backgrounds and uploaded recipe images
js/     form validation, carousel and recipe page scripts
php/    pages with server logic (login, sign up, recipes, submit, edit, delete)
sql/    database dump
```
