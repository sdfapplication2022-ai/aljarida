<div class="footer12-section-area">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-6">
        <div class="footer-log-area">
          <img src="{{ asset('assets/img/logo/logo-5.png') }}" alt="">
          <div class="space24"></div>
          <p>الجريدة منصة إخبارية شاملة تقدم لكم الخبر الدقيق والتحليل المعمق في مختلف المجالات.</p>
          <div class="space24"></div>
          <ul>
            <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
            <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
            <li><a href="#"><i class="fa-brands fa-linkedin-in"></i></a></li>
            <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="space30 d-md-none d-block"></div>
        <div class="footer-content-area footer1">
          <h3>روابط سريعة</h3>
          <ul>
            <li><a href="{{ route('home') }}">الرئيسية</a></li>
            <li><a href="{{ route('team') }}">فريق التحرير</a></li>
            <li><a href="{{ route('subscribe') }}">النشرة البريدية</a></li>
            <li><a href="{{ route('contact') }}">اتصل بنا</a></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="space30 d-lg-none d-block"></div>
        <div class="footer-content-area">
          <h3>الأقسام</h3>
          <ul>
            @foreach ($menuCategories as $c)
              <li><a href="{{ route('category', $c->slug) }}">{{ $c->name }}</a></li>
            @endforeach
          </ul>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="space30 d-lg-none d-block"></div>
        <div class="footer-contact-area">
          <h3>اتصل بنا</h3>
          <div class="icons-area">
            <div class="icons"><i class="fa-solid fa-phone-volume"></i></div>
            <div class="text"><a href="tel:+21690033504" dir="ltr">+216 90 033 504</a></div>
          </div>
          <div class="icons-area">
            <div class="icons"><i class="fa-solid fa-location-dot"></i></div>
            <div class="text"><a href="#">جندوبة، تونس</a></div>
          </div>
          <div class="icons-area">
            <div class="icons"><i class="fa-solid fa-envelope"></i></div>
            <div class="text"><a href="mailto:info@aljareeda.tn">info@aljareeda.tn</a></div>
          </div>
        </div>
      </div>
    </div>
    <div class="space80 d-lg-block d-none"></div>
    <div class="space40 d-lg-none d-block"></div>
    <div class="row">
      <div class="col-lg-12">
        <div class="copyright-area">
          <div class="pera"><p>ⓒ حقوق النشر {{ date('Y') }} الجريدة. جميع الحقوق محفوظة</p></div>
        </div>
      </div>
    </div>
  </div>
</div>