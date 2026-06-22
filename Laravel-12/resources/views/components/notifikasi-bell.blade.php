@php
    $jumlahNotifikasiBaru = auth()->check()
        ? \App\Models\Notifikasi::where('id_user', auth()->id())
            ->where('dibaca', false)
            ->count()
        : 0;
@endphp

<a href="{{ route('notifikasi.index') }}"
   class="relative inline-flex items-center justify-center w-10 h-10 rounded-full transition
   {{ request()->routeIs('notifikasi.*') ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">

    <svg xmlns="http://www.w3.org/2000/svg"
         fill="none"
         viewBox="0 0 24 24"
         stroke-width="1.8"
         stroke="currentColor"
         class="w-6 h-6">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a3 3 0 1 1-5.714 0" />
    </svg>

    @if ($jumlahNotifikasiBaru > 0)
        <span class="absolute -top-1 -right-1 min-w-5 h-5 px-1 inline-flex items-center justify-center rounded-full bg-red-600 text-white text-xs font-bold">
            {{ $jumlahNotifikasiBaru > 99 ? '99+' : $jumlahNotifikasiBaru }}
        </span>
    @endif
</a>