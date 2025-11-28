{{-- @props(['label', 'type' => 'text', 'name', 'value' => ''])

<div class="mb-4">
    <label class="block text-gray-700 dark:text-gray-300 mb-1" for="{{ $name }}">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" value="{{ $value }}"
        {{ $attributes->merge(['class' => 'w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400']) }}>
</div> --}}


@props(['label', 'type' => 'text', 'name', 'value' => ''])

<div class="mb-4">
    <label class="block text-gray-700 dark:text-gray-300 mb-1" for="{{ $name }}">{{ $label }}</label>
    @if($type === 'file')
        <input type="file" name="{{ $name }}" id="{{ $name }}"
            {{ $attributes->merge(['class' => 'w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400']) }}>
    @else
        <input type="{{ $type }}" name="{{ $name }}" value="{{ $value }}" id="{{ $name }}"
            {{ $attributes->merge(['class' => 'w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400']) }}>
    @endif
</div>
