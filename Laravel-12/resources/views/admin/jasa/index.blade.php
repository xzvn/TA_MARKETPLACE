@extends('layouts.admin')

@section('content')

<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Kelola Jasa</h1>
        <p class="text-sm text-slate-500 mt-1">
            Admin dapat menyetujui atau menolak jasa yang dibuat freelancer.
        </p>
    </div>

    ```
    @if (session('success'))
    <div class="p-4 rounded-xl bg-green-100 text-green-700">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 text-left">Jasa</th>
                    <th class="px-5 py-3 text-left">Freelancer</th>
                    <th class="px-5 py-3 text-left">Kategori</th>
                    <th class="px-5 py-3 text-left">Harga</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse ($jasas as $jasa)
                <tr>
                    <td class="px-5 py-4">
                        <p class="font-semibold text-slate-900">
                            {{ $jasa->nama_jasa }}
                        </p>
                        <p class="text-xs text-slate-500 line-clamp-1">
                            {{ $jasa->deskripsi }}
                        </p>
                    </td>

                    <td class="px-5 py-4">
                        {{ $jasa->freelancer->nama ?? '-' }}
                    </td>

                    <td class="px-5 py-4">
                        {{ $jasa->kategori }}
                    </td>

                    <td class="px-5 py-4">
                        Rp {{ number_format($jasa->harga, 0, ',', '.') }}
                    </td>

                    <td class="px-5 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if ($jasa->status_jasa === 'active') bg-green-100 text-green-700
                            @elseif ($jasa->status_jasa === 'pending') bg-yellow-100 text-yellow-700
                            @elseif ($jasa->status_jasa === 'rejected') bg-red-100 text-red-700
                            @else bg-slate-100 text-slate-700
                            @endif">
                            {{ ucfirst($jasa->status_jasa) }}
                        </span>
                    </td>

                    <td class="px-5 py-4">
                        <div class="flex justify-end gap-2">
                            @if ($jasa->status_jasa !== 'active')
                            <form method="POST" action="{{ route('admin.jasa.approve', $jasa->id) }}">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-2 rounded-lg bg-green-600 text-white text-xs font-semibold hover:bg-green-700">
                                    Approve
                                </button>
                            </form>
                            @endif

                            @if ($jasa->status_jasa !== 'rejected')
                            <form method="POST" action="{{ route('admin.jasa.reject', $jasa->id) }}">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-2 rounded-lg bg-red-600 text-white text-xs font-semibold hover:bg-red-700">
                                    Reject
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-slate-500">
                        Belum ada jasa.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    ```

</div>
<x-auto-refresh :seconds="30" />
@endsection