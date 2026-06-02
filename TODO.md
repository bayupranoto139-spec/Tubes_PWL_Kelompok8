## TODO - Perbaikan redirect setelah login berdasarkan role

- [x] Cek `app/Http/Controllers/Auth/LoginController.php` untuk logic redirect setelah login
- [x] Samakan mapping redirect role -> path dengan middleware/konfigurasi yang sudah ada (`RedirectIfAuthenticated` / `bootstrap/app.php`)
- [x] Cek ketersediaan route untuk tujuan redirect: `/admin`, `/staff`, `/user/doctor/dashboard`, `/user/patient/dashboard`
- [ ] Jika route belum ada/kurang middleware, perbaiki route group/middleware terkait (catatan: saat ini hanya `/admin` dan panel pasien yang tersedia via Filament/web routes)
- [ ] Audit penggunaan `CheckRole` dan middleware role pada route yang relevan
- [ ] Uji manual login untuk tiap role dan pastikan redirect langsung ke dashboard masing-masing

