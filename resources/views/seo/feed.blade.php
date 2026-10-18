{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
    <title>{{ config('app.name') }}</title>
    <link>{{ route('home') }}</link>
    <description>آخر أخبار {{ config('app.name') }}</description>
    <language>ar</language>
    <atom:link href="{{ route('feed') }}" rel="self" type="application/rss+xml" />
@foreach ($articles as $a)
    <item>
        <title>{{ $a->title }}</title>
        <link>{{ route('article', $a->slug) }}</link>
        <guid isPermaLink="true">{{ route('article', $a->slug) }}</guid>
        <pubDate>{{ optional($a->published_at ?? $a->created_at)->toRssString() }}</pubDate>
        @if ($a->category)<category>{{ $a->category->name }}</category>@endif
        <description><![CDATA[{!! str_replace(']]>', ']]&gt;', \Illuminate\Support\Str::limit(strip_tags($a->content), 300)) !!}]]></description>
        @if ($a->image_url)<enclosure url="{{ $a->image_url }}" type="image/jpeg" length="0" />@endif
    </item>
@endforeach
</channel>
</rss>
