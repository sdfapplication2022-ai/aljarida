@extends('layouts.app')
@section('title', $article->title)

@section('meta')
    @include('partials.seo', [
        'seoTitle'       => $article->title,
        'seoDescription' => \Illuminate\Support\Str::limit(strip_tags($article->content), 155),
        'seoImage'       => $article->image_url,
        'seoType'        => 'article',
        'seoPublished'   => optional($article->published_at)->toAtomString(),
        'seoSection'     => $article->category?->name,
        'seoJsonLd'      => $article->toJsonLd(),
    ])
@endsection

@section('content')
@include('partials.news-styles')
@include('partials.page-header', [
    'title' => 'تفاصيل المقال',
    'crumbs' => array_filter([
        $article->category?->name => $article->category ? route('category', $article->category->slug) : null,
        $article->title => null,
    ], fn ($v, $k) => $k !== '', ARRAY_FILTER_USE_BOTH),
])

@php($shareUrl = urlencode(request()->url()))

<!--===== ARTICLE AREA STARTS =======-->
<div class="blog-auhtor-section-area sp1">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div class="blog-auhtor-sidebar-area heading2">

          <div class="tags-area">
            <ul>
              <li>
                @if ($article->user)
                  <a href="{{ route('author', $article->user) }}"><img src="{{ asset('assets/img/icons/contact1.svg') }}" alt="">{{ $article->user->name }}</a>
                @else
                  <a href="#"><img src="{{ asset('assets/img/icons/contact1.svg') }}" alt="">التحرير</a>
                @endif
              </li>
              <li><a href="#"><img src="{{ asset('assets/img/icons/calender1.svg') }}" alt="">{{ optional($article->published_at)->format('Y/m/d') }}</a></li>
              <li><a href="#"><i class="fa-regular fa-clock"></i> {{ $article->estimated_reading_time }} د</a></li>
              <li><a href="#"><i class="fa-regular fa-eye"></i> {{ $article->views }}</a></li>
              @if ($article->category)
                <li><a href="{{ route('category', $article->category->slug) }}"><i class="fa-regular fa-folder"></i> {{ $article->category->name }}</a></li>
              @endif
              @if ($article->city)
                <li><a href="{{ route('city-news', ['city' => $article->city]) }}"><i class="fa-solid fa-location-dot"></i> {{ $article->city }}</a></li>
              @endif
            </ul>
          </div>

          <h2 dir="auto">{{ $article->title }}</h2>
          <div class="space34"></div>

          @include('partials.article-tools')
          @include('partials.reading-mode')
          <form action="{{ route('article.save', $article) }}" method="POST" class="my-3">
            @csrf
            <button type="submit" class="btn btn-outline-success">
              <i class="fa-{{ $isSaved ? 'solid' : 'regular' }} fa-bookmark" aria-hidden="true"></i>
              {{ $isSaved ? 'إزالة من قائمة القراءة لاحقًا' : 'أضف إلى قائمة القراءة لاحقًا' }}
            </button>
          </form>
          @include('partials.article-toc')

          @if ($article->image_url)
            <a href="{{ $article->image_url }}" class="show-hero js-lightbox" title="{{ $article->title }}" style="--bg:url('{{ $article->image_url }}')">
              <img src="{{ $article->image_url }}" alt="{{ $article->title }}" fetchpriority="high">
            </a>
            <div class="space24"></div>
          @endif

          @if ($article->video_player_url)
            <video controls class="w-100" src="{{ $article->video_player_url }}"></video>
            <div class="space24"></div>
          @endif

          <div class="article-body">{!! clean($article->display_content) !!}</div>
          <div class="space50"></div>

          <div class="social-tags">
            <div class="tags">
              <h4>العلامات:</h4>
              <ul>
                @forelse ($article->tags as $tag)
                  <li><a href="{{ route('tag', $tag->slug) }}" @if ($loop->last) class="m-0" @endif>{{ $tag->name }}</a></li>
                @empty
                  <li><a href="#" class="m-0">{{ $article->category?->name ?? 'أخبار' }}</a></li>
                @endforelse
              </ul>
            </div>
            <div class="social">
              <h4>مشاركة:</h4>
              <ul>
                <li><a target="_blank" rel="noopener" href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a></li>
                <li><a target="_blank" rel="noopener" href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ urlencode($article->title) }}" aria-label="X"><i class="fa-brands fa-x-twitter"></i></a></li>
                <li><a target="_blank" rel="noopener" href="https://wa.me/?text={{ urlencode($article->title.' '.request()->url()) }}" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a></li>
                <li><a target="_blank" rel="noopener" href="https://t.me/share/url?url={{ $shareUrl }}&text={{ urlencode($article->title) }}" aria-label="Telegram"><i class="fa-brands fa-telegram"></i></a></li>
              </ul>
            </div>
          </div>

          <div class="space24"></div>

          <form action="{{ route('article.like', $article) }}" method="POST"
                class="like-form" data-like>
            @csrf
            <button type="submit" class="header-btn20 like-btn"
                    aria-pressed="{{ $liked ? 'true' : 'false' }}"
                    aria-label="{{ $liked ? 'إلغاء الإعجاب' : 'أعجبني' }}">
              <i class="{{ $liked ? 'fa-solid' : 'fa-regular' }} fa-heart like-icon" aria-hidden="true"></i>
              <span class="like-label">{{ $liked ? 'أعجبك هذا المقال' : 'أعجبني' }}</span>
              (<span class="like-count">{{ (int) $article->likes_count }}</span>)
            </button>
          </form>

          @include('partials.post-nav')

          <div class="space50"></div>
          <h3 id="comments">التعليقات ({{ $comments->count() }})</h3>
          <div class="space32"></div>

          @include('partials.flash')

          @foreach ($comments as $c)
            @php($avatar = $c->user && $c->user->photo ? asset('storage/'.$c->user->photo) : null)
            @php($initial = mb_strtoupper(mb_substr(trim($c->author_name), 0, 1)))
            @php($bg = ['#14301f', '#2f7d5b', '#7a4fd1', '#c2410c', '#0e7490', '#a21caf'][abs(crc32($c->author_name)) % 6])
            @php($mine = $c->isOwnedBy(auth()->user(), $visitorToken))
            <div class="comments-boxarea @if ($loop->even) box2 @endif">
              <div class="comments-boxes">
                <div class="comments-auhtor-box">
                  <div class="img3">
                    @if ($avatar)
                      <img src="{{ $avatar }}" alt="{{ $c->author_name }}">
                    @else
                      <span class="avatar-initial" style="background: {{ $bg }}" aria-hidden="true">{{ $initial }}</span>
                    @endif
                  </div>
                  <div class="content">
                    <a href="#" class="name">{{ $c->author_name }}</a>
                    <a href="#" class="date">{{ $c->created_at->format('Y/m/d') }}</a>
                    @unless ($c->is_approved)
                      <span class="badge bg-warning text-dark">بانتظار المراجعة · لا يراه غيرك</span>
                    @endunless
                  </div>
                </div>
              </div>
              <div class="space16"></div>
              <p dir="auto">{{ $c->content }}</p>
              @if ($mine)
                <form action="{{ route('comment.destroy', $c) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('هل تريد حذف تعليقك؟')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-link btn-sm text-danger p-0"><i class="fa-regular fa-trash-can"></i> حذف تعليقي</button>
                </form>
              @endif
            </div>
            <div class="space30"></div>
          @endforeach

          <div class="space20"></div>
          <div class="contact-form-area">
            <h4>اترك تعليقًا</h4>
            <div class="space16"></div>
            <p>سيظهر تعليقك للجميع بعد مراجعته من طرف فريق التحرير.</p>
            <div class="space12"></div>
            <form action="{{ route('article.comment', $article) }}" method="POST">
              @csrf
              <div style="position:absolute;left:-9999px" aria-hidden="true">
                <input type="text" name="website" tabindex="-1" autocomplete="off">
              </div>
              <div class="row">
                @guest
                  <div class="col-md-6">
                    <div class="input-area"><input type="text" name="guest_name" maxlength="100" placeholder="اسمك" required
                         value="{{ old('guest_name', $guest->guest_name ?? '') }}"></div>
                    @error('guest_name')<small class="text-danger">{{ $message }}</small>@enderror
                  </div>
                  <div class="col-md-6">
                    <div class="input-area"><input type="email" name="guest_email" maxlength="255" placeholder="بريدك الإلكتروني (لن يُنشر)" required
                         value="{{ old('guest_email', $guest->guest_email ?? '') }}"></div>
                    @error('guest_email')<small class="text-danger">{{ $message }}</small>@enderror
                  </div>
                  <div class="col-lg-12"><div class="space16"></div></div>
                @else
                  <div class="col-lg-12"><p>سيُنشر التعليق باسم: <strong>{{ auth()->user()->name }}</strong></p></div>
                @endguest
                <div class="col-lg-12">
                  <div class="input-area"><textarea name="content" placeholder="اكتب تعليقك..." required>{{ old('content') }}</textarea></div>
                  @error('content')<small class="text-danger">{{ $message }}</small>@enderror
                  {{-- honeypot : jamais affiché, jamais annoncé --}}
                </div>
                <div class="col-lg-12">
                  <div class="space24"></div>
                  <div class="input-area">
                    <button type="submit" class="header-btn20">إرسال التعليق <i class="fa-solid fa-angle-left"></i></button>
                  </div>
                </div>
              </div>
            </form>
          </div>

        </div>
      </div>

      <aside class="col-lg-4">
        @include('partials.article-sidebar')
      </aside>
    </div>
  </div>
