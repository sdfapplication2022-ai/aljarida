{{-- Matchs du jour servis par l'API interne « api.matches », rechargés toutes les minutes.
     Variables optionnelles : $matches (array), $title (string), $limit (int|null), $emptyMessage (string). --}}
@php
    $matches = $matches ?? [];
    $title = $title ?? 'نتائج المباريات';
    $limit = $limit ?? null;
    $emptyMessage = $emptyMessage ?? 'لا توجد مباريات اليوم حاليًا.';
    $shown = $limit ? array_slice($matches, 0, (int) $limit) : $matches;
@endphp

<div class="mw-box" data-matches data-endpoint="{{ route('api.matches') }}" @if ($limit) data-limit="{{ (int) $limit }}" @endif>
  <div class="mw-head">
    <h3>{{ $title }}</h3>
    <span class="mw-count" data-matches-count>{{ count($shown) }} مباراة</span>
  </div>

  <p class="mw-empty" data-matches-empty @if (count($shown)) hidden @endif>{{ $emptyMessage }}</p>

  <ul class="mw-list" data-matches-list @if (! count($shown)) hidden @endif>
    @foreach ($shown as $m)
      @php($live = in_array($m['status'], ['1H', '2H', 'HT', 'ET', 'P', 'BT'], true))
      <li class="mw-match {{ $live ? 'is-live' : '' }}">
        <small class="mw-league">{{ $m['league'] }}</small>
        <div class="mw-row">
          <span class="mw-team">
            @if (! empty($m['home_logo']))<img src="{{ $m['home_logo'] }}" alt="" width="22" height="22" loading="lazy">@endif
            <span>{{ $m['home'] }}</span>
          </span>
          <strong class="mw-score">
            @if ($m['status'] === 'NS') {{ $m['time'] }}
            @else {{ $m['goals_h'] ?? 0 }} - {{ $m['goals_a'] ?? 0 }} @endif
          </strong>
          <span class="mw-team">
            @if (! empty($m['away_logo']))<img src="{{ $m['away_logo'] }}" alt="" width="22" height="22" loading="lazy">@endif
            <span>{{ $m['away'] }}</span>
          </span>
        </div>
        @if ($live) <span class="mw-live">مباشر {{ $m['elapsed'] }}'</span>
        @elseif ($m['status'] === 'FT') <span class="mw-meta">انتهت</span> @endif
      </li>
    @endforeach
  </ul>
</div>

