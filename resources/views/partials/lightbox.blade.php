{{-- Taille des images + lightbox (Magnific Popup, déjà chargé par le thème).
     À inclure dans les pages "show" : @include('partials.lightbox') --}}
@once
@push('styles')
<style>
    /* ====== RÉGLAGES DES TAILLES : modifiez ces 4 valeurs ====== */
    :root {
        --show-hero-height: 460px;      /* hauteur de l'image principale (desktop) */
        --show-hero-height-sm: 240px;   /* hauteur de l'image principale (mobile) */
        --body-img-max-width: 760px;    /* largeur max des images dans le texte */
        --body-img-max-height: 520px;   /* hauteur max des images dans le texte */
    }

    /* Image principale (article / blog) */
    .show-hero { position: relative; isolation: isolate; display: block; overflow: hidden; border-radius: 12px; cursor: zoom-in; }
    .show-hero::before { content: ""; position: absolute; inset: -20px; z-index: -1; background: var(--bg, none) center / cover no-repeat; filter: blur(20px) brightness(.8); }
    .show-hero img {
        display: block; width: 100%; height: var(--show-hero-height);
        object-fit: contain; transition: transform .4s ease;
    }
    .show-hero:hover img { transform: scale(1.03); }
    @media (max-width: 767px) { .show-hero img { height: var(--show-hero-height-sm); } }

    /* Images à l'intérieur du contenu (éditeur Jodit) */
    .article-body img {
        display: block; margin: 1.5rem auto; border-radius: 8px; cursor: zoom-in;
        width: auto !important; height: auto !important;
        max-width: min(100%, var(--body-img-max-width)) !important;
        max-height: var(--body-img-max-height);
        object-fit: contain;
    }
    .article-body a.js-lightbox { display: block; }

    /* La lightbox doit passer au-dessus du header sticky du thème */
    .mfp-bg { z-index: 100042; }
    .mfp-wrap { z-index: 100043; }
</style>
@endpush

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
