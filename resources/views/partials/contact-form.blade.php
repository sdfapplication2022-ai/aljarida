<div class="contact-boxarea">
  <h3>تواصل معنا</h3>
  <p>إذا كنت ترغب في مناقشة مقال صحفي، أو لديك فكرة تود نشرها، نحن هنا لتقديم الدعم.</p>
  @include('partials.flash')
  <form action="{{ route('contact.send') }}" method="POST">
    @csrf
    <div class="row">
      <div class="col-lg-6"><div class="input-area"><input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="الاسم الأول" required></div></div>
      <div class="col-lg-6"><div class="input-area"><input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="الاسم الأخير" required></div></div>
      <div class="col-lg-6"><div class="input-area"><input type="email" name="email" value="{{ old('email') }}" placeholder="البريد الإلكتروني" required></div></div>
      <div class="col-lg-6"><div class="input-area"><input type="text" name="phone" value="{{ old('phone') }}" placeholder="رقم الهاتف"></div></div>
      <div class="col-lg-12">
        <div class="input-area">
          <select name="service_type" class="country-area nice-select6" dir="rtl">
            <option  value="">نوع الخدمة</option>
            @foreach (($services ?? collect())->pluck('title') as $t)
              <option value="{{ $t }}" @selected(old('service_type') === $t)>{{ $t }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-lg-12"><div class="input-area"><textarea name="message" placeholder="رسالتك" required>{{ old('message') }}</textarea></div></div>
      <div class="col-lg-12"><div class="input-area"><button class="header-btn20" type="submit">إرسال <i class="fa-solid fa-angle-left"></i></button></div></div>
    </div>
  </form>
</div>
