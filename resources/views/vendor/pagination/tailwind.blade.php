<div class="flex items-center justify-between select-none flex-wrap sm:flex-nowrap">

    <div class="order-2 sm:order-1">
        <p class="text-sm text-text-200/90 leading-5">
            {!! __('Affichage des résultats') !!}
            @if ($paginator->firstItem())
            <span class="font-medium">{{ $paginator->firstItem() }}</span>
            {!! __('à') !!}
            <span class="font-medium">{{ $paginator->lastItem() }}</span>
            @else
            {{ $paginator->count() }}
            @endif
            {!! __('sur') !!}
            <span class="font-medium">{{ $paginator->total() }}</span>
            {!! __('résultats') !!}
        </p>
    </div>

    <div class="rounded-b-lg order-1 sm:order-2 px-4 py-2 pl-0 sm:pl-4">
        <ol class="flex justify-end gap-1 text-xs font-medium">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
            <li>
                <span class="inline-flex size-8 items-center justify-center rounded border border-c-border cursor-cursor-default">
                    <span class="sr-only">Prev Page</span>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="size-3"
                        viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
            </li>
            @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex size-8 items-center justify-center rounded border border-c-border bg-main/5 hover:bg-main/20">
                    <span class="sr-only">Prev Page</span>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="size-3"
                        viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
            <li>
                <span class="block size-8 rounded border-c-border bg-main text-center leading-8 cursor-cursor-default">{{ $element }}</span>
            </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
            @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
            <li>
                <span class="block size-8 rounded border-primary-300 text-center leading-8 bg-main text-primary">{{ $page }}</span>
            </li>
            @else
            <li>
                <a href="{{ $url }}" class="block size-8 rounded border border-c-border text-center leading-8 bg-main/5 hover:bg-main/20">{{ $page }}</a>
            </li>
            @endif
            @endforeach
            @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex size-8 items-center justify-center rounded border border-c-border bg-main/5 hover:bg-main/20">
                    <span class="sr-only">Next Page</span>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="size-3"
                        viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </li>
            @else
            <li>
                <span class="inline-flex size-8 items-center justify-center rounded border border-c-border cursor-default">
                    <span class="sr-only">Next Page</span>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="size-3"
                        viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
            </li>
            @endif
        </ol>
    </div>

</div>