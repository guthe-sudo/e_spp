<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminCheck;

Route::get('/new', 'TestController@controllerMethod');


Route::prefix('app')->middleware([AdminCheck::class])->group(function(){
      
      //Route Tahun Akademik
      Route::post('/create_tahun_akademik', 'AdminController@addTahun_akademik');
      Route::get('/get_tahun_akademik', 'AdminController@getTahun_akademik');
      Route::post('/edit_tahun_akademik', 'AdminController@editTahun_akademik');
      Route::post('/delete_tahun_akademik', 'AdminController@deleteTahun_akademik');

      //Route Tingkat
      Route::post('/create_tingkat', 'AdminController@addTingkat');
      Route::get('/get_tingkat', 'AdminController@getTingkat');
      Route::post('/edit_tingkat', 'AdminController@editTingkat');
      Route::post('/delete_tingkat', 'AdminController@deleteTingkat');

      //Route Jurusan
      Route::post('/create_jurusan', 'AdminController@addJurusan');
      Route::get('/get_jurusan', 'AdminController@getJurusan');
      Route::post('/edit_jurusan', 'AdminController@editJurusan');
      Route::post('/delete_jurusan', 'AdminController@deleteJurusan');

      //Route Kelas
      Route::post('/create_kelas', 'AdminController@addKelas');
      Route::get('/get_kelas', 'AdminController@getKelas');
      Route::post('/edit_kelas', 'AdminController@editKelas');
      Route::post('/delete_kelas', 'AdminController@deleteKelas');

      //Route Siswa
      Route::post('/create_siswa', 'AdminController@addSiswa');
      Route::get('/get_siswa', 'AdminController@getSiswa');
      Route::post('/edit_siswa', 'AdminController@editSiswa');
      Route::post('/delete_siswa', 'AdminController@deleteSiswa');
      Route::post('/siswa', 'AdminController@siswa');
      Route::post('/delete_image', 'AdminController@deleteImage');
      Route::post('/delete_image_edit', 'AdminController@deleteEditImage');

      //Route Spp
      Route::post('/create_spp', 'AdminController@addSpp');
      Route::get('/get_spp', 'AdminController@getSpp');
      Route::post('/edit_spp', 'AdminController@editSpp');
      Route::post('/delete_spp', 'AdminController@deleteSpp');

      //Route Iuran
      Route::post('/create_iuran', 'AdminController@addIuran');
      Route::get('/get_iuran', 'AdminController@getIuran');
      Route::post('/edit_iuran', 'AdminController@editIuran');
      Route::post('/delete_iuran', 'AdminController@deleteIuran');

      //Route Kategori
      Route::post('/create_kategori', 'AdminController@addKategori');
      Route::get('/get_kategori', 'AdminController@getKategori');
      Route::post('/edit_kategori', 'AdminController@editKategori');
      Route::post('/delete_kategori', 'AdminController@deleteKategori');

      //Route SubKategori
      Route::post('/create_subkategori', 'AdminController@addSubkategori');
      Route::get('/get_subkategori', 'AdminController@getSubkategori');
      Route::post('/edit_subkategori', 'AdminController@editSubkategori');
      Route::post('/delete_subkategori', 'AdminController@deleteSubkategori');

      //Route Transaksi
      Route::post('/create_transaksiSpp', 'AdminController@transaksiSpp');
      Route::get('/get_transaksi', 'AdminController@getTransaksi');
      //Route::post('/transaksi', 'AdminController@transaksi');

      //Route Pengeluaran
      Route::post('/create_pengeluaran', 'AdminController@addPengeluaran');
      Route::get('/get_pengeluaran', 'AdminController@getPengeluaran');
      Route::post('/edit_pengeluaran', 'AdminController@editPengeluaran');
      Route::post('/delete_pengeluaran', 'AdminController@deletePengeluaran');
      
      //Route untuk Admin & User
      Route::post('/create_user', 'AdminController@createUser');
      Route::get('/get_users', 'AdminController@getUsers');
      Route::post('/edit_user', 'AdminController@editAdmin');
      Route::post('/delete_user', 'AdminController@deleteAdmin');
      Route::post('/admin_login', 'AdminController@adminLogin');
      //Route::post('/user_login', 'AdminController@userlogin');

      //Route untuk Role
      Route::post('/create_role', 'AdminController@addRole');
      Route::get('/get_roles', 'AdminController@getRoles');
      Route::post('/edit_role', 'AdminController@editRole');
      Route::post('/delete_role', 'AdminController@deleteRole');

      //Route untuk assignRole
      Route::post('/assign_roles', 'AdminController@assignRole');
});
      
// Route::prefix('member')->middleware([UserCheck::class])->group(function(){
//       Route::post('/user_login', 'UserController@userlogin');
// });

      //Route untuk login
      Route::get('/logout', 'AdminController@logout');
      Route::get('/', 'AdminController@index');
      //Route::get('/coba', 'UserController@index1');
      Route::any('{slug}', 'AdminController@index');
      //Route::any('{slug}', 'UserController@index1');
      
      //Route::get('/', function () {
            //    return view('welcome');
            //});
            //Route::any('{slug}', function () {
                  //    return view('welcome');
                  //});