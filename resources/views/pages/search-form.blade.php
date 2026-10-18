{{-- À inclure dans le header : @include('partials.search-form') --}}
<form action="{{ route('search') }}" method="GET" role="search" class="d-flex gap-2 mb-3">
    <input type="search" name="q" value="{{ $q ?? request('q') }}" maxlength="100"
           class="form-control" placeholder="ابحث في المقالات..." aria-label="بحث" required>
    <button type="submit" class="header-btn20"><i class="fa-solid fa-magnifying-glass"></i></button>
</form>
