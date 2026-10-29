# EHMS - Enterprise Health Management System
**PT PGAS Solution - Divisi K3 & Kesehatan Kerja**

EHMS (Enterprise Health Management System) adalah platform surveilans kesehatan pekerja terpadu yang memadukan integrasi data **Medical Check Up (MCU)** tahunan, pemeriksaan harian **Daily Check Up (DCU)**, **Master Karyawan**, **Pemantauan Kasus Komorbiditas Pekerja**, dan **15 Aturan Klinis Power BI**.

---

## 🔗 Koneksi Langsung Frontend GitHub & Google Apps Script

Frontend pada repositori ini (`index.html`, `google-apps-script/Index.html`, dan `php-mvc/`) telah **terhubung langsung** dengan endpoint deployment Google Apps Script Anda:

```text
https://script.google.com/macros/s/AKfycbyElWAEb7e0gO_TL_WBai7GHUKUn9tEAegPXUK6TMmQgDrPHCjoXbwWXlc1549HwTgVag/exec
```

### Cara Kerja Arsitektur Hybrid (GitHub Frontend &harr; Google Sheets Apps Script):
1. **Frontend GitHub (`index.html`)**:
   - Dapat langsung di-deploy menggunakan **GitHub Pages** (dari root `/` branch `main`).
   - Secara otomatis berkomunikasi dengan `https://script.google.com/macros/s/AKfycbyElWAEb7e0gO_TL_WBai7GHUKUn9tEAegPXUK6TMmQgDrPHCjoXbwWXlc1549HwTgVag/exec` melalui REST API (`?action=summary`, `?action=mcu`, `?action=dcu`, `?action=employees`, `?action=comorbid`, `?action=screening`, `?action=emr`, serta `POST` untuk simpan/hapus data).
   - Dilengkapi **Hybrid Data Mirror** dari folder `./data-sheets/*.csv` sehingga seluruh **2.285 data MCU**, **1.975 data DCU**, **2.287 Master Karyawan**, dan **1.479 Kasus Komorbid** langsung tampil instan tanpa jeda.
2. **Backend Google Apps Script (`google-apps-script/Code.gs`)**:
   - Mendukung **Dual-Mode**:
     - Merespons request JSON/JSONP (`doGet` & `doPost`) dari Frontend GitHub.
     - Merender Web App (`HtmlService`) ketika URL `https://script.google.com/macros/s/AKfycbyElWAEb7e0gO_TL_WBai7GHUKUn9tEAegPXUK6TMmQgDrPHCjoXbwWXlc1549HwTgVag/exec` dibuka langsung di browser.

---

## 📁 Struktur Direktori Repositori (`kode_github`)

```text
├── index.html                 # 🌟 Frontend Utama SPA (Siap Deploy di GitHub Pages & Terhubung ke Apps Script)
├── google-apps-script/        # Backend & Web App Google Apps Script
│   ├── Code.gs                # Backend Apps Script (REST API JSON/JSONP + HtmlService + CRUD Sheet)
│   ├── SetupSheet.gs          # Skrip otomatisasi 4 tab Sheet, warna header & dropdown validasi
│   ├── Index.html             # Tampilan Web App Apps Script (6 Modul Lengkap + Modal EMR 6 Tab)
│   └── appsscript.json        # Manifest Web App Google Apps Script (Access: ANYONE)
│
├── data-sheets/               # Master Data Hasil Migrasi Penuh (Data Riil)
│   ├── MCU_DATA.csv           # 2.285 baris data MCU lengkap (49 kolom klinis & lab)
│   ├── DCU_DATA.csv           # 1.975 baris data DCU harian bersih (Murni FIT / UNFIT)
│   ├── KARYAWAN_DATA.csv      # 2.287 master data karyawan (5 Status Karyawan & Area Operasi)
│   ├── KOMORBID_DATA.csv      # 1.479 kasus pemantauan komorbiditas & rujukan spesialis
│   └── schema.sql             # SQL Dump untuk migrasi RDBMS
│
└── php-mvc/                   # Versi Aplikasi PHP MVC (Laragon / Apache / Nginx)
    ├── app/                   # Config (terhubung ke GAS_WEBAPP_URL), Controllers, Models, Views
    ├── database/              # SQLite database (ehms.sqlite)
    ├── public/                # CSS & JS (dilengkapi fitur Sync Google Apps Script)
    └── index.php              # Front Controller & Router
```

---

## ☁️ Langkah Update Deployment di Google Sheet & Apps Script

Agar endpoint `https://script.google.com/macros/s/AKfycbyElWAEb7e0gO_TL_WBai7GHUKUn9tEAegPXUK6TMmQgDrPHCjoXbwWXlc1549HwTgVag/exec` melayani REST API JSON lintas-domain dari GitHub secara penuh:

1. Buka Google Spreadsheet **EHMS - PGAS Solution** Anda, lalu klik menu **Extensions (Ekstensi)** &rarr; **Apps Script**.
2. Ganti isi file `Code.gs`, `SetupSheet.gs`, dan `Index.html` di editor Apps Script dengan isi file terbaru dari folder `google-apps-script/`.
3. Jika tab sheet belum dibuat, jalankan fungsi `setupEhmsSpreadsheet()` pada `SetupSheet.gs` dan impor ke-4 file CSV dari folder `data-sheets/` ke masing-masing tab (`MCU_DATA`, `DCU_DATA`, `KARYAWAN_DATA`, `KOMORBID_DATA`).
4. Klik tombol **Deploy** &rarr; **Manage deployments** &rarr; klik ikon **Edit (Pensil)** pada deployment Anda:
   - **Version**: Pilih *New version* (Versi baru)
   - **Execute as**: *Me*
   - **Who has access**: **Anyone** *(Siapa saja — penting agar frontend GitHub dapat mengambil JSON tanpa terhalang halaman login Google)*
5. Klik **Deploy**.

---

## 🌐 Langkah Deploy Frontend ke GitHub Pages

1. Push seluruh isi folder `kode_github` ini ke repositori GitHub Anda:
   ```bash
   git init
   git add .
   git commit -m "Deploy EHMS Frontend connected to Google Apps Script"
   git branch -M main
   git remote add origin https://github.com/<username>/<nama-repo>.git
   git push -u origin main
   ```
2. Di halaman repositori GitHub Anda, buka **Settings** &rarr; **Pages**.
3. Pada bagian **Build and deployment**, pilih Source: **Deploy from a branch**, lalu pilih branch **`main`** dan folder **`/ (root)`**, kemudian klik **Save**.
4. Halaman aplikasi Anda akan langsung aktif dan terhubung ke `https://script.google.com/macros/s/AKfycbyElWAEb7e0gO_TL_WBai7GHUKUn9tEAegPXUK6TMmQgDrPHCjoXbwWXlc1549HwTgVag/exec`!

---

## 🔒 Lisensi & Hak Cipta
Hak Cipta &copy; 2026 PT PGAS Solution. Seluruh data medis dilindungi oleh kerahasiaan rekam medis K3 korporat.
