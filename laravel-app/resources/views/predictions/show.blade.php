<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Prediksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .navbar { background: linear-gradient(135deg, #667eea, #764ba2); }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.08); }
        .result-healthy { color: #28a745; }
        .result-onychomycosis { color: #dc3545; }
        .result-psoriasis { color: #fd7e14; }
        .nail-img { width: 100%; max-height: 300px; object-fit: cover; border-radius: 12px; }
    </style>
</head>
<body>
<nav class="navbar navbar-dark px-4 py-3 mb-4">
    <span class="navbar-brand fw-bold">🔬 Nail Disease Detector</span>
    <a href="{{ route('predictions.index') }}" class="btn btn-light btn-sm">
        ← Riwayat
    </a>
</nav>

<div class="container" style="max-width: 700px">
    <div class="row g-4">

        {{-- Gambar --}}
        <div class="col-md-5">
            <div class="card p-3">
                <img src="{{ Storage::url($prediction->image_path) }}"
                     class="nail-img">
                <small class="text-muted mt-2 d-block text-center">
                    {{ basename($prediction->image_path) }}
                </small>
            </div>
        </div>

        {{-- Hasil --}}
        <div class="col-md-7">
            <div class="card p-4">
                <h5 class="mb-3">📊 Hasil Analisis</h5>

                {{-- Label Hasil --}}
                <div class="text-center mb-4">
                    @php
                        $icons = [
                            'healthy'       => '✅',
                            'onychomycosis' => '🦠',
                            'psoriasis'     => '⚠️',
                        ];
                        $icon = $icons[$prediction->result] ?? '❓';
                    @endphp
                    <div style="font-size: 3rem">{{ $icon }}</div>
                    <h3 class="result-{{ $prediction->result }} fw-bold">
                        {{ ucfirst($prediction->result) }}
                    </h3>
                    <p class="text-muted">
                        Confidence: <strong>{{ $prediction->confidence }}%</strong>
                    </p>
                </div>

                {{-- Progress bar confidence --}}
                <div class="mb-4">
                    <div class="progress" style="height: 12px; border-radius: 6px">
                        <div class="progress-bar bg-success"
     data-width="{{ (int) $prediction->confidence }}"
     id="confidenceBar">
                        </div>
                    </div>
                </div>

                {{-- Probabilitas per kelas --}}
                @if($prediction->probabilities)
                    @php $probs = json_decode($prediction->probabilities, true); @endphp
                    <h6 class="mb-3">Probabilitas per Kelas:</h6>
                    @foreach($probs as $class => $prob)
                        @php
                            $colors = [
                                'healthy'       => 'success',
                                'onychomycosis' => 'danger',
                                'psoriasis'     => 'warning',
                            ];
                            $color = $colors[$class] ?? 'secondary';
                        @endphp
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <small>{{ ucfirst($class) }}</small>
                                <small>{{ $prob }}%</small>
                            </div>
                            <div class="progress" style="height: 8px">
                                <div class="progress-bar bg-{{ $color }} prob-bar"
     data-width="{{ (int) $prob }}"></div>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- Info waktu --}}
                <hr>
                <small class="text-muted">
                    🕐 Dianalisis {{ $prediction->created_at->diffForHumans() }}
                </small>
            </div>

            {{-- Tombol aksi --}}
            <div class="d-grid gap-2 mt-3">
                <a href="{{ route('predictions.create') }}"
                   class="btn btn-primary">
                    🔍 Analisis Gambar Baru
                </a>
                <a href="{{ route('predictions.index') }}"
                   class="btn btn-outline-secondary">
                    📋 Lihat Riwayat
                </a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Set progress bar width via JavaScript
    const bar = document.getElementById('confidenceBar');
    if (bar) bar.style.width = bar.getAttribute('data-width') + '%';

    // Set semua progress bar probabilitas
    document.querySelectorAll('.prob-bar').forEach(el => {
        el.style.width = el.getAttribute('data-width') + '%';
    });
</script>
</body>
</html>