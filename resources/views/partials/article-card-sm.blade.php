{{-- Carte "blog-author-boxarea" du modèle blog-single --}}
<div class="col-lg-4 col-md-6">
  <div class="blog-author-boxarea">
    <div class="img1">
      <a href="{{ route('article', $a->slug) }}">
        @include('partials.img', ['src' => $a->image_url, 'alt' => $a->title])
      </a>
    </div>
    <div class="content-area">
      <div class="tags-area">
        <ul>
          <li>
            <a href="#">
              <img src="{{ asset('assets/img/icons/contact1.svg') }}" alt="">
              {{ $a->user?->name ?? 'التحرير' }}
            </a>
          </li>
          <li>
            <a href="#">
              <img src="{{ asset('assets/img/icons/calender1.svg') }}" alt="">
              {{ optional($a->published_at)->format('Y/m/d') }}
            </a>
          </li>
          <li>
            <a href="#">
              <i class="fa-regular fa-clock" aria-hidden="true"></i>
              {{ $a->estimated_reading_time }} د
            </a>
          </li>
        </ul>
      </div>
      <a href="{{ route('article', $a->slug) }}" dir="auto">{{ $a->title }}</a>
      <p>{{ \Illuminate\Support\Str::limit($a->excerpt, 100) }}</p>
      <a href="{{ route('article', $a->slug) }}" class="readmore">
        قراءة المزيد <i class="fa-solid fa-arrow-left"></i>
      </a>
    </div>
  </div>
  <div class="space30"></div>
</div>
