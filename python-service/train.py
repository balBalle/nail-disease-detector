import os
import numpy as np
import cv2
from skimage.feature import graycomatrix, graycoprops
from scipy import stats
from sklearn.svm import SVC
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder, StandardScaler
from sklearn.metrics import classification_report, confusion_matrix
from sklearn.utils.class_weight import compute_class_weight
from sklearn.pipeline import Pipeline
import joblib

# ── Konfigurasi ──────────────────────────────────────────
DATASET_PATH = 'dataset'
MODEL_PATH   = 'models'
CATEGORIES   = ['healthy', 'onychomycosis', 'psoriasis']
IMG_SIZE     = (256, 256)

os.makedirs(MODEL_PATH, exist_ok=True)

# ── Fungsi Preprocessing ─────────────────────────────────
def preprocess(filepath):
    img = cv2.imread(filepath)
    img = cv2.resize(img, IMG_SIZE)
    img = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    img = cv2.GaussianBlur(img, (5, 5), 0)
    return img

# ── Fungsi Ekstraksi Fitur GLCM ──────────────────────────
def extract_glcm_features(img):
    # Kuantisasi ke 8 level
    img_q = (img // 32).astype(np.uint8)

    # Multi-distance GLCM
    distances = [1, 2, 3]
    angles    = [0, np.pi/4, np.pi/2, 3*np.pi/4]
    glcm      = graycomatrix(img_q, distances=distances,
                              angles=angles, levels=8,
                              symmetric=True, normed=True)

    # Ekstrak properti GLCM
    features = []
    for prop in ['contrast', 'dissimilarity',
                 'homogeneity', 'energy', 'correlation']:
        values = graycoprops(glcm, prop).flatten()
        features.append(values.mean())
        features.append(values.std())

    # Tambah fitur statistik
    features.append(float(img.mean()))
    features.append(float(img.std()))
    features.append(float(stats.skew(img.flatten())))
    features.append(float(stats.kurtosis(img.flatten())))

    # Tambah fitur histogram (8 bin)
    hist, _ = np.histogram(img, bins=8, range=(0, 256))
    hist     = hist / hist.sum()
    features.extend(hist.tolist())

    return np.array(features)

# ── Load Dataset & Ekstrak Fitur ─────────────────────────
print("=" * 50)
print("Memuat dataset dan mengekstrak fitur GLCM...")
print("=" * 50)

X, y = [], []

for category in CATEGORIES:
    folder = os.path.join(DATASET_PATH, category)
    files  = [f for f in os.listdir(folder)
              if f.lower().endswith(('.jpg', '.jpeg', '.png'))]

    print(f"\n[INFO] Memproses: {category} ({len(files)} gambar)")

    for i, filename in enumerate(files):
        filepath = os.path.join(folder, filename)
        try:
            img      = preprocess(filepath)
            features = extract_glcm_features(img)
            X.append(features)
            y.append(category)

            if (i + 1) % 50 == 0:
                print(f"  → {i + 1}/{len(files)} selesai")

        except Exception as e:
            print(f"  ⚠ Skip {filename}: {e}")

X = np.array(X)
y = np.array(y)

print(f"\n[INFO] Total sampel : {len(X)}")
print(f"[INFO] Jumlah fitur : {X.shape[1]}")

# ── Encode Label ─────────────────────────────────────────
le = LabelEncoder()
y_encoded = le.fit_transform(y)
print(f"[INFO] Kelas: {list(le.classes_)}")

# ── Split Data ───────────────────────────────────────────
X_train, X_test, y_train, y_test = train_test_split(
    X, y_encoded,
    test_size=0.2,
    random_state=42,
    stratify=y_encoded
)

print(f"\n[INFO] Data training : {len(X_train)}")
print(f"[INFO] Data testing  : {len(X_test)}")

# ── Hitung Class Weight ───────────────────────────────────
class_weights = compute_class_weight(
    class_weight='balanced',
    classes=np.unique(y_encoded),
    y=y_train
)
weight_dict = dict(enumerate(class_weights))

# ── Training Model SVM + StandardScaler ──────────────────
print("\n" + "=" * 50)
print("Training model SVM + StandardScaler...")
print("=" * 50)

pipeline = Pipeline([
    ('scaler', StandardScaler()),
    ('svm', SVC(
        kernel='rbf',
        C=100,
        gamma='scale',
        class_weight=weight_dict,
        probability=True,
        random_state=42
    ))
])

pipeline.fit(X_train, y_train)
print("[INFO] Training selesai!")

# ── Evaluasi Model ───────────────────────────────────────
print("\n" + "=" * 50)
print("Evaluasi Model:")
print("=" * 50)

y_pred = pipeline.predict(X_test)

print("\nClassification Report:")
print(classification_report(
    y_test, y_pred,
    target_names=le.classes_
))

print("Confusion Matrix:")
print(confusion_matrix(y_test, y_pred))

accuracy = (y_pred == y_test).mean() * 100
print(f"\n✅ Akurasi: {accuracy:.2f}%")

# ── Simpan Model ─────────────────────────────────────────
joblib.dump(pipeline, os.path.join(MODEL_PATH, 'svm_model.pkl'))
joblib.dump(le,       os.path.join(MODEL_PATH, 'label_encoder.pkl'))

print("\n✅ Model berhasil disimpan!")
print("   → models/svm_model.pkl")
print("   → models/label_encoder.pkl")