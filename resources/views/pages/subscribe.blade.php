@extends('layouts.app')
@section('title', 'اشترك الآن')

@section('content')
@include('partials.page-header', ['title' => 'اشترك في نشرتنا البريدية', 'crumbs' => ['اشترك الآن' => null]])

<div class="container sp6">
  <!-- AJOUT DE LA STRUCTURE ROW / COL POUR UNE RESPONSIVITÉ PARFAITE -->
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-12">

      <div class="sub-card" style="margin: 0 auto; width: 100%;">
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

          <button class="header-btn20" type="submit" style="white-space: nowrap;">اشترك <i class="fa-solid fa-angle-left"></i></button>
        </form>

        @error('email')<div class="sub-error">{{ $message }}</div>@enderror
        <small class="sub-note">سنستخدم بريدك فقط لإرسال النشرة.</small>
      </div>

    </div>
  </div>
</div>
@endsection
