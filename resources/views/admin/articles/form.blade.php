@extends('layouts.admin')

@section('title',   $item->exists ? 'تعديل مقال' : 'مقال جديد')
@section('heading', $item->exists ? 'تعديل المقال' : 'إضافة مقال جديد')

@section('content')

<form id="article-form" method="POST" enctype="multipart/form-data"
      action="{{ $item->exists
          ? route('admin.crud.update', [$cfg['key'], $item->id])
          : route('admin.crud.store', $cfg['key']) }}">
    @csrf
    @if ($item->exists) @method('PUT') @endif

    <div class="row g-4">

        {{-- ============================================================ --}}
        {{-- COLONNE PRINCIPALE --}}
        {{-- ============================================================ --}}
        <div class="col-lg-8">

            {{-- ---------- TITRE + SLUG ---------- --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <label class="form-label fw-bold text-muted small text-uppercase mb-2">
                        عنوان المقال <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="title" id="article-title"
                           value="{{ old('title', $item->title) }}" required
                           placeholder="اكتب عنواناً جذاباً…"
                           class="form-control form-control-lg border-0 border-bottom rounded-0 px-0 fs-3 fw-bold"
                           style="border-bottom:2px solid #e9ecef!important; box-shadow:none;">

                    <div class="d-flex align-items-center gap-2 mt-3 small text-muted">
                        <i class="fa-solid fa-link"></i>
                        <span>{{ url('/article') }}/</span>
                        <input type="text" name="slug" id="article-slug"
                               value="{{ old('slug', $item->slug) }}"
                               placeholder="slug-automatique"
                               class="form-control form-control-sm border-0 bg-transparent p-0"
                               style="box-shadow:none;">
                        <button type="button" id="regen-slug"
                                class="btn btn-link btn-sm text-decoration-none p-0 ms-auto">
                            <i class="fa-solid fa-rotate"></i> إعادة توليد
                        </button>
                    </div>
                </div>
            </div>

            {{-- ---------- CONTENU (Jodit) ---------- --}}
            <div class="card mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">
                        <i class="fa-solid fa-pen-nib" style="color:var(--brand)"></i> المحتوى
                    </h6>
                    <div class="small text-muted d-flex gap-3">
                        <span><i class="fa-solid fa-align-left"></i> <b id="word-count">0</b> كلمة</span>
                        <span><i class="fa-regular fa-clock"></i> <b id="reading-time">0</b> دقيقة</span>
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    <textarea id="content-editor" name="content" rows="20"
                              class="form-control">{{ old('content', $item->content) }}</textarea>
                </div>
            </div>

            {{-- ---------- EXTRAIT ---------- --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-2">
                        <label class="form-label fw-bold text-muted small text-uppercase mb-0">
                            مقتطف / ملخص
                        </label>
                        <small class="text-muted">
                            <span id="excerpt-count">0</span> / 500
                        </small>
                    </div>
                    <textarea name="excerpt" id="excerpt-field" rows="3" maxlength="500"
                              placeholder="ملخص قصير يظهر في القوائم ونتائج البحث…"
                              class="form-control">{{ old('excerpt', $item->excerpt) }}</textarea>
                </div>
            </div>

            {{-- ---------- SEO ---------- --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">
                        <i class="fa-solid fa-magnifying-glass-chart" style="color:var(--brand)"></i>
                        تحسين محركات البحث (SEO)
                    </h6>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <label class="form-label fw-bold text-muted small text-uppercase mb-0">
                                عنوان SEO
                            </label>
                            <small class="text-muted"><span id="mt-count">0</span> / 60</small>
                        </div>
                        <input type="text" name="meta_title" id="meta-title" maxlength="70"
                               value="{{ old('meta_title', $item->meta_title) }}"
                               placeholder="العنوان كما سيظهر في Google…"
                               class="form-control">
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <label class="form-label fw-bold text-muted small text-uppercase mb-0">
                                وصف SEO
                            </label>
                            <small class="text-muted"><span id="md-count">0</span> / 160</small>
                        </div>
                        <textarea name="meta_description" id="meta-desc" rows="2" maxlength="170"
                                  placeholder="الوصف الظاهر في نتائج البحث…"
                                  class="form-control">{{ old('meta_description', $item->meta_description) }}</textarea>
                    </div>

                    <div class="p-3 rounded-3 bg-light border">
                        <div class="small text-muted mb-1 fw-semibold text-uppercase" style="font-size:.7rem">
                            معاينة Google
                        </div>
                        <div class="small text-muted">aljarida.tn › article › <span id="g-slug">slug</span></div>
                        <div class="fs-5 text-primary mt-1 text-truncate" id="g-title">عنوان المقال</div>
                        <div class="small text-secondary"
                             id="g-desc"
                             style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                            وصف المقال الظاهر في نتائج البحث…
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- SIDEBAR --}}
        {{-- ============================================================ --}}
        <div class="col-lg-4">

            {{-- ---------- PUBLICATION ---------- --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fa-solid fa-rocket" style="color:var(--brand)"></i> النشر
                    </h6>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">التصنيف</label>
                        <select name="category_id" class="form-select">
                            <option value="">— بدون تصنيف —</option>
                            @foreach (\App\Models\Category::orderBy('name')->get() as $c)
                                <option value="{{ $c->id }}" @selected(old('category_id', $item->category_id) == $c->id)>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="article-city" class="form-label fw-bold small">المدينة</label>
                        <input type="text" id="article-city" name="city" list="tunisia-cities"
                               value="{{ old('city', $item->city) }}" maxlength="100" class="form-control"
                               placeholder="جندوبة، طبرقة، بوسالم...">
                        <datalist id="tunisia-cities">
                            @foreach (['جندوبة', 'طبرقة', 'بوسالم', 'غار الدماء', 'فرنانة', 'عين دراهم', 'بلطة بوعوان', 'وادي مليز'] as $city)
                                <option value="{{ $city }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">تاريخ النشر</label>
                        <input type="datetime-local" name="published_at"
                               value="{{ old('published_at', optional($item->published_at)->format('Y-m-d\TH:i')) }}"
                               class="form-control">
                        <div class="form-text small">
                            <i class="fa-regular fa-circle-question"></i>
                            اتركه فارغاً للنشر الفوري
                        </div>
                    </div>

                    <div class="p-3 rounded-3 mb-3" style="background:#fffbeb; border:1px solid #fde68a;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-bold small text-dark">مقال مميز</div>
                                <div class="text-muted" style="font-size:.75rem">يظهر في الواجهة</div>
                            </div>
                            <div class="form-check form-switch fs-5 mb-0">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" value="1" id="is-featured"
                                       class="form-check-input" role="switch"
                                       @checked(old('is_featured', $item->is_featured))>
                            </div>
                        </div>
                    </div>

                    @if($item->exists)
                        <div class="pt-3 border-top small text-muted">
                            @if($item->is_published)
                                <span class="badge bg-success-subtle text-success">
                                    <i class="fa-solid fa-circle-check"></i> منشور
                                </span>
                                {{ optional($item->published_at)->format('d/m/Y H:i') }}
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">
                                    <i class="fa-solid fa-clock"></i> مسودة
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="card-footer bg-light border-0 p-3">
                    <button type="submit" name="is_published" value="1"
                            class="btn btn-success w-100 mb-2">
                        <i class="fa-solid fa-paper-plane me-1"></i> نشر المقال
                    </button>
                    <button type="submit" name="is_published" value="0"
                            class="btn btn-outline-secondary w-100 mb-2">
                        <i class="fa-regular fa-floppy-disk me-1"></i> حفظ كمسودة
                    </button>
                    <a href="{{ route('admin.crud.index', $cfg['key']) }}"
                       class="btn btn-link btn-sm text-muted w-100 text-decoration-none">
                        إلغاء
                    </a>
                </div>
            </div>

            {{-- ---------- IMAGE À LA UNE (label natif) ---------- --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fa-solid fa-image" style="color:var(--brand)"></i> الصورة البارزة
                    </h6>

                    {{-- ⚠️ On utilise un <label for="image-input"> : clic = picker natif --}}
                    <label for="image-input" id="image-dropzone"
                           class="dropzone-area d-block {{ $item->image_url ? 'd-none' : '' }}">
                        <div class="dz-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                        <p class="mb-1 fw-semibold small">اسحب صورة أو انقر</p>
                        <p class="text-muted small mb-0">JPG, PNG, WebP · 8 MB</p>
                    </label>
                    <input type="file" name="image" id="image-input"
                           accept="image/jpeg,image/png,image/webp"
                           style="display:none;">

                    <div id="image-preview-wrap"
                         class="position-relative {{ $item->image_url ? '' : 'd-none' }}">
                        <img id="image-preview"
                             src="{{ $item->image_url }}"
                             class="img-fluid rounded-3 w-100"
                             style="aspect-ratio:16/9; object-fit:cover;">
                        <div class="position-absolute top-0 end-0 p-2 d-flex gap-1">
                            <label for="image-input"
                                   class="btn btn-sm btn-light shadow-sm mb-0">
                                <i class="fa-solid fa-rotate"></i>
                            </label>
                            <button type="button" id="image-remove"
                                    class="btn btn-sm btn-danger shadow-sm">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ---------- VIDÉO (label natif) ---------- --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fa-solid fa-video" style="color:var(--brand)"></i> فيديو (اختياري)
                    </h6>

                    <label for="video-input" id="video-dropzone"
                           class="dropzone-area dropzone-sm d-block {{ $item->video_player_url ? 'd-none' : '' }}">
                        <div class="dz-icon-sm"><i class="fa-solid fa-film"></i></div>
                        <p class="mb-0 fw-semibold small">إضافة فيديو MP4</p>
                        <p class="text-muted small mb-0">50 MB كحد أقصى</p>
                    </label>
                    <input type="file" name="video_url" id="video-input"
                           accept="video/mp4"
                           style="display:none;">

                    <div id="video-preview-wrap"
                         class="{{ $item->video_player_url ? '' : 'd-none' }}">
                        <video id="video-preview"
                               src="{{ $item->video_player_url }}"
                               class="w-100 rounded-3" controls></video>
                        <button type="button" id="video-remove"
                                class="btn btn-sm btn-outline-danger w-100 mt-2">
                            <i class="fa-solid fa-trash"></i> حذف الفيديو
                        </button>
                    </div>
                </div>
            </div>

            {{-- ---------- CONSEILS ---------- --}}
            <div class="card border-0 text-white mb-4"
                 style="background:linear-gradient(135deg,var(--brand) 0%,var(--brand-2) 100%);">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fa-solid fa-lightbulb" style="color:var(--accent)"></i> نصائح
                    </h6>
                    <ul class="small mb-0 ps-3" style="opacity:.9">
                        <li class="mb-1">عنوان من 60–70 حرفاً</li>
                        <li class="mb-1">أضف صورة → +94% مشاهدات</li>
                        <li>املأ وصف SEO لتحسين الظهور</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- ============================================================ --}}
{{-- SCRIPTS — Jodit est déjà chargé par layouts.admin --}}
{{-- ============================================================ --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ================================================================
    // 1. DRAG & DROP + PREVIEW (avant Jodit — au cas où Jodit plante)
    // ================================================================
    setupDropzone('image', function (url) {
        document.getElementById('image-preview').src = url;
        document.getElementById('image-dropzone').classList.add('d-none');
        document.getElementById('image-preview-wrap').classList.remove('d-none');
    });

    setupDropzone('video', function (url) {
        document.getElementById('video-preview').src = url;
        document.getElementById('video-dropzone').classList.add('d-none');
        document.getElementById('video-preview-wrap').classList.remove('d-none');
    });

    document.getElementById('image-remove')?.addEventListener('click', function () {
        document.getElementById('image-input').value = '';
        document.getElementById('image-preview-wrap').classList.add('d-none');
        document.getElementById('image-dropzone').classList.remove('d-none');
    });

    document.getElementById('video-remove')?.addEventListener('click', function () {
        document.getElementById('video-input').value = '';
        document.getElementById('video-preview-wrap').classList.add('d-none');
        document.getElementById('video-dropzone').classList.remove('d-none');
    });

    // ================================================================
    // 2. SLUG AUTO
    // ================================================================
    var titleInput  = document.getElementById('article-title');
    var slugInput   = document.getElementById('article-slug');
    var slugTouched = {{ old('slug', $item->slug) ? 'true' : 'false' }};

    function makeSlug(txt) {
        return (txt || '')
            .toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/[^\p{L}\p{N}\s-]/gu, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }

    function refreshSlug() {
        if (!slugTouched) slugInput.value = makeSlug(titleInput.value);
        updateSerp();
    }

    titleInput.addEventListener('input', refreshSlug);
    slugInput.addEventListener('input', function () { slugTouched = true; updateSerp(); });
    document.getElementById('regen-slug').addEventListener('click', function () {
        slugTouched = false;
        slugInput.value = makeSlug(titleInput.value);
        updateSerp();
    });

    // ================================================================
    // 3. COMPTEURS + SERP
    // ================================================================
    var metaTitle = document.getElementById('meta-title');
    var metaDesc  = document.getElementById('meta-desc');
    var excerpt   = document.getElementById('excerpt-field');

    function updateSerp() {
        document.getElementById('mt-count').textContent = metaTitle.value.length;
        document.getElementById('md-count').textContent = metaDesc.value.length;
        document.getElementById('excerpt-count').textContent = excerpt.value.length;
        document.getElementById('g-slug').textContent  = slugInput.value || 'slug';
        document.getElementById('g-title').textContent = metaTitle.value || titleInput.value || 'عنوان المقال';
        document.getElementById('g-desc').textContent  =
            metaDesc.value || excerpt.value || 'وصف المقال الظاهر في نتائج البحث…';
    }

    [metaTitle, metaDesc, excerpt].forEach(function (el) {
        el.addEventListener('input', updateSerp);
    });
    updateSerp();

    // ================================================================
    // 4. JODIT — en dernier, avec try/catch
    // ================================================================
    try {
        if (typeof Jodit === 'undefined') {
            console.warn('Jodit non chargé — éditeur en mode textarea brut.');
            return;
        }

        var contentEl = document.getElementById('content-editor');
        var editor = new Jodit(contentEl, {
            direction: 'rtl',
            language: 'ar',
            height: 560,
            toolbarAdaptive: false,
            buttons: 'bold,italic,underline,strikethrough,|,ul,ol,|,paragraph,fontsize,brush,|,align,|,link,image,table,hr,|,undo,redo,eraser,|,source,fullsize',
            uploader: { insertImageAsBase64URI: true },
            askBeforePasteHTML: false,
            defaultActionOnPaste: 'insert_clear_html',
            events: {
                'change': updateReadingInfo,
                'afterInit': updateReadingInfo
            }
        });

        function updateReadingInfo() {
            var txt = editor.getEditorValue ? editor.getEditorValue(true) : editor.value;
            var div = document.createElement('div');
            div.innerHTML = txt;
            var words = (div.textContent || '').trim().split(/\s+/).filter(Boolean).length;
            var min = Math.max(1, Math.ceil(words / 200));
            document.getElementById('word-count').textContent = words;
            document.getElementById('reading-time').textContent = min;
        }
    } catch (err) {
        console.error('Jodit init error:', err);
    }

    // ================================================================
    // 5. HELPER DROPZONE (drag & drop)
    // ================================================================
    function setupDropzone(name, onFile) {
        var zone  = document.getElementById(name + '-dropzone');
        var input = document.getElementById(name + '-input');
        if (!zone || !input) return;

        // ⚠️ Pas de zone.addEventListener('click') → on utilise <label for>
        // qui ouvre le picker NATIVEMENT (aucun JS requis pour ouvrir)

        input.addEventListener('change', function (e) {
            var f = e.target.files[0];
            if (f) previewFile(f, onFile);
        });

        ['dragenter', 'dragover'].forEach(function (evt) {
            zone.addEventListener(evt, function (e) {
                e.preventDefault();
                zone.classList.add('dragging');
            });
        });
        ['dragleave', 'drop'].forEach(function (evt) {
            zone.addEventListener(evt, function (e) {
                e.preventDefault();
                zone.classList.remove('dragging');
            });
        });
        zone.addEventListener('drop', function (e) {
            var f = e.dataTransfer.files[0];
            if (!f) return;
            var expected = name === 'image' ? 'image/' : 'video/';
            if (!f.type.startsWith(expected)) {
                alert('نوع الملف غير مدعوم.');
                return;
            }
            input.files = e.dataTransfer.files;
            previewFile(f, onFile);
        });
    }

    function previewFile(file, cb) {
        var reader = new FileReader();
        reader.onload = function (e) { cb(e.target.result); };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush

@endsection
