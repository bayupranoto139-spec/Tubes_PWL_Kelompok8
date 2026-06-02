# TODO

## Penyebab redirect ke `/doctor/dashboard` dan blank page
- [x] Cek logic redirect berdasarkan role di `LoginController` dan `RedirectIfAuthenticated`.
- [x] Cek daftar route: ternyata `/doctor/dashboard` tidak terdaftar.
- [ ] Tambahkan route dokter di `routes/web.php` agar URL `/doctor/dashboard` memanggil `Doctor\DashboardController@index`.
- [ ] Tambahkan route lain yang dipakai sidebar doctor (`doctor.today`, `doctor.schedule`, `doctor.prescription`, `doctor.profile`) jika belum ada.
- [ ] Jalankan `php artisan route:list` dan pastikan route doctor muncul.

