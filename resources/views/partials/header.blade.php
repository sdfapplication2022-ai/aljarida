@php($menuCategories = \App\Models\Category::orderBy('id')->get())

<header class="homepage12-body">
  <div class="header-area homepage12 header header-sticky d-none d-lg-block" id="header">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="header-elements">
            <div class="site-logo">
              <a href="{{ route('home') }}"><img src="{{ asset('assets/img/logo/logo-5.png') }}" alt="الجريدة"></a>
            </div>
            <div class="main-menu">
              <ul>
                <li><a href="{{ route('home') }}">الرئيسية</a></li>
                @foreach ($menuCategories as $c)
                  <li><a href="{{ route('category', $c->slug) }}">{{ $c->name }}</a></li>
                @endforeach
                <li><a href="{{ route('contact') }}">اتصل بنا</a></li>
              </ul>
            </div>
            <div class="btn-area">
              <a href="{{ route('subscribe') }}" class="header-btn20">اشترك الآن <i class="fa-solid fa-angle-left"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="mobile-header mobile-haeder12 d-block d-lg-none">
  <div class="container-fluid">
    <div class="col-12">
      <div class="mobile-header-elements">
        <div class="mobile-logo">
          <a href="{{ route('home') }}"><img src="{{ asset('assets/img/logo/logo.png') }}" alt=""></a>
        </div>
        <div class="mobile-nav-icon dots-menu"><i class="fa-solid fa-bars"></i></div>
      </div>
    </div>
  </div>
</div>

<div class="mobile-sidebar mobile-sidebar12">
  <div class="logosicon-area">
    <div class="logos"><img src="{{ asset('assets/img/logo/logo.png') }}" alt=""></div>
    <div class="menu-close"><i class="fa-solid fa-xmark"></i></div>
  </div>
  <div class="mobile-nav mobile-nav1">
    <ul class="mobile-nav-list nav-list1">
      <li><a href="{{ route('home') }}">الرئيسية</a></li>
      @foreach ($menuCategories as $c)
        <li><a href="{{ route('category', $c->slug) }}">{{ $c->name }}</a></li>
      @endforeach
      <li><a href="{{ route('contact') }}">اتصل بنا</a></li>
    </ul>

    <div class="allmobilesection">
      <a href="{{ route('subscribe') }}" class="header-btn20">اشترك الآن <span><i class="fa-solid fa-angle-left"></i></span></a>
      <div class="single-footer">
        <h3>معلومات الاتصال</h3>
        <div class="footer1-contact-info">
          <div class="contact-info-single">
            <div class="contact-info-icon"><i class="fa-solid fa-phone-volume"></i></div>
            <div class="contact-info-text"><a href="tel:+1234567890">+123 456 7890</a></div>
          </div>
          <div class="contact-info-single">
            <div class="contact-info-icon"><i class="fa-solid fa-envelope"></i></div>
            <div class="contact-info-text"><a href="mailto:info@aljareeda.tn">info@aljareeda.tn</a></div>
          </div>
          <div class="single-footer">
            <h3>موقعنا</h3>
            <div class="contact-info-single">
              <div class="contact-info-icon"><i class="fa-solid fa-location-dot"></i></div>
              <div class="contact-info-text"><a href="#">شارع الصحافة، تونس</a></div>
            </div>
          </div>
          <div class="single-footer">
            <h3>تابعنا</h3>
            <div class="social-links-mobile-menu">
              <ul>
                <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
