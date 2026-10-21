@php
  $isCategoryPage = request()->routeIs('category');
  $currentSlug    = $isCategoryPage ? request()->route('slug') : null;
@endphp

<header class="homepage12-body">
  <div class="header-area homepage12 header header-sticky d-none d-lg-block" id="header">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="header-elements">

            <!-- Logo -->
            <div class="site-logo">
              <a href="{{ route('home') }}"><img src="{{ asset('assets/img/logo/logo-5.png') }}" alt="الجريدة"></a>
            </div>

            <!-- Menu desktop -->
            <nav class="main-menu" aria-label="القائمة الرئيسية">
              <ul>
                <li>
                  <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">الرئيسية</a>
                </li>

                @if ($menuCategories->count())
                  <li class="has-dropdown">
                    <a href="#" class="dropdown-toggle-link {{ $isCategoryPage ? 'active' : '' }}"
                       aria-haspopup="true" aria-expanded="false">
                      الأقسام <i class="fa-solid fa-angle-down"></i>
                    </a>
                    <ul class="nav-dropdown">
                      @foreach ($menuCategories as $c)
                        <li>
                          <a href="{{ route('category', $c->slug) }}"
                             class="{{ $currentSlug === $c->slug ? 'current' : '' }}">{{ $c->name }}</a>
                        </li>
                      @endforeach
                    </ul>
                  </li>
                @endif

                <li>
                  <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">اتصل بنا</a>
                </li>
              </ul>
            </nav>

            <!-- Actions (Recherche + Bouton) -->
            <div class="header-actions">
              <div class="header-search d-none d-xl-block">
                @include('partials.search-form')
              </div>
              <div class="btn-area">
                <a href="{{ route('subscribe') }}" class="header-btn20">اشترك الآن <i class="fa-solid fa-angle-left"></i></a>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- Mobile Header -->
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

<!-- Sidebar Mobile -->
<div class="mobile-sidebar mobile-sidebar12">
  <div class="logosicon-area">
    <div class="logos"><img src="{{ asset('assets/img/logo/logo.png') }}" alt=""></div>
    <div class="menu-close"><i class="fa-solid fa-xmark"></i></div>
  </div>

  <div class="mobile-nav mobile-nav1">

    <!-- Recherche Mobile -->
    <div class="mobile-search-wrapper">
      @include('partials.search-form')
    </div>

    <ul class="mobile-nav-list nav-list1 m-accordion">
      <li>
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">الرئيسية</a>
      </li>

      @if ($menuCategories->count())
        <li class="m-has-sub {{ $isCategoryPage ? 'open' : '' }}">
          <button type="button" class="m-sub-toggle" aria-expanded="{{ $isCategoryPage ? 'true' : 'false' }}">
            <span>الأقسام</span>
            <i class="fa-solid fa-angle-down"></i>
          </button>
          <ul class="m-sub-list">
            @foreach ($menuCategories as $c)
              <li>
                <a href="{{ route('category', $c->slug) }}"
                   class="{{ $currentSlug === $c->slug ? 'current' : '' }}">{{ $c->name }}</a>
              </li>
            @endforeach
          </ul>
        </li>
      @endif

      <li>
        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">اتصل بنا</a>
      </li>
    </ul>

    <div class="allmobilesection">
      <a href="{{ route('subscribe') }}" class="header-btn20" style="width: 100%; text-align: center; margin-bottom: 20px;">
        اشترك الآن <span><i class="fa-solid fa-angle-left"></i></span>
      </a>

      <!-- ... Reste du menu mobile (contact, réseaux sociaux) ... -->
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  /* Desktop : accessibilité clavier / tactile pour le dropdown */
  document.querySelectorAll('.main-menu .has-dropdown').forEach(function (item) {
    var link = item.querySelector('.dropdown-toggle-link');
    link.addEventListener('click', function (e) {
      e.preventDefault();
      var open = item.classList.toggle('is-open');
      link.setAttribute('aria-expanded', open);
    });
    item.addEventListener('mouseenter', function () { link.setAttribute('aria-expanded', 'true'); });
    item.addEventListener('mouseleave', function () {
      item.classList.remove('is-open');
      link.setAttribute('aria-expanded', 'false');
    });
  });
  document.addEventListener('click', function (e) {
    document.querySelectorAll('.main-menu .has-dropdown.is-open').forEach(function (item) {
      if (!item.contains(e.target)) item.classList.remove('is-open');
    });
  });

  /* Mobile : accordéon des catégories */
  document.querySelectorAll('.m-sub-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var li = btn.closest('.m-has-sub');
      var open = li.classList.toggle('open');
      btn.setAttribute('aria-expanded', open);
    });
  });
});
</script>
