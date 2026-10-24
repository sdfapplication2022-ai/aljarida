@extends('layouts.admin')
@section('title', $cfg['title'])
@section('heading', $cfg['title'])

@section('content')
<div class="card">
    <div class="card-header bg-white border-0 p-4 pb-0 d-flex flex-wrap gap-3 justify-content-between align-items-center">
        <form method="GET" class="d-flex gap-2" style="max-width: 400px; width: 100%;">
            @if (! empty($cfg['search']))
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 bg-light" placeholder="بحث...">
                </div>
            @endif
        </form>
        @if ($cfg['create'])
            <a href="{{ route('admin.crud.create', $cfg['key']) }}" class="btn btn-success d-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i> إضافة {{ $cfg['single'] }}
            </a>
        @endif
    </div>

    <div class="card-body p-0 mt-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        @foreach ($cfg['columns'] as $label) <th class="text-nowrap">{{ $label }}</th> @endforeach
                        <th class="text-end px-4" style="width:120px">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($items as $item)
                    <tr>
                        @foreach ($cfg['columns'] as $col => $label)
                            @php($val = data_get($item, $col))
                            <td>
                                @if (is_bool($val))
                                    <span class="badge rounded-pill bg-{{ $val ? 'success' : 'secondary' }} px-3">{{ $val ? 'نعم' : 'لا' }}</span>
                                @elseif (isset($cfg['valueLabels'][$col][$val]))
                                    {{ $cfg['valueLabels'][$col][$val] }}
                                @elseif ($val instanceof \Carbon\Carbon)
                                    <span class="text-muted"><i class="fa-regular fa-clock me-1"></i> {{ $val->format('Y/m/d H:i') }}</span>
                                @else
                                    {{ \Illuminate\Support\Str::limit(strip_tags((string) $val), 60) }}
                                @endif
                            </td>
                        @endforeach
                        <td class="text-end px-4">
                            <div class="d-flex gap-1 justify-content-end">
                                @if ($cfg['edit'])
                                    <a href="{{ route('admin.crud.edit', [$cfg['key'], $item->id]) }}" class="btn btn-sm btn-light text-primary" title="تعديل">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                @endif
                                <form action="{{ route('admin.crud.destroy', [$cfg['key'], $item->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light text-danger" title="حذف"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="{{ count($cfg['columns']) + 1 }}" class="text-center text-muted py-5">
                        <i class="fa-solid fa-inbox fa-2x mb-3 text-light"></i><br>لا توجد بيانات
                    </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($items->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $items->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection