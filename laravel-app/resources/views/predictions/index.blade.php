<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Prediksi - Deteksi Penyakit Kuku</title>

    {{-- Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50:  '#f0f4ff',
                            100: '#e0e9ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    },
                    animation: {
                        'fade-in':  'fadeIn .35s ease both',
                        'slide-up': 'slideUp .4s ease both',
                    },
                    keyframes: {
                        fadeIn:  { from: { opacity: 0 }, to: { opacity: 1 } },
                        slideUp: { from: { opacity: 0, transform: 'translateY(16px)' }, to: { opacity: 1, transform: 'translateY(0)' } },
                    },
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .dark body { background: #0f172a; }

        /* Confidence bar fill */
        .conf-bar {
            background: linear-gradient(90deg, #6366f1, #818cf8);
            transition: width .6s ease;
        }
    </style>
</head>

{{-- ─────────────── PAGE ─────────────── --}}
<body class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 transition-colors duration-300">

{{-- ══ NAVBAR ══ --}}
<nav class="sticky top-0 z-50 w-full border-b border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md">
    <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ route('predictions.index') }}" class="flex items-center gap-2 group">
            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-brand-600 text-white text-sm font-bold shadow-sm">N</span>
            <span class="font-semibold text-slate-900 dark:text-white tracking-tight">NailDetect</span>
        </a>

        {{-- Nav actions --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('predictions.create') }}"
               class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium bg-brand-600 hover:bg-brand-700 text-white shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Upload Gambar
            </a>

            {{-- Dark mode toggle --}}
            <button id="theme-toggle" title="Toggle dark mode"
                    class="p-1.5 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                </svg>
                <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                </svg>
            </button>
        </div>
    </div>
</nav>

{{-- ══ MAIN ══ --}}
<main class="max-w-5xl mx-auto px-4 py-10 animate-slide-up">

    {{-- ── Error alert (session) ── --}}
    @if(session('error'))
    <div id="session-alert"
         class="mb-6 flex items-start gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 animate-fade-in">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
        </svg>
        <div class="flex-1 text-sm">{{ session('error') }}</div>
        <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    @endif

    {{-- ── Card ── --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">

        {{-- Header --}}
        <div class="px-6 pt-6 pb-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"/>
            </svg>
            <h1 class="text-lg font-semibold text-slate-900 dark:text-white">Riwayat Prediksi</h1>
        </div>

        <div class="px-6 py-6">
            @if($predictions->isEmpty())
                {{-- ── Empty state ── --}}
                <div class="flex flex-col items-center justify-center text-center py-12 animate-fade-in">
                    <div class="w-16 h-16 rounded-full bg-brand-50 dark:bg-brand-900/30 flex items-center justify-center ring-4 ring-brand-100 dark:ring-brand-900/50 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Belum ada data prediksi</p>
                    <p class="mt-1 text-xs text-slate-400">Unggah gambar kuku untuk memulai deteksi pertama Anda.</p>
                    <a href="{{ route('predictions.create') }}"
                       class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold bg-brand-600 hover:bg-brand-700 active:scale-[.98] text-white shadow-sm shadow-brand-200 dark:shadow-none transition-all duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        Upload Gambar Pertama
                    </a>
                </div>
            @else
                {{-- ── Table ── --}}
                <div class="overflow-x-auto -mx-6">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-800">
                                <th class="px-6 py-2.5">#</th>
                                <th class="px-6 py-2.5">Gambar</th>
                                <th class="px-6 py-2.5">Hasil</th>
                                <th class="px-6 py-2.5">Confidence</th>
                                <th class="px-6 py-2.5">Status</th>
                                <th class="px-6 py-2.5">Waktu</th>
                                <th class="px-6 py-2.5"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($predictions as $p)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-3 text-slate-500 dark:text-slate-400">{{ $p->id }}</td>
                                <td class="px-6 py-3">
                                    <img src="{{ Storage::url($p->image_path) }}"
                                         class="w-12 h-12 rounded-lg object-cover border border-slate-200 dark:border-slate-700"
                                         alt="Thumbnail">
                                </td>
                                <td class="px-6 py-3">
                                    @if($p->result && $p->result !== 'invalid')
                                        @php
                                            $resultStyles = [
                                                'healthy'        => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 ring-1 ring-emerald-200 dark:ring-emerald-800',
                                                'onychomycosis'  => 'bg-red-50 text-red-700 dark:bg-red-950/50 dark:text-red-300 ring-1 ring-red-200 dark:ring-red-800',
                                                'psoriasis'      => 'bg-orange-50 text-orange-700 dark:bg-orange-950/50 dark:text-orange-300 ring-1 ring-orange-200 dark:ring-orange-800',
                                            ];
                                            $resultClass = $resultStyles[$p->result] ?? 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300 ring-1 ring-slate-200 dark:ring-slate-700';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $resultClass }}">
                                            {{ ucfirst($p->result) }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    @if($p->confidence)
                                        <div class="flex items-center gap-2">
                                            <div class="w-20 h-1.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                                <div class="conf-bar h-full rounded-full" style="width: {{ (int) $p->confidence }}%"></div>
                                            </div>
                                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $p->confidence }}%</span>
                                        </div>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    @php
                                        $statusStyles = [
                                            'done'       => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 ring-1 ring-emerald-200 dark:ring-emerald-800',
                                            'error'      => 'bg-red-50 text-red-700 dark:bg-red-950/50 dark:text-red-300 ring-1 ring-red-200 dark:ring-red-800',
                                            'processing' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300 ring-1 ring-amber-200 dark:ring-amber-800',
                                        ];
                                        $statusClass = $statusStyles[$p->status] ?? 'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300 ring-1 ring-amber-200 dark:ring-amber-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-xs text-slate-400 whitespace-nowrap">{{ $p->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-3 text-right">
                                    @if($p->status === 'done')
                                        <a href="{{ route('predictions.show', $p->id) }}"
                                           class="inline-flex items-center gap-1 text-xs font-medium text-brand-600 dark:text-brand-400 hover:text-brand-700 dark:hover:text-brand-300 transition-colors">
                                            Detail
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5 15.75 12l-7.5 7.5"/>
                                            </svg>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4 px-2 text-sm text-slate-500 dark:text-slate-400">
                    {{ $predictions->links() }}
                </div>
            @endif
        </div>
    </div>
</main>

{{-- ══ FOOTER ══ --}}
<footer class="mt-10 pb-6 text-center text-xs text-slate-400 dark:text-slate-600">
    NailDetect &mdash; Deteksi penyakit kuku berbasis AI &copy; {{ date('Y') }}
</footer>

{{-- ══ SCRIPTS ══ --}}
<script>
    /* ── Dark mode ── */
    const html = document.documentElement;
    const saved = localStorage.getItem('theme');
    if (saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        html.classList.add('dark');
    }
    document.getElementById('theme-toggle').addEventListener('click', () => {
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    });
</script>

</body>
</html>