<div style="display:flex;align-items:center;justify-content:center;gap:12px;padding:8px" aria-label="{{ __('Language') }}">
    @foreach(['en' => 'English', 'ar' => 'العربية'] as $locale => $label)
        <form method="POST" action="{{ route('locale.update', $locale) }}">
            @csrf
            <input type="hidden" name="return" value="{{ request()->getRequestUri() }}">
            <button type="submit" lang="{{ $locale }}" aria-pressed="{{ app()->getLocale() === $locale ? 'true' : 'false' }}"
                style="font-size:13px;cursor:pointer;{{ app()->getLocale() === $locale ? 'font-weight:700;text-decoration:underline' : '' }}">{{ $label }}</button>
        </form>
    @endforeach
</div>
