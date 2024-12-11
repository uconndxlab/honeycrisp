@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Honeycrisp')
<img src="{{ asset('images/logo.png') }}" class="logo" alt="Honeycrisp Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