@once
@push('styles')
<style>
  .mw-box { background: #fff; border-radius: 14px; padding: 20px; box-shadow: 0 6px 24px rgba(20, 48, 31, .08); margin-bottom: 20px; }
  .mw-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 14px; }
  .mw-head h3 { font-size: 20px; margin: 0; color: #14301f; }
  .mw-count { background: #e9f7d2; color: #14301f; font-size: 13px; font-weight: 700; padding: 4px 12px; border-radius: 999px; white-space: nowrap; }
  .mw-list { list-style: none; margin: 0; padding: 0; }
  .mw-list[hidden], .mw-empty[hidden] { display: none; }
  .mw-match { padding: 12px 0; border-bottom: 1px solid #eef1ef; }
  .mw-match:last-child { border-bottom: 0; }
  .mw-match.is-live { background: #f6fce9; border-radius: 10px; padding: 12px; margin-bottom: 6px; border-bottom: 0; }
  .mw-league { display: block; margin-bottom: 6px; font-size: 12px; color: #6b7570; }
  .mw-row { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 10px; }
  .mw-team { display: flex; align-items: center; gap: 6px; min-width: 0; font-size: 14px; color: #3d544d; }
  .mw-team img { width: 22px; height: 22px; object-fit: contain; flex: none; }
  .mw-team > span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .mw-score { min-width: 56px; text-align: center; font-size: 17px; font-weight: 800; color: #14301f; white-space: nowrap; }
  .mw-live { display: inline-block; margin-top: 6px; background: #d90429; color: #fff; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 999px; }
  .mw-meta { display: inline-block; margin-top: 6px; font-size: 12px; color: #6b7570; }
  .mw-empty { margin: 0; padding: 18px 0; text-align: center; font-size: 14px; color: #6b7570; }
  @media (max-width: 575px) { .mw-team { font-size: 13px; } .mw-score { min-width: 46px; font-size: 15px; } }
</style>
@endpush
@endonce

@once
@push('scripts')
<script>
(function () {
    var boxes = document.querySelectorAll('[data-matches]');
    if (!boxes.length) { return; }

    var LIVE = ['1H', '2H', 'HT', 'ET', 'P', 'BT'];

    function teamNode(name, logo) {
        var team = document.createElement('span');
        team.className = 'mw-team';
        if (logo) {
            var img = document.createElement('img');
            img.src = logo;
            img.alt = '';
            img.width = 22;
            img.height = 22;
            img.loading = 'lazy';
            team.appendChild(img);
        }
        var label = document.createElement('span');
        label.textContent = name || '';
        team.appendChild(label);
        return team;
    }

    function statusNode(match, live) {
        var node = document.createElement('span');
        if (live) {
            node.className = 'mw-live';
            node.textContent = 'مباشر' + (match.elapsed != null ? ' ' + match.elapsed + "'" : '');
        } else if (match.status === 'FT' || match.status === 'AET' || match.status === 'PEN') {
            node.className = 'mw-meta';
            node.textContent = 'انتهت';
        } else if (match.status === 'PST') {
            node.className = 'mw-meta';
            node.textContent = 'مؤجلة';
        } else if (match.status === 'CANC') {
            node.className = 'mw-meta';
            node.textContent = 'ملغاة';
        } else {
            return null;
        }
        return node;
    }

    function renderItem(match) {
        var live = LIVE.indexOf(match.status) !== -1;
        var item = document.createElement('li');
        item.className = 'mw-match' + (live ? ' is-live' : '');

        var league = document.createElement('small');
        league.className = 'mw-league';
        league.textContent = match.league || '';
        item.appendChild(league);

        var row = document.createElement('div');
        row.className = 'mw-row';
        row.appendChild(teamNode(match.home, match.home_logo));

        var score = document.createElement('strong');
        score.className = 'mw-score';
        score.textContent = match.status === 'NS'
            ? (match.time || '')
            : ((match.goals_h == null ? 0 : match.goals_h) + ' - ' + (match.goals_a == null ? 0 : match.goals_a));
        row.appendChild(score);

        row.appendChild(teamNode(match.away, match.away_logo));
        item.appendChild(row);

        var status = statusNode(match, live);
        if (status) { item.appendChild(status); }

        return item;
    }

    function render(box, matches) {
        var list = box.querySelector('[data-matches-list]');
        var limit = parseInt(box.getAttribute('data-limit'), 10) || 0;
        var shown = limit > 0 ? matches.slice(0, limit) : matches;

        list.innerHTML = '';
        shown.forEach(function (match) { list.appendChild(renderItem(match)); });

        var count = box.querySelector('[data-matches-count]');
        if (count) { count.textContent = shown.length + ' مباراة'; }

        var empty = box.querySelector('[data-matches-empty]');
        if (empty) { empty.hidden = shown.length > 0; }
        list.hidden = shown.length === 0;
    }

    function refresh(box) {
        fetch(box.getAttribute('data-endpoint'), { headers: { 'Accept': 'application/json' } })
            .then(function (response) { return response.json(); })
            .then(function (matches) { if (Array.isArray(matches)) { render(box, matches); } })
            .catch(function () {});
    }

    setInterval(function () {
        if (document.hidden) { return; }
        Array.prototype.forEach.call(boxes, refresh);
    }, 60000);
})();
</script>
@endpush
@endonce
