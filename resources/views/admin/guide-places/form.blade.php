@extends('layouts.admin')

@section('title', $item->exists ? 'تعديل مكان' : 'مكان جديد')
@section('heading', $item->exists ? 'تعديل المكان' : 'إضافة مكان جديد')

@section('content')
<form id="guide-place-form" method="POST" enctype="multipart/form-data"
      action="{{ $item->exists
          ? route('admin.crud.update', [$cfg['key'], $item->id])
          : route('admin.crud.store', $cfg['key']) }}">
    @csrf
    @if ($item->exists) @method('PUT') @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body p-4">
                    <label for="place-name" class="form-label fw-bold text-muted small text-uppercase mb-2">
                        اسم المكان <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" id="place-name" required maxlength="255"
                           value="{{ old('name', $item->name) }}"
                           placeholder="مثال: إقامة الياسمين"
                           class="form-control form-control-lg border-0 border-bottom rounded-0 px-0 fs-3 fw-bold @error('name') is-invalid @enderror"
                           style="border-bottom:2px solid #e9ecef!important;box-shadow:none;">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    <div class="d-flex align-items-center gap-2 mt-3 small text-muted">
                        <i class="fa-solid fa-link"></i>
                        <span>{{ url('/guide-places') }}/</span>
                        <input type="text" name="slug" id="place-slug"
                               value="{{ old('slug', $item->slug) }}"
                               placeholder="رابط-المكان"
                               class="form-control form-control-sm border-0 bg-transparent p-0"
                               style="box-shadow:none;">
                        <button type="button" id="regen-slug" class="btn btn-link btn-sm text-decoration-none p-0 ms-auto">
                            <i class="fa-solid fa-rotate"></i> إعادة توليد
                        </button>
                    </div>
                    @error('slug')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                    <h6 class="fw-bold mb-0"><i class="fa-solid fa-pen-nib text-success"></i> وصف المكان</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <textarea id="description-editor" name="description" rows="12"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description) }}</textarea>
                    @error('description')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4"><i class="fa-solid fa-location-dot text-success"></i> الموقع والتواصل</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="place-type" class="form-label fw-bold small">التصنيف <span class="text-danger">*</span></label>
                            <select name="type" id="place-type" required class="form-select @error('type') is-invalid @enderror">
                                <option value="">— اختر التصنيف —</option>
                                @foreach (\App\Models\GuidePlace::TYPE_LABELS as $key => $label)
                                    <option value="{{ $key }}" @selected(old('type', $item->type) === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="place-city" class="form-label fw-bold small">المدينة <span class="text-danger">*</span></label>
                            <input type="text" name="city" id="place-city" list="tunisia-cities" maxlength="100" required
                                   value="{{ old('city', $item->city) }}"
                                   class="form-control @error('city') is-invalid @enderror">
                            <datalist id="tunisia-cities">
                                @foreach (['جندوبة', 'طبرقة', 'بوسالم', 'غار الدماء', 'فرنانة', 'عين دراهم', 'بلطة بوعوان', 'وادي مليز'] as $city)
                                    <option value="{{ $city }}">
                                @endforeach
                            </datalist>
                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="place-address" class="form-label fw-bold small">العنوان</label>
                            <input type="text" name="address" id="place-address" maxlength="255"
                                   value="{{ old('address', $item->address) }}" class="form-control @error('address') is-invalid @enderror">
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="place-campus" class="form-label fw-bold small">الكلية أو المعهد القريب</label>
                            <input type="text" name="near_campus" id="place-campus" maxlength="255"
                                   value="{{ old('near_campus', $item->near_campus) }}" class="form-control @error('near_campus') is-invalid @enderror">
                            @error('near_campus')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="place-phone" class="form-label fw-bold small">الهاتف</label>
                            <input type="text" name="phone" id="place-phone" maxlength="30"
                                   value="{{ old('phone', $item->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="place-whatsapp" class="form-label fw-bold small">واتساب</label>
                            <input type="text" name="whatsapp" id="place-whatsapp" maxlength="30"
                                   value="{{ old('whatsapp', $item->whatsapp) }}" class="form-control @error('whatsapp') is-invalid @enderror">
                            @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="place-website" class="form-label fw-bold small">الموقع الإلكتروني</label>
                            <input type="url" name="website" id="place-website" maxlength="255"
                                   value="{{ old('website', $item->website) }}" placeholder="https://"
                                   class="form-control @error('website') is-invalid @enderror">
                            @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="place-latitude" class="form-label fw-bold small">خط العرض</label>
                            <input type="number" name="latitude" id="place-latitude" min="-90" max="90" step="any"
                                   value="{{ old('latitude', $item->latitude) }}" class="form-control @error('latitude') is-invalid @enderror">
                            @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="place-longitude" class="form-label fw-bold small">خط الطول</label>
                            <input type="number" name="longitude" id="place-longitude" min="-180" max="180" step="any"
                                   value="{{ old('longitude', $item->longitude) }}" class="form-control @error('longitude') is-invalid @enderror">
                            @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-list-check text-success"></i> الأسعار والمرافق</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="place-price" class="form-label fw-bold small">السعر ابتداءً من (دينار)</label>
                            <input type="number" name="price_from" id="place-price" min="0" step="1"
                                   value="{{ old('price_from', $item->price_from) }}" class="form-control @error('price_from') is-invalid @enderror">
                            @error('price_from')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="place-price-unit" class="form-label fw-bold small">وحدة السعر</label>
                            <input type="text" name="price_unit" id="place-price-unit" maxlength="20"
                                   value="{{ old('price_unit', $item->price_unit) }}" placeholder="شهري، ليلة، وجبة..."
                                   class="form-control @error('price_unit') is-invalid @enderror">
                            @error('price_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="place-amenities" class="form-label fw-bold small">المرافق (قائمة JSON نصية)</label>
                            <textarea name="amenities" id="place-amenities" rows="4" dir="auto"
                                      placeholder='["واي فاي","مكيف","مطبخ"]'
                                      class="form-control @error('amenities') is-invalid @enderror">{{ old('amenities', $item->amenities ? json_encode($item->amenities, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : '') }}</textarea>
                            <div class="form-text">اكتب المرافق بهذا الشكل: ["واي فاي","مكيف","مطبخ"]</div>
                            @error('amenities')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-rocket text-success"></i> النشر والتوثيق</h6>
                    <div class="p-3 rounded-3 mb-3" style="background:#fffbeb;border:1px solid #fde68a;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-bold small text-dark">مكان موثّق</div>
                                <div class="text-muted" style="font-size:.75rem">تم التحقق من معلومات المكان</div>
                            </div>
                            <div class="form-check form-switch fs-5 mb-0">
                                <input type="checkbox" name="is_verified" value="1" id="is-verified"
                                       class="form-check-input" role="switch" @checked(old('is_verified', $item->is_verified))>
                            </div>
                        </div>
                    </div>

                    @if ($item->exists)
                        <div class="pt-3 border-top small text-muted">
                            @if ($item->is_published)
                                <span class="badge bg-success-subtle text-success"><i class="fa-solid fa-circle-check"></i> منشور</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary"><i class="fa-solid fa-clock"></i> مسودة</span>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-light border-0 p-3">
                    <button type="submit" name="is_published" value="1" class="btn btn-success w-100 mb-2">
                        <i class="fa-solid fa-paper-plane me-1"></i> نشر المكان
                    </button>
                    <button type="submit" name="is_published" value="0" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="fa-regular fa-floppy-disk me-1"></i> حفظ كمسودة
                    </button>
                    <a href="{{ route('admin.crud.index', $cfg['key']) }}" class="btn btn-link btn-sm text-muted text-decoration-none w-100">
                        إلغاء
                    </a>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-image text-success"></i> صورة المكان</h6>
                    <label for="image-input" id="image-dropzone"
                           class="dropzone-area d-block {{ $item->image_url ? 'd-none' : '' }}">
                        <div class="dz-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                        <p class="mb-1 fw-semibold small">اسحب صورة أو انقر لاختيارها</p>
                        <p class="text-muted small mb-0">JPG, PNG, WebP · 8 MB كحد أقصى</p>
                    </label>
                    <input type="file" name="image" id="image-input" accept="image/jpeg,image/png,image/webp" class="d-none">

                    <div id="image-preview-wrap" class="position-relative {{ $item->image_url ? '' : 'd-none' }}">
                        <img id="image-preview" src="{{ $item->image_url }}" alt="معاينة صورة المكان"
                             class="img-fluid rounded-3 w-100" style="aspect-ratio:16/9;object-fit:cover;">
                        <label for="image-input" class="btn btn-sm btn-light shadow-sm mt-2">
                            <i class="fa-solid fa-rotate"></i> تغيير الصورة
                        </label>
                    </div>
                    <div id="image-client-error" class="text-danger small mt-2" role="alert"></div>
                    @error('image')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var nameInput = document.getElementById('place-name');
    var slugInput = document.getElementById('place-slug');
    var slugTouched = {{ old('slug', $item->slug) ? 'true' : 'false' }};

    function makeSlug(value) {
        return (value || '')
            .toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/[^\p{L}\p{N}\s-]/gu, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }

    function refreshSlug() {
        if (!slugTouched) {
            slugInput.value = makeSlug(nameInput.value);
        }
    }

    nameInput.addEventListener('input', refreshSlug);
    slugInput.addEventListener('input', function () {
        slugTouched = true;
    });
    document.getElementById('regen-slug').addEventListener('click', function () {
        slugTouched = false;
        refreshSlug();
    });

    var imageInput = document.getElementById('image-input');
    var imageDropzone = document.getElementById('image-dropzone');

    function previewImage(file) {
        if (!file) {
            return false;
        }

        if (!file.type.startsWith('image/')) {
            document.getElementById('image-client-error').textContent = 'يرجى اختيار ملف صورة صالح.';
            return false;
        }

        var reader = new FileReader();
        reader.onload = function (event) {
            document.getElementById('image-preview').src = event.target.result;
            imageDropzone.classList.add('d-none');
            document.getElementById('image-preview-wrap').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
        document.getElementById('image-client-error').textContent = '';

        return true;
    }

    imageInput.addEventListener('change', function () {
        previewImage(imageInput.files[0]);
    });

    ['dragenter', 'dragover'].forEach(function (eventName) {
        imageDropzone.addEventListener(eventName, function (event) {
            event.preventDefault();
            imageDropzone.classList.add('dragging');
        });
    });

    ['dragleave', 'drop'].forEach(function (eventName) {
        imageDropzone.addEventListener(eventName, function (event) {
            event.preventDefault();
            imageDropzone.classList.remove('dragging');
        });
    });

    imageDropzone.addEventListener('drop', function (event) {
        var file = event.dataTransfer.files[0];
        if (!file || !file.type.startsWith('image/')) {
            previewImage(file);
            return;
        }

        imageInput.files = event.dataTransfer.files;
        previewImage(file);
    });

    if (typeof Jodit !== 'undefined') {
        new Jodit(document.getElementById('description-editor'), {
            direction: 'rtl',
            language: 'ar',
            height: 360,
            toolbarAdaptive: false,
            buttons: 'bold,italic,underline,|,ul,ol,|,paragraph,fontsize,brush,|,align,|,link,image,table,hr,|,undo,redo,eraser,|,source,fullsize',
            uploader: { insertImageAsBase64URI: true },
            askBeforePasteHTML: false,
            defaultActionOnPaste: 'insert_clear_html'
        });
    }
});
</script>
@endpush
@endsection
