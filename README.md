# ULTRA - SHOP
ULTRA - SHOP merupakan suatu aplikasi yang di buat untuk mempermudah konsumen dalam membeli pakaian secara online.
ULTRA - SHOP hadir untuk meningkatkan efisiensi konsumen dalam membeli barang yang dapat dilakukan kapanpun dan dimanapun.
Dengan menggunakan aplikasi berbasis web ULTRA - SHOP memiliki berbagai pilihan pakaian yang lengkap untuk melengkapi outfit anda mulai dari ujung rambut hingga ujung kaki semua ada di ULTRA - SHOP e commerce No.1 belanja pakaian semua merk semua ukuran semua yang anda inginkan ada di ULTRA - SHOP.
ULTRA - SHOP memiliki fitur  yang dapat membantu konsumen dalam mempermudah pencarian barang seperti bantuan filter dari merk, model, hingga range harga.
Selain itu ULTRA - SHOP juga memiliki customer service yang hadir 24 jam untuk menemani konsumen yang memiliki masalah di ULTRA - SHOP.

Project Init:
-
Terminal:
```
composer install
cp .env.example .env
php artisan key:generate
```

.env File:
```
APP_URL=http://127.0.0.1:8000
DB_DATABASE=ultra_shop
```

Terminal:
```
php artisan migrate
php artisan storage:link
php artisan serve
```