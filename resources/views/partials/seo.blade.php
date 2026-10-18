@php
    $seoTitle       = $seoTitle ?? config('app.name');
    $seoDescription = $seoDescription ?? 'الجريدة الإلكترونية: أخبار، تحليلات وتقارير على مدار الساعة.';
    $seoImage       = $seoImage ?? asset('assets/img/logo/logo.png');
    $seoType        = $seoType ?? 'website';
    $seoUrl         = $seoUrl ?? url()->current();
@endphp
<meta name="description" content="{{ $seoDescription }}">
@if (! empty($seoNoindex))<meta name="robots" content="noindex,follow">@endif
<link rel="canonical" href="{{ $seoUrl }}">
<link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }}" href="{{ route('feed') }}">

<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:locale" content="ar_AR">
<meta property="og:type" content="{{ $seoType }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:url" content="{{ $seoUrl }}">
<meta property="og:image" content="{{ $seoImage }}">
@if ($seoType === 'article')
    @isset($seoPublished)<meta property="article:published_time" content="{{ $seoPublished }}">@endisset
    @isset($seoSection)<meta property="article:section" content="{{ $seoSection }}">@endisset
@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $seoImage }}">

@isset($seoJsonLd)
<script type="application/ld+json">{!! json_encode($seoJsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endisset
