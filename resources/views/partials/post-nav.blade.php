{{-- Navigation vers l'article précédent / suivant. Données : $older, $newer --}}
@if (! empty($older) || ! empty($newer))
<nav class="post-nav" aria-label="التنقل بين المقالات">
  @if (! empty($older))
    <a class="post-nav-item" href="{{ route('article', $older->slug) }}" rel="prev">
      <small><i class="fa-solid fa-angle-right"></i> المقال السابق</small>
      <strong>{{ \Illuminate\Support\Str::limit($older->title, 90) }}</strong>
    </a>
  @else <span></span> @endif

  @if (! empty($newer))
    <a class="post-nav-item post-nav-next" href="{{ route('article', $newer->slug) }}" rel="next">
      <small>المقال التالي <i class="fa-solid fa-angle-left"></i></small>
      <strong>{{ \Illuminate\Support\Str::limit($newer->title, 90) }}</strong>
    </a>
  @else <span></span> @endif
</nav>

@endif
