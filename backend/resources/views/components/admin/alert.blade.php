@props([
    'type' => 'success', // can be 'success', 'error', 'warning', 'info'
    'message' => null
])

@php
$colors = match($type) {
    'success' => 'bg-green-100 border-green-400 text-green-700',
    'error' => 'bg-red-100 border-red-400 text-red-700',
    'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
    'info' => 'bg-blue-100 border-blue-400 text-blue-700',
    default => 'bg-gray-100 border-gray-400 text-gray-700',
};
@endphp

<div x-data="{ show: true }" x-show="show"
     x-transition
     x-init="setTimeout(() => show = false, 5000)"
     class="mb-4 p-4 border-l-4 rounded shadow {{ $colors }} relative">
    <span>{{ $message ?? $slot }}</span>
    <button @click="show = false" class="absolute top-2 right-2 font-bold text-xl leading-none">&times;</button>
</div>
