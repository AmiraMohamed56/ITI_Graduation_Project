@props(['icon', 'text', 'href', 'pattern' => null])

@php
    // If a pattern is provided, use it to determine active state
    $isActive = $pattern ? request()->routeIs($pattern) : request()->url() === $href;
@endphp

<li class="relative group">
    <a href="{{ $href }}"
       class="flex items-center px-4 py-3 rounded transition-all duration-200
              {{ $isActive 
                  ? 'bg-blue-100 dark:bg-blue-600 text-blue-700 dark:text-white font-semibold' 
                  : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
        <span class="material-icons">{{ $icon }}</span>
        <span class="ml-3" x-show="$el.closest('aside').classList.contains('w-64')">{{ $text }}</span>
        <span class="tooltip">{{ $text }}</span>
    </a>
</li>
