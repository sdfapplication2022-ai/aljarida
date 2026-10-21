@if (session('success'))
    <div class="flash-ok" role="status" aria-live="polite">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="flash-err" role="alert">
        @foreach ($errors->all() as $e) <div>{{ $e }}</div> @endforeach
    </div>
@endif