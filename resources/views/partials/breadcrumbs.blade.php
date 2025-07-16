@props(['crumbs' => []])
<nav class="text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
    <ol class="list-reset flex items-center space-x-2">
        @foreach ($crumbs as $crumb)
            <li>
                @if (isset($crumb['url']))
                    <a href="{{ $crumb['url'] }}" class="text-blue-600 hover:underline">{{ $crumb['label'] }}</a>
                @else
                    <span>{{ $crumb['label'] }}</span>
                @endif
            </li>
            @unless($loop->last)
                <li>/</li>
            @endunless
        @endforeach
    </ol>
</nav>
