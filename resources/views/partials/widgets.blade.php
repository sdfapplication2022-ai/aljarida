{{-- Sondage + météo + prière + change (colonne latérale). Données : $poll, $pollVote, $widgets --}}
@php
    $w = $widgets['weather'] ?? null;
    $p = $widgets['prayer'] ?? null;
    $r = $widgets['rates'] ?? null;
@endphp

@once
@push('styles')
<style>
    .np-muted { color: var(--np-muted, #6b7570); font-size: 13px; }
    .np-muted a { color: inherit; text-decoration: underline; }
    .np-box h3 small { font-size: 13px; font-weight: 400; color: var(--np-muted, #6b7570); }

    /* Sondage */
    .np-flash { background: #eef7dc; border-radius: 8px; padding: 8px 12px; margin-bottom: 10px; font-size: 14px; }
    .np-poll-q { font-weight: 700; line-height: 1.7; margin-bottom: 14px; }
    .np-opt { display: flex; align-items: center; gap: 10px; padding: 10px 14px; margin-bottom: 8px; border: 1.5px solid #dfe6e2; border-radius: 10px; cursor: pointer; transition: border-color .15s, background .15s; }
    .np-opt:hover { border-color: var(--np-accent, #7ac70c); }
    .np-opt:has(input:checked) { border-color: var(--np-accent, #7ac70c); background: #f3fae6; }
    .np-opt input { accent-color: #5aa000; }
    .np-vote-btn { width: 100%; height: 46px; margin-top: 6px; border: 0; border-radius: 10px; background: var(--np-lime, #e0ff4f); color: var(--np-ink, #14301f); font-weight: 700; cursor: pointer; }
    .np-vote-btn:hover { filter: brightness(.95); }
    .np-err { color: #dc3545; font-size: 13px; margin-bottom: 6px; }
    .np-res { margin-bottom: 12px; }
    .np-res-line { display: flex; justify-content: space-between; gap: 10px; font-size: 14px; margin-bottom: 4px; }
    .np-bar { height: 10px; background: #eef1ef; border-radius: 999px; overflow: hidden; }
    .np-bar span { display: block; height: 100%; background: #b9d98a; border-radius: 999px; }
    .np-res.is-mine .np-bar span { background: var(--np-accent, #7ac70c); }
    .np-res.is-mine .np-res-line span::after { content: " ✓"; color: #5aa000; }

    /* Météo */
    .np-w-main { display: flex; align-items: center; gap: 16px; margin: 6px 0 10px; }
    .np-w-main > i { font-size: 44px; color: #f0a500; }
    .np-w-temp { font-size: 40px; font-weight: 800; line-height: 1; color: var(--np-ink, #14301f); }
    .np-w-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 6px 12px; font-size: 14px; }

    /* Prière */
    .np-times { list-style: none; margin: 10px 0 0; padding: 0; display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; }
    .np-times li { display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px 4px; border-radius: 8px; background: #f4f7f5; font-size: 13px; }
    .np-times li strong { font-size: 15px; }
    .np-times li.is-next { background: var(--np-ink, #14301f); color: #fff; }
    .np-times li.is-next strong { color: var(--np-lime, #e0ff4f); }
    .np-next { margin-top: 12px; font-size: 14px; }

    /* Change */
    .np-rates { list-style: none; margin: 8px 0 12px; padding: 0; }
    .np-rates li { display: flex; justify-content: space-between; gap: 10px; padding: 9px 0; border-bottom: 1px solid #eef1ef; font-size: 14px; }
    .np-rates li:last-child { border-bottom: 0; }
    .np-rates small { color: var(--np-muted, #6b7570); }
</style>
@endpush

@push('scripts')
<script>
(function () {
    var box = document.querySelector('[data-next-prayer]');
    if (!box) return;
    var at = new Date(box.getAttribute('data-at')).getTime();
    var out = box.querySelector('.np-countdown');
    function tick() {
        var d = at - Date.now();
        if (d <= 0) { out.textContent = 'حان الآن'; return; }
        var h = Math.floor(d / 3600000), m = Math.floor((d % 3600000) / 60000);
        out.textContent = (h ? h + ' س ' : '') + m + ' د';
    }
    tick();
    setInterval(tick, 30000);
})();
</script>
@endpush
@endonce

{{-- ===== استطلاع الرأي ===== --}}
@if (! empty($poll))
  @php($total = (int) $poll->options->sum('votes_count'))
  <div class="np-box" id="poll">
    <h3>استطلاع الرأي</h3>
    @if (session('poll_message'))<div class="np-flash">{{ session('poll_message') }}</div>@endif
    <p class="np-poll-q">{{ $poll->question }}</p>

    @if (! empty($pollVote) || ! $poll->isOpen())
      @foreach ($poll->options as $o)
        @php($pct = $total ? (int) round($o->votes_count * 100 / $total) : 0)
        <div class="np-res @if (! empty($pollVote) && $pollVote->poll_option_id === $o->id) is-mine @endif">
          <div class="np-res-line"><span>{{ $o->label }}</span><strong>{{ $pct }}%</strong></div>
          <div class="np-bar"><span style="width: {{ $pct }}%"></span></div>
        </div>
      @endforeach
      <span class="np-muted">{{ number_format($total) }} صوت @if (! empty($pollVote)) · شكرًا لمشاركتك @endif</span>
    @else
      <form action="{{ route('poll.vote', $poll) }}" method="POST">
        @csrf
        <div style="position:absolute;left:-9999px" aria-hidden="true">
          <input type="text" name="website" tabindex="-1" autocomplete="off">
        </div>
        @foreach ($poll->options as $o)
          <label class="np-opt"><input type="radio" name="option_id" value="{{ $o->id }}" required> <span>{{ $o->label }}</span></label>
        @endforeach
        @error('option_id')<div class="np-err">{{ $message }}</div>@enderror
        @error('website')<div class="np-err">{{ $message }}</div>@enderror
        <button type="submit" class="np-vote-btn">صوّت</button>
      </form>
    @endif
  </div>
@endif

{{-- ===== الطقس ===== --}}
@if ($w)
  <div class="np-box">
    <h3>الطقس في تونس</h3>
    <div class="np-w-main">
      <i class="fa-solid {{ $w['icon'] }}"></i>
      <div>
        <div class="np-w-temp" dir="ltr">{{ $w['temp'] }}°</div>
        <div>{{ $w['label'] }}</div>
      </div>
    </div>
    <div class="np-w-meta">
      <span>العظمى: <b dir="ltr">{{ $w['max'] }}°</b></span>
      <span>الصغرى: <b dir="ltr">{{ $w['min'] }}°</b></span>
      <span>الرطوبة: <b>{{ $w['humidity'] }}%</b></span>
      <span>الرياح: <b>{{ $w['wind'] }}</b> كم/س</span>
    </div>
    <div class="np-muted" style="margin-top:10px"><a href="https://open-meteo.com/" target="_blank" rel="noopener">Weather data by Open-Meteo.com</a></div>
  </div>
@endif

{{-- ===== مواقيت الصلاة ===== --}}
@if ($p)
  <div class="np-box" data-next-prayer data-at="{{ $p['next']['at'] }}">
    <h3>مواقيت الصلاة <small>· تونس</small></h3>
    @if ($p['hijri'])<div class="np-muted">{{ $p['hijri'] }}</div>@endif
    <ul class="np-times">
      @foreach ($p['times'] as $k => $t)
        <li class="@if ($k === $p['next']['key']) is-next @endif"><span>{{ $p['labels'][$k] }}</span><strong dir="ltr">{{ $t }}</strong></li>
      @endforeach
    </ul>
    <div class="np-next">الصلاة القادمة: <b>{{ $p['labels'][$p['next']['key']] }}</b> بعد <span class="np-countdown">…</span></div>
  </div>
@endif

{{-- ===== أسعار الصرف ===== --}}
@if ($r)
  <div class="np-box">
    <h3>الدينار التونسي</h3>
    <ul class="np-rates">
      @foreach ($r['rows'] as $row)
        <li><span>1 {{ $row['name'] }} <small>({{ $row['code'] }})</small></span><strong>{{ number_format($row['value'], 3) }} د.ت</strong></li>
      @endforeach
    </ul>
    <span class="np-muted">أسعار مرجعية بتاريخ {{ $r['updated'] }}، وقد تختلف عن أسعار البنوك. <a href="https://www.exchangerate-api.com" target="_blank" rel="noopener">Rates By Exchange Rate API</a></span>
  </div>
@endif
