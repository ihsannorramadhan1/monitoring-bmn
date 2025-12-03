# User Manual - Sistem Monitoring BMN KPKNL Banjarmasin

## 1. Introduction
Selamat datang di **Sistem Monitoring BMN (Barang Milik Negara)**. Aplikasi ini dirancang untuk membantu KPKNL Banjarmasin dalam memantau, mengelola, dan melaporkan agenda pengelolaan BMN secara efisien, transparan, dan akurat.

Sistem ini mencakup fitur:
-   **Dashboard Monitoring**: Visualisasi status dan kinerja secara real-time.
-   **Manajemen Agenda**: Pencatatan dan pelacakan status permohonan BMN.
-   **Master Data**: Pengelolaan data referensi (Satker, Jenis Pengelolaan, User).
-   **Pelaporan**: Pembuatan laporan otomatis (PDF/Excel) untuk analisis dan evaluasi.

---

## 2. Login & Roles
Untuk mengakses sistem, silakan login menggunakan kredensial yang telah diberikan oleh administrator.

### Halaman Login
1.  Buka aplikasi di browser.
2.  Masukkan **Email** dan **Password**.
3.  Klik tombol **Log in**.

### Roles (Peran Pengguna)
Sistem ini memiliki dua peran utama:
1.  **Admin**:
    -   Akses penuh ke semua fitur.
    -   Dapat mengelola Master Data (Satker, Jenis, User).
    -   Dapat membuat, mengedit, dan menghapus Agenda.
    -   Dapat mengakses semua laporan.
2.  **Staff**:
    -   Fokus pada pengelolaan Agenda (Input dan Update status).
    -   Dapat melihat Dashboard dan Laporan.
    -   Akses terbatas pada Master Data (View only).

---

## 3. Dashboard Explanation
Setelah login, Anda akan diarahkan ke halaman **Dashboard**. Halaman ini memberikan ringkasan cepat mengenai kinerja pengelolaan BMN.

**Komponen Dashboard:**
-   **Kartu Ringkasan (KPI)**:
    -   **Total Agenda**: Jumlah seluruh agenda yang masuk.
    -   **Selesai**: Jumlah agenda dengan status 'Disetujui' atau 'Ditolak'.
    -   **Pending**: Jumlah agenda yang masih dalam proses (Masuk, Verifikasi, Disposisi, Proses).
    -   **Avg Durasi**: Rata-rata waktu penyelesaian agenda (dalam hari).
-   **Grafik Tren Bulanan**: Menampilkan jumlah agenda yang masuk per bulan selama 6 bulan terakhir.
-   **Grafik Status**: Diagram lingkaran yang menunjukkan proporsi status agenda saat ini.
-   **Tabel Agenda Terbaru**: Daftar 5 agenda terakhir yang masuk ke sistem.

---

## 4. Master Data Management
Fitur ini digunakan untuk mengelola data referensi yang digunakan dalam sistem. Hanya **Admin** yang dapat mengubah data ini.

### 4.1. Master Satker (Satuan Kerja)
Menu: `Master Data` -> `Satker`
-   **Lihat Data**: Tabel menampilkan Kode, Nama Satker, dan PIC.
-   **Tambah Data**: Klik tombol `+ Tambah Satker`, isi form, lalu Simpan.
-   **Edit Data**: Klik tombol `Edit` pada baris satker yang ingin diubah.
-   **Hapus Data**: Klik tombol `Delete` dan konfirmasi pada modal yang muncul.

### 4.2. Master Jenis Pengelolaan
Menu: `Master Data` -> `Jenis Pengelolaan`
-   Digunakan untuk mengatur jenis layanan (misal: Sewa, Pinjam Pakai) dan **SLA (Target Hari)**.
-   SLA ini akan digunakan sistem untuk menghitung tanggal target penyelesaian secara otomatis.

### 4.3. Master User
Menu: `Master Data` -> `User`
-   Digunakan untuk menambah akun staff baru atau menonaktifkan akun lama.

---

## 5. Agenda Management Workflow
Ini adalah fitur inti aplikasi. Alur kerja pengelolaan agenda adalah sebagai berikut:

