{{-- Taille des images + lightbox (Magnific Popup, déjà chargé par le thème).
     À inclure dans les pages "show" : @include('partials.lightbox') --}}
@once
h

@push('scripts')
<script>
(function ($) {
    if (!$ || !$.fn.magnificPopup) return;

    // 1. Entoure automatiquement chaque image du contenu d'un lien lightbox
    $('.article-body img').each(function () {
        var $img = $(this), src = $img.attr('src');
        if (!src || $img.closest('a').length) return;
        $img.wrap($('<a class="js-lightbox"></a>').attr({ href: src, title: $img.attr('alt') || '' }));
    });

    // 2. Active la lightbox avec galerie (flèches + compteur) sur toute la page
    $('.js-lightbox').magnificPopup({
        type: 'image',
        gallery: { enabled: true, navigateByImgClick: true, tCounter: '%curr% / %total%' },
        closeOnContentClick: false,
        removalDelay: 200,
        tClose: 'إغلاق (Esc)',
        tLoading: 'جارٍ التحميل...',
        image: { tError: 'تعذّر تحميل الصورة.', titleSrc: 'title' }
    });
})(window.jQuery);
</script>
@endpush
@endonce
