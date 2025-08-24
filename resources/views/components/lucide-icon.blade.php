@props(['name'])
@php
    $component = 'lucide-' . \Illuminate\Support\Str::kebab($name);
@endphp
<x-dynamic-component :component="$component" {{ $attributes }} />

