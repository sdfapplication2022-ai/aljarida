@extends('layouts.admin')

@section('title',   $item->exists ? 'تعديل نتيجة' : 'نتيجة جديدة')
@section('heading', $item->exists ? 'تعديل نتيجة المباراة' : 'إضافة نتيجة مباراة')

@section('content')
<style>
    .sb-card{background:linear-gradient(135deg,#14301f,#1f4a2f);color:#fff;border-radius:16px;padding:28px 20px}
    .sb-comp{font-size:.85rem;opacity:.8;text-align:center;margin-bottom:18px}
    .sb-row{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:16px}
    .sb-team{text-align:center}
    .sb-logo{width:76px;height:76px;margin:0 auto 10px;border-radius:50%;background:rgba(255,255,255,.12);
             display:flex;align-items:center;justify-content:center;overflow:hidden;font-size:28px}
    .sb-logo img{width:100%;height:100%;object-fit:contain;background:#fff}
    .sb-name{font-weight:700;font-size:1.05rem}
    .sb-score{font-size:2.6rem;font-weight:800;letter-spacing:4px;direction:ltr;white-space:nowrap}
    .sb-meta{margin-top:16px;text-align:center;font-size:.8rem;opacity:.75}
    .dz{display:block;border:2px dashed #cfd8d3;border-radius:12px;padding:18px;text-align:center;cursor:pointer;
        background:#f9fbfa;transition:.2s}
    .dz:hover,.dz.dragging{border-color:#7ac70c;background:#f1f9e4}
    .dz i{font-size:26px;color:#7ac70c;margin-bottom:6px}
    .score-input{width:90px;text-align:center;font-size:1.8rem;font-weight:800;direction:ltr}
    .logo-thumb{width:64px;height:64px;border-radius:50%;object-fit:contain;background:#f1f3f2;border:1px solid #e3e8e5}
</style>

<form method="POST" enctype="multipart/form-data"
      action="{{ $item->exists
          ? route('admin.crud.update', [$cfg['key'], $item->id])
          : route('admin.crud.store', $cfg['key']) }}">
    @csrf
    @if ($item->exists) @method('PUT') @endif

    <div class="row g-4">

        {{-- ================= COLONNE PRINCIPALE ================= --}}
        <div class="col-lg-8">

            {{-- Aperçu live du tableau d'affichage --}}
            <div class="sb-card mb-4">
                <div class="sb-comp" id="pv-comp">{{ old('competition', $item->competition) ?: 'المسابقة' }}</div>
                <div class="sb-row">
                    <div class="sb-team">
                        <div class="sb-logo" id="pv-home-logo">
                            @if ($item->home_logo_url)<img src="{{ $item->home_logo_url }}" alt="">@else<i class="fa-solid fa-shield"></i>@endif
                        </div>
                        <div class="sb-name" id="pv-home">{{ old('home_team', $item->home_team) ?: 'المستضيف' }}</div>
                    </div>
                    <div class="sb-score"><span id="pv-hs">{{ old('home_score', $item->home_score ?? 0) }}</span> - <span id="pv-as">{{ old('away_score', $item->away_score ?? 0) }}</span></div>
                    <div class="sb-team">
                        <div class="sb-logo" id="pv-away-logo">
                            @if ($item->away_logo_url)<img src="{{ $item->away_logo_url }}" alt="">@else<i class="fa-solid fa-shield"></i>@endif
                        </div>
                        <div class="sb-name" id="pv-away">{{ old('away_team', $item->away_team) ?: 'الضيف' }}</div>
                    </div>
                </div>
                <div class="sb-meta" id="pv-meta">&nbsp;</div>
            </div>

            {{-- Match --}}
            <div class="card mb-4"><div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-trophy text-success"></i> معلومات المباراة</h6>

                <div class="mb-3">
                    <label class="form-label fw-bold small">المسابقة <span class="text-danger">*</span></label>
                    <input type="text" name="competition" id="f-comp" list="competitions" required maxlength="255"
                           value="{{ old('competition', $item->competition) }}"
                           class="form-control form-control-lg @error('competition') is-invalid @enderror"
                           placeholder="الرابطة المحترفة الأولى، كأس تونس، بطولة الجهة...">
                    <datalist id="competitions">
                        @foreach (['الرابطة المحترفة الأولى', 'الرابطة المحترفة الثانية', 'كأس تونس', 'بطولة الرابطة الجهوية', 'مباراة ودية'] as $c)
                            <option value="{{ $c }}">
                        @endforeach
                    </datalist>
                    @error('competition')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 align-items-end">
                    {{-- Home --}}
                    <div class="col-md-5">
                        <label class="form-label fw-bold small">الفريق المستضيف <span class="text-danger">*</span></label>
                        <input type="text" name="home_team" id="f-home" required maxlength="255"
                               value="{{ old('home_team', $item->home_team) }}"
                               class="form-control @error('home_team') is-invalid @enderror">
                        @error('home_team')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="d-flex align-items-center gap-3 mt-3">
                            <img id="home-thumb" class="logo-thumb {{ $item->home_logo_url ? '' : 'd-none' }}" src="{{ $item->home_logo_url }}" alt="">
                            <label for="home-logo-input" class="btn btn-sm btn-outline-secondary mb-0">
                                <i class="fa-solid fa-image"></i> شعار الفريق
                            </label>
                            <input type="file" name="home_logo" id="home-logo-input" accept="image/jpeg,image/png,image/webp" hidden>
                        </div>
                    </div>

                    {{-- Scores --}}
                    <div class="col-md-2 text-center">
                        <div class="d-flex align-items-center justify-content-center gap-2" style="direction:ltr">
                            <input type="number" name="home_score" id="f-hs" min="0" max="255" required
                                   value="{{ old('home_score', $item->home_score ?? 0) }}"
                                   class="form-control score-input px-1 @error('home_score') is-invalid @enderror">
                            <span class="fw-bold fs-4">:</span>
                            <input type="number" name="away_score" id="f-as" min="0" max="255" required
                                   value="{{ old('away_score', $item->away_score ?? 0) }}"
                                   class="form-control score-input px-1 @error('away_score') is-invalid @enderror">
                        </div>
                        <div class="small text-muted mt-1">النتيجة</div>
                    </div>

                    {{-- Away --}}
                    <div class="col-md-5">
                        <label class="form-label fw-bold small">الفريق الضيف <span class="text-danger">*</span></label>
                        <input type="text" name="away_team" id="f-away" required maxlength="255"
                               value="{{ old('away_team', $item->away_team) }}"
                               class="form-control @error('away_team') is-invalid @enderror">
                        @error('away_team')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="d-flex align-items-center gap-3 mt-3">
                            <img id="away-thumb" class="logo-thumb {{ $item->away_logo_url ? '' : 'd-none' }}" src="{{ $item->away_logo_url }}" alt="">
                            <label for="away-logo-input" class="btn btn-sm btn-outline-secondary mb-0">
                                <i class="fa-solid fa-image"></i> شعار الفريق
                            </label>
                            <input type="file" name="away_logo" id="away-logo-input" accept="image/jpeg,image/png,image/webp" hidden>
                        </div>
                    </div>
                </div>
            </div></div>

            {{-- Lieu & date --}}
            <div class="card mb-4"><div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-location-dot text-success"></i> المكان والتاريخ</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">تاريخ ووقت المباراة <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="played_at" id="f-date" required
                               value="{{ old('played_at', optional($item->played_at)->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}"
                               class="form-control @error('played_at') is-invalid @enderror">
                        @error('played_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">المدينة</label>
                        <input type="text" name="city" id="f-city" list="tn-cities" maxlength="100"
                               value="{{ old('city', $item->city) }}" class="form-control"
                               placeholder="جندوبة، طبرقة، بوسالم...">
                        <datalist id="tn-cities">
                            @foreach (['جندوبة', 'طبرقة', 'بوسالم', 'غار الدماء', 'فرنانة', 'عين دراهم', 'بلطة بوعوان', 'وادي مليز'] as $c)
                                <option value="{{ $c }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small">الملعب</label>
                        <input type="text" name="venue" id="f-venue" maxlength="255"
                               value="{{ old('venue', $item->venue) }}" class="form-control"
                               placeholder="الملعب البلدي...">
                    </div>
                </div>
            </div></div>
        </div>

        {{-- ================= SIDEBAR ================= --}}
        <div class="col-lg-4">

            {{-- Publication --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-rocket text-success"></i> النشر</h6>
                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
                         style="background:#f4faea;border:1px solid #d9efb5">
                        <div>
                            <div class="fw-bold small">نشر النتيجة</div>
                            <div class="text-muted" style="font-size:.75rem">تظهر للزوّار على الموقع</div>
                        </div>
                        <div class="form-check form-switch fs-5 mb-0">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1" class="form-check-input" role="switch"
                                   @checked(old('is_published', $item->is_published))>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 p-3">
                    <button type="submit" class="btn btn-success w-100 mb-2">
                        <i class="fa-regular fa-floppy-disk"></i> حفظ
                    </button>
                    <a href="{{ route('admin.crud.index', $cfg['key']) }}"
                       class="btn btn-link btn-sm text-muted w-100 text-decoration-none">إلغاء</a>
                </div>
            </div>

            {{-- Image du match --}}
            <div class="card mb-4"><div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-image text-success"></i> صورة المباراة</h6>

                <label for="image-input" id="image-dropzone" class="dz {{ $item->image_url ? 'd-none' : '' }}">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <p class="mb-0 fw-semibold small">اسحب صورة أو انقر</p>
                    <p class="text-muted small mb-0">JPG, PNG, WebP · 4 MB</p>
                </label>
                <input type="file" name="image" id="image-input" accept="image/jpeg,image/png,image/webp" hidden>

                <div id="image-wrap" class="position-relative {{ $item->image_url ? '' : 'd-none' }}">
                    <img id="image-preview" src="{{ $item->image_url }}" class="img-fluid rounded-3 w-100"
                         style="aspect-ratio:16/9;object-fit:cover" alt="">
                    <label for="image-input" class="btn btn-sm btn-light shadow-sm position-absolute top-0 end-0 m-2 mb-0">
                        <i class="fa-solid fa-rotate"></i>
                    </label>
                </div>
                @error('image')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
            </div></div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var $ = function (id) { return document.getElementById(id); };

    // ---------- Aperçu live ----------
    function meta() {
        var parts = [];
        if ($('f-date').value) {
            var d = new Date($('f-date').value);
            if (!isNaN(d)) parts.push(d.toLocaleDateString('ar-TN', { year: 'numeric', month: 'long', day: 'numeric' }));
        }
        if ($('f-venue').value) parts.push($('f-venue').value);
        if ($('f-city').value)  parts.push($('f-city').value);
        $('pv-meta').textContent = parts.join(' · ') || '\u00A0';
    }
    function refresh() {
        $('pv-comp').textContent = $('f-comp').value || 'المسابقة';
        $('pv-home').textContent = $('f-home').value || 'المستضيف';
        $('pv-away').textContent = $('f-away').value || 'الضيف';
        $('pv-hs').textContent   = $('f-hs').value || 0;
        $('pv-as').textContent   = $('f-as').value || 0;
        meta();
    }
    ['f-comp','f-home','f-away','f-hs','f-as','f-date','f-venue','f-city'].forEach(function (id) {
        $(id).addEventListener('input', refresh);
    });
    refresh();

    // ---------- Lecture d'un fichier image ----------
    function readImage(file, cb) {
        if (!file || !file.type.startsWith('image/')) { alert('نوع الملف غير مدعوم.'); return; }
        var r = new FileReader();
        r.onload = function (e) { cb(e.target.result); };
        r.readAsDataURL(file);
    }

    // ---------- Logos ----------
    function logoField(inputId, thumbId, previewId) {
        $(inputId).addEventListener('change', function (e) {
            readImage(e.target.files[0], function (url) {
                $(thumbId).src = url;
                $(thumbId).classList.remove('d-none');
                $(previewId).innerHTML = '<img src="' + url + '" alt="">';
            });
        });
    }
    logoField('home-logo-input', 'home-thumb', 'pv-home-logo');
    logoField('away-logo-input', 'away-thumb', 'pv-away-logo');

    // ---------- Image du match (clic + drag & drop) ----------
    var zone = $('image-dropzone'), input = $('image-input');
    function showImage(url) {
        $('image-preview').src = url;
        zone.classList.add('d-none');
        $('image-wrap').classList.remove('d-none');
    }
    input.addEventListener('change', function (e) { readImage(e.target.files[0], showImage); });
    ['dragenter', 'dragover'].forEach(function (ev) {
        zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.add('dragging'); });
    });
    ['dragleave', 'drop'].forEach(function (ev) {
        zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.remove('dragging'); });
    });
    zone.addEventListener('drop', function (e) {
        var f = e.dataTransfer.files[0];
        if (!f) return;
        input.files = e.dataTransfer.files;
        readImage(f, showImage);
    });
});
</script>
@endpush
@endsection
