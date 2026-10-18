{{-- Styles partagés du design « journal » (accueil + articles). --}}
@once
@push('styles')
<style>
    :root {
        --np-ink: #14301f; --np-muted: #6b7570; --np-accent: #7ac70c; --np-lime: #e0ff4f;
        --np-red: #d90429; --np-radius: 14px;
        --np-top: 120px;   /* espace sous le header du thème : augmentez si le header chevauche le contenu */
        --np-shadow: 0 6px 24px rgba(20, 48, 31, .08);
    }
    .np-wrap { padding: var(--np-top) 0 48px; }
    .np-container { max-width: 1200px; margin: 0 auto; padding: 0 16px; }

    /* ---- Bandeau عاجل ---- */
    .np-ticker { display: flex; align-items: stretch; background: var(--np-ink); color: #fff; border-radius: 10px; overflow: hidden; margin-bottom: 24px; }
    .np-ticker-label { background: var(--np-red); padding: 10px 18px; font-weight: 700; white-space: nowrap; display: flex; align-items: center; gap: 8px; }
    .np-ticker-track { flex: 1; overflow: hidden; display: flex; align-items: center; }
    .np-ticker-move { display: flex; flex: none; width: max-content; animation: np-scroll 45s linear infinite; }
    .np-ticker-track:hover .np-ticker-move { animation-play-state: paused; }
    .np-ticker a { color: #fff; text-decoration: none; padding: 0 12px; white-space: nowrap; }
    .np-ticker a:hover { color: var(--np-lime); }
    .np-dot { color: var(--np-accent); }
    @keyframes np-scroll { to { transform: translateX(50%); } }
    @media (prefers-reduced-motion: reduce) { .np-ticker-move { animation: none; } }

    /* ---- Éléments communs ---- */
    .np-badge { position: relative; z-index: 2; display: inline-block; background: var(--np-lime); color: var(--np-ink); font-size: 12px; font-weight: 700; padding: 3px 12px; border-radius: 999px; text-decoration: none; }
    .np-meta { display: flex; flex-wrap: wrap; gap: 14px; font-size: 13px; color: var(--np-muted); margin-top: 8px; }
    .np-meta i { margin-left: 4px; }
    .np-thumb { display: block; overflow: hidden; background: var(--np-ink); }
    .np-thumb img { display: block; width: 100%; height: 100%; object-fit: contain; transition: transform .5s ease; }
    .np-noimg { display: block; width: 100%; height: 100%; min-height: 60px; background: linear-gradient(135deg, #14301f, #2f7d5b); }
    .np-clamp2, .np-clamp3 { display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden; }
    .np-clamp2 { -webkit-line-clamp: 2; }
    .np-clamp3 { -webkit-line-clamp: 3; }
    .np-title a { color: var(--np-ink); text-decoration: none; }
    .np-title a:hover { color: var(--np-accent); }
    /* lien étiré : toute la carte est cliquable */
    .np-title a::after { content: ""; position: absolute; inset: 0; z-index: 1; }

    .np-section-title { display: flex; align-items: center; justify-content: space-between; margin: 44px 0 18px; padding-bottom: 10px; border-bottom: 2px solid #e3e8e5; }
    .np-section-title h2 { position: relative; margin: 0; padding-right: 16px; font-size: 24px; color: var(--np-ink); }
    .np-section-title h2::before { content: ""; position: absolute; right: 0; top: 50%; transform: translateY(-50%); width: 5px; height: 70%; background: var(--np-accent); border-radius: 3px; }
    .np-section-title a { color: var(--np-ink); font-size: 14px; text-decoration: none; }
    .np-section-title a:hover { color: var(--np-accent); }

    /* ---- À la une ---- */
    .np-hero { display: grid; grid-template-columns: 2fr 1fr; gap: 16px; }
    .np-lead { position: relative; display: flex; align-items: flex-end; min-height: 480px; border-radius: var(--np-radius); overflow: hidden; background: var(--np-ink); }
    .np-lead > img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain; transition: transform .5s ease; }
    .np-lead:hover > img { transform: scale(1.04); }
    .np-lead::before { content: ""; position: absolute; inset: 0; z-index: 1; background: linear-gradient(to top, rgba(0,0,0,.88), rgba(0,0,0,.05) 70%); }
    .np-lead-body { z-index: 2; padding: 28px; color: #fff; width: 100%; }
    .np-lead h2 { font-size: 30px; line-height: 1.5; margin: 12px 0 8px; }
    .np-lead h2 a { color: #fff; }
    .np-lead h2 a:hover { color: var(--np-lime); }
    .np-lead p { color: rgba(255,255,255,.85); margin: 0; }
    .np-lead .np-meta { color: rgba(255,255,255,.75); }

    .np-side { display: grid; grid-template-rows: repeat(4, 1fr); gap: 12px; }
    .np-side-item { position: relative; display: flex; align-items: center; gap: 12px; background: #fff; border-radius: 12px; padding: 10px; box-shadow: var(--np-shadow); }
    .np-side-item .np-thumb { width: 104px; height: 78px; flex: none; border-radius: 8px; }
    .np-side-item h3 { font-size: 15px; line-height: 1.6; margin: 6px 0 0; }
    .np-side-item:hover .np-thumb img { transform: scale(1.08); }

    /* ---- Dernières actualités + colonne latérale ---- */
    .np-main { display: grid; grid-template-columns: 2fr 1fr; gap: 28px; align-items: start; }
    .np-row { position: relative; display: flex; gap: 16px; background: #fff; border-radius: var(--np-radius); padding: 14px; margin-bottom: 14px; box-shadow: var(--np-shadow); }
    .np-row .np-thumb { width: 230px; aspect-ratio: 16 / 10; flex: none; border-radius: 10px; }
    .np-row h3 { font-size: 19px; line-height: 1.6; margin: 8px 0 6px; }
    .np-row p { margin: 0; color: var(--np-muted); font-size: 14px; }
    .np-row:hover .np-thumb img { transform: scale(1.06); }

    .np-box { background: #fff; border-radius: var(--np-radius); padding: 20px; box-shadow: var(--np-shadow); margin-bottom: 20px; }
    .np-box h3 { font-size: 20px; margin: 0 0 8px; color: var(--np-ink); }
    .np-top-list { list-style: none; margin: 0; padding: 0; counter-reset: n; }
    .np-top-list li { position: relative; display: flex; gap: 14px; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #eef1ef; }
    .np-top-list li:last-child { border-bottom: 0; }
    .np-top-list li::before { counter-increment: n; content: counter(n); min-width: 30px; font-size: 30px; font-weight: 800; line-height: 1; color: var(--np-accent); }
    .np-top-list h4 { font-size: 15px; line-height: 1.7; margin: 0; }
    .np-cats { list-style: none; margin: 0; padding: 0; display: flex; flex-wrap: wrap; gap: 8px; }
    .np-cats a { display: block; padding: 6px 14px; border-radius: 999px; background: #eef4ea; color: var(--np-ink); font-size: 14px; text-decoration: none; }
    .np-cats a:hover { background: var(--np-ink); color: #fff; }

    /* ---- Sections par rubrique ---- */
    .np-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .np-card { position: relative; background: #fff; border-radius: var(--np-radius); overflow: hidden; box-shadow: var(--np-shadow); transition: transform .2s ease; }
    .np-card:hover { transform: translateY(-4px); }
    .np-card:hover .np-thumb img { transform: scale(1.06); }
    .np-card .np-thumb { aspect-ratio: 16 / 10; }
    .np-card-body { padding: 14px; }
    .np-card h3 { font-size: 16px; line-height: 1.7; margin: 0; }

    /* ---- Bandeau de widgets ---- */
    .np-widgets { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; align-items: stretch; margin-top: 28px; }
    .np-widgets .np-box { margin: 0; }

    /* ---- Sections par rubrique : 2 colonnes ---- */
    .np-sections { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0 32px; }
    .np-sec { min-width: 0; }
    .np-sec .np-card .np-thumb { aspect-ratio: 16 / 9; }
    .np-sec .np-card h3 { font-size: 19px; }
    .np-mini { position: relative; display: flex; align-items: center; gap: 12px; margin-top: 10px; padding: 10px; background: #fff; border-radius: 12px; box-shadow: var(--np-shadow); }
    .np-mini .np-thumb { width: 96px; height: 72px; flex: none; border-radius: 8px; }
    .np-mini h4 { font-size: 15px; line-height: 1.6; margin: 0; }
    .np-mini:hover .np-thumb img { transform: scale(1.08); }
    @media (max-width: 991px) { .np-sections { grid-template-columns: 1fr; } }

    .np-empty { text-align: center; padding: 60px 0; color: var(--np-muted); }

    @media (max-width: 991px) {
        .np-hero, .np-main { grid-template-columns: 1fr; }
        .np-lead { min-height: 360px; }
        .np-lead h2 { font-size: 24px; }
        .np-side { grid-template-rows: none; }
        .np-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 575px) {
        .np-wrap { padding-top: 96px; }
        .np-row { flex-direction: column; }
        .np-row .np-thumb { width: 100%; }
        .np-grid { grid-template-columns: 1fr; }
        .np-lead { min-height: 300px; }
    }

    /* ---- Affiches entières : image complète sur un fond flou de la même image ---- */
    .np-thumb { position: relative; isolation: isolate; }
    .np-thumb::before { content: ""; position: absolute; inset: -16px; z-index: -1; background: var(--bg, none) center / cover no-repeat; filter: blur(16px) brightness(.85); }
    .np-lead { isolation: isolate; }
    .np-lead::after { content: ""; position: absolute; inset: -30px; z-index: -1; background: var(--bg, none) center / cover no-repeat; filter: blur(24px) brightness(.75); }
</style>
@endpush
@endonce
