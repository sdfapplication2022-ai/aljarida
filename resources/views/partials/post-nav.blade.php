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

@once
@push('styles')
<style>
    .post-nav { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin: 36px 0 8px; }
    .post-nav-item { display: block; padding: 16px 18px; background: #fff; border: 1px solid #e3e8e5; border-radius: 12px; text-decoration: none; color: #14301f; transition: border-color .15s, transform .15s; }
    .post-nav-item:hover { border-color: #7ac70c; transform: translateY(-2px); }
    .post-nav-item small { display: block; margin-bottom: 6px; color: #6b7570; font-size: 13px; }
    .post-nav-item strong { display: block; font-size: 15px; line-height: 1.7; }
    .post-nav-next { text-align: left; }
    @media (max-width: 575px) { .post-nav { grid-template-columns: 1fr; } .post-nav-next { text-align: right; } }
</style>
@endpush
@endonce
@endif
