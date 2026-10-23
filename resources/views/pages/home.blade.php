@extends('layouts.app')
@section('title', 'الصفحة الرئيسية')

@section('meta')
    @include('partials.seo', [
        'seoTitle' => config('app.name'),
        'seoJsonLd' => [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name'),
            'url' => route('home'),
            'inLanguage' => 'ar',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => route('search').'?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ],
    ])
@endsection

@section('content')
@include('partials.news-styles')
@php($lead = $featured->first())
@php($side = $featured->slice(1, 4))

<div class="np-wrap">
  <div class="np-container">
    <h1 class="visually-hidden">{{ config('app.name') }} - آخر الأخبار</h1>

    @include('partials.flash')

    {{-- ===== عاجل ===== --}}
    @if ($breaking->isNotEmpty())
      <div class="np-ticker" role="region" aria-label="أخبار عاجلة">
        <span class="np-ticker-label"><i class="fa-solid fa-bolt"></i> عاجل</span>
        <div class="np-ticker-track">
          <div class="np-ticker-move">
            @foreach ([1, 2] as $copy)
              @foreach ($breaking as $b)
                <a href="{{ route('article', $b->slug) }}" @if ($copy === 2) aria-hidden="true" tabindex="-1" @endif>{{ $b->title }}</a><span class="np-dot">•</span>
              @endforeach
            @endforeach
          </div>
        </div>
      </div>
    @endif

    @if ($lead)
      {{-- ===== À la une ===== --}}
      <div class="np-hero">
        <article class="np-lead" @if ($lead->image_url) style="--bg:url('{{ $lead->image_url }}')" @endif>
          @if ($lead->image_url)
            <img src="{{ $lead->image_url }}" alt="{{ $lead->title }}" fetchpriority="high">
          @endif
          <div class="np-lead-body">
            @if ($lead->category)
              <a class="np-badge" href="{{ route('category', $lead->category->slug) }}">{{ $lead->category->name }}</a>
            @endif
            <h2 class="np-title"><a href="{{ route('article', $lead->slug) }}">{{ $lead->title }}</a></h2>
            <p class="np-clamp2">{{ $lead->excerpt }}</p>
            <div class="np-meta">
              <span><i class="fa-regular fa-clock"></i>{{ $lead->time_ago }}</span>
              <span><i class="fa-regular fa-user"></i>{{ $lead->user?->name ?? 'التحرير' }}</span>
            </div>
          </div>
        </article>

        <div class="np-side">
          @foreach ($side as $a)
            <article class="np-side-item">
              <span class="np-thumb" @if ($a->image_url) style="--bg:url('{{ $a->image_url }}')" @endif>
                @if ($a->image_url)<img src="{{ $a->image_url }}" alt="" loading="lazy">@else<span class="np-noimg"></span>@endif
              </span>
              <div>
                @if ($a->category)
                  <a class="np-badge" href="{{ route('category', $a->category->slug) }}">{{ $a->category->name }}</a>
                @endif
                <h3 class="np-title np-clamp3"><a href="{{ route('article', $a->slug) }}">{{ $a->title }}</a></h3>
                <div class="np-meta"><span><i class="fa-regular fa-clock"></i>{{ $a->time_ago }}</span></div>
              </div>
            </article>
          @endforeach
        </div>
      </div>
    @elseif ($latest->isEmpty())
      <div class="np-empty">لا توجد مقالات منشورة بعد.</div>
    @endif

    {{-- ===== Widgets ===== --}}
    @if (! empty($poll) || array_filter($widgets ?? []))
      <div class="np-widgets">
        @include('partials.widgets')
      </div>
    @endif

    {{-- ===== آخر الأخبار + الأكثر قراءة ===== --}}
    @if ($latest->isNotEmpty())
      <div class="np-section-title"><h2>آخر الأخبار</h2></div>
      <div class="np-main">
        <div>
          @foreach ($latest as $a)
            <article class="np-row">
              <span class="np-thumb" @if ($a->image_url) style="--bg:url('{{ $a->image_url }}')" @endif>
                @if ($a->image_url)<img src="{{ $a->image_url }}" alt="" loading="lazy">@else<span class="np-noimg"></span>@endif
              </span>
              <div>
                @if ($a->category)
                  <a class="np-badge" href="{{ route('category', $a->category->slug) }}">{{ $a->category->name }}</a>
                @endif
                <h3 class="np-title"><a href="{{ route('article', $a->slug) }}">{{ $a->title }}</a></h3>
                <p class="np-clamp2">{{ $a->excerpt }}</p>
                <div class="np-meta">
                  <span><i class="fa-regular fa-clock"></i>{{ $a->time_ago }}</span>
                  <span><i class="fa-regular fa-user"></i>{{ $a->user?->name ?? 'التحرير' }}</span>
                </div>
              </div>
            </article>
          @endforeach
        </div>

        <aside>
          @if ($mostRead->isNotEmpty())
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
        </aside>
      </div>
    @endif

    {{-- ===== Sections par rubrique ===== --}}
    @if ($sections->isNotEmpty())
      <div class="np-sections">
        @foreach ($sections as $cat)
          @php($first = $cat->latestArticles->first())
          @php($rest = $cat->latestArticles->slice(1))
          <section class="np-sec">
            <div class="np-section-title">
              <h2>{{ $cat->name }}</h2>
              <a href="{{ route('category', $cat->slug) }}">المزيد <i class="fa-solid fa-angle-left"></i></a>
            </div>

            <article class="np-card">
            <span class="np-thumb" @if ($first->image_url) style="--bg:url('{{ $first->image_url }}')" @endif>
                @if ($first->image_url)
                <img src="{{ $first->image_url }}" alt="" loading="lazy">
                @elseif ($first->video_player_url)
                {{-- Nouveau bloc pour la vidéo --}}
                <span class="np-video-placeholder">
                    <i class="fa-solid fa-play"></i>
                    <span>فيديو</span>
                </span>
                @else
                <span class="np-noimg"></span>
                @endif
            </span>
            <div class="np-card-body">
                <h3 class="np-title np-clamp3"><a href="{{ route('article', $first->slug) }}">{{ $first->title }}</a></h3>
                <div class="np-meta"><span><i class="fa-regular fa-clock"></i>{{ $first->time_ago }}</span></div>
            </div>
            </article>

            @foreach ($rest as $a)
              <article class="np-mini">
                <span class="np-thumb" @if ($a->image_url) style="--bg:url('{{ $a->image_url }}')" @endif>
                  @if ($a->image_url)<img src="{{ $a->image_url }}" alt="" loading="lazy">@else<span class="np-noimg"></span>@endif
                </span>
                <div>
                  <h4 class="np-title np-clamp2"><a href="{{ route('article', $a->slug) }}">{{ $a->title }}</a></h4>
                  <div class="np-meta"><span><i class="fa-regular fa-clock"></i>{{ $a->time_ago }}</span></div>
                </div>
              </article>
            @endforeach
          </section>
        @endforeach
      </div>
    @endif
  </div>
</div>

{{-- ================= Sections du site ================= --}}

@if ($about)
<div class="abut12-section-area sp1">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <div class="about-images-area">
          <div class="img1 image-anime reveal">
            @if ($about->video_player_url)
              <div class="video"><a href="{{ $about->video_player_url }}" class="popup-youtube"><i class="fa-solid fa-play"></i></a></div>
            @endif
            <img src="{{ $about->main_image_url ?? asset('assets/img/all-images/new-img/about-img1-h13.png') }}" alt="{{ $about->title }}">
          </div>
          <div class="img2 image-anime reveal">
            <img src="{{ $about->secondary_image_url ?? asset('assets/img/logo/plume.png') }}" alt="">
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="about12-header heading18 space-margin60">
          <h5 data-aos="fade-left" data-aos-duration="800">{{ $about->subtitle ?? 'من نحن' }}</h5>
          <div class="space16"></div>
          <h2>{{ $about->title }}</h2>
          <div class="space20"></div>
          <div class="rich-text" data-aos="fade-left" data-aos-duration="1000">{!! clean($about->description) !!}</div>
          <div class="space48"></div>
          <div class="btn-area1" data-aos="zoom-in" data-aos-duration="1200">
            <a href="{{ route('team') }}" class="header-btn20">تعرف على فريقنا <i class="fa-solid fa-angle-left"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endif

@if ($services->count())
<div class="service12-section-area sp1">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="service-header space-margin60 heading18">
          <h5>خدماتنا</h5>
          <div class="space16"></div>
          <h2>تغطية إخبارية شاملة</h2>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="service-slider owl-carousel">
        @foreach ($services as $s)
        <div class="service12-boxarea">
          <div class="icons"><img src="{{ $s->icon ?: $s->photo_url }}" alt=""></div>
          <div class="space20"></div>
          <div class="content">
            <a href="{{ route('contact') }}">{{ $s->title }}</a>
            <div class="space16"></div>
            <div class="rich-text">{!! clean($s->description) !!}</div>
            <div class="space24"></div>
            <a href="{{ route('contact') }}" class="readmore">اكتشف المزيد <i class="fa-solid fa-angle-left"></i></a>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endif

@if ($testimonials->count())
<div class="space100 d-lg-block d-none"></div>
<div class="testimonial12-section-area sp1">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <div class="testimonial12-boxarea-vertical">
          @foreach ($testimonials as $t)
          <div class="testimonial-boxarea">
            <i class="fa-solid fa-quote-right" style="font-size:34px;color:#7ac70c" aria-hidden="true"></i>
            <div class="space32"></div>
            <div class="rich-text">{!! clean($t->content) !!}</div>
            <div class="space32"></div>
            <div class="name-ara">
              <div class="text">
                <a href="#">{{ $t->author_name }}</a>
                <ul>
                  @for ($i = 0; $i < $t->rating; $i++)
                    <li><i class="fa-solid fa-star"></i></li>
                  @endfor
                </ul>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      <div class="col-lg-1"></div>
      <div class="col-lg-5">
        <div class="testimonial-haeder heading18">
          <h5 data-aos="fade-left" data-aos-duration="800">آراء القراء</h5>
          <div class="space16"></div>
          <h2>مصداقية الأخبار تبدأ من هنا</h2>
          <div class="space20"></div>
          <p data-aos="fade-left" data-aos-duration="1000">تعتمد الجريدة على تقديم محتوى خبري دقيق وموثوق، مما يجعلها المصدر الأول للقراء الباحثين عن الحقيقة.</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endif

@if ($team->count())
<div class="team12-section-area sp2">
  <div class="container">
    <div class="row">
      <div class="col-lg-7 m-auto">
        <div class="team-header text-center heading18 space-margin60">
          <h5>فريق التحرير</h5>
          <div class="space16"></div>
          <h2>نخبة من الصحفيين والخبراء</h2>
        </div>
      </div>
    </div>
    <div class="row">
      @foreach ($team as $m)
        @include('partials.team-card', ['m' => $m])
      @endforeach
    </div>
  </div>
</div>
@endif

<div class="contact12-section-area sp6">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 m-auto">
        <div class="contact-header-area text-center heading18 space-margin60">
          <h5>اتصل بنا</h5>
          <div class="space16"></div>
          <h2>نحن هنا للاستماع إليك</h2>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-5">
        <div class="contact-info-area">
          <h3>معلومات الاتصال</h3>
          <p>نحن في الجريدة ملتزمون بتقديم خدمات إعلامية متميزة. تواصل معنا لتجد الإجابة على جميع تساؤلاتك.</p>
                <div class="space32"></div>
                <div class="contact-auhtor-box">
                    <div class="icons"><img src="{{ asset('assets/img/icons/linkedin.svg') }}" alt=""></div>
                    <div class="content"><h4>موقع اللينكدإن</h4><a href="https://www.linkedin.com/in/raoudha-mediouni-118a772a9">raoudha.mediouni</a></div>
                </div>
                <div class="space40"></div>
                <div class="contact-auhtor-box">
                    <div class="icons"><img src="{{ asset('assets/img/icons/phone5.svg') }}" alt=""></div>
                    <div class="content"><h4>رقم الهاتف</h4><a href="tel:+21690033504" dir="ltr">+216 90 033 504</a></div>
                </div>
                <div class="space40"></div>
                <div class="contact-auhtor-box">
                    <div class="icons"><img src="{{ asset('assets/img/icons/email5.svg') }}" alt=""></div>
                    <div class="content"><h4>البريد الإلكتروني</h4><a href="mailto:raoudhamediouni@gmail.com">raoudhamediouni@gmail.com</a></div>
                </div>
        </div>
      </div>
      <div class="col-lg-7">
        @include('partials.contact-form', ['services' => $services])
      </div>
    </div>
  </div>
</div>

@if ($blogs->count())
<div class="blog12-section-area sp2">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 m-auto">
        <div class="blog-header heading18 space-margin60 text-center">
          <h5>مدونتنا</h5>
          <div class="space24"></div>
          <h2>أحدث أخبار الصحافة والتحليلات</h2>
        </div>
      </div>
    </div>
    <div class="row">
      @foreach ($blogs as $b)
      <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-duration="800">
        <div class="blog12-boxarea">
          <div class="img1">
            @include('partials.img', ['src' => $b->image_url, 'alt' => $b->title])
          </div>
          <div class="content-area">
            <ul>
              <li><a href="#"><i class="fa-regular fa-calendar-days"></i> {{ optional($b->published_at)->format('Y/m/d') }}</a></li>
              <li style="margin-left:16px"><a href="#"><i class="fa-regular fa-user"></i> {{ $b->author }}</a></li>
            </ul>
            <div class="space16"></div>
            <a href="{{ route('blog.show', $b->id) }}" class="head">{{ $b->title }}</a>
            <div class="space12"></div>
            <p>{{ \Illuminate\Support\Str::limit(strip_tags($b->content), 110) }}</p>
            <div class="space24"></div>
            <a href="{{ route('blog.show', $b->id) }}" class="readmore">قراءة المزيد <span><i class="fa-solid fa-angle-left"></i></span></a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endif

@include('partials.newsletter')
@endsection
