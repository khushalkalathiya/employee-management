@props(['field', 'label', 'sortField' => '', 'sortDirection' => 'asc', 'extraSortClass' => ''])
<th class="{{ $extraSortClass ?? '' }}">
    <button class="group flex items-center gap-1 transition" wire:click="sortBy('{{ $field }}')">

        <span class="table-th">
            {{ $label }}
        </span>

        <span class="text-xs">

            @if ($sortField === $field)
                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
            @else
                <svg class="h-3.5 w-3.5 opacity-0 transition-opacity group-hover:opacity-60" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">

                    <path d="M8 7l4-4 4 4M16 17l-4 4-4-4" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" />
                </svg>
            @endif

        </span>

    </button>
</th>
