# Modern Blog Website

A full-featured, responsive blog platform built with **Laravel 12**, **Vue 3** (Composition API), and **Tailwind CSS 4**. Includes a public-facing frontend and an admin content-management panel.

## Features

### Content Management
- Admin panel to create, edit, delete, and manage posts
- Rich text editor (Tiptap) with bold, italic, headings, links, images, and code blocks
- Categories and tags
- Featured image upload
- Draft / Published / Scheduled status
- SEO meta title & description per post

### Public Pages
- Homepage with featured post highlight + latest posts grid
- Single post page with author info, date, reading time, related posts
- Category and Tag listing pages
- About and Contact (working contact form)
- Search (live suggestions + full page)
- Author profile pages

### Engagement
- Comments with reply support
- Like/reaction button on posts
- Social share buttons (Facebook, Twitter/X, LinkedIn, WhatsApp)
- Newsletter signup form
- Dark / light mode toggle
- Table of contents for long posts
- Pagination

### SEO & Discoverability
- SEO-friendly slugs (`/post/...`, `/category/...`, `/tag/...`)
- Open Graph & Twitter Card meta tags
- Auto-generated `sitemap.xml`, `robots.txt`, and RSS `feed`
- Google & social search ready

## Requirements
- PHP 8.2+ with `pdo_sqlite`
- Composer
- Node.js 18+ / npm

## Installation

```bash
# 1. Install PHP dependencies
composer install

# 2. Environment config
copy .env.example .env     # Windows
php artisan key:generate

# 3. Install frontend dependencies
npm install

# 4. Set up the database (SQLite default)
php artisan migrate --seed

# 5. Link storage for uploaded images
php artisan storage:link

# 6. Build assets (or use dev server)
npm run build
```

## Running locally

In two terminals:

```bash
# Terminal 1 - Laravel
php artisan serve --port=8001     # 8000 may be used by another project

# Terminal 2 - Vite HMR (development only)
npm run dev
```

Open http://localhost:8001

> Note: dev assets are served by Vite on port 5173 via Laravel's `@vite`.
> For a production-like preview run `npm run build` then just `php artisan serve`.

```

## Naming conventions
- Vue components: PascalCase (`PostCard.vue`)
- Vue pages: suffixed `View.vue`
- Composables: `use<Feature>.js`
- Controllers: `<Model>Controller.php`

## Formatting & quality
- Backend: `./vendor/bin/pint`
- Frontend: lint via ESLint/Prettier (add config as needed), code is manually ordered per component

## Useful commands
```bash
php artisan migrate:fresh --seed   # reset + reseed demo data
php artisan storage:link           # re-link storage if missing
```
