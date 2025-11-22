@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold leading-5 text-white bg-gradient-to-r from-purple-500 to-pink-500 shadow-lg transition-all duration-200'
            : 'inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold leading-5 text-gray-700 hover:text-gray-900 hover:bg-white/50 transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
