@extends('layouts.app')
@section('title', $blog->title)

@section('content')
@include('partials.page-header', [
    'title' => $blog->title,
    'crumbs' => ['المدونة' => null, $blog->title => null],
])

@php($blogImage = $blog->image_url ?? asset('assets/img/all-images/case-img18.png'))

<!--===== BLOG SINGLE STARTS =======-->
<div class="case-single-section-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="case-auhtor-area sp1">
                    <div class="row align-items-center">
                        <div class="col-lg-4">
                            <div class="case-single-hedaer heading2">
                                <h2>نبذة</h2>
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($blog->content), 260) }}</p>
                                <div class="case-others-area">
                                    <ul>
                                        <li><span>الكاتب:</span>{{ $blog->author ?: 'التحرير' }}</li>
                                        <li><span>التاريخ:</span>{{ optional($blog->published_at)->format('Y/m/d') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1"></div>
                        <div class="col-lg-7">
                            <div class="case-images">
                                <a href="{{ $blogImage }}" class="show-hero js-lightbox" title="{{ $blog->title }}" style="--bg:url('{{ $blogImage }}')">
                                    <img src="{{ $blogImage }}" alt="{{ $blog->title }}">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="case-lista-area sp1 bg2">
  <div class="container">
    <div class="row">
      <div class="col-lg-7">
          <div class="case-pera-area heading2">
              <h2>{{ $blog->title }}</h2>
              <div class="article-body">{!! clean($blog->display_content) !!}</div>
          </div>
      </div>
      <div class="col-lg-5">
          <div class="case-list">
              <ul>
                  @forelse ($others as $o)
                      <li><img src="{{ asset('assets/img/icons/check6.svg') }}" alt=""><a href="{{ route('blog.show', $o->id) }}">{{ $o->title }}</a></li>
                  @empty
                      <li><img src="{{ asset('assets/img/icons/check6.svg') }}" alt="">لا توجد تدوينات أخرى بعد</li>
                  @endforelse
              </ul>
          </div>
      </div>
    </div>
  </div>
</div>
<!--===== BLOG SINGLE ENDS =======-->

@include('partials.lightbox')
@include('partials.newsletter')
@endsection
