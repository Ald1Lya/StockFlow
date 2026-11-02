@props(['type' => 'info'])

@php
    $styles = [
        'error' => 'border-red-200 bg-red-50 text-red-700',
        'info' => 'border-[#e2dac8] bg-white text-[#7a7264]',
        'success' => 'border-green-200 bg-green-50 text-green-700',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
    ];

    $role = $type === 'error' ? 'alert' : 'status';
@endphp

<div {{ $attributes->class(['rounded-lg border px-4 py-3 text-sm', $styles[$type] ?? $styles['info']]) }}
     role="{{ $role }}">
    {{ $slot }}
</div>