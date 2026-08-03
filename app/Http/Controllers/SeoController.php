<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

class SeoController extends Controller
{
    public function robots()
    {
        $content = implode("\n", [
            'User-agent: *',
            'Disallow: /admin',
            'Allow: /',
            '',
            'Sitemap: '.url('/sitemap.xml'),
        ]);

        return response($content)->header('Content-Type', 'text/plain');
    }

    public function sitemap()
    {
        $posts = Post::published()->orderByDesc('published_at')->get();
        $categories = Category::all();
        $tags = Tag::all();

        $urls = collect([['loc' => url('/'), 'lastmod' => now()->toAtomString()]])
            ->push(['loc' => url('/about'), 'lastmod' => now()->toAtomString()])
            ->push(['loc' => url('/contact'), 'lastmod' => now()->toAtomString()])
            ->merge($posts->map(fn ($p) => [
                'loc' => url('/post/'.$p->slug),
                'lastmod' => $p->updated_at->toAtomString(),
            ]))
            ->merge($categories->map(fn ($c) => ['loc' => url('/category/'.$c->slug), 'lastmod' => now()->toAtomString()]))
            ->merge($tags->map(fn ($t) => ['loc' => url('/tag/'.$t->slug), 'lastmod' => now()->toAtomString()]));

        $xml = view('seo.sitemap', ['urls' => $urls])->render();

        return response($xml)->header('Content-Type', 'application/xml');
    }

    public function feed()
    {
        $posts = Post::published()->orderByDesc('published_at')->limit(20)->get();

        $xml = view('seo.feed', [
            'posts' => $posts,
            'siteName' => config('app.name', 'Blog'),
            'siteUrl' => url('/'),
        ])->render();

        return response($xml)->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
