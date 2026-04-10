@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-c-border text-start text-base font-medium text-primary-dark bg-primary-100/50 focus:outline-none focus:text-primary-300 focus:bg-primary-100 focus:border-primary-dark transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium  hover: hover:bg-primary-100/10 hover:border-gray-300 focus:outline-none focus: focus:bg-primary-100/10 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
