{{-- Recherche avec suggestions instantanées. À inclure dans le header : @include('partials.search-form') --}}
@once
<style>
    [data-live-search] { position: relative; }
    .ls-box { position: absolute; top: 100%; right: 0; left: 0; z-index: 100060; margin-top: 6px; background: #fff; border: 1px solid #e3e8e5; border-radius: 12px; box-shadow: 0 12px 32px rgba(20, 48, 31, .15); overflow: hidden; }
    .ls-box[hidden] { display: none; }
    .ls-box a { display: block; padding: 10px 14px; color: #14301f; text-decoration: none; border-bottom: 1px solid #f0f3f1; font-size: 14px; line-height: 1.6; }
    .ls-box a:hover, .ls-box a:focus { background: #f3fae6; }
    .ls-box small { display: block; color: #6b7570; font-size: 12px; }
    .ls-box a.ls-all { background: #14301f; color: #fff; text-align: center; border: 0; }
    .ls-empty { padding: 12px 14px; color: #6b7570; font-size: 14px; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-live-search]').forEach(function (form) {
        var input = form.querySelector('input[name="q"]');
        var box = form.querySelector('.ls-box');
        var timer, ctrl;

        function render(items, q) {
            box.textContent = '';
            if (!items.length) {
                var e = document.createElement('div');
                e.className = 'ls-empty';
                e.textContent = 'لا توجد نتائج مطابقة';
                box.appendChild(e);
            }
            items.forEach(function (it) {
                var a = document.createElement('a');
                a.href = it.url;
                a.textContent = it.title;
                if (it.category) {
                    var s = document.createElement('small');
                    s.textContent = it.category;
                    a.appendChild(s);
                }
                box.appendChild(a);
            });
            var all = document.createElement('a');
            all.className = 'ls-all';
            all.href = form.getAttribute('action') + '?q=' + encodeURIComponent(q);
            all.textContent = 'عرض كل النتائج';
            box.appendChild(all);
            box.hidden = false;
        }

        input.addEventListener('input', function () {
            clearTimeout(timer);
            var q = input.value.trim();
            if (q.length < 2) { box.hidden = true; return; }
            timer = setTimeout(function () {
                if (ctrl) ctrl.abort();
                ctrl = new AbortController();
                fetch(form.getAttribute('data-suggest') + '?q=' + encodeURIComponent(q), {
                    signal: ctrl.signal, headers: { 'Accept': 'application/json' }
                })
                .then(function (r) { return r.json(); })
                .then(function (items) { render(items, q); })
                .catch(function () {});
            }, 250);
        });

        input.addEventListener('keydown', function (e) { if (e.key === 'Escape') box.hidden = true; });
        document.addEventListener('click', function (e) { if (!form.contains(e.target)) box.hidden = true; });
    });
});
</script>
@endonce

<form action="{{ route('search') }}" method="GET" role="search" class="d-flex gap-2 mb-3"
      data-live-search data-suggest="{{ route('search.suggest') }}">
    <input type="search" name="q" value="{{ $q ?? request('q') }}" maxlength="100" autocomplete="off"
           class="form-control" placeholder="ابحث في المقالات..." aria-label="بحث" required>
    <button type="submit" class="header-btn20" aria-label="بحث"><i class="fa-solid fa-magnifying-glass"></i></button>
    <div class="ls-box" hidden></div>
</form>
