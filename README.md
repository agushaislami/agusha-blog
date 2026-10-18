# Agusha Blog

> Aplikasi blog berbasis Laravel untuk membaca, menulis, mengelola, dan mendiskusikan artikel dalam satu tempat.

Agusha Blog adalah aplikasi blog multi-user yang menyediakan pengalaman membaca bagi pengunjung serta fitur penulisan dan pengelolaan konten bagi pengguna yang telah masuk. Proyek ini dikembangkan dari template blog Bootstrap menjadi aplikasi Laravel yang terhubung ke database, dengan dashboard penulis, editor artikel, komentar, daftar bacaan, profil author, serta tema gelap dan terang.

Dokumen ini merangkum tujuan, fitur, alur penggunaan, struktur teknis yang diketahui, dan area yang perlu diperiksa sebelum aplikasi dipublikasikan. Detail yang belum terkonfirmasi dari source code ditandai sebagai hal yang perlu diverifikasi.

## Daftar Isi

- [Tujuan Proyek](#tujuan-proyek)
- [Fitur Utama](#fitur-utama)
- [Jenis Pengguna dan Alur](#jenis-pengguna-dan-alur)
- [Arsitektur Aplikasi](#arsitektur-aplikasi)
- [Model Data](#model-data)
- [Alur Konten Artikel](#alur-konten-artikel)
- [Pengalaman Pengguna](#pengalaman-pengguna)
- [Pengembangan dan Pengujian](#pengembangan-dan-pengujian)
- [Keamanan dan Kesiapan Produksi](#keamanan-dan-kesiapan-produksi)
- [Batasan dan Pengembangan Berikutnya](#batasan-dan-pengembangan-berikutnya)
- [Catatan Proyek](#catatan-proyek)

## Tujuan Proyek

Agusha Blog dibuat sebagai ruang untuk berbagi dan membaca cerita, gagasan, pengalaman, serta pengetahuan dari berbagai penulis. Pembaca dapat menjelajahi artikel berdasarkan kategori dan tag, menyimpan bacaan untuk nanti, serta berdiskusi melalui komentar. Pengguna yang masuk dapat mengelola profil dan artikel mereka, menyimpan tulisan sebagai draft, menerbitkannya, dan melihat statistik view.

Selain menjadi aplikasi blog, proyek ini juga menjadi sarana untuk menerapkan pengembangan aplikasi web berbasis Laravel, mulai dari integrasi template, pengolahan data melalui database, pengelolaan autentikasi, hingga penyempurnaan antarmuka dan pengalaman pengguna.

## Fitur Utama

### Beranda dan eksplorasi artikel

- Menampilkan bagian hero, artikel pilihan, dan daftar artikel.
- Mengambil data artikel dari database.
- Mendukung pengurutan artikel berdasarkan:
  - Terbaru
  - Terlama
  - Paling banyak dilihat
- Menampilkan kategori dan tag yang berkaitan dengan artikel.
- Menyediakan pagination pada daftar artikel.
- Menghubungkan nama author ke halaman profil publiknya.
- Menyediakan tombol bookmark untuk menyimpan artikel bagi pengguna yang telah masuk.

### Akun dan autentikasi

- Registrasi, login, dan logout.
- Halaman login dan registrasi menggunakan layout tersendiri.
- Navigasi akun ditempatkan pada dropdown di sisi kanan navigasi utama.
- Fitur menulis, daftar bacaan, komentar, dan pengelolaan profil tersedia sesuai status login serta aturan akses aplikasi.

### Penulisan dan pengelolaan artikel

Pengguna yang telah masuk dapat membuat dan mengelola artikel. Data artikel mencakup judul, subjudul, isi, kategori, tag, dan gambar utama.

- Menyimpan artikel sebagai `draft` atau menerbitkannya sebagai `published`.
- Mengedit artikel yang sudah dibuat.
- Menghapus artikel dengan dialog konfirmasi.
- Melihat preview untuk artikel draft maupun published.
- Mengunggah gambar utama dan melihat preview gambar sebelum menyimpan.
- Membuka preview gambar utama di tab baru.
- Menampilkan penanda `Edited` bagi artikel yang pernah diubah; penanda ini terpisah dari status publikasi.
- Melihat jumlah view artikel dari dashboard.

### Editor artikel

Editor menyediakan pemformatan isi artikel dan pengelolaan gambar di dalam konten.

- Pemformatan teks, termasuk bold, heading, dan code mark.
- Tombol toolbar berbentuk ikon dengan indikator status aktif/nonaktif.
- Dukungan pintasan keyboard, termasuk `Ctrl+B`.
- Menyisipkan gambar ke dalam isi artikel.
- Mengubah ukuran gambar yang disisipkan.
- Mengatur perataan gambar ke kiri, tengah, atau kanan.
- Menyimpan draft secara otomatis.
- Menampilkan status autosave beserta waktu hingga detik.
- Menjaga agar representasi gambar yang tersimpan tetap dapat ditangani saat artikel dibuka kembali untuk diedit.

> Implementasi editor dan sanitasi HTML perlu diperiksa dari source code sebelum aplikasi digunakan untuk konten publik yang tidak sepenuhnya tepercaya.

### Dashboard penulis

Dashboard menggunakan layout terpisah dengan navigasi sidebar. Area ini menyediakan:

- Tautan kembali ke beranda.
- Akses ke dashboard dan status artikel.
- Tab `Draft` dan `Published`, dengan `Published` sebagai tab awal.
- Aksi membuat dan mengedit artikel.
- Jumlah view artikel.
- Akses ke komentar pada artikel milik penulis.
- Akses ke profil dan daftar bacaan.
- Dropdown akun dan logout.

Tabel status dirancang ringkas agar informasi dan aksi mudah dibaca tanpa memberikan ruang berlebihan pada kolom aksi.

### Kategori, tag, dan pencarian konten

Kategori dan tag digunakan untuk membantu pembaca menemukan serta menjelajahi artikel berdasarkan topik. Tampilan label disesuaikan dengan tema aktif agar tetap terbaca.

### Komentar

- Komentar hanya tersedia bagi pengguna yang telah masuk.
- Komentar dapat diurutkan dari yang terlama atau terbaru; urutan default adalah terlama.
- Penulis komentar dapat mengedit dan menghapus komentarnya sendiri.
- Penulis artikel dapat menghapus komentar pada artikelnya.
- Dashboard penulis menampilkan komentar yang terkait dengan artikel miliknya beserta judul artikel rujukan.
- Foto profil dan nama pengguna ditampilkan pada komentar; tautan ke profil author dibatasi sesuai area klik yang dirancang.

### Daftar bacaan

- Pengguna yang telah masuk dapat menyimpan artikel ke daftar bacaan.
- Ikon bookmark berbentuk outline menunjukkan artikel belum disimpan.
- Ikon bookmark berbentuk fill menunjukkan artikel telah disimpan.
- Halaman daftar bacaan menampilkan artikel tersimpan dalam tata letak yang responsif.

### Profil author

- Setiap author memiliki halaman profil publik.
- Halaman profil menampilkan informasi profil dan artikel author.
- Hanya pemilik akun yang dapat mengedit profilnya.
- Pagination tersedia pada daftar artikel author.
- Foto profil dapat dibuka dalam tampilan lebih besar.
- Tampilan foto dapat ditutup dengan tombol `Escape` atau mengeklik area kosong.
- Avatar placeholder digunakan ketika pengguna belum memiliki foto profil.

### About, Contact, dan navigasi

- Halaman About mendukung bahasa Indonesia dan bahasa Inggris.
- Pergantian bahasa dilakukan tanpa memuat ulang halaman dan dilengkapi animasi fade.
- Halaman Contact tersedia, tetapi pengembangan lebih lanjut ditunda.
- Navigasi, tampilan mobile, tautan artikel, dan pagination telah mengalami beberapa perbaikan.

### Tema dan notifikasi

- Mode gelap dan terang.
- Tombol pergantian tema berbentuk ikon.
- Penyesuaian kontras teks, tombol, input, tag, dan tautan untuk kedua tema.
- Notifikasi sementara untuk tindakan seperti login, registrasi, dan penyimpanan artikel.
- Notifikasi memiliki animasi slide dan ditempatkan agar tidak mengganggu konten utama.

## Jenis Pengguna dan Alur

### Pengunjung

Pengunjung dapat membuka beranda, menjelajahi artikel, melihat artikel yang diterbitkan, menelusuri kategori dan tag, serta membuka profil author. Fitur yang memerlukan akun, seperti komentar dan daftar bacaan, mengharuskan pengguna untuk masuk.

### Pengguna yang telah masuk

Pengguna dapat menulis artikel, menyimpan draft, menerbitkan atau mengedit artikelnya, mengelola profil, menyimpan artikel ke daftar bacaan, serta menulis dan mengelola komentar sesuai hak aksesnya.

### Penulis artikel

Penulis dapat melihat komentar yang terkait dengan artikel miliknya dan menghapus komentar pada artikel tersebut. Hak akses ini perlu tetap diverifikasi di sisi server, bukan hanya melalui penyembunyian tombol pada antarmuka.

## Arsitektur Aplikasi

Aplikasi mengikuti pola MVC (Model–View–Controller) Laravel.

- **Routes — `routes/web.php`**  
  Mendefinisikan endpoint web dan menghubungkan permintaan dengan controller atau handler terkait.

- **Controllers — `app/Http/Controllers`**  
  Menangani permintaan, memproses alur aplikasi, dan menyiapkan data untuk view. Pemindahan logika dari definisi rute ke controller membantu menjaga file rute tetap ringkas.

- **Models — `app/Models`**  
  Merepresentasikan entitas data yang digunakan aplikasi, seperti pengguna, artikel, dan komentar.

- **Migrations — `database/migrations`**  
  Mendefinisikan perubahan skema database. Berdasarkan ringkasan pengembangan, skema mencakup kebutuhan artikel, kategori/tag, komentar, daftar bacaan, profil, autosave, dan pencatatan view. Nama tabel, kolom, indeks, serta relasi pastinya harus diverifikasi melalui migration aktual.

- **Blade views — `resources/views`**  
  Merender halaman publik, autentikasi, editor, preview, profil author, daftar bacaan, About, Contact, dan dashboard.

- **Aset frontend — `public/assets`**  
  Menyimpan aset frontend, termasuk aset yang berkaitan dengan tampilan dan tema. Lokasi serta pembagian aset yang sebenarnya perlu diperiksa dari struktur repositori.

### Alur permintaan secara umum

1. Pengguna membuka URL.
2. Laravel mencocokkan permintaan dengan rute yang sesuai.
3. Controller memvalidasi input dan menjalankan logika aplikasi.
4. Model berinteraksi dengan database bila diperlukan.
5. Controller mengirim data ke view Blade.
6. View merender respons yang diterima pengguna.

Diagram sederhana:

```text
Browser
   |
   v
routes/web.php
   |
   v
Controller  <---->  Model
   |                  |
   v                  v
Blade View         Database
   |
   v
HTML Response
```

Diagram tersebut menggambarkan pola umum MVC, bukan pemetaan lengkap setiap endpoint pada implementasi aktual.

## Model Data

Berikut kelompok entitas yang tersirat dari fitur dan riwayat pengembangan. Ini adalah gambaran konseptual, bukan ERD final atau daftar nama tabel yang telah diverifikasi.

| Entitas konseptual | Peran |
|---|---|
| User | Akun, autentikasi, dan identitas author |
| Post / Article | Judul, subjudul, isi, gambar utama, status publikasi, dan data view |
| Category | Pengelompokan artikel berdasarkan kategori |
| Tag | Penandaan topik artikel |
| Comment | Diskusi pada artikel dan identitas penulis komentar |
| Bookmark / Reading List | Relasi pengguna dengan artikel yang disimpan |
| Author Profile | Informasi profil publik dan foto profil |
| Draft / Autosave Data | Penyimpanan otomatis konten yang sedang ditulis, sesuai implementasi |
| View Record / Counter | Pencatatan atau agregasi view artikel, sesuai implementasi |

Relasi konseptual yang diharapkan antara lain:

- Satu user dapat memiliki banyak artikel.
- Satu artikel dapat memiliki banyak komentar.
- Satu user dapat menulis banyak komentar.
- User dapat menyimpan banyak artikel dan sebuah artikel dapat disimpan oleh banyak user.
- Artikel dapat berhubungan dengan kategori dan tag sesuai aturan pengelompokan aplikasi.
- Profil author berkaitan dengan user.

**Verifikasi sebelum menjadikan bagian ini sebagai spesifikasi database:** periksa seluruh migration, model, foreign key, pivot table, aturan penghapusan data, dan implementasi pencatatan view. Pastikan dokumentasi final menggunakan nama tabel dan relasi yang benar-benar ada di source code.

## Alur Konten Artikel

Alur umum penulisan artikel:

1. Pengguna masuk ke akun.
2. Pengguna membuka halaman Tulis Artikel.
3. Pengguna mengisi judul, subjudul, isi, kategori, tag, dan gambar utama bila diperlukan.
4. Draft dapat disimpan secara manual atau melalui autosave.
5. Pengguna memilih status `draft` atau `published`.
6. Artikel dapat dipreview, diedit, atau dihapus melalui dashboard.
7. Artikel yang diterbitkan muncul pada area publik sesuai aturan query dan tampilan aplikasi.
8. Jumlah view ditampilkan pada dashboard sesuai mekanisme pencatatan view yang diterapkan.

Artikel yang pernah diedit tetap memiliki status publikasi `draft` atau `published`. Penanda `Edited` digunakan sebagai informasi tambahan, bukan status ketiga.

## Pengalaman Pengguna

Selama pengembangan, sejumlah masalah antarmuka dan alur penggunaan diperbaiki secara iteratif, antara lain:

- Perbaikan aliran data antara controller dan view.
- Penyesuaian ukuran serta tata letak pagination.
- Perbaikan kontras tema gelap dan terang pada berbagai komponen.
- Penyederhanaan tabel dashboard dan kolom aksi.
- Perbaikan tampilan header preview artikel.
- Penyesuaian perilaku toolbar editor dan pintasan keyboard.
- Perbaikan pergantian bahasa pada halaman About.
- Penyesuaian respons tombol bookmark dan halaman daftar bacaan.
- Penyempurnaan area klik tautan profil author.
- Penambahan konfirmasi sebelum penghapusan artikel.

Pendekatan ini menunjukkan bahwa pengembangan tidak hanya berfokus pada keberadaan fitur, tetapi juga pada kejelasan status, konsistensi visual, dan perilaku interaksi.

## Pengembangan dan Pengujian

Riwayat pengembangan dimulai dari template Bootstrap, integrasi dengan Laravel, pengambilan artikel dari database, dan pemisahan logika ke controller. Setelah fondasi tersedia, pengembangan berlanjut ke autentikasi, dashboard, alur artikel, editor, kategori/tag, tema, profil, daftar bacaan, komentar, dan view counter.

Masalah yang ditemukan selama proses—seperti tabel sesi yang belum tersedia, data yang tidak diteruskan ke view, masalah kontras, dan transisi bahasa—ditangani secara bertahap melalui perbaikan dan pengujian ulang.

Sebelum rilis, jalankan pemeriksaan setidaknya pada alur berikut:

- Registrasi, login, logout, dan akses halaman yang memerlukan autentikasi.
- Pembuatan, pengeditan, preview, publikasi, draft, dan penghapusan artikel.
- Akses artikel oleh pemilik dan pengguna lain.
- Validasi gambar utama dan gambar dalam isi artikel.
- Autosave ketika konten berubah serta pemulihan draft.
- Pembuatan, pengeditan, penghapusan, dan pengurutan komentar.
- Bookmark saat ditambah, dibatalkan, dan dibuka kembali.
- Pembatasan edit profil dan pengelolaan komentar berdasarkan kepemilikan.
- Pengurutan artikel, pagination, kategori, tag, dan view counter.
- Pergantian tema dan bahasa pada desktop maupun perangkat mobile.

Daftar ini adalah rekomendasi pengujian; keberhasilan tiap tes belum dapat disimpulkan hanya dari ringkasan fitur.

## Keamanan dan Kesiapan Produksi

Karena aplikasi menerima input pengguna dan mendukung konten kaya, area berikut perlu ditinjau sebelum deployment publik:

1. **Authorization:** pastikan controller atau policy memeriksa kepemilikan artikel, profil, komentar, dan data autosave. Menyembunyikan tombol di UI saja tidak cukup.
2. **Validasi upload:** batasi jenis MIME, ukuran file, dimensi bila relevan, serta nama dan lokasi penyimpanan file.
3. **Keamanan rich text:** sanitasi HTML yang disimpan dan ditampilkan untuk mencegah XSS. Jangan menganggap output editor otomatis aman.
4. **CSRF dan autentikasi:** pastikan operasi yang mengubah data menggunakan mekanisme proteksi Laravel dan middleware yang sesuai.
5. **Validasi server-side:** validasi judul, isi, kategori, tag, status publikasi, komentar, dan input lainnya di server.
6. **Autosave:** pastikan endpoint autosave hanya dapat mengubah draft milik pengguna yang benar dan tidak menimpa data secara tidak sengaja.
7. **View counter:** tentukan kebijakan penghitungan view, termasuk apakah refresh berulang dihitung dan bagaimana trafik otomatis ditangani.
8. **Database dan file:** uji migrasi, backup, relasi foreign key, serta perilaku ketika file gambar dihapus atau tidak tersedia.
9. **Konfigurasi deployment:** gunakan environment variable yang benar, jangan commit rahasia ke repositori, dan jalankan konfigurasi produksi Laravel yang sesuai.
10. **Pengujian otomatis:** tambahkan feature test untuk autentikasi, authorization, dan operasi CRUD yang paling penting.

Butir di atas merupakan checklist audit, bukan klaim bahwa terdapat kerentanan atau bahwa seluruh mekanisme tersebut sudah diterapkan.

## Batasan dan Pengembangan Berikutnya

- Halaman Contact masih tersedia, tetapi belum menjadi fokus pengembangan lanjutan.
- Skema database dan relasi perlu didokumentasikan berdasarkan migration aktual.
- Cakupan automated test belum dijelaskan dalam ringkasan proyek dan perlu diperiksa.
- Kesiapan produksi perlu ditentukan melalui audit keamanan, pengujian, dan pemeriksaan konfigurasi.
- Dokumentasi instalasi, versi dependency, serta langkah deployment perlu ditambahkan berdasarkan kondisi repositori yang sebenarnya.

## Catatan Proyek

Agusha Blog berkembang dari template Bootstrap menjadi aplikasi blog Laravel dengan alur pembaca dan penulis yang lebih lengkap. Proses pengembangannya berlangsung secara iteratif: membangun fondasi, menambahkan fitur, menguji perilaku, memperbaiki masalah, lalu memoles pengalaman pengguna.

Proyek ini memperlihatkan penerapan konsep pengembangan web seperti MVC, autentikasi, pengelolaan konten, relasi data, editor artikel, serta penyempurnaan UI/UX. Dokumentasi ini sebaiknya diperbarui setiap kali arsitektur, skema database, atau perilaku fitur berubah.

---

**Status dokumen:** Disusun berdasarkan ringkasan pengembangan proyek. Detail yang belum dikonfirmasi harus dicocokkan dengan source code sebelum dijadikan spesifikasi implementasi final.
