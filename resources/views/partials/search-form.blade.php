<form action="{{ route('search') }}" method="GET" role="search" class="header-search-form"
      data-live-search data-suggest="{{ route('search.suggest') }}">
    <input type="search" name="q" value="{{ $q ?? request('q') }}" maxlength="100" autocomplete="off"
           class="form-control" placeholder="ابحث في المقالات..." aria-label="بحث" required>
    <button type="submit" class="header-btn20 search-submit-btn" aria-label="بحث">
        <i class="fa-solid fa-magnifying-glass"></i>
    </button>
    <div class="ls-box" hidden></div>
</form>
