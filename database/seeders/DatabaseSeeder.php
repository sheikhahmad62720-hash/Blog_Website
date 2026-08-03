<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@blog.test',
            'password' => 'password',
            'role' => 'admin',
            'bio' => 'Blog founder and lead editor. Writing about technology, life, and everything in between.',
        ]);

        User::factory()->count(4)->create([
            'role' => 'author',
            'bio' => 'Guest author sharing insights on the blog.',
        ]);

        $categories = collect([
            ['name' => 'Technology', 'description' => 'Software, hardware, and digital trends'],
            ['name' => 'Lifestyle', 'description' => 'Everyday living, wellness, and habits'],
            ['name' => 'Travel', 'description' => 'Destinations, guides, and stories'],
            ['name' => 'Business', 'description' => 'Startups, growth, and productivity'],
        ])->map(fn ($data) => Category::create([...$data, 'slug' => Str::slug($data['name'])]));

        $tags = collect(['Laravel', 'Vue', 'PHP', 'JavaScript', 'Design', 'Tips', 'Productivity', 'News'])
            ->map(fn ($name) => Tag::create(['name' => $name, 'slug' => Str::slug($name)]));

        $authors = User::where('role', 'author')->get();

        foreach ($categories as $category) {
            foreach ($authors as $author) {
                $post = Post::factory()->create([
                    'user_id' => $author->id,
                    'category_id' => $category->id,
                ]);
                $post->tags()->sync($tags->random(rand(2, 3))->pluck('id'));
            }
        }

        $featured = Post::create([
            'user_id' => $admin->id,
            'category_id' => $categories->first()->id,
            'title' => 'Welcome to the Modern Blog Platform',
            'slug' => 'welcome-to-the-modern-blog-platform',
            'excerpt' => 'A clean, fast, and fully featured blog engine built with Laravel, Vue 3, and Tailwind CSS.',
            'content' => '<h2>Built to Publish</h2><p>This blog is a complete platform for writers and editors. It ships with a rich text editor, categories, tags, comments, search, and an admin panel.</p><h2>Fast by Default</h2><p>Laravel powers the API while Vue renders every page with smooth transitions. Images are lazy loaded and the layout is fully responsive.</p><h2>Ready for Growth</h2><p>SEO meta tags, an RSS feed, and a sitemap are generated automatically so your content is discoverable from day one.</p>',
            'meta_title' => 'Welcome to the Modern Blog Platform',
            'meta_description' => 'A clean, fast, and fully featured blog engine built with Laravel, Vue 3, and Tailwind CSS.',
            'status' => 'published',
            'published_at' => now(),
            'views' => 1200,
        ]);
        $featured->tags()->sync($tags->take(2)->pluck('id'));

        // A couple of drafts for the admin demo
        Post::factory()->count(2)->create([
            'status' => 'draft',
            'user_id' => $admin->id,
        ]);
    }
}
