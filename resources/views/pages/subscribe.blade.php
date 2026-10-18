@extends('layouts.app')
@section('title', 'اشترك الآن')

@push('styles')
<style>
    .sub-card {
        max-width: 560px; margin: 0 auto; padding: 40px 32px; text-align: center;
        background: #fff; border-radius: 16px; box-shadow: 0 10px 40px rgba(20, 48, 31, .08);
    }
    .sub-card .sub-icon {
        width: 64px; height: 64px; margin: 0 auto 16px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: #e9f7d2; color: #14301f; font-size: 26px;
    }
    .sub-card h3 { margin-bottom: 8px; }
    .sub-card p  { margin-bottom: 24px; }
    .sub-form { display: flex; flex-wrap: wrap; gap: 10px; }
    .sub-input {
        flex: 1 1 240px; height: 54px; padding: 0 18px; font-size: 16px; color: #14301f;
        background: #fff; border: 1.5px solid #cfd8d3; border-radius: 10px; outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .sub-input::placeholder { color: #8a948f; }
    .sub-input:focus { border-color: #7ac70c; box-shadow: 0 0 0 4px rgba(122, 199, 12, .18); }
    .sub-input.is-invalid { border-color: #dc3545; }
    .sub-form .header-btn20 { height: 54px; border: 0; cursor: pointer; }
    .sub-error { margin-top: 10px; color: #dc3545; font-size: 14px; }
    .sub-note  { display: block; margin-top: 18px; color: #6b7570; }
</style>
@endpush

@section('content')
@include('partials.page-header', ['title' => 'اشترك في نشرتنا البريدية', 'crumbs' => ['اشترك الآن' => null]])

<div class="container sp6">
  <div class="sub-card">
    <div class="sub-icon"><i class="fa-regular fa-envelope"></i></div>
    <h3>ابقَ على اطلاع</h3>
    <p>أدخل بريدك الإلكتروني لتصلك أحدث الأخبار.</p>

    @include('partials.flash')

    <form action="{{ route('subscribe.store') }}" method="POST" class="sub-form" novalidate>
      @csrf
      {{-- honeypot : invisible pour les humains --}}
      <div style="position:absolute;left:-9999px" aria-hidden="true">
        <input type="text" name="website" tabindex="-1" autocomplete="off">
      </div>

      <input type="email" name="email" class="sub-input @error('email') is-invalid @enderror"
             placeholder="بريدك الإلكتروني" value="{{ old('email') }}" required autocomplete="email">
      <button class="header-btn20" type="submit">اشترك <i class="fa-solid fa-angle-left"></i></button>
    </form>

    @error('email')<div class="sub-error">{{ $message }}</div>@enderror
    <small class="sub-note">سنستخدم بريدك فقط لإرسال النشرة.</small>
  </div>
</div>
@endsection
