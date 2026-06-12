<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deteksi Penyakit Kuku</title>

    {{-- Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Heroicons --}}
    <script src="https://unpkg.com/@heroicons/react/24/outline" type="module"></script>

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
                        'fade-in':    'fadeIn .35s ease both',
                        'slide-up':   'slideUp .4s ease both',
                        'spin-slow':  'spin 1.2s linear infinite',
                        'pulse-ring': 'pulseRing 1.5s ease-out infinite',
                    },
                    keyframes: {
                        fadeIn:    { from: { opacity: 0 }, to: { opacity: 1 } },
                        slideUp:   { from: { opacity: 0, transform: 'translateY(16px)' }, to: { opacity: 1, transform: 'translateY(0)' } },
                        pulseRing: {
                            '0%':   { transform: 'scale(1)', opacity: .6 },
                            '100%': { transform: 'scale(1.6)', opacity: 0 },
                        },
                    },
                }
            }
        }
    </script>

    <style>
        /* ── Base ── */
        body { font-family: 'Inter', sans-serif; }

        /* ── Dark-mode toggle ── */
        .dark body { background: #0f172a; }

        /* ── Upload drag state ── */
        #drop-zone.drag-over {
            border-color: #6366f1;
            background-color: rgba(99,102,241,.06);
            transform: scale(1.01);
        }

        /* ── Spinner ring ── */
        .spinner {
            width: 40px; height: 40px;
            border: 3px solid #e0e9ff;
            border-top-color: #6366f1;
            border-radius: 50%;
            animation: spin 0.9s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Progress bar shimmer ── */
        .shimmer {
            background: linear-gradient(90deg, #6366f1 0%, #818cf8 50%, #6366f1 100%);
            background-size: 200%;
            animation: shimmer 1.5s linear infinite;
        }
        @keyframes shimmer { to { background-position: -200%; } }

        /* ── Toast ── */
        #toast {
            transition: opacity .3s, transform .3s;
            pointer-events: none;
        }
        #toast.show { opacity: 1; transform: translateY(0); }
        #toast.hide { opacity: 0; transform: translateY(12px); }
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
                {{-- History icon --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"/>
                </svg>
                Riwayat
            </a>

            {{-- Dark mode toggle --}}
            <button id="theme-toggle" title="Toggle dark mode"
                    class="p-1.5 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                {{-- Sun --}}
                <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                </svg>
                {{-- Moon --}}
                <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                </svg>
            </button>
        </div>
    </div>
</nav>

{{-- ══ MAIN ══ --}}
<main class="max-w-xl mx-auto px-4 py-10 animate-slide-up">

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
        <div class="px-6 pt-6 pb-4 border-b border-slate-100 dark:border-slate-800">
            <h1 class="text-lg font-semibold text-slate-900 dark:text-white">Upload Gambar Kuku</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Unggah foto kuku Anda untuk mendeteksi kemungkinan penyakit menggunakan AI.
            </p>
        </div>

        <div class="px-6 py-6">
            <form id="upload-form" action="{{ route('predictions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- ── Drop Zone ── --}}
                <div id="drop-zone"
                     class="relative flex flex-col items-center justify-center gap-3 min-h-[220px] rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-700 cursor-pointer transition-all duration-200 hover:border-brand-500 hover:bg-brand-50/50 dark:hover:bg-brand-900/10 group"
                     onclick="document.getElementById('imageInput').click()">

                    {{-- Placeholder (hidden when preview shown) --}}
                    <div id="upload-placeholder" class="flex flex-col items-center gap-2 pointer-events-none select-none">
                        <div class="w-14 h-14 rounded-full bg-brand-50 dark:bg-brand-900/30 flex items-center justify-center ring-4 ring-brand-100 dark:ring-brand-900/50 group-hover:scale-105 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                <span class="text-brand-600 dark:text-brand-400">Klik untuk upload</span> atau seret ke sini
                            </p>
                            <p class="mt-1 text-xs text-slate-400">JPG, JPEG, PNG — Maks. 2 MB</p>
                        </div>
                    </div>

                    {{-- Image preview --}}
                    <div id="preview-wrapper" class="hidden w-full h-full absolute inset-0 rounded-[10px] overflow-hidden">
                        <img id="preview-img" src="#" alt="Preview"
                             class="w-full h-full object-cover">
                        {{-- Overlay on hover --}}
                        <div class="absolute inset-0 bg-slate-900/50 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center gap-2 text-white text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                            </svg>
                            Ganti gambar
                        </div>
                    </div>

                </div>

                {{-- File name badge --}}
                <div id="file-badge" class="hidden mt-3 flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                    </svg>
                    <span id="file-name">—</span>
                    <button type="button" id="remove-btn"
                            class="ml-auto text-red-400 hover:text-red-600 transition-colors"
                            title="Hapus gambar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Validation error --}}
                @error('image')
                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror

                <input type="file" id="imageInput" name="image"
                       accept="image/jpg,image/jpeg,image/png"
                       class="sr-only" required>

                {{-- Submit button --}}
                <button id="submit-btn" type="submit"
                        class="mt-6 w-full flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold bg-brand-600 hover:bg-brand-700 active:scale-[.98] text-white shadow-sm shadow-brand-200 dark:shadow-none transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                    {{-- Default state --}}
                    <span id="btn-default" class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                        Deteksi Sekarang
                    </span>
                    {{-- Loading state --}}
                    <span id="btn-loading" class="hidden flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin-slow"></span>
                        Menganalisis…
                    </span>
                </button>
            </form>

            {{-- ── Loading overlay ── --}}
            <div id="loading-overlay"
                 class="hidden fixed inset-0 z-50 flex flex-col items-center justify-center gap-5 bg-white/80 dark:bg-slate-950/80 backdrop-blur-sm animate-fade-in">
                <div class="relative flex items-center justify-center">
                    <span class="absolute w-16 h-16 rounded-full bg-brand-200 dark:bg-brand-800 animate-pulse-ring"></span>
                    <div class="spinner"></div>
                </div>
                <div class="text-center">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Sedang menganalisis gambar…</p>
                    <p class="text-xs text-slate-400 mt-1">Mohon tunggu sebentar</p>
                </div>
                {{-- Fake progress bar --}}
                <div class="w-48 h-1 rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                    <div id="loading-bar" class="h-full w-0 rounded-full shimmer transition-[width] duration-700"></div>
                </div>
            </div>

        </div>
    </div>

    {{-- Tips section --}}
    <div class="mt-5 grid grid-cols-3 gap-3 text-center animate-fade-in" style="animation-delay:.1s">
        @foreach([
            ['icon' => 'M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z', 'label' => 'Foto jernih', 'sub' => 'Pastikan gambar tajam & terang'],
            ['icon' => 'M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15', 'label' => 'Close-up kuku', 'sub' => 'Fokus pada satu kuku'],
            ['icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'label' => 'Format valid', 'sub' => 'JPG atau PNG, maks 2 MB'],
        ] as $tip)
        <div class="p-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto mb-1.5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $tip['icon'] }}"/>
            </svg>
            <p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $tip['label'] }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5 leading-snug">{{ $tip['sub'] }}</p>
        </div>
        @endforeach
    </div>
</main>

{{-- ══ FOOTER ══ --}}
<footer class="mt-10 pb-6 text-center text-xs text-slate-400 dark:text-slate-600">
    NailDetect &mdash; Deteksi penyakit kuku berbasis AI &copy; {{ date('Y') }}
</footer>

{{-- ── Toast notification ── --}}
<div id="toast"
     class="hide fixed bottom-6 left-1/2 -translate-x-1/2 z-[60] flex items-center gap-2 px-4 py-2.5 rounded-xl shadow-lg text-sm font-medium bg-slate-800 dark:bg-slate-700 text-white max-w-[90vw]">
    <span id="toast-icon" class="text-lg"></span>
    <span id="toast-msg"></span>
</div>

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

    /* ── Toast helper ── */
    function showToast(msg, icon = '⚠️', duration = 4000) {
        const t = document.getElementById('toast');
        document.getElementById('toast-msg').textContent  = msg;
        document.getElementById('toast-icon').textContent = icon;
        t.classList.replace('hide', 'show');
        setTimeout(() => t.classList.replace('show', 'hide'), duration);
    }

    /* ── File input & drag-and-drop ── */
    const inputEl       = document.getElementById('imageInput');
    const dropZone      = document.getElementById('drop-zone');
    const placeholder   = document.getElementById('upload-placeholder');
    const previewWrap   = document.getElementById('preview-wrapper');
    const previewImg    = document.getElementById('preview-img');
    const fileBadge     = document.getElementById('file-badge');
    const fileNameEl    = document.getElementById('file-name');
    const removeBtn     = document.getElementById('remove-btn');
    const submitBtn     = document.getElementById('submit-btn');
    const MAX_MB        = 2;
    const ALLOWED_TYPES = ['image/jpeg', 'image/png'];

    function setPreview(file) {
        if (!ALLOWED_TYPES.includes(file.type)) {
            showToast('Format tidak didukung. Gunakan JPG atau PNG.', '🚫');
            return;
        }
        if (file.size > MAX_MB * 1024 * 1024) {
            showToast(`Ukuran file melebihi ${MAX_MB} MB.`, '📦');
            return;
        }
        const reader = new FileReader();
        reader.onload = e => {
            previewImg.src = e.target.result;
            placeholder.classList.add('hidden');
            previewWrap.classList.remove('hidden');
            fileBadge.classList.remove('hidden');
            fileBadge.classList.add('flex');
            fileNameEl.textContent = file.name;
        };
        reader.readAsDataURL(file);
    }

    inputEl.addEventListener('change', e => {
        if (e.target.files[0]) setPreview(e.target.files[0]);
    });

    removeBtn.addEventListener('click', e => {
        e.stopPropagation();
        inputEl.value = '';
        previewImg.src = '#';
        placeholder.classList.remove('hidden');
        previewWrap.classList.add('hidden');
        fileBadge.classList.add('hidden');
        fileBadge.classList.remove('flex');
    });

    /* Drag & drop */
    ['dragenter','dragover'].forEach(ev => {
        dropZone.addEventListener(ev, e => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });
    });
    ['dragleave','drop'].forEach(ev => {
        dropZone.addEventListener(ev, e => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
            if (ev === 'drop' && e.dataTransfer.files[0]) {
                const dt = new DataTransfer();
                dt.items.add(e.dataTransfer.files[0]);
                inputEl.files = dt.files;
                setPreview(e.dataTransfer.files[0]);
            }
        });
    });

    /* ── Form submit — show loading ── */
    const form           = document.getElementById('upload-form');
    const loadingOverlay = document.getElementById('loading-overlay');
    const loadingBar     = document.getElementById('loading-bar');
    const btnDefault     = document.getElementById('btn-default');
    const btnLoading     = document.getElementById('btn-loading');

    form.addEventListener('submit', e => {
        if (!inputEl.files.length) {
            e.preventDefault();
            showToast('Pilih gambar terlebih dahulu.', '📸');
            return;
        }
        /* Show loading UI */
        submitBtn.disabled = true;
        btnDefault.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        loadingOverlay.classList.remove('hidden');

        /* Animate fake progress: 0 → 80% quickly, then slowly crawl */
        let pct = 0;
        const tick = setInterval(() => {
            pct = pct < 75 ? pct + (Math.random() * 8) : pct + .5;
            if (pct > 92) { clearInterval(tick); }
            loadingBar.style.width = Math.min(pct, 92) + '%';
        }, 300);
    });
</script>

</body>
</html>