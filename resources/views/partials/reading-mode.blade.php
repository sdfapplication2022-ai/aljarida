<div class="reader-mode-tools">
  <button type="button" class="header-btn20" data-reading-mode aria-pressed="false">
    <i class="fa-solid fa-book-open" aria-hidden="true"></i>
    <span>وضع القراءة</span>
  </button>
</div>

<style>
body.reader-mode .homepage12-body,
body.reader-mode .mobile-header,
body.reader-mode .mobile-sidebar,
body.reader-mode .footer12-section-area,
body.reader-mode .blog-auhtor-section-area aside,
body.reader-mode .blog-auhtor-section-area .social-tags,
body.reader-mode .blog-auhtor-section-area .like-form,
body.reader-mode .blog-auhtor-section-area .contact-form-area,
body.reader-mode .blog1-scetion-area { display: none !important; }
body.reader-mode .blog-auhtor-section-area .row > .col-lg-8 { width: 100%; max-width: 900px; margin-inline: auto; }
body.reader-mode .reader-mode-tools { position: sticky; top: 0; z-index: 20; background: #fff; padding: 12px; box-shadow: 0 2px 12px #0002; }
body.reader-mode .blog-auhtor-section-area .article-body { font-size: 1.25em; line-height: 2; }
</style>

@once
@push('scripts')
<script>
(function () {
    var button = document.querySelector('[data-reading-mode]');
    if (!button) return;

    var key = 'np_reader_mode';
    function setMode(enabled) {
        document.body.classList.toggle('reader-mode', enabled);
        button.setAttribute('aria-pressed', enabled ? 'true' : 'false');
        button.querySelector('span').textContent = enabled ? 'إنهاء القراءة' : 'وضع القراءة';
        try { localStorage.setItem(key, enabled ? '1' : '0'); } catch (error) {}
    }

    var enabled = false;
    try { enabled = localStorage.getItem(key) === '1'; } catch (error) {}
    setMode(enabled);

    button.addEventListener('click', function () {
        setMode(!document.body.classList.contains('reader-mode'));
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && document.body.classList.contains('reader-mode')) setMode(false);
    });
})();
</script>
@endpush
@endonce
