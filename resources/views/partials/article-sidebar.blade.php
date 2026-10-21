{{-- Colonne latérale des articles : widgets, les plus lus, derniers commentaires, tags, catégories --}}
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

@if (! empty($latestComments) && $latestComments->isNotEmpty())
  <div class="np-box">
    <h3>آخر التعليقات</h3>
    <ul class="np-latest-comments">
      @foreach ($latestComments as $c)
        <li>
          <p>{{ \Illuminate\Support\Str::limit($c->content, 90) }}</p>
          <div class="np-meta">
            <span><i class="fa-regular fa-user"></i>{{ $c->author_name }}</span>
            <span><i class="fa-regular fa-clock"></i>{{ $c->created_at->diffForHumans() }}</span>
          </div>
          @if ($c->article)
            <a class="np-comment-link" href="{{ route('article', $c->article->slug) }}#comments">
              {{ \Illuminate\Support\Str::limit($c->article->title, 50) }}
            </a>
          @endif
        </li>
      @endforeach
    </ul>
  </div>
@endif

@if (! empty($popularTags) && $popularTags->isNotEmpty())
  <div class="np-box">
    <h3>الوسوم</h3>
    <ul class="np-cats np-tags">
      @foreach ($popularTags as $t)
        <li>
          <a href="{{ route('tag', $t->slug) }}">
            #{{ $t->name }} <small>({{ $t->articles_count }})</small>
          </a>
        </li>
      @endforeach
    </ul>
  </div>
@endif

@if ($menuCategories->isNotEmpty())
  <div class="np-box">
    <h3>الأقسام</h3>
    <ul class="np-cats">
      @foreach ($menuCategories as $c)
        <li><a href="{{ route('category', $c->slug) }}">{{ $c->name }}</a></li>
      @endforeach
    </ul>
  </div>
@endif