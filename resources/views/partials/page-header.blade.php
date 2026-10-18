{{-- Bandeau de page interne (modèle blog-single / case-single).
     Variables : $title (string), $crumbs (array label => url|null) --}}
<div class="about-header-area" style="background-image: url({{ asset('assets/img/bg/inner-header.png') }}); background-repeat: no-repeat; background-size: cover; background-position: center;">
    <img src="{{ asset('assets/img/elements/elements1.png') }}" alt="" class="elements1 aniamtion-key-1">
    <img src="{{ asset('assets/img/elements/star2.png') }}" alt="" class="star2 keyframe5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="about-inner-header heading9 text-center">
                    <h1>{{ $title }}</h1>
                    <a href="{{ route('home') }}">الرئيسية
                        @foreach (($crumbs ?? []) as $label => $url)
                            <i class="fa-solid fa-angle-left"></i>
                            @if ($url) <span><a href="{{ $url }}" style="font-weight:inherit">{{ $label }}</a></span> @else <span>{{ \Illuminate\Support\Str::limit($label, 60) }}</span> @endif
                        @endforeach
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
