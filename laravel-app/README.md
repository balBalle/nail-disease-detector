<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


# NAIL DISEASE DETECTOR

Panduan Setup dan Menjalankan Aplikasi

---

1. PERSYARATAN SISTEM

---

Pastikan perangkat sudah terinstall:

* PHP 8.2 atau lebih baru
* Composer
* Node.js dan NPM
* MySQL
* Python 3.10 atau lebih baru
* Git (opsional)

---

2. SETUP LARAVEL

---

Langkah 1 - Buat Folder Project Utama

```
mkdir nail-disease-detector
cd nail-disease-detector
```

Langkah 2 - Install Laravel

```
composer create-project laravel/laravel laravel-app
cd laravel-app
```

Langkah 3 - Install Dependency Laravel

```
composer require laravel/ui

php artisan ui bootstrap --auth

composer require guzzlehttp/guzzle

npm install
npm run build
```

Langkah 4 - Konfigurasi Database

Buka file:

```
laravel-app/.env
```

Ubah konfigurasi berikut:

```
APP_NAME="Nail Disease Detector"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nail_disease_db
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan username dan password MySQL sesuai perangkat Anda.

Langkah 5 - Buat Database dan Jalankan Migration

Buat database:

```
mysql -u root -p -e "CREATE DATABASE nail_disease_db;"
```

Jalankan migration:

```
php artisan migrate
```

Langkah 6 - Tambahkan Konfigurasi Python Service

Tambahkan pada file .env:

```
PYTHON_SERVICE_URL=http://127.0.0.1:5000
```

---

3. SETUP PYTHON SERVICE

---

Langkah 7 - Buat Folder Python Service

Kembali ke folder root project:

```
cd ..
```

Buat folder:

```
mkdir python-service
cd python-service
```

Langkah 8 - Buat Virtual Environment

Windows:

```
python -m venv venv
venv\Scripts\activate
```

Mac/Linux:

```
python3 -m venv venv
source venv/bin/activate
```

Jika berhasil, terminal akan menampilkan:

```
(venv)
```

Langkah 9 - Buat requirements.txt

Isi file requirements.txt:

```
flask==3.0.0
flask-cors==4.0.0
numpy==1.26.4
scikit-image==0.22.0
scikit-learn==1.4.0
Pillow==10.2.0
opencv-python==4.9.0.80
joblib==1.3.2
```

Langkah 10 - Install Dependency Python

```
pip install -r requirements.txt
```

Tunggu hingga proses instalasi selesai.

Langkah 11 - Verifikasi Instalasi

Jalankan:

```
python -c "import flask; import numpy; import skimage; import sklearn; import PIL; import cv2; print('Semua library berhasil diinstall!')"
```

Jika muncul pesan:

```
Semua library berhasil diinstall!
```

maka instalasi berhasil.

---

4. STRUKTUR FOLDER PROJECT

---

nail-disease-detector/
│
├── laravel-app/
│   ├── .env
│   ├── composer.json
│   └── node_modules/
│
└── python-service/
├── requirements.txt
└── venv/

---

5. MENJALANKAN APLIKASI

---

Aplikasi memerlukan dua terminal yang berjalan bersamaan.

TERMINAL 1 - Laravel

Masuk ke folder Laravel:

```
cd nail-disease-detector/laravel-app
```

Jalankan:

```
php artisan serve
```

Laravel akan berjalan pada:

```
http://localhost:8000
```

TERMINAL 2 - Python Service

Masuk ke folder Python Service:

```
cd nail-disease-detector/python-service
```

Aktifkan virtual environment:

Windows:

```
venv\Scripts\activate
```

Mac/Linux:

```
source venv/bin/activate
```

Jalankan service:

```
python app.py
```

Python Service akan berjalan pada:

```
http://localhost:5000
```

---

6. TROUBLESHOOTING

---

1. composer: command not found

Solusi:
Install Composer dan pastikan sudah masuk PATH.

2. php: command not found

Solusi:
Tambahkan folder PHP ke Environment Variable PATH.

3. pip: command not found

Solusi:
Gunakan pip3 atau reinstall Python.

4. Error koneksi MySQL

Solusi:
Periksa DB_DATABASE, DB_USERNAME, dan DB_PASSWORD pada file .env.

5. Port 8000 sudah digunakan

Solusi:

```
php artisan serve --port=8001
```

6. Port 5000 sudah digunakan

Solusi:
Ubah port pada file app.py dan sesuaikan PYTHON_SERVICE_URL pada file .env.

---

7. SELESAI

---

Jika kedua service berjalan tanpa error:

Laravel:
http://localhost:8000

Python Service:
http://localhost:5000

Maka aplikasi Nail Disease Detector siap digunakan.
