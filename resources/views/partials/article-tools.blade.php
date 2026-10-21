{{-- Outils de lecture : taille du texte, lecture à voix haute, copier le lien, partage natif + barre de progression --}}
<div class="read-progress" aria-hidden="true"><span></span></div>

<div class="read-tools" data-read-tools role="toolbar" aria-label="أدوات القراءة">
  <button type="button" data-fs="-1" aria-label="تصغير الخط"><span>أ-</span></button>
  <button type="button" data-fs="1" aria-label="تكبير الخط"><span>أ+</span></button>
  <button type="button" data-listen aria-pressed="false"><i class="fa-solid fa-volume-high"></i> <span class="lbl">استمع</span></button>
  <button type="button" data-copy data-url="{{ request()->url() }}"><i class="fa-regular fa-copy"></i> <span class="lbl">نسخ الرابط</span></button>
  <button type="button" data-share hidden><i class="fa-solid fa-share-nodes"></i> <span class="lbl">مشاركة</span></button>
</div>

@once

@push('scripts')
<script>
(function () {
    var body = document.querySelector('.article-body');
    var tools = document.querySelector('[data-read-tools]');
    if (!body || !tools) return;

    /* ---------- Barre de progression de lecture ---------- */
    var bar = document.querySelector('.read-progress span'), ticking = false;
    function update() {
        var r = body.getBoundingClientRect();
        var p = (window.innerHeight * 0.3 - r.top) / r.height;
        bar.style.width = Math.round(Math.min(Math.max(p, 0), 1) * 100) + '%';
        ticking = false;
    }
    function onScroll() { if (!ticking) { ticking = true; requestAnimationFrame(update); } }
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    update();

    /* ---------- Taille du texte (mémorisée) ---------- */
    var KEY = 'np_font_scale', scale = 1, base = parseFloat(getComputedStyle(body).fontSize) || 16;
    try { scale = parseFloat(localStorage.getItem(KEY)) || 1; } catch (e) {}
    function applyScale() { body.style.fontSize = (base * scale) + 'px'; }
    applyScale();
    tools.querySelectorAll('[data-fs]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            scale = Math.round((scale + parseFloat(btn.getAttribute('data-fs')) * 0.1) * 100) / 100;
            scale = Math.min(1.6, Math.max(0.85, scale));
            applyScale();
            try { localStorage.setItem(KEY, scale); } catch (e) {}
        });
    });

    /* ---------- Copier le lien ---------- */
    var copyBtn = tools.querySelector('[data-copy]');
    copyBtn.addEventListener('click', function () {
        var url = copyBtn.getAttribute('data-url'), lbl = copyBtn.querySelector('.lbl');
        function done() {
            var old = lbl.textContent;
            lbl.textContent = 'تم النسخ ✓';
            setTimeout(function () { lbl.textContent = old; }, 2000);
        }
        function fallback() {
            var t = document.createElement('textarea');
            t.value = url; t.style.position = 'fixed'; t.style.opacity = '0';
            document.body.appendChild(t); t.select();
            try { document.execCommand('copy'); done(); } catch (e) {}
            document.body.removeChild(t);
        }
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(done).catch(fallback);
        } else { fallback(); }
    });

    /* ---------- Partage natif (mobile) ---------- */
    var shareBtn = tools.querySelector('[data-share]');
    if (navigator.share) {
        shareBtn.hidden = false;
        shareBtn.addEventListener('click', function () {
            navigator.share({ title: document.title, url: copyBtn.getAttribute('data-url') }).catch(function () {});
        });
    }

    /* ---------- Lecture à voix haute (synthèse vocale du navigateur) ---------- */
    var listenBtn = tools.querySelector('[data-listen]');
    if (!('speechSynthesis' in window)) { listenBtn.hidden = true; return; }

    var listenLbl = listenBtn.querySelector('.lbl'), speaking = false;

    // Masque le bouton si le navigateur n'a aucune voix arabe (évite un bouton qui ne fonctionne pas)
    function syncListenButton() {
        var voices = window.speechSynthesis.getVoices();
        var hasArabic = voices.some(function (v) { return /^ar/i.test(v.lang); });
        listenBtn.hidden = voices.length > 0 && !hasArabic;
    }
    syncListenButton();
    window.speechSynthesis.addEventListener('voiceschanged', syncListenButton);
    function stop() {
        window.speechSynthesis.cancel();
        speaking = false;
        listenBtn.setAttribute('aria-pressed', 'false');
        listenLbl.textContent = 'استمع';
    }
    function chunks(text) {
        var out = [], cur = '';
        text.split(/(?<=[.!؟?])\s+/).forEach(function (s) {
            if ((cur + ' ' + s).length > 200) { if (cur) out.push(cur); cur = s; }
            else { cur = cur ? cur + ' ' + s : s; }
        });
        if (cur) out.push(cur);
        var res = [];
        out.forEach(function (c) {          // phrases très longues sans ponctuation
            while (c.length > 220) {
                var i = c.lastIndexOf(' ', 200); if (i < 1) i = 200;
                res.push(c.slice(0, i)); c = c.slice(i + 1);
            }
            res.push(c);
        });
        return res;
    }
    listenBtn.addEventListener('click', function () {
        if (speaking) { stop(); return; }
        var voices = window.speechSynthesis.getVoices();
        var voice = voices.find(function (v) { return /^ar/i.test(v.lang); });
        if (!voice && voices.length) { alert('لا يتوفر صوت عربي في متصفحك أو جهازك.'); return; }

        var titleEl = document.querySelector('.blog-auhtor-sidebar-area h2');
        var text = ((titleEl ? titleEl.textContent : '') + '. ' + body.innerText).replace(/\s+/g, ' ');
        var parts = chunks(text);
        parts.forEach(function (c, i) {
            var u = new SpeechSynthesisUtterance(c);
            u.lang = 'ar'; u.rate = 0.95;
            if (voice) u.voice = voice;
            if (i === parts.length - 1) u.onend = stop;
            u.onerror = stop;
            window.speechSynthesis.speak(u);
        });
        speaking = true;
        listenBtn.setAttribute('aria-pressed', 'true');
        listenLbl.textContent = 'إيقاف';
    });
    window.addEventListener('beforeunload', function () { window.speechSynthesis.cancel(); });
})();
</script>
@endpush
@endonce
