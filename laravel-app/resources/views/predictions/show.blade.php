<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Prediksi - Deteksi Penyakit Kuku</title>

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

        /* Confidence & probability bars */
        .bar-fill {
            transition: width .8s ease;
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
            <a href="{{ route('predictions.index') }}"
               class="flex items-center gap-1.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"/>
                </svg>
                Riwayat
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
<main class="max-w-3xl mx-auto px-4 py-10 animate-slide-up">

    <div class="grid md:grid-cols-2 gap-5">

        {{-- ── Gambar ── --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
            <div class="aspect-square">
                <img src="{{ Storage::url($prediction->image_path) }}"
                     class="w-full h-full object-cover"
                     alt="Gambar kuku">
            </div>
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/>
                </svg>
                <span class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ basename($prediction->image_path) }}</span>
            </div>
        </div>

        {{-- ── Hasil ── --}}
        <div class="flex flex-col gap-5">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">

                {{-- Header --}}
                <div class="px-6 pt-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h1 class="text-lg font-semibold text-slate-900 dark:text-white">Hasil Analisis</h1>
                </div>

                <div class="px-6 py-6">

                    {{-- Label hasil --}}
                    @php
                        $resultMeta = [
                            'healthy' => [
                                'label' => 'Healthy',
                                'icon'  => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
                                'wrap'  => 'bg-emerald-50 dark:bg-emerald-950/40 ring-emerald-100 dark:ring-emerald-900/50',
                                'icon_color' => 'text-emerald-500',
                                'text_color' => 'text-emerald-700 dark:text-emerald-300',
                            ],
                            'onychomycosis' => [
                                'label' => 'Onychomycosis',
                                'icon'  => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z',
                                'wrap'  => 'bg-red-50 dark:bg-red-950/40 ring-red-100 dark:ring-red-900/50',
                                'icon_color' => 'text-red-500',
                                'text_color' => 'text-red-700 dark:text-red-300',
                            ],
                            'psoriasis' => [
                                'label' => 'Psoriasis',
                                'icon'  => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z',
                                'wrap'  => 'bg-orange-50 dark:bg-orange-950/40 ring-orange-100 dark:ring-orange-900/50',
                                'icon_color' => 'text-orange-500',
                                'text_color' => 'text-orange-700 dark:text-orange-300',
                            ],
                        ];
                        $meta = $resultMeta[$prediction->result] ?? [
                            'label' => ucfirst($prediction->result ?? 'Tidak diketahui'),
                            'icon'  => 'M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h3A2.25 2.25 0 0 1 15.75 5.25V9m-8.25 0h9.75a2.25 2.25 0 0 1 2.25 2.25v8.25a2.25 2.25 0 0 1-2.25 2.25H5.25a2.25 2.25 0 0 1-2.25-2.25V11.25A2.25 2.25 0 0 1 5.25 9Z',
                            'wrap'  => 'bg-slate-100 dark:bg-slate-800 ring-slate-200 dark:ring-slate-700',
                            'icon_color' => 'text-slate-400',
                            'text_color' => 'text-slate-600 dark:text-slate-300',
                        ];
                    @endphp

                    <div class="flex flex-col items-center text-center mb-6 animate-fade-in">
                        <div class="w-16 h-16 rounded-full {{ $meta['wrap'] }} ring-4 flex items-center justify-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 {{ $meta['icon_color'] }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $meta['icon'] }}"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold {{ $meta['text_color'] }}">{{ $meta['label'] }}</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Confidence: <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $prediction->confidence }}%</span>
                        </p>
                    </div>

                    {{-- Progress bar confidence --}}
                    <div class="mb-6">
                        <div class="h-2.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                            <div class="bar-fill h-full rounded-full bg-gradient-to-r from-brand-500 to-brand-600"
                                 style="width: {{ (int) $prediction->confidence }}%"></div>
                        </div>
                    </div>

                    {{-- Probabilitas per kelas --}}
                    @if($prediction->probabilities)
                        @php $probs = json_decode($prediction->probabilities, true); @endphp
                        <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Probabilitas per Kelas</h3>
                        <div class="space-y-3">
                            @foreach($probs as $class => $prob)
                                @php
                                    $probColors = [
                                        'healthy'       => 'from-emerald-400 to-emerald-500',
                                        'onychomycosis' => 'from-red-400 to-red-500',
                                        'psoriasis'     => 'from-orange-400 to-orange-500',
                                    ];
                                    $probColor = $probColors[$class] ?? 'from-slate-400 to-slate-500';
                                @endphp
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-xs font-medium text-slate-600 dark:text-slate-300">{{ ucfirst($class) }}</span>
                                        <span class="text-xs text-slate-400">{{ $prob }}%</span>
                                    </div>
                                    <div class="h-1.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                        <div class="bar-fill h-full rounded-full bg-gradient-to-r {{ $probColor }}"
                                             style="width: {{ (int) $prob }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Info waktu --}}
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2 text-xs text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"/>
                        </svg>
                        Dianalisis {{ $prediction->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            {{-- ── Tombol aksi ── --}}
            <div class="flex flex-col gap-2.5">
                <a href="{{ route('predictions.create') }}"
                   class="flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold bg-brand-600 hover:bg-brand-700 active:scale-[.98] text-white shadow-sm shadow-brand-200 dark:shadow-none transition-all duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                    Analisis Gambar Baru
                </a>
                <a href="{{ route('predictions.index') }}"
                   class="flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 active:scale-[.98] transition-all duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"/>
                    </svg>
                    Lihat Riwayat
                </a>
            </div>
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