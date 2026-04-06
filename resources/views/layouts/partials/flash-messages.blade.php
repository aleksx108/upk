@if (session('status') || session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="space-y-3">
            @if (session('status'))
                <div class="overflow-hidden rounded-xl border border-emerald-200 bg-white shadow-sm ring-1 ring-emerald-100/80">
                    <div class="flex items-start gap-4 px-4 py-4 sm:px-5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 ring-1 ring-emerald-200">
                            <i class="fa-solid fa-check"></i>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-700">{{ __('Success') }}</p>
                            <p class="mt-1 text-sm leading-6 text-gray-700">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="overflow-hidden rounded-xl border border-red-200 bg-white shadow-sm ring-1 ring-red-100/80">
                    <div class="flex items-start gap-4 px-4 py-4 sm:px-5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 ring-1 ring-red-200">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-red-700">{{ __('Error') }}</p>
                            <p class="mt-1 text-sm leading-6 text-gray-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif


