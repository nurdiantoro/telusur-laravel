@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">

        {{-- mobile --}}
        <div class="flex gap-2 items-center justify-between sm:hidden">

            @if ($paginator->onFirstPage())
                <span
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white cursor-not-allowed leading-5 rounded-md dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-800 bg-white leading-5 rounded-md hover:text-gray-700  active:bg-gray-100 active:text-gray-800 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900 dark:hover:text-gray-200">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-800 bg-white leading-5 rounded-md hover:text-gray-700  active:bg-gray-100 active:text-gray-800 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900 dark:hover:text-gray-200">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white cursor-not-allowed leading-5 rounded-md dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600">
                    {!! __('pagination.next') !!}
                </span>
            @endif

        </div>

        {{-- desktop --}}
        @php
            $current = $paginator->currentPage();
            $last = $paginator->lastPage();
        @endphp

        <div class="hidden sm:flex-1 sm:flex sm:gap-2 sm:items-center sm:justify-between">
            <div>
                <span class="inline-flex rtl:flex-row-reverse rounded-md">

                    {{-- Previous --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true">
                            <span
                                class="inline-flex items-center px-2 py-2 text-sm font-medium text-gray-700 bg-white cursor-not-allowed leading-5 dark:bg-gray-700 dark:text-gray-400">
                                Previous
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}"
                            class="inline-flex items-center px-2 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 bg-white leading-5 transition duration-150">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Previous
                        </a>
                    @endif


                    {{-- First Page --}}
                    @if ($current > 3)
                        <a href="{{ $paginator->url(1) }}"
                            class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white hover:bg-gray-100">
                            1
                        </a>

                        <span class="inline-flex items-center px-4 py-2 -ml-px text-sm text-gray-700 bg-white">
                            ...
                        </span>
                    @endif


                    {{-- Pages Around Current --}}
                    @for ($i = max(1, $current - 1); $i <= min($last, $current + 1); $i++)
                        @if ($i == $current)
                            <span aria-current="page">
                                <span
                                    class="inline-flex items-center px-4 py-2 -ml-px text-sm font-bold text-white bg-warna-01 cursor-default">
                                    {{ $i }}
                                </span>
                            </span>
                        @else
                            <a href="{{ $paginator->url($i) }}"
                                class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white hover:bg-gray-100">
                                {{ $i }}
                            </a>
                        @endif
                    @endfor


                    {{-- Last Page --}}
                    @if ($current < $last - 2)
                        <span class="inline-flex items-center px-4 py-2 -ml-px text-sm text-gray-700 bg-white">
                            ...
                        </span>

                        <a href="{{ $paginator->url($last) }}"
                            class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white hover:bg-gray-100">
                            {{ $last }}
                        </a>
                    @endif


                    {{-- Next --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}"
                            class="inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 leading-5 transition duration-150">
                            Next
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true">
                            <span
                                class="inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-700 bg-white cursor-not-allowed">
                                Next
                            </span>
                        </span>
                    @endif

                </span>
            </div>
        </div>
    </nav>
@endif
