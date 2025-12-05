{{-- @props(['type' => 'primary'])

@php
$classes = match($type) {
    'primary' => 'bg-blue-600 text-white hover:bg-blue-700',
    'secondary' => 'bg-gray-600 text-white hover:bg-gray-700',
    'danger' => 'bg-red-600 text-white hover:bg-red-700',
    default => 'bg-gray-600 text-white hover:bg-gray-700',
};
@endphp

<button {{ $attributes->merge(['class' => "px-4 py-2 rounded $classes"]) }}>
    {{ $slot }}
</button> --}}


@props([
    'type' => 'primary',
    'size' => 'md',       // future-proof sizing
    'loading' => false,   // optional loading support
])

@php
$base = "inline-flex items-center justify-center font-medium
         rounded-xl transition-all duration-200 select-none
         focus:outline-none focus:ring-2 focus:ring-offset-2
         disabled:opacity-60 disabled:cursor-not-allowed";

$sizes = match($size) {
    'sm' => 'px-3 py-1.5 text-sm',
    'lg' => 'px-5 py-3 text-lg',
    default => 'px-4 py-2 text-sm'
};

$colors = match($type) {
    'primary' =>
        "bg-blue-600 text-white hover:bg-blue-700 active:bg-blue-800
         focus:ring-blue-500 shadow-sm hover:shadow",
    'secondary' =>
        "bg-gray-200 text-gray-800 hover:bg-gray-300 active:bg-gray-400
         focus:ring-gray-400 shadow-sm hover:shadow",
    'danger' =>
        "bg-red-600 text-white hover:bg-red-700 active:bg-red-800
         focus:ring-red-500 shadow-sm hover:shadow",
    default =>
        "bg-gray-600 text-white hover:bg-gray-700 active:bg-gray-800
         focus:ring-gray-500 shadow-sm hover:shadow",
};

$finalClasses = "$base $sizes $colors drop-shadow";
@endphp

<button {{ $attributes->merge(['class' => $finalClasses]) }} {{ $loading ? 'disabled' : '' }}>
    @if($loading)
        <svg class="animate-spin h-5 w-5 mr-2 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z"></path>
        </svg>
    @endif

    {{ $slot }}
</button>
