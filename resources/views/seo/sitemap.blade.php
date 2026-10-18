{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc>{{ route('home') }}</loc><changefreq>hourly</changefreq><priority>1.0</priority></url>
    <url><loc>{{ route('team') }}</loc><changefreq>monthly</changefreq><priority>0.3</priority></url>
    <url><loc>{{ route('contact') }}</loc><changefreq>yearly</changefreq><priority>0.3</priority></url>
@foreach ($categories as $c)
    <url><loc>{{ route('category', $c->slug) }}</loc>@if ($c->updated_at)<lastmod>{{ $c->updated_at->toAtomString() }}</lastmod>@endif<changefreq>daily</changefreq><priority>0.7</priority></url>
@endforeach
@foreach ($tags as $t)
    <url><loc>{{ route('tag', $t->slug) }}</loc>@if ($t->updated_at)<lastmod>{{ $t->updated_at->toAtomString() }}</lastmod>@endif<changefreq>weekly</changefreq><priority>0.4</priority></url>
@endforeach
@foreach ($articles as $a)
    <url><loc>{{ route('article', $a->slug) }}</loc>@if ($a->updated_at)<lastmod>{{ $a->updated_at->toAtomString() }}</lastmod>@endif<changefreq>weekly</changefreq><priority>0.8</priority></url>
@endforeach
</urlset>
