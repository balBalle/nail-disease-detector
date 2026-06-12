from flask import Flask, request, jsonify
from flask_cors import CORS
import os
import numpy as np
import cv2
from skimage.feature import graycomatrix, graycoprops
from scipy import stats
import joblib

app = Flask(__name__)
CORS(app)

UPLOAD_FOLDER = 'uploads'
os.makedirs(UPLOAD_FOLDER, exist_ok=True)

ALLOWED_EXTENSIONS = {'jpg', 'jpeg', 'png'}

# ── Load Model ───────────────────────────────────────────
print("[INFO] Memuat model...")
model = joblib.load('models/svm_model.pkl')
le    = joblib.load('models/label_encoder.pkl')
print("[INFO] Model berhasil dimuat!")

# ── Helper Functions ─────────────────────────────────────
def allowed_file(filename):
    return '.' in filename and \
           filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

def preprocess(filepath):
    img = cv2.imread(filepath)
    img = cv2.resize(img, (256, 256))
    img = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    img = cv2.GaussianBlur(img, (5, 5), 0)
    return img

def extract_glcm_features(img):
    img_q     = (img // 32).astype(np.uint8)
    distances = [1, 2, 3]
    angles    = [0, np.pi/4, np.pi/2, 3*np.pi/4]
    glcm      = graycomatrix(img_q, distances=distances,
                              angles=angles, levels=8,
                              symmetric=True, normed=True)
    features = []
    for prop in ['contrast', 'dissimilarity',
                 'homogeneity', 'energy', 'correlation']:
        values = graycoprops(glcm, prop).flatten()
        features.append(values.mean())
        features.append(values.std())

    features.append(float(img.mean()))
    features.append(float(img.std()))
    features.append(float(stats.skew(img.flatten())))
    features.append(float(stats.kurtosis(img.flatten())))

    hist, _ = np.histogram(img, bins=8, range=(0, 256))
    hist     = hist / hist.sum()
    features.extend(hist.tolist())

    return np.array(features)

def is_nail_image(filepath):
    img = cv2.imread(filepath)

    # Resize dulu sebelum analisis
    img = cv2.resize(img, (256, 256))

    img_hsv = cv2.cvtColor(img, cv2.COLOR_BGR2HSV)
    gray    = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

    # ── Cek 1: Skin tone ─────────────────────────────────
    lower_skin = np.array([0,  20,  70], dtype=np.uint8)
    upper_skin = np.array([20, 255, 255], dtype=np.uint8)
    skin_mask  = cv2.inRange(img_hsv, lower_skin, upper_skin)
    total_px   = 256 * 256
    skin_ratio = np.sum(skin_mask > 0) / total_px

    print(f"[DEBUG] Skin ratio : {skin_ratio:.3f}")

    # ── Cek 2: Variance ───────────────────────────────────
    variance = float(np.var(gray))
    print(f"[DEBUG] Variance   : {variance:.2f}")

    # ── Cek 3: Brightness ────────────────────────────────
    brightness = float(np.mean(gray))
    print(f"[DEBUG] Brightness : {brightness:.2f}")

    # ── Keputusan ─────────────────────────────────────────
    reasons = []

    if skin_ratio < 0.08:
        reasons.append("tidak terdeteksi warna kulit/kuku")

    if brightness < 30:
        reasons.append("gambar terlalu gelap")

    if brightness > 240:
        reasons.append("gambar terlalu terang/putih")

    if variance < 100:
        reasons.append("tekstur gambar terlalu seragam")

    is_valid = len(reasons) == 0

    return is_valid, reasons

# ── Routes ───────────────────────────────────────────────
@app.route('/')
def index():
    return jsonify({
        'status':  'running',
        'message': 'Python GLCM Service is running!',
        'version': '3.0.0'
    })

@app.route('/predict', methods=['POST'])
def predict():
    if 'image' not in request.files:
        return jsonify({
            'success': False,
            'message': 'Tidak ada file gambar yang dikirim'
        }), 400

    file = request.files['image']

    if file.filename == '':
        return jsonify({
            'success': False,
            'message': 'Tidak ada file yang dipilih'
        }), 400

    if not allowed_file(file.filename):
        return jsonify({
            'success': False,
            'message': 'Format tidak didukung. Gunakan JPG, JPEG, atau PNG'
        }), 400

    # Simpan file
    filepath = os.path.join(UPLOAD_FOLDER, file.filename)
    file.save(filepath)
    print(f"\n[INFO] File diterima: {file.filename}")

    # ── Validasi Gambar Kuku ─────────────────────────────
    print("[INFO] Memvalidasi gambar...")
    is_valid, reasons = is_nail_image(filepath)

    if not is_valid:
        print(f"[WARNING] Bukan gambar kuku: {reasons}")
        return jsonify({
            'success': False,
            'message': 'Gambar bukan kuku, silakan upload gambar yang valid',
            'reasons': reasons
        }), 422

    print("[INFO] Validasi OK, memulai prediksi...")

    try:
        # Preprocessing & Ekstraksi Fitur
        img      = preprocess(filepath)
        features = extract_glcm_features(img)

        # Prediksi
        features_2d   = features.reshape(1, -1)
        prediction    = model.predict(features_2d)[0]
        probabilities = model.predict_proba(features_2d)[0]
        confidence    = float(round(probabilities.max() * 100, 2))
        label         = le.inverse_transform([prediction])[0]

        # Probabilitas per kelas
        prob_dict = {}
        for i, cls in enumerate(le.classes_):
            prob_dict[cls] = float(round(probabilities[i] * 100, 2))

        print(f"[INFO] Hasil: {label} ({confidence}%)")

        return jsonify({
            'success':       True,
            'message':       'Prediksi berhasil',
            'filename':      file.filename,
            'result':        label,
            'confidence':    confidence,
            'probabilities': prob_dict
        })

    except Exception as e:
        print(f"[ERROR] {str(e)}")
        return jsonify({
            'success': False,
            'message': f'Error saat prediksi: {str(e)}'
        }), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)