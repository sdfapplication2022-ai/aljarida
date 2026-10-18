@extends('layouts.admin')
@section('title', $cfg['title'])
@section('heading', ($item->exists ? 'تعديل ' : 'إضافة ').$cfg['single'])

@section('content')
@php
    $isEdit = $item->exists;
@endphp
<form method="POST" enctype="multipart/form-data"
      action="{{ $isEdit ? route('admin.crud.update', [$cfg['key'], $item->id]) : route('admin.crud.store', $cfg['key']) }}">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="card shadow-sm"><div class="card-body">
    @foreach ($cfg['fields'] as $f)
        @php
            $name = $f[0]; $label = $f[1]; $type = $f[2];
            $opt = $f[4] ?? [];
            $current = old($name, $type === 'password' ? '' : ($item->{$name} ?? null));
        @endphp
        <div class="mb-3">
            @if ($type === 'checkbox')
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" name="{{ $name }}" value="1" id="f_{{ $name }}"
                           @checked(old($name, $isEdit ? $item->{$name} : false))>
                    <label class="form-check-label" for="f_{{ $name }}">{{ $label }}</label>
                </div>
            @else
                <label class="form-label" for="f_{{ $name }}">{{ $label }}</label>

                @if (in_array($type, ['text','email','password','number','url']))
                    <input type="{{ $type }}" class="form-control" id="f_{{ $name }}" name="{{ $name }}" value="{{ $current }}" @if($type==='number') min="1" max="5" @endif>

                @elseif ($type === 'textarea')
                    <textarea class="form-control" rows="4" id="f_{{ $name }}" name="{{ $name }}">{{ $current }}</textarea>

                @elseif ($type === 'richtext')
                    <textarea class="form-control js-wysiwyg" rows="10" id="f_{{ $name }}" name="{{ $name }}" dir="auto">{{ $current }}</textarea>

                @elseif ($type === 'datetime')
                    <input type="datetime-local" class="form-control" id="f_{{ $name }}" name="{{ $name }}"
                           value="{{ $current ? \Illuminate\Support\Carbon::parse($current)->format('Y-m-d\TH:i') : '' }}">

                @elseif ($type === 'file')
                    <input type="file" class="form-control" id="f_{{ $name }}" name="{{ $name }}">
                    @if ($isEdit && ($item->getRawOriginal($name)))
                        <div class="form-text">الملف الحالي: {{ $item->getRawOriginal($name) }}</div>
                    @endif

                @elseif ($type === 'select')
                    <select class="form-select" id="f_{{ $name }}" name="{{ $name }}">
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
                    @php
                        $selected = old($name, $isEdit ? $item->{$name}->pluck('id')->all() : []);
                    @endphp
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
    <div class="card-footer">
        <button class="btn btn-success">حفظ</button>
        <a href="{{ route('admin.crud.index', $cfg['key']) }}" class="btn btn-light">إلغاء</a>
    </div></div>
</form>
@push('scripts')
<script>
document.querySelectorAll('textarea.js-wysiwyg').forEach(function (el) {
    var big = el.name === 'content';
    new Jodit(el, {
        direction: 'rtl', language: 'ar', height: big ? 480 : 260, toolbarAdaptive: false,
        buttons: 'bold,italic,underline,strikethrough,|,ul,ol,|,paragraph,fontsize,brush,|,align,|,link,image,table,hr,|,undo,redo,eraser,|,source,fullsize',
        uploader: { insertImageAsBase64URI: true },
        askBeforePasteHTML: false, defaultActionOnPaste: 'insert_clear_html'
    });
});
</script>
@endpush
@endsection
