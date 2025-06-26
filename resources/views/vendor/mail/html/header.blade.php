@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
<img src="{{ $application['logo'] }}" class="logo" alt="Logo" style="max-width: 100px">
{{--{{ $slot }}--}}
@endif
</a>
</td>
</tr>
