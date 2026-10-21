{{-- Sommaire généré à partir des h2/h3 du .article-body — n'apparaît que si ≥ 3 titres --}}
<details class="toc" data-toc hidden>
    <summary><i class="fa-solid fa-list-ul" aria-hidden="true"></i> محتويات المقال</summary>
    <ol></ol>
</details>

@once

@push('scripts')
<script>
(function () {
    var body = document.querySelector('.article-body');
    var toc  = document.querySelector('[data-toc]');
    if (!body || !toc) return;

    var heads = body.querySelectorAll('h2, h3');
    if (heads.length < 3) return;

    var list = toc.querySelector('ol');
    heads.forEach(function (h, i) {
        if (!h.id) h.id = 'sec-' + i;
        var li = document.createElement('li');
        li.className = h.tagName === 'H3' ? 'toc-l2' : 'toc-l1';
        var a = document.createElement('a');
        a.href = '#' + h.id;
        a.textContent = h.textContent.trim();
        li.appendChild(a);
        list.appendChild(li);
    });

    toc.hidden = false;
    toc.open = window.matchMedia('(min-width: 768px)').matches;

    if ('IntersectionObserver' in window) {
        var links = toc.querySelectorAll('a');
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (!en.isIntersecting) return;
                links.forEach(function (l) {
                    l.classList.toggle('is-active', l.hash === '#' + en.target.id);
                });
            });
        }, { rootMargin: '-25% 0px -65% 0px' });
        heads.forEach(function (h) { io.observe(h); });
    }
})();
</script>
@endpush
@endonce
