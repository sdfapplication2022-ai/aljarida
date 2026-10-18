@extends('layouts.app')
@section('title', '#'.$tag->name)
@section('meta')
    @include('partials.seo', [
        'seoTitle' => 'الوسم: '.$tag->name,
        'seoDescription' => 'كل المقالات المرتبطة بالوسم '.$tag->name,
        'seoNoindex' => $articles->isEmpty(),
    ])
@endsection

@section('content')
@include('partials.page-header', ['title' => '#'.$tag->name, 'crumbs' => ['الوسوم' => null, $tag->name => null]])

<div class="blog1-scetion-area sp2">
  <div class="container">
    <div class="row">
      @forelse ($articles as $a)
        @include('partials.article-card-sm', ['a' => $a])
      @empty
        <p class="text-center">لا توجد مقالات بهذا الوسم بعد.</p>
      @endforelse
    </div>
    <div class="mt-4 d-flex justify-content-center">{{ $articles->links('pagination::bootstrap-5') }}</div>
  </div>
</div>
@include('partials.newsletter')
@endsection
