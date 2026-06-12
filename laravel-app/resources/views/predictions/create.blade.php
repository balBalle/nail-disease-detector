<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Gambar - Nail Disease Detector</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .navbar { background: linear-gradient(135deg, #667eea, #764ba2); }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.08); }
        .upload-area {
            border: 2px dashed #667eea;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: background 0.2s;
        }
        .upload-area:hover { background: #f0f0ff; }
        #preview { max-width: 100%; max-height: 300px; border-radius: 10px; display: none; margin-top: 15px; }
    </style>
</head>
<body>
<nav class="navbar navbar-dark px-4 py-3 mb-4">
    <span class="navbar-brand fw-bold">🔬 Nail Disease Detector</span>
    <a href="{{ route('predictions.index') }}" class="btn btn-light btn-sm">
        📋 Riwayat
    </a>
</nav>

<div class="container" style="max-width: 600px">

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            ⚠️ {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card p-4">
        <h5 class="mb-4">📤 Upload Gambar Kuku</h5>

        <form action="{{ route('predictions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="upload-area" onclick="document.getElementById('imageInput').click()">
                <div id="uploadPlaceholder">
                    <div style="font-size: 3rem">🖼️</div>
                    <p class="mb-0 text-muted">Klik untuk pilih gambar</p>
                    <small class="text-muted">JPG, JPEG, PNG — Maks. 2MB</small>
                </div>
                <img id="preview" src="#" alt="Preview">
            </div>

            <input type="file"
                   id="imageInput"
                   name="image"
                   accept="image/jpg,image/jpeg,image/png"
                   class="d-none"
                   required>

            @error('image')
                <div class="text-danger mt-2 small">{{ $message }}</div>
            @enderror

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    🔍 Analisis Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('imageInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (evt) {
            const preview = document.getElementById('preview');
            const placeholder = document.getElementById('uploadPlaceholder');
            preview.src = evt.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });
</script>
</body>
</html>