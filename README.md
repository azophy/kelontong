# KELONTONG SIMPLE WEB-BASED POINT-OF-SALES SOFTWARE
Version Alpha
22rd of July, 2011

Ini adalah versi aplha dari software KELONTONG. Software ini diluncurkan dengan lisesnsi GPL yang
bisa dilihat di file LICENSE.txt. Anda boleh menggunakan aplikasi ini dengan gratis. Jika anda 
menemukan bug, atau memiliki kritik, saran, ide, atau perbaikan/pengembangan lainnya yang bisa
membuat software ini semakin baik, silahkan hubungi saya di: azophy@gmail.com.

## PANDUAN MENGINSTALL
1. siapkan database, web hosting, dll.
2. Import file databse.sql ke database anda.
3. Ubah nilai-nilai di file 'script/config.php'.
4. Aplikasi Kelontong Alpha sudah siap di jalankan!

## KNOWN BUGS
-  bug stok barang di transaksi pembelian & penjualan

## TO DO
- implementasi "client & server based host script"
- gunakan fungsi2 MYSQLi
- AutoSuggest nama barang utk page2 transaksi
- date picker utk laporan penjualan per tanggal dan periode
- fitur pajak di transaksi
- fungsi convertDate() menggunakan fungsi2 native dan elegan + tampilkan nama bulan

## CHANGE LOG
<<<version Alpha date 4-7-2011>>>
- First release

<<<version Alpha date 10-7-2011>>>
- perbaiki bug laporan2 penjualan
- bug nama satuan dan golongan di data_barang
- bug row total di laporan penjualan per tanggal, per periode, harian, pekanan, dan bulanan
- bug harga jual di ajax_handler
- bug harga jual di laporan_print
- bug posisi separator di addThousandSeparator (library.php)
- bug harga beli di transaksi_pembelian
- bug tombol print laporan
- fitur cetak nota di transaksi_penjualan
- fitur addThousandSeparator di halaman transaksi_penjualan
- fitur addThousandSeparator di halaman transaksi_pembelian
- pisahkan fungsi ajax_handler "barang_dari_*" menjadi "beli_barang_dari_*" dan "jual_barang_dari_*"
- bedakan priviledges tiap page sesuai 'id_status'

<<<version Alpha date 22-7-2011>>>
- perbaiki bug variabel paging yang belum di-deklarasikan di config.php 
- perbaiki isi database contoh
- sempurnakan fitur diskon
- fungsi convertDate() => masih pake teknik substring

