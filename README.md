# Modern Blog Website

A responsive full-stack blogging platform built with **Laravel 12, Vue 3, and Tailwind CSS 4**, featuring a modern public blog and an admin dashboard for content management.

## Features

- 📝 Complete blog post CRUD management
- ✍️ Rich text editing with Tiptap
- 🏷️ Categories, tags, and featured images
- 📅 Draft, published, and scheduled posts
- 🔍 Live search with suggestions
- 💬 Comments, replies, and reactions
- 📱 Social sharing integration
- 📧 Newsletter subscriptions and contact forms
- 🌙 Dark and light mode
- 🔎 SEO-friendly URLs, meta tags, sitemap, and RSS
- 👤 Author profiles and related posts
- 📑 Pagination and table of contents

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** Vue 3 Composition API
- **Styling:** Tailwind CSS 4
- **Rich Text Editor:** Tiptap
- **Database:** SQLite
- **Build Tool:** Vite

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

Start the Laravel server:

```bash
php artisan serve --port=8001
```

In a separate terminal, start the frontend development server:

```bash
npm run dev
```

Open **http://localhost:8001** in your browser.

## Useful Commands

```bash
# Run database migrations
php artisan migrate

# Reset database and seed demo data
php artisan migrate:fresh --seed

# Create storage symlink
php artisan storage:link

# Format PHP code
./vendor/bin/pint

# Build production assets
npm run build
```

## License

This project is available for personal and educational use.