### 5.1. Membuat Agenda Baru
1.  Masuk ke menu **Agenda**.
2.  Klik tombol **+ Buat Agenda Baru**.
3.  Isi formulir:
    -   **Nomor Agenda**: (Otomatis terisi, bisa diubah jika perlu).
    -   **Satker**: Pilih dari daftar.
    -   **Jenis Pengelolaan**: Pilih jenis layanan.
    -   **Tanggal Masuk**: Default hari ini.
    -   **Keterangan/Notes**: Catatan tambahan.
    -   **Upload File**: Unggah dokumen pendukung (PDF/Gambar).
4.  Klik **Simpan**.
    -   *Sistem akan otomatis menghitung Tanggal Target berdasarkan jenis pengelolaan yang dipilih.*

### 5.2. Update Status Agenda
1.  Di halaman daftar Agenda, klik tombol **Edit** atau **Lihat** pada agenda yang diinginkan.
2.  Ubah **Status** sesuai progres (misal: dari 'Masuk' ke 'Verifikasi').
3.  Klik **Simpan**.
    -   *Setiap perubahan status akan tercatat di "Log Aktivitas" (History) untuk keperluan audit.*

### 5.3. Menyelesaikan Agenda
-   Jika agenda disetujui, ubah status menjadi **Disetujui**.
-   Jika ditolak, ubah status menjadi **Ditolak**.
-   Sistem akan mencatat **Tanggal Selesai** dan menghitung **Durasi Pengerjaan** secara otomatis.

---

## 6. Reports Generation Guide
Sistem menyediakan 5 jenis laporan utama untuk keperluan monitoring dan evaluasi.
Akses menu: **Laporan**.

### Jenis Laporan:
1.  **Daftar Agenda Persetujuan**:
    -   Laporan detail semua agenda.
    -   Bisa difilter berdasarkan Tanggal dan Satker.
2.  **Status Persetujuan**:
    -   Ringkasan statistik berdasarkan status (Berapa % disetujui, ditolak, dll).
3.  **Analisis Durasi Proses**:
    -   Membandingkan durasi aktual vs target SLA.
    -   Menampilkan daftar agenda yang **Overdue** (Terlambat).
4.  **Performance per Satker**:
    -   Peringkat Satker berdasarkan keaktifan dan kecepatan penyelesaian.
5.  **Executive Summary**:
    -   Ringkasan level tinggi untuk pimpinan (KPI, Isu Utama, Tren).

### Cara Export:
1.  Pilih jenis laporan.
2.  (Opsional) Isi filter tanggal.
3.  Klik tombol **Export PDF** untuk dokumen siap cetak.
4.  Klik tombol **Export Excel** untuk data mentah yang bisa diolah lebih lanjut.

---

## 7. FAQ (Frequently Asked Questions)

**Q: Bagaimana jika Satker yang saya cari tidak ada di list?**
A: Hubungi Admin untuk menambahkan Satker tersebut di menu Master Data > Satker.

**Q: Apakah saya bisa menghapus agenda yang salah input?**
A: Ya, klik tombol Delete pada daftar agenda. Namun, hati-hati karena data yang dihapus tidak bisa dikembalikan (kecuali via database admin).

**Q: Kenapa Tanggal Target terisi otomatis?**
A: Sistem menghitungnya berdasarkan "Target Hari" yang disetting pada Master Jenis Pengelolaan.

**Q: Apa itu status "Overdue"?**
A: Agenda yang belum selesai (status bukan Disetujui/Ditolak) tetapi sudah melewati Tanggal Target.

---

## 8. Troubleshooting

**Masalah: Gagal Upload File**
-   Pastikan ukuran file tidak melebihi batas (misal 2MB).
-   Pastikan format file didukung (PDF, JPG, PNG).

**Masalah: Laporan PDF tidak muncul/error**
-   Pastikan tidak ada karakter aneh pada data agenda.
-   Coba refresh halaman dan generate ulang.

**Masalah: Lupa Password**
-   Hubungi Administrator sistem untuk mereset password akun Anda.

---
*Dokumen ini dibuat otomatis oleh Sistem Monitoring BMN.*
