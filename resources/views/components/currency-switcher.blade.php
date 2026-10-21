<div style="display:flex;align-items:center;justify-content:center;gap:12px;padding:8px;flex-wrap:wrap" aria-label="Display currency">
    @foreach(['PKR' => 'PKR', 'SAR' => 'SAR (Saudi Riyal)'] as $currency => $label)
        <form method="POST" action="{{ route('currency.update', $currency) }}">
            @csrf
            <input type="hidden" name="return" value="{{ request()->getRequestUri() }}">
            <button type="submit" aria-pressed="{{ \App\Support\Price::currency() === $currency ? 'true' : 'false' }}"
                style="font-size:13px;cursor:pointer;{{ \App\Support\Price::currency() === $currency ? 'font-weight:700;text-decoration:underline' : '' }}">{{ $label }}</button>
        </form>
    @endforeach
    @if(\App\Support\Price::currency() === 'SAR')
        <span style="font-size:11px">Estimated prices. Orders are charged in PKR.
            <a href="https://www.exchangerate-api.com" target="_blank" rel="noopener noreferrer" style="text-decoration:underline">Rates by ExchangeRate-API</a>
        </span>
    @endif
</div>
