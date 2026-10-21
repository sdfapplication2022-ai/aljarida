{{-- Sommaire généré à partir des h2/h3 du .article-body — n'apparaît que si ≥ 3 titres --}}
<details class="toc" data-toc hidden>
    <summary><i class="fa-solid fa-list-ul" aria-hidden="true"></i> محتويات المقال</summary>
    <ol></ol>
</details>

@once
@push('styles')
<style>
    .toc { margin: 0 0 20px; padding: 12px 16px; background: #fff; border: 1px solid #e3e8e5; border-radius: 12px; }
    .toc summary { display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700; color: #14301f; list-style: none; }
    .toc summary::-webkit-details-marker { display: none; }
    .toc summary::after { content: "\f107"; font-family: "Font Awesome 6 Free", sans-serif; font-weight: 900; margin-right: auto; transition: transform .2s; }
    .toc[open] summary::after { transform: rotate(180deg); }
    .toc ol { list-style: none; margin: 12px 0 0; padding: 0; counter-reset: toc; }
    .toc li { counter-increment: toc; padding: 6px 0; border-top: 1px solid #f0f3f1; }
    .toc li::before { content: counter(toc) ". "; color: #7ac70c; font-weight: 700; }
    .toc a { color: #14301f; text-decoration: none; }
    .toc a:hover, .toc a.is-active { color: #5aa000; }
    .toc li.toc-l2 { padding-right: 22px; font-size: 14px; }
    @media (max-width: 767px) { .toc ol { max-height: 40vh; overflow-y: auto; } }
</style>
@endpush

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