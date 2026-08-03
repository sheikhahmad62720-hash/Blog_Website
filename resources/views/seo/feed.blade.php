<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ $siteName }}</title>
        <link>{{ $siteUrl }}</link>
        <description>Latest articles from {{ $siteName }}</description>
        <language>en</language>
        <atom:link href="{{ url('/feed') }}" rel="self" type="application/rss+xml" />
        @foreach ($posts as $post)
            <item>
                <title>{{ $post->title }}</title>
                <link>{{ url('/post/' . $post->slug) }}</link>
                <guid isPermaLink="true">{{ url('/post/' . $post->slug) }}</guid>
                <pubDate>{{ $post->published_at->toRssString() }}</pubDate>
                <description>{{ $post->short_excerpt }}</description>
                @if ($post->category)
                    <category>{{ $post->category->name }}</category>
                @endif
                <author>{{ $post->author->email ?? '' }} ({{ $post->author->name ?? '' }})</author>
            </item>
        @endforeach
    </channel>
</rss>