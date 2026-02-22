@if ($paginator->hasPages())
    @php
        $currentPerPage = (int) request('per_page', 10);
        if (!in_array($currentPerPage, [10, 25, 50, 100], true)) {
            $currentPerPage = 10;
        }
    @endphp

    <nav role="navigation" aria-label="Pagination" class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        {{-- Mobile View --}}
        <div class="flex-1 flex items-center justify-between md:hidden gap-2">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-3 py-2 text-xs font-medium text-gray-400 bg-white border border-gray-200 cursor-default rounded-md">« prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-3 py-2 text-xs font-medium text-[#2E86AB] bg-white border border-gray-200 rounded-md hover:bg-[#2E86AB]/10 transition-colors">« prev</a>
            @endif

            <div class="flex-1 text-center text-xs text-gray-600 px-2">
                <span class="font-semibold">{{ $paginator->currentPage() }}</span>
                <span class="mx-1">/</span>
                <span>{{ $paginator->lastPage() }}</span>
            </div>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-3 py-2 text-xs font-medium text-[#2E86AB] bg-white border border-gray-200 rounded-md hover:bg-[#2E86AB]/10 transition-colors">next »</a>
            @else
                <span class="relative inline-flex items-center px-3 py-2 text-xs font-medium text-gray-400 bg-white border border-gray-200 cursor-default rounded-md">next »</span>
            @endif
        </div>

        {{-- Desktop View --}}
        <div class="hidden md:flex md:flex-1 md:items-center md:justify-between">
            <div>
                <p class="text-xs md:text-sm text-gray-600">
                    Menampilkan
                    <span class="font-semibold text-gray-900">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-semibold text-gray-900">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-semibold text-gray-900">{{ $paginator->total() }}</span>
                    data
                </p>
            </div>

            <div class="flex flex-col gap-3 md:flex-row md:items-center md:gap-4">
                @if (request()->routeIs('complaint-list'))
                    <div class="flex items-center gap-2 md:gap-3">
                        <label for="per_page" class="text-xs md:text-sm font-medium text-slate-600 whitespace-nowrap">
                            Baris/hal:
                        </label>

                        <div class="relative group">
                            <select id="per_page" onchange="changePerPage(this.value)"
                                class="appearance-none cursor-pointer pl-3 pr-8 py-1.5 md:py-2 md:pl-4 md:pr-9 bg-slate-50 border border-slate-200 text-slate-700 text-xs md:text-sm font-semibold rounded-lg focus:ring-2 focus:ring-[#2E86AB]/20 focus:border-[#2E86AB] hover:bg-white hover:border-slate-300 transition-all shadow-sm w-16 md:w-20">
                                <option value="10" {{ $currentPerPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $currentPerPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $currentPerPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $currentPerPage == 100 ? 'selected' : '' }}>100</option>
                            </select>

                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-1.5 md:px-2 text-slate-500 group-hover:text-slate-700 transition-colors">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                @endif

                <span class="relative z-0 inline-flex shadow-sm rounded-lg overflow-auto border border-gray-200 bg-white">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-2 md:px-3 py-1.5 md:py-2 text-xs md:text-sm font-medium text-gray-400 cursor-default whitespace-nowrap">« prev</span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-2 md:px-3 py-1.5 md:py-2 text-xs md:text-sm font-medium text-[#2E86AB] hover:bg-[#2E86AB]/10 transition-colors whitespace-nowrap">« prev</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="relative inline-flex items-center px-2 md:px-4 py-1.5 md:py-2 text-xs md:text-sm font-medium text-gray-400 whitespace-nowrap">{{ $element }}</span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="relative inline-flex items-center px-2 md:px-4 py-1.5 md:py-2 text-xs md:text-sm font-semibold bg-[#2E86AB] text-white whitespace-nowrap">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-2 md:px-4 py-1.5 md:py-2 text-xs md:text-sm font-medium text-[#2E86AB] hover:bg-[#2E86AB]/10 transition-colors whitespace-nowrap">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-2 md:px-3 py-1.5 md:py-2 text-xs md:text-sm font-medium text-[#2E86AB] hover:bg-[#2E86AB]/10 transition-colors whitespace-nowrap">next »</a>
                    @else
                        <span class="relative inline-flex items-center px-2 md:px-3 py-1.5 md:py-2 text-xs md:text-sm font-medium text-gray-400 cursor-default whitespace-nowrap">next »</span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
