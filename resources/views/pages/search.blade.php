@extends('layouts.app')
@section('title', $q !== '' ? 'نتائج البحث: '.$q : 'بحث')

@section('meta')
    @include('partials.seo', ['seoTitle' => 'بحث', 'seoNoindex' => true])
@endsection

@section('content')
@include('partials.page-header', ['title' => 'البحث', 'crumbs' => ['البحث' => null]])

<div class="blog1-scetion-area sp2">
  <div class="container">

    {{-- Barre de recherche --}}
    <div class="row">
      <div class="col-lg-8 m-auto">
        @include('partials.search-form', ['q' => $q])
      </div>
    </div>

    {{-- Compteur de résultats --}}
    @if ($q !== '')
      <p class="text-center my-4">
        {{ $articles->total() }} نتيجة عن «{{ $q }}»
      </p>
    @endif

    {{-- Grille de résultats --}}
    <div class="row">
      @forelse ($articles as $a)
        @include('partials.article-card-sm', ['a' => $a])
      @empty
        @if ($q !== '')
          <p class="text-center">لا توجد نتائج. جرّب كلمات أخرى.</p>
        @endif
      @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
      {{ $articles->links('pagination::bootstrap-5') }}
    </div>

  </div>
</div>

@include('partials.newsletter')
@endsection