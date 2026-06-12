<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nail Disease Detector</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .navbar { background: linear-gradient(135deg, #667eea, #764ba2); }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.08); }
        .badge-healthy { background: #28a745; }
        .badge-onychomycosis { background: #dc3545; }
        .badge-psoriasis { background: #fd7e14; }
        .badge-error { background: #6c757d; }
        .thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body>
<nav class="navbar navbar-dark px-4 py-3 mb-4">
    <span class="navbar-brand fw-bold">🔬 Nail Disease Detector</span>
    <a href="{{ route('predictions.create') }}" class="btn btn-light btn-sm">
        + Upload Gambar
    </a>
</nav>

<div class="container">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            ⚠️ {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card p-4">
        <h5 class="mb-4">📋 Riwayat Prediksi</h5>

        @if($predictions->isEmpty())
            <div class="text-center py-5 text-muted">
                <h1>🔍</h1>
                <p>Belum ada data prediksi</p>
                <a href="{{ route('predictions.create') }}" class="btn btn-primary">
                    Upload Gambar Pertama
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Gambar</th>
                            <th>Hasil</th>
                            <th>Confidence</th>
                            <th>Status</th>
                            <th>Waktu</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($predictions as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>
                                <img src="{{ Storage::url($p->image_path) }}"
                                     class="thumb">
                            </td>
                            <td>
                                @if($p->result && $p->result !== 'invalid')
                                    <span class="badge badge-{{ $p->result }} text-white px-3 py-2">
                                        {{ ucfirst($p->result) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($p->confidence)
                                    <div class="progress" style="width:100px; height:8px">
                                        <div class="progress-bar bg-success conf-bar"
     data-width="{{ (int) $p->confidence }}"></div>
                                    </div>
                                    <small>{{ $p->confidence }}%</small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $p->status === 'done' ? 'success' : ($p->status === 'error' ? 'danger' : 'warning') }}">
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td><small class="text-muted">{{ $p->created_at->diffForHumans() }}</small></td>
                            <td>
                                @if($p->status === 'done')
                                    <a href="{{ route('predictions.show', $p->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $predictions->links() }}
        @endif
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.conf-bar').forEach(el => {
        el.style.width = el.getAttribute('data-width') + '%';
    });
</script>
</body>
</html>