@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 rounded-lg text-start text-base font-semibold text-white bg-gradient-to-r from-purple-500 to-pink-500 focus:outline-none transition duration-200'
            : 'block w-full ps-3 pe-4 py-2 rounded-lg text-start text-base font-semibold text-gray-700 hover:text-gray-900 hover:bg-white/50 focus:outline-none transition duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
