@extends('layouts.admin')

@section('title',   $item->exists ? 'تعديل مدونة' : 'مدونة جديدة')
@section('heading', $item->exists ? 'تعديل المدونة' : 'إضافة مدونة جديدة')

@section('content')

<form id="blog-form" method="POST" enctype="multipart/form-data"
      action="{{ $item->exists
          ? route('admin.crud.update', [$cfg['key'], $item->id])
          : route('admin.crud.store', $cfg['key']) }}">
    @csrf
    @if ($item->exists) @method('PUT') @endif

    <div class="row g-4">

        {{-- ============================================================ --}}
        {{-- COLONNE PRINCIPALE (8/12) --}}
        {{-- ============================================================ --}}
        <div class="col-lg-8">

            {{-- ---------- TITRE ---------- --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <label class="form-label fw-bold text-muted small text-uppercase mb-2">
                        عنوان المدونة <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="title" id="blog-title"
                           value="{{ old('title', $item->title) }}" required
                           placeholder="اكتب عنواناً جذاباً…"
                           class="form-control form-control-lg border-0 border-bottom rounded-0 px-0 fs-3 fw-bold"
                           style="border-bottom:2px solid #e9ecef!important; box-shadow:none;">
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
                              class="form-control">{{ old('excerpt', $item->getRawOriginal('excerpt') ?? '') }}</textarea>
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

                    {{-- Aperçu Google --}}
                    <div class="p-3 rounded-3 bg-light border">
                        <div class="small text-muted mb-1 fw-semibold text-uppercase" style="font-size:.7rem">
                            معاينة Google
                        </div>
                        <div class="small text-muted">
                            aljarida.tn › blog › <span id="g-slug">{{ $item->id ?? 'nouveau' }}</span>
                        </div>
                        <div class="fs-5 text-primary mt-1 text-truncate" id="g-title">عنوان المدونة</div>
                        <div class="small text-secondary"
                             id="g-desc"
                             style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                            وصف المدونة الظاهر في نتائج البحث…
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- SIDEBAR (4/12) --}}
        {{-- ============================================================ --}}
        <div class="col-lg-4">

            {{-- ---------- PUBLICATION ---------- --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fa-solid fa-rocket" style="color:var(--brand)"></i> النشر
                    </h6>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">
                            الكاتب <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="author" id="blog-author"
                               value="{{ old('author', $item->author) }}" required
                               placeholder="اسم الكاتب…"
                               class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">تاريخ النشر</label>
                        <input type="datetime-local" name="published_at" id="published-at"
                               value="{{ old('published_at', optional($item->published_at)->format('Y-m-d\TH:i')) }}"
                               class="form-control">
                        <div class="form-text small">
                            <i class="fa-regular fa-circle-question"></i>
                            اتركه فارغاً → يظهر فوراً · تاريخ مستقبلي → جدولة
                        </div>
                    </div>

                    {{-- Aperçu statut dynamique --}}
                    <div class="pt-3 border-top">
                        <div id="status-badge" class="small">
                            <span class="badge bg-success-subtle text-success">
                                <i class="fa-solid fa-circle-check"></i>
                                <span id="status-text">مرئي فوراً</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light border-0 p-3">
                    <button type="submit" class="btn btn-success w-100 mb-2">
                        <i class="fa-solid fa-floppy-disk me-1"></i>
                        {{ $item->exists ? 'تحديث المدونة' : 'حفظ المدونة' }}
                    </button>
                    <a href="{{ route('admin.crud.index', $cfg['key']) }}"
                       class="btn btn-link btn-sm text-muted w-100 text-decoration-none">
                        إلغاء
                    </a>
                </div>
            </div>

            {{-- ---------- IMAGE (label natif + dropzone) ---------- --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fa-solid fa-image" style="color:var(--brand)"></i> صورة المدونة
                    </h6>

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
{{-- SCRIPTS --}}
{{-- ============================================================ --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ================================================================
    // 1. DRAG & DROP + PREVIEW
    // ================================================================
    setupDropzone('image', function (url) {
        document.getElementById('image-preview').src = url;
        document.getElementById('image-dropzone').classList.add('d-none');
        document.getElementById('image-preview-wrap').classList.remove('d-none');
    });

    document.getElementById('image-remove')?.addEventListener('click', function () {
        document.getElementById('image-input').value = '';
        document.getElementById('image-preview-wrap').classList.add('d-none');
        document.getElementById('image-dropzone').classList.remove('d-none');
    });

    // ================================================================
    // 2. COMPTEURS + SERP + STATUT
    // ================================================================
    var titleInput  = document.getElementById('blog-title');
    var metaTitle   = document.getElementById('meta-title');
    var metaDesc    = document.getElementById('meta-desc');
    var excerpt     = document.getElementById('excerpt-field');
    var publishedAt = document.getElementById('published-at');

    function updateSerp() {
        document.getElementById('mt-count').textContent = metaTitle.value.length;
        document.getElementById('md-count').textContent = metaDesc.value.length;
        document.getElementById('excerpt-count').textContent = excerpt.value.length;

        document.getElementById('g-title').textContent =
            metaTitle.value || titleInput.value || 'عنوان المدونة';
        document.getElementById('g-desc').textContent =
            metaDesc.value || excerpt.value || 'وصف المدونة الظاهر في نتائج البحث…';
    }

    function updateStatus() {
        var badge = document.getElementById('status-badge');
        var text  = document.getElementById('status-text');
        if (!publishedAt.value) {
            badge.innerHTML = '<span class="badge bg-success-subtle text-success"><i class="fa-solid fa-circle-check"></i> <span id="status-text">مرئي فوراً</span></span>';
            return;
        }
        var d = new Date(publishedAt.value);
        var now = new Date();
        if (d > now) {
            badge.innerHTML = '<span class="badge bg-warning-subtle text-warning-emphasis"><i class="fa-solid fa-clock"></i> <span>مجدول للنشر</span></span>';
        } else {
            badge.innerHTML = '<span class="badge bg-success-subtle text-success"><i class="fa-solid fa-circle-check"></i> <span>منشور</span></span>';
        }
    }

    [metaTitle, metaDesc, excerpt, titleInput].forEach(function (el) {
        el.addEventListener('input', updateSerp);
    });
    publishedAt.addEventListener('change', updateStatus);
    updateSerp();
    updateStatus();

    // ================================================================
    // 3. JODIT (avec try/catch)
    // ================================================================
    try {
        if (typeof Jodit === 'undefined') {
            console.warn('Jodit non chargé.');
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
    // 4. HELPER DROPZONE
    // ================================================================
    function setupDropzone(name, onFile) {
        var zone  = document.getElementById(name + '-dropzone');
        var input = document.getElementById(name + '-input');
        if (!zone || !input) return;

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
            if (!f.type.startsWith('image/')) {
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
