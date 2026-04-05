@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex gap-[5px] items-center justify-between">

        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-400 bg-white border border-gray-200 cursor-not-allowed leading-5 rounded-md">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 leading-5 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 leading-5 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-400 bg-white border border-gray-200 cursor-not-allowed leading-5 rounded-md">
                {!! __('pagination.next') !!}
            </span>
        @endif

    </nav>
@endif