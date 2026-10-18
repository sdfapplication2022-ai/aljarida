@extends('layouts.app')
@section('title', $category->name)

@section('meta')
    @include('partials.seo', [
        'seoTitle'       => $category->name,
        'seoDescription' => \Illuminate\Support\Str::limit(strip_tags((string) $category->description), 155) ?: 'أخبار '.$category->name,
    ])
@endsection

@section('content')
@include('partials.page-header', ['title' => $category->name, 'crumbs' => [$category->name => null]])

<div class="blog1-scetion-area sp2">
  <div class="container">
    @if ($category->description)
      <div class="row"><div class="col-lg-8 m-auto text-center"><div class="rich-text">{!! clean($category->description) !!}</div><div class="space30"></div></div></div>
    @endif
    <div class="row">
      @forelse ($articles as $a)
        @include('partials.article-card-sm', ['a' => $a])
      @empty
        <p class="text-center">لا توجد مقالات في هذا القسم بعد.</p>
      @endforelse
    </div>
    <div class="mt-4 d-flex justify-content-center">{{ $articles->links('pagination::bootstrap-5') }}</div>
  </div>
</div>
@include('partials.newsletter')
@endsection
