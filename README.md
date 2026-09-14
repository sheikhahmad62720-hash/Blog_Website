# Modern Blog Website

A responsive full-stack blog platform built with **Laravel 12, Vue 3, and Tailwind CSS 4**, featuring a public blog and admin content-management panel.

## Features

* 📝 Create, edit, delete & manage blog posts
* ✍️ Rich text editor with Tiptap
* 🏷️ Categories, tags & featured images
* 📅 Draft, published & scheduled posts
* 🔍 Search with live suggestions
* 💬 Comments, replies & post reactions
* 📱 Social sharing buttons
* 📧 Newsletter & contact forms
* 🌙 Dark / light mode
* 🔎 SEO-friendly URLs, meta tags, sitemap & RSS
* 👤 Author profiles & related posts
* 📑 Pagination & table of contents

## Tech Stack

* **Backend:** Laravel 12 / PHP 8.2+
* **Frontend:** Vue 3 Composition API
* **Styling:** Tailwind CSS 4
* **Editor:** Tiptap
* **Database:** SQLite
* **Build Tool:** Vite

## Installation

```bash
composer install
copy .env.example .env
php artisan key:generate

npm install
php artisan migrate --seed
php artisan storage:link

npm run build
```

## Run Locally

```bash
php artisan serve --port=8001
npm run dev
```

Open: `http://localhost:8001`

## Useful Commands

```bash
php artisan migrate:fresh --seed
php artisan storage:link
./vendor/bin/pint
```
