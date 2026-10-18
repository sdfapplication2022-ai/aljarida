@extends('layouts.app')
@section('title', $user->name)
@section('meta')
    @include('partials.seo', [
        'seoTitle' => $user->name,
        'seoDescription' => \Illuminate\Support\Str::limit(strip_tags((string) $user->bio), 155) ?: 'مقالات '.$user->name,
    ])
@endsection

@section('content')
@include('partials.page-header', ['title' => $user->name, 'crumbs' => ['الكتّاب' => null, $user->name => null]])

<div class="blog1-scetion-area sp2">
  <div class="container">
    @if ($user->bio)
      <div class="row"><div class="col-lg-8 m-auto text-center"><div class="rich-text">{!! clean($user->bio) !!}</div><div class="space30"></div></div></div>
    @endif
    <div class="row">
      @forelse ($articles as $a)
        @include('partials.article-card-sm', ['a' => $a])
      @empty
        <p class="text-center">لا توجد مقالات منشورة بعد.</p>
      @endforelse
    </div>
    <div class="mt-4 d-flex justify-content-center">{{ $articles->links('pagination::bootstrap-5') }}</div>
  </div>
</div>
@include('partials.newsletter')
@endsection
