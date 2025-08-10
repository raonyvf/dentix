@props(['title', 'headings' => []])
<div class="bg-white rounded-lg shadow">
    <div class="flex items-center justify-between px-4 pt-4">
        <h3 class="text-lg font-semibold text-gray-700">{{ $title }}</h3>
        {{ $header ?? '' }}
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    @foreach($headings as $heading)
                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">{{ $heading }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
