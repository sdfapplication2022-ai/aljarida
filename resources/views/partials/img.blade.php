{{-- <img> avec fallback automatique sur une image de secours.
     Usage : @include('partials.img', ['src' => $a->image_url, 'alt' => $a->title, 'class' => '...', 'lazy' => true]) --}}
@php
    $fallback = asset('assets/img/placeholder.png');
    $loading  = ($lazy ?? true) ? 'lazy' : 'eager';
    $cls      = $class ?? '';
@endphp
<img src="{{ $src ?: $fallback }}"
     alt="{{ $alt ?? '' }}"
     @if (! empty($cls)) class="{{ $cls }}" @endif
     loading="{{ $loading }}"
     decoding="async"
     onerror="this.onerror=null;this.src='{{ $fallback }}';">