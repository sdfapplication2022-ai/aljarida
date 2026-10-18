{{-- Colonne latérale des articles : sondage, météo, prière, change, les plus lus --}}
@include('partials.widgets')

@if (isset($mostRead) && $mostRead->isNotEmpty())
  <div class="np-box">
    <h3>الأكثر قراءة</h3>
    <ol class="np-top-list">
      @foreach ($mostRead as $a)
        <li>
          <div>
            <h4 class="np-title"><a href="{{ route('article', $a->slug) }}">{{ $a->title }}</a></h4>
            <div class="np-meta"><span><i class="fa-regular fa-eye"></i>{{ number_format($a->views) }}</span></div>
          </div>
        </li>
      @endforeach
    </ol>
  </div>
@endif

@if (isset($menuCategories) && $menuCategories->isNotEmpty())
  <div class="np-box">
    <h3>الأقسام</h3>
    <ul class="np-cats">
      @foreach ($menuCategories as $c)
        <li><a href="{{ route('category', $c->slug) }}">{{ $c->name }}</a></li>
      @endforeach
    </ul>
  </div>
@endif