</div>
<!--===== ARTICLE AREA ENDS =======-->

@if ($related->count())
<!--===== RELATED AREA STARTS =======-->
<div class="blog1-scetion-area sp2 bg2">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 m-auto">
        <div class="blog-hedaer-area heading2 text-center"><h2>مقالات ذات صلة</h2></div>
      </div>
    </div>
    <div class="row">
      @foreach ($related as $a) @include('partials.article-card-sm', ['a' => $a]) @endforeach
    </div>
  </div>
</div>
<!--===== RELATED AREA ENDS =======-->
@endif

@include('partials.lightbox')
@include('partials.newsletter')
@endsection

@once
@push('scripts')
<script>
(function () {
    document.querySelectorAll('[data-like]').forEach(function (form) {
        async function onSubmit(e) {
            if (!window.fetch) return;
            e.preventDefault();
            var btn = form.querySelector('.like-btn');
            if (btn.disabled) return;
            btn.disabled = true;
            try {
                var res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value,
                        'Accept': 'application/json',
                    },
                    body: new FormData(form),
                });
                if (!res.ok) throw new Error();
                var data = await res.json();
                btn.querySelector('.like-icon').className =
                    (data.liked ? 'fa-solid' : 'fa-regular') + ' fa-heart like-icon';
                btn.querySelector('.like-label').textContent =
                    data.liked ? 'أعجبك هذا المقال' : 'أعجبني';
                btn.querySelector('.like-count').textContent = data.count;
                btn.setAttribute('aria-pressed', data.liked ? 'true' : 'false');
                btn.setAttribute('aria-label', data.liked ? 'إلغاء الإعجاب' : 'أعجبني');
            } catch (_) {
                form.removeEventListener('submit', onSubmit);
                form.submit();
            } finally {
                btn.disabled = false;
            }
        }
        form.addEventListener('submit', onSubmit);
    });
})();
</script>

@endpush
@endonce
