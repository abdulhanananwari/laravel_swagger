## install aplikasi
1. composer install
2. copy file .env.example ke .env
3. pada L5_SWAGGER_CONST_HOST isi dengan host yang digunakan contoh : http://localhost:port
4. php artisan key:generate
5. php artisan migrate
6. php artisan db:seed
7. php artisan jwt:secret
8. php artisan l5-swagger:generate


## user & password
user: admin@gmail.com
<br>
pass: d3v6789012345

## swagger documentation
setelah menjalankan command `php artisan l5-swagger:generate` kemudian buka {{url yang digunakan}}/api/documentation contoh = http://localhost:10001/api/documentation
