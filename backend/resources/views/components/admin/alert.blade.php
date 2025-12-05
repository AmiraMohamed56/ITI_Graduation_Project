@props([
    'type' => 'success', // can be 'success', 'error', 'warning', 'info'
    'message' => null
])

@php
$colors = match($type) {
    'success' => 'bg-emerald-100 border-emerald-400 text-emerald-700',
    'error' => 'bg-rose-100 border-rose-400 text-rose-700',
    'warning' => 'bg-amber-100 border-amber-400 text-amber-700',
    'info' => 'bg-blue-100 border-blue-400 text-blue-700',
    default => 'bg-slate-100 border-slate-400 text-slate-700',
};
@endphp

<div x-data="{ show: true }" x-show="show"
     x-transition
     x-init="setTimeout(() => show = false, 5000)"
     class="mb-4 p-1 border-l-4 rounded shadow {{ $colors }} relative">
    <span>{{ $message ?? $slot }}</span>
    <button @click="show = false" class="absolute top-1 right-2 font-bold text-xl leading-none">&times;</button>
</div>
