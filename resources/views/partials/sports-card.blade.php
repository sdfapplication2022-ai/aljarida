@php
  $homeWin = $result->home_score > $result->away_score;
  $awayWin = $result->away_score > $result->home_score;
@endphp

<article class="sp-card">
  @if ($result->image_url)
    <div class="sp-cover">
      <img src="{{ $result->image_url }}" alt="{{ $result->home_team }} - {{ $result->away_team }}" loading="lazy">
    </div>
  @endif

  <div class="sp-body">
    <div class="sp-top">
      <span class="sp-comp">{{ $result->competition }}</span>
      <time class="sp-date" datetime="{{ $result->played_at->toAtomString() }}">
        {{ $result->played_at->locale('ar')->translatedFormat('d F Y · H:i') }}
      </time>
    </div>

    <div class="sp-match">
      <div class="sp-team {{ $homeWin ? 'is-winner' : '' }}">
        <div class="sp-logo">
          @if ($result->home_logo_url)
            <img src="{{ $result->home_logo_url }}" alt="{{ $result->home_team }}" loading="lazy">
          @else
            <i class="fa-solid fa-shield"></i>
          @endif
        </div>
        <strong>{{ $result->home_team }}</strong>
      </div>

      <div class="sp-score" dir="ltr">
        <span class="{{ $homeWin ? 'win' : '' }}">{{ $result->home_score }}</span>
        <small>:</small>
        <span class="{{ $awayWin ? 'win' : '' }}">{{ $result->away_score }}</span>
      </div>

      <div class="sp-team {{ $awayWin ? 'is-winner' : '' }}">
        <div class="sp-logo">
          @if ($result->away_logo_url)
            <img src="{{ $result->away_logo_url }}" alt="{{ $result->away_team }}" loading="lazy">
          @else
            <i class="fa-solid fa-shield"></i>
          @endif
        </div>
        <strong>{{ $result->away_team }}</strong>
      </div>
    </div>

    @if ($result->city || $result->venue)
      <p class="sp-place">
        <i class="fa-solid fa-location-dot"></i>
        {{ $result->venue }}@if ($result->venue && $result->city) · @endif{{ $result->city }}
      </p>
    @endif
  </div>
</article>
