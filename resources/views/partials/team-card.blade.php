<div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-duration="800">
  <div class="team12-boxarea">
    <div class="img1"><img src="{{ $m->photo_url }}" alt="{{ $m->name }}"></div>
    <div class="content-area">
      <div class="text">
        <a href="{{ route('team') }}">{{ $m->name }}</a>
        <div class="space12"></div>
        <p>{{ $m->position }}</p>
      </div>
    </div>
    <ul>
      @if ($m->facebook)<li><a href="{{ $m->facebook }}"><i class="fa-brands fa-facebook-f"></i></a></li>@endif
      @if ($m->twitter)<li><a href="{{ $m->twitter }}"><i class="fa-brands fa-twitter"></i></a></li>@endif
      @if ($m->instagram)<li><a href="{{ $m->instagram }}"><i class="fa-brands fa-instagram"></i></a></li>@endif
      @if ($m->linkedin)<li><a href="{{ $m->linkedin }}"><i class="fa-brands fa-linkedin-in"></i></a></li>@endif
    </ul>
  </div>
</div>
