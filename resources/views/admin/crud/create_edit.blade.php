@extends('layouts.admin')
@section('title', $cfg['title'])
@section('heading', ($item->exists ? 'تعديل ' : 'إضافة ').$cfg['single'])

@section('content')
@php $isEdit = $item->exists; @endphp

<form method="POST" enctype="multipart/form-data"
      action="{{ $isEdit ? route('admin.crud.update', [$cfg['key'], $item->id]) : route('admin.crud.store', $cfg['key']) }}">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body p-4 p-md-5">
                    @foreach ($cfg['fields'] as $f)
                        @php
                            $name = $f[0]; $label = $f[1]; $type = $f[2];
                            $opt = $f[4] ?? [];
                            $current = old($name, $type === 'password' ? '' : ($item->{$name} ?? null));
                        @endphp
                        
                        <div class="mb-4">
                            @if ($type === 'checkbox')
                                <div class="form-check form-switch fs-5">
                                    <input type="checkbox" class="form-check-input" name="{{ $name }}" value="1" id="f_{{ $name }}"
                                           @checked(old($name, $isEdit ? $item->{$name} : false))>
                                    <label class="form-check-label fs-6 ms-2 mt-1 fw-semibold" for="f_{{ $name }}">{{ $label }}</label>
                                </div>
                            @else
                                <label class="form-label fw-bold text-dark" for="f_{{ $name }}">{{ $label }}</label>

                                @if (in_array($type, ['text','email','password','number','url']))
                                    <input type="{{ $type }}" class="form-control form-control-lg" id="f_{{ $name }}" name="{{ $name }}" value="{{ $current }}" @if($type==='number') min="1" max="5" @endif>

                                @elseif ($type === 'textarea')
                                    <textarea class="form-control" rows="4" id="f_{{ $name }}" name="{{ $name }}">{{ $current }}</textarea>

                                @elseif ($type === 'richtext')
                                    <textarea class="form-control" rows="14" id="f_{{ $name }}" name="{{ $name }}" dir="auto">{{ $current }}</textarea>
                                    <div class="form-text mt-2"><i class="fa-solid fa-circle-info me-1"></i> يمكنك استعمال وسوم HTML (p, h2, strong, img, ul ...).</div>

                                @elseif ($type === 'datetime')
                                    <input type="datetime-local" class="form-control form-control-lg" id="f_{{ $name }}" name="{{ $name }}"
                                           value="{{ $current ? \Illuminate\Support\Carbon::parse($current)->format('Y-m-d\TH:i') : '' }}">

                                @elseif ($type === 'file')
                                    <input type="file" class="form-control" id="f_{{ $name }}" name="{{ $name }}">
                                    @if ($isEdit && ($item->getRawOriginal($name)))
                                        <div class="mt-2 text-muted small bg-light p-2 rounded d-inline-block">
                                            <i class="fa-solid fa-paperclip"></i> الملف الحالي: {{ $item->getRawOriginal($name) }}
                                        </div>
                                    @endif

                                @elseif ($type === 'select')
                                    <select class="form-select form-select-lg" id="f_{{ $name }}" name="{{ $name }}">
                                        @if (isset($opt['source']))
                                            <option value="">— اختر —</option>
                                            @foreach ($opt['source']::orderBy('id')->get() as $o)
                                                <option value="{{ $o->id }}" @selected((string) $current === (string) $o->id)>{{ $o->{$opt['label']} }}</option>
                                            @endforeach
                                        @else
                                            @foreach ($opt['choices'] as $k => $v)
                                                <option value="{{ $k }}" @selected((string) $current === (string) $k)>{{ $v }}</option>
                                            @endforeach
                                        @endif
                                    </select>

                                @elseif ($type === 'tags')
                                    @php $selected = old($name, $isEdit ? $item->{$name}->pluck('id')->all() : []); @endphp
                                    <select class="form-select" id="f_{{ $name }}" name="{{ $name }}[]" multiple size="6">
                                        @foreach ($opt['source']::orderBy('name')->get() as $o)
                                            <option value="{{ $o->id }}" @selected(in_array($o->id, (array) $selected))>{{ $o->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
                
                <div class="card-footer bg-light border-0 p-4 d-flex justify-content-between">
                    <button class="btn btn-success px-5"><i class="fa-solid fa-floppy-disk me-2"></i> حفظ البيانات</button>
                    <a href="{{ route('admin.crud.index', $cfg['key']) }}" class="btn btn-outline-secondary px-4">إلغاء</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection