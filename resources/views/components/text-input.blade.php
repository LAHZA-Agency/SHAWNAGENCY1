@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-c-border bg-primary/50 focus:border-c-border focus:ring-main rounded-md shadow-sm']) }}>
