{{-- @props(['icon', 'text', 'href'])

<li>
    <a href="{{ $href }}" class="flex items-center px-6 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
        <span class="material-icons">{{ $icon }}</span>
        <span class="ml-3" x-show="$el.closest('aside').classList.contains('w-64')">{{ $text }}</span>
    </a>
</li> --}}

@props(['icon', 'text', 'href'])

<li class="relative group">
    <a href="{{ $href }}" class="flex items-center px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
        <span class="material-icons">{{ $icon }}</span>
        <span class="ml-3">{{ $text }}</span>
        <span class="tooltip">{{ $text }}</span>
    </a>
</li>


