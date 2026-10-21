<div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-duration="800">
  <div class="blog12-boxarea">
    <div class="img1">
      <a href="{{ route('article', $a->slug) }}">
        @include('partials.img', ['src' => $a->image_url, 'alt' => $a->title])
      </a>
    </div>
    <div class="content-area">
      <ul>
        <li>
          <a href="#">
            <i class="fa-regular fa-calendar-days"></i>
            {{ optional($a->published_at)->format('Y/m/d') }}
          </a>
        </li>
        @if ($a->category)
          <li style="margin-left:16px">
            <a href="{{ route('category', $a->category->slug) }}">
              <i class="fa-regular fa-folder"></i> {{ $a->category->name }}
            </a>
          </li>
        @endif
        <li style="margin-left:16px">
          <a href="#">
            <i class="fa-regular fa-clock"></i>
            {{ $a->estimated_reading_time }} د
          </a>
        </li>
      </ul>
      <div class="space16"></div>
      <a href="{{ route('article', $a->slug) }}" class="head">{{ $a->title }}</a>
      <div class="space12"></div>
      <p>{{ \Illuminate\Support\Str::limit($a->excerpt, 120) }}</p>
      <div class="space24"></div>
      <a href="{{ route('article', $a->slug) }}" class="readmore">
        قراءة المزيد <span><i class="fa-solid fa-angle-left"></i></span>
      </a>
    </div>
  </div>
</div>