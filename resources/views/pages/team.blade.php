@extends('layouts.app')
@section('title', 'فريق التحرير')

@section('content')
@include('partials.page-header', ['title' => 'فريق التحرير', 'crumbs' => ['فريق التحرير' => null]])
<div class="team12-section-area sp2">
  <div class="container">
    @if ($about)
      <div class="row"><div class="col-lg-8 m-auto text-center"><div class="rich-text">{!! clean($about->description) !!}</div><div class="space30"></div></div></div>
    @endif
    <div class="row">@foreach ($team as $m) @include('partials.team-card', ['m' => $m]) @endforeach</div>
  </div>
</div>
@include('partials.newsletter')
@endsection
