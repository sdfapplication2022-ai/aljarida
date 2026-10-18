@extends('layouts.app')
@section('title', 'اتصل بنا')

@section('content')
@include('partials.page-header', ['title' => 'اتصل بنا', 'crumbs' => ['اتصل بنا' => null]])
<div class="contact12-section-area sp6">
  <div class="container">
    <div class="row">
      <div class="col-lg-5">@include('partials.contact-info')</div>
      <div class="col-lg-7">@include('partials.contact-form')</div>
    </div>
  </div>
</div>
@endsection
