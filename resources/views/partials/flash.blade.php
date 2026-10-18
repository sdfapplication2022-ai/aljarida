@if (session('success'))
    <div class="flash-ok">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="flash-err">
        @foreach ($errors->all() as $e) <div>{{ $e }}</div> @endforeach
    </div>
@endif
