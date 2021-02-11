<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\User;
use App\Tahun_akademik;
use App\Tingkat;
use App\Jurusan;
use App\Major;
use App\Spp;
use App\Iuran;
use App\Siswa;
use App\Kategori;
use App\Subkategori;
use App\Role;
use App\Transaction;
use App\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\TryCatch;

class AdminController extends Controller
{
    //Controller untuk Index (Validasi Login)
    public function index(Request $request) {
        // pengecekan pertama jika login sebagai admin
        //return Auth::check();
        if(!Auth::check() && $request->path() != 'login') {
            return redirect('/login');
        }
        if(!Auth::check() && $request->path() == 'login') {
            return view('welcome');
        } 
        
        // Jika sudah login.. akan dilakukan pengecekan jika login sebagai admin
        $user = Auth::user();
        if($user->role->isAdmin == "Tidak") {
            return redirect('/login');
        }
        if($request->path() == 'login') {
            return redirect('/');
        }
        //return view('welcome');
        return $this->checkForPermission($user, $request);

    }
    public function checkForPermission($user, $request){
        $permission = json_decode($user->role->permission);

        $hasPermission = false;
        foreach($permission as $index)
        foreach($index->items as $value)
        {
            if($index->items == $request->path()){
                if($index->read){
                    $hasPermission = true;
                }
            }
            if($value->name == $request->path()){
                if($value->read){
                    $hasPermission = true;
                }
            }
        }
        if($hasPermission) return view('welcome');
        return view('notfound');    
    }

    //Controller untuk Logout
    public function logout() {
        Auth::logout();
        return redirect('/login');
    }


    //Controller untuk Tahun Akademik
    public function addTahun_akademik(Request $request) {
        //validate request
        $this->validate($request, [
            'nama_tahun' => 'required',
            'keterangan' => 'required',
            'aktif' => 'required',
        ]);
        return Tahun_akademik::create([
            'nama_tahun' => $request->nama_tahun,
            'keterangan' => $request->keterangan,
            'aktif' => $request->aktif,
        ]);
    }
    public function editTahun_akademik(Request $request) {
        //validate request
        $this->validate($request, [
            'nama_tahun' => 'required',
            'keterangan' => 'required',
            'aktif' => 'required',
        ]);
        return Tahun_akademik::where('id_tahun_akademik', $request->id_tahun_akademik)->update([
            'nama_tahun' => $request->nama_tahun,
            'keterangan' => $request->keterangan,
            'aktif' => $request->aktif,
        ]);
    }
    public function deleteTahun_akademik(Request $request) {
        //validate request
        $this->validate($request, [
            'id_tahun_akademik' => 'required',
        ]);
        return Tahun_akademik::where('id_tahun_akademik', $request->id_tahun_akademik)->delete();
    }
    public function getTahun_akademik() {
        return Tahun_akademik::orderBy('id_tahun_akademik','desc')->get();
    }

    //Controller untuk Tingkat
    public function addTingkat(Request $request) {
        //validate request
        $this->validate($request, [
            'tingkat' => 'required',
            'keterangan' => 'required',
        ]);
        return Tingkat::create([
            'tingkat' => $request->tingkat,
            'keterangan' => $request->keterangan,
        ]);
    }
    public function editTingkat(Request $request) {
        //validate request
        $this->validate($request, [
            'tingkat' => 'required',
            'keterangan' => 'required',
        ]);
        return Tingkat::where('id_tingkat', $request->id_tingkat)->update([
            'tingkat' => $request->tingkat,
            'keterangan' => $request->keterangan,
        ]);
    }
    public function deleteTingkat(Request $request) {
        //validate request
        $this->validate($request, [
            'id_tingkat' => 'required',
        ]);
        return Tingkat::where('id_tingkat', $request->id_tingkat)->delete();
    }
    public function getTingkat() {
        return Tingkat::orderBy('id_tingkat','desc')->get();
    }

    //Controller untuk Jurusan
    public function addJurusan(Request $request) {
        //validate request
        $this->validate($request, [
            'nama_jurusan' => 'required',
            'aktif' => 'required',
        ]);
        return Jurusan::create([
            'nama_jurusan' => $request->nama_jurusan,
            'aktif' => $request->aktif,
        ]);
    }
    public function editJurusan(Request $request) {
        //validate request
        $this->validate($request, [
            'nama_jurusan' => 'required',
            'aktif' => 'required',
        ]);
        return Jurusan::where('id_jurusan', $request->id_jurusan)->update([
            'nama_jurusan' => $request->nama_jurusan,
            'aktif' => $request->aktif,
        ]);
    }
    public function deleteJurusan(Request $request) {
        //validate request
        $this->validate($request, [
            'id_jurusan' => 'required',
        ]);
        return Jurusan::where('id_jurusan', $request->id_jurusan)->delete();
    }
    public function getJurusan() {
        return Jurusan::orderBy('id_jurusan','desc')->get();
    }

    //Controller untuk Kelas
    public function addKelas(Request $request) {
        //validate request
        $this->validate($request, [
            'id_jurusan' => 'required',
            'id_tingkat' => 'required',
        ]);
        return Major::create([
            'id_jurusan' => $request->id_jurusan,
            'id_tingkat' => $request->id_tingkat,
        ]);
    }
    public function editKelas(Request $request) {
        //validate request
        $this->validate($request, [
            'id_jurusan' => 'required',
            'id_tingkat' => 'required',
        ]);
        return Major::where('id_kelas', $request->id_kelas)->update([
            'id_jurusan' => $request->id_jurusan,
            'id_tingkat' => $request->id_tingkat,
        ]);
    }
    public function deleteKelas(Request $request) {
        //validate request
        $this->validate($request, [
            'id_kelas' => 'required',
        ]);
        return Major::where('id_kelas', $request->id_kelas)->delete();
    }
    public function getKelas() {
        $majors = Major::select([
            'id_kelas',
            'jurusans.id_jurusan',
            'jurusans.nama_jurusan',
            'tingkats.tingkat',
            'tingkats.id_tingkat',
        ])->leftJoin('jurusans','majors.id_jurusan', "=", 'jurusans.id_jurusan')
        ->leftJoin('tingkats','majors.id_tingkat', "=", 'tingkats.id_tingkat')
        ->orderBy('id_kelas','desc')->get();
        return response()->json($majors);
    }

    // Controller untuk Siswa
    public function addSiswa(Request $request) {
        //validate request
        $this->validate($request, [
            'nis' => 'required',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'jk' => 'required',
            'id_tahun_akademik' => 'required',
            'id_tingkat' => 'required',
            'id_jurusan' => 'required',
            'alamat' => 'required',
            'foto' => 'required',
        ]);
        // DB::beginTransaction();
        // try {
             return Siswa::create([
                'nis' => $request->nis,
                'nama' => $request->nama,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'jk' => $request->jk,
                'id_tahun_akademik' => $request->id_tahun_akademik,
                'id_tingkat' => $request->id_tingkat,
                'id_jurusan' => $request->id_jurusan,
                'alamat' => $request->alamat,
                'foto' => $request->foto,
            ]);
        // DB::commit();
        //     return 'done';
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return 'not done';
        // }
    }
    public function editSiswa(Request $request) {
        //validate request
        $this->validate($request, [
            'nis' => 'required',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'jk' => 'required',
            'id_tahun_akademik' => 'required',
            'id_tingkat' => 'required',
            'id_jurusan' => 'required',
            'alamat' => 'required',
            'foto' => 'required',
        ]);
        return Siswa::where('nis', $request->nis)->update([
            'nama' => $request->nama,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'jk' => $request->jk,
            'id_tahun_akademik' => $request->id_tahun_akademik,
            'id_tingkat' => $request->id_tingkat,
            'id_jurusan' => $request->id_jurusan,
            'alamat' => $request->alamat,
            'foto' => $request->foto,
        ]);
    }
    public function deleteSiswa(Request $request) {
        //validate request
        $this->validate($request, [
            'nis' => 'required',
        ]);
        $imageName = $request->foto;
        $filePath = public_path().'/uploads/'.$imageName;
        if(file_exists($filePath)){
            @unlink($filePath);
        };
        return Siswa::where('nis', $request->nis)->delete();
    }
    public function getSiswa() {
        $siswas = Siswa::select([
            'nis',
            'nama',
            'tempat_lahir',
            'tgl_lahir',
            'jk',
            'tahun_akademiks.nama_tahun',
            'tahun_akademiks.id_tahun_akademik',
            'tingkats.tingkat',
            'tingkats.id_tingkat',
            'jurusans.nama_jurusan',
            'jurusans.id_jurusan',
            'alamat',
            'foto',
        ])->leftJoin('tahun_akademiks','siswas.id_tahun_akademik', "=", 'tahun_akademiks.id_tahun_akademik')
        ->leftJoin('tingkats','siswas.id_tingkat', "=", 'tingkats.id_tingkat')
        ->leftJoin('jurusans','siswas.id_jurusan', "=", 'jurusans.id_jurusan')
        ->orderBy('nis','desc')->get();
        return response()->json($siswas);
    }
    public function siswa(Request $request) {
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,jpg,png'
        ]);
        $imageName = time().'.'.$request->file->extension();
        $request->file->move(public_path('/uploads'),$imageName);
        return $imageName;
    }
    public function deleteImage(Request $request){
        $imageName = $request->imageName;
        $this->deleteFileFromServer($imageName);
        return 'done';
    }
    public function deleteEditImage(Request $request){
        $imageName = $request->imageName;
        $this->deleteFileFromServer($imageName);
        return 'done';
    }
    //menghapus file dari folder uploads...
    public function deleteFileFromServer($imageName) {
        $filePath = public_path().'/uploads/'.$imageName;
        if(file_exists($filePath)){
            unlink($filePath);
        };
        return;
    }
    

    //Controller untuk Spp
    public function addSpp(Request $request) {
        //validate request
        $this->validate($request, [
            'id_tahun_akademik' => 'required',
            'jumlah' => 'required',
            'id_tingkat' => 'required',
        ]);
            return Spp::create([
                'id_tahun_akademik' => $request->id_tahun_akademik,
                'jumlah' => $request->jumlah,
                'id_tingkat' => $request->id_tingkat,
            ]);
        
    }
    public function editSpp(Request $request) {
        //validate request
        $this->validate($request, [
            'id_tahun_akademik' => 'required',
            'jumlah' => 'required',
            'id_tingkat' => 'required',
        ]);
        return Spp::where('id_spp', $request->id_spp)->update([
            'id_tahun_akademik' => $request->id_tahun_akademik,
            'jumlah' => $request->jumlah,
            'id_tingkat' => $request->id_tingkat,
        ]);
    }
    public function deleteSpp(Request $request) {
        //validate request
        $this->validate($request, [
            'id_spp' => 'required',
        ]);
        return Spp::where('id_spp', $request->id_spp)->delete();
    }
    public function getSpp() {
        $spps = Spp::select([
            'spps.id_spp',
            'tahun_akademiks.nama_tahun',
            'tahun_akademiks.id_tahun_akademik',
            'spps.jumlah',
            'tingkats.tingkat',
            'tingkats.id_tingkat',
        ])->leftJoin('tahun_akademiks','spps.id_tahun_akademik', "=", 'tahun_akademiks.id_tahun_akademik')
        ->leftJoin('tingkats','spps.id_tingkat', "=", 'tingkats.id_tingkat')
        ->orderBy('id_spp','desc')->get();
        return response()->json($spps);
    }

    //Controller untuk Iuran
    public function addIuran(Request $request) {
        //validate request
        $this->validate($request, [
            'id_tahun_akademik' => 'required',
            'id_tingkat' => 'required',
            'jenis_tagihan' => 'required',
            'jumlah' => 'required',
        ]);
        return Iuran::create([
            'id_tahun_akademik' => $request->id_tahun_akademik,
            'id_tingkat' => $request->id_tingkat,
            'jenis_tagihan' => $request->jenis_tagihan,
            'jumlah' => $request->jumlah,
        ]);
    }
    public function editIuran(Request $request) {
        //validate request
        $this->validate($request, [
            'id_tahun_akademik' => 'required',
            'id_tingkat' => 'required',
            'jenis_tagihan' => 'required',
            'jumlah' => 'required',
        ]);
        return Iuran::where('id_iuran', $request->id_iuran)->update([
            'id_tahun_akademik' => $request->id_tahun_akademik,
            'id_tingkat' => $request->id_tingkat,
            'jenis_tagihan' => $request->jenis_tagihan,
            'jumlah' => $request->jumlah,
        ]);
    }
    public function deleteIuran(Request $request) {
        //validate request
        $this->validate($request, [
            'id_iuran' => 'required',
        ]);
        return Iuran::where('id_iuran', $request->id_iuran)->delete();
    }
    public function getIuran() {
        $iurans = Iuran::select([
            'iurans.id_iuran',
            'tahun_akademiks.id_tahun_akademik',
            'tahun_akademiks.nama_tahun',
            'tingkats.tingkat',
            'tingkats.id_tingkat',
            'iurans.jenis_tagihan',
            'iurans.jumlah'
        ])->leftJoin('tahun_akademiks','iurans.id_tahun_akademik', "=", 'tahun_akademiks.id_tahun_akademik')
        ->leftJoin('tingkats','iurans.id_tingkat', "=", 'tingkats.id_tingkat')
        ->orderBy('id_iuran','desc')->get();
        return response()->json($iurans);
    }
    
    //Controller untuk Kategori
    public function addKategori(Request $request) {
        //validate request
        $this->validate($request, [
            'nama_kategori' => 'required',
        ]);
        return Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);
    }
    public function editKategori(Request $request) {
        //validate request
        $this->validate($request, [
            'nama_kategori' => 'required',
        ]);
        return Kategori::where('id_kategori', $request->id_kategori)->update([
            'nama_kategori' => $request->nama_kategori,
        ]);
    }
    public function deleteKategori(Request $request) {
        //validate request
        $this->validate($request, [
            'id_kategori' => 'required',
        ]);
        return Kategori::where('id_kategori', $request->id_kategori)->delete();
    }
    public function getKategori() {
        return Kategori::orderBy('id_kategori','desc')->get();
    }

    //Controller untuk Sub-Kategori
    public function addSubkategori(Request $request) {
        //validate request
        $this->validate($request, [
            'id_kategori' => 'required',
            'jenis_subkategori' => 'required',
        ]);
        return Subkategori::create([
            'id_kategori' => $request->id_kategori,
            'jenis_subkategori' => $request->jenis_subkategori,
        ]);
    }
    public function editSubkategori(Request $request) {
        //validate request
        $this->validate($request, [
            'id_kategori' => 'required',
            'jenis_subkategori' => 'required',
        ]);
        return Subkategori::where('id_subkategori', $request->id_subkategori)->update([
            'id_kategori' => $request->id_kategori,
            'jenis_subkategori' => $request->jenis_subkategori,
        ]);
    }
    public function deleteSubkategori(Request $request) {
        //validate request
        $this->validate($request, [
            'id_subkategori' => 'required',
        ]);
        return Subkategori::where('id_subkategori', $request->id_subkategori)->delete();
    }
    public function getSubkategori() {
        $sub = Subkategori::select([
            'id_subkategori',
            'kategoris.id_kategori',
            'kategoris.nama_kategori',
            'jenis_subkategori',
        ])->leftJoin('kategoris','kategoris.id_kategori', "=", 'subkategoris.id_kategori')
        ->orderBy('id_subkategori','desc')->get();
        return response()->json($sub);
    }

    //Controller untuk Pengeluaran
    public function addPengeluaran(Request $request) {
        //validate request
        $this->validate($request, [
            'id_kategori' => 'required',
            'id_subkategori' => 'required',
            'tgl_pengeluaran' => 'required',
            'jumlah' => 'required',
        ]);
        return Pengeluaran::create([
            'id_kategori' => $request->id_kategori,
            'id_subkategori' => $request->id_subkategori,
            'tgl_pengeluaran' => $request->tgl_pengeluaran,
            'jumlah' => $request->jumlah,
        ]);
    }
    public function editPengeluaran(Request $request) {
        //validate request
        $this->validate($request, [
            'id_kategori' => 'required',
            'id_subkategori' => 'required',
            'tgl_pengeluaran' => 'required',
            'jumlah' => 'required',
        ]);
        return Pengeluaran::where('id_pengeluaran', $request->id_pengeluaran)->update([
            'id_kategori' => $request->id_kategori,
            'id_subkategori' => $request->id_subkategori,
            'tgl_pengeluaran' => $request->tgl_pengeluaran,
            'jumlah' => $request->jumlah,
        ]);
    }
    public function deletePengeluaran(Request $request) {
        //validate request
        $this->validate($request, [
            'id_subkategori' => 'required',
        ]);
        return Pengeluaran::where('id_subkategori', $request->id_subkategori)->delete();
    }
    public function getPengeluaran() {
        $p = Pengeluaran::select([
            'id_pengeluaran',
            'kategoris.id_kategori',
            'kategoris.nama_kategori',
            'subkategoris.id_subkategori',
            'subkategoris.jenis_subkategori',
            'tgl_pengeluaran',
            'jumlah',
        ])->leftJoin('kategoris','kategoris.id_kategori', "=", 'pengeluarans.id_kategori')
        ->leftJoin('subkategoris','subkategoris.id_subkategori', "=", 'pengeluarans.id_kategori')
        ->orderBy('id_pengeluaran','desc')->get();
        return response()->json($p);
    }

    //Controller untuk Role
    public function addRole(Request $request) {
        //validate request
        $this->validate($request, [
            'roleName' => 'required',
            'isAdmin' => 'required',
        ]);
        return Role::create([
            'roleName' => $request->roleName,
            'isAdmin' => $request->isAdmin,
        ]);
    }
    public function editRole(Request $request) {
        //validate request
        $this->validate($request, [
            'roleName' => 'required',
            'isAdmin' => 'required',
        ]);
        return Role::where('id_role', $request->id_role)->update([
            'roleName' => $request->roleName,
            'isAdmin' => $request->isAdmin,
        ]);
    }
    public function deleteRole(Request $request) {
        //validate request
        $this->validate($request, [
            'id_role' => 'required',
        ]);
        return Role::where('id_role', $request->id_role)->delete();
    }
    public function getRoles() {
        return Role::all();
    }
    
    //Controller untuk AssignRole
    public function assignRole(Request $request){
        //validate request
        $this->validate($request, [
            'permission' => 'required',
            'id_role' => 'required',
        ]);
        return Role::where('id_role', $request->id_role)->update([
            'permission' => $request->permission,
        ]);
    }


    //Controller untuk Transaksi
    public function transaksiSpp (Request $request) {
        //validate transaksiSpp
        $this->validate($request, [
            'tgl_bayar' => 'required',
            'nis' => 'required',
            'id_spp' => 'required',
            'id_tahun_akademik' => 'required',
            'id_tingkat' => 'required',
            'id_jurusan' => 'required',
        ]);
        return Transaction::create([
            'tgl_bayar' => $request->tgl_bayar,
            'nis' => $request->nis,
            'id_spp' => $request->id_spp,
            'id_tahun_akademik' => $request->id_tahun_akademik,
            'id_tingkat' => $request->id_tingkat,
            'id_jurusan' => $request->id_jurusan,
        ]);
    }

    //Conotroller Untuk Uploads
    // public function addUpload(Request $request) {
    //     //validate request
       
    //     $this->validate($request, [
    //         'tgl' => 'required',
    //         'bukti' => 'required',
    //         'keterangan' => 'required',
    //     ]);
    //     return Upload::create([
    //         'tgl' => $request->tgl,
    //         'bukti' => $request->bukti,
    //         'keterangan' => $request->keterangan,
    //     ]);
    // }
    // public function editUpload(Request $request) {
    //     //validate request
    //     $this->validate($request, [
    //         'tgl' => 'required',
    //         'bukti' => 'required',
    //         'keterangan' => 'required',
    //         'id' => 'required',
    //     ]);
    //     return Upload::where('id', $request->id)->update([
    //         'tgl' => $request->tgl,
    //         'bukti' => $request->bukti,
    //         'keterangan' => $request->keterangan,
    //     ]);
    // }
    // public function deleteUpload(Request $request) {
    //     $this->validate($request, [
    //         'id' => 'required',
    //     ]);
    //     $imageName = $request->bukti;
    //     $filePath = public_path().'/uploads/'.$imageName;
    //     if(file_exists($filePath)){
    //         @unlink($filePath);
    //     };
    //     return Upload::where('id', $request->id)->delete();
    // }
    // public function getUpload() {
    //     return Upload::orderBy('id','desc')->get();
    // }
    // public function upload(Request $request) {
    //     $this->validate($request, [
    //         'file' => 'required|mimes:jpeg,jpg,png'
    //     ]);
    //     $imageName = time().'.'.$request->file->extension();
    //     $request->file->move(public_path('/uploads'),$imageName);
    //     return $imageName;
    // }
    // public function deleteImage(Request $request){
    //     $imageName = $request->imageName;
    //     $this->deleteFileFromServer($imageName);
    //     return 'done';
    // }
    // public function deleteEditImage(Request $request){
    //     $imageName = $request->imageName;
    //     $this->deleteFileFromServer($imageName);
    //     return 'done';
    // }
    // //menghapus file dari folder uploads...
    // public function deleteFileFromServer($imageName) {
    //     $filePath = public_path().'/uploads/'.$imageName;
    //     if(file_exists($filePath)){
    //         unlink($filePath);
    //     };
    //     return;
    // }

    //Controller untuk AdminUsers
    public function createUser(Request $request) {
        //validate request
        $this->validate($request, [
            'fullName' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:8',
            'id_role' => 'required',
        ]);
        $password = bcrypt($request->password);
        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => $password,
            'id_role' => $request->id_role,
        ]);
        return $user;
    }
    public function editAdmin(Request $request) {
        //validate request
        $this->validate($request, [
            'fullName' => 'required',
            'email' => "bail|required|email|unique:users,email,$request->id",            
            'password' => 'min:8',
            'id_role' => 'required',
        ]);
        $data = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'id_role' => $request->id_role,
        ];
        if($request->password) {
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }
        $user = User::where('id', $request->id)->update($data);
        return $user;
    }
    public function deleteAdmin(Request $request) {
        //validate request
        $this->validate($request, [
            'id' => 'required',
        ]);
        return User::where('id', $request->id)->delete();
    }
    public function getUsers() {
        $users = User::select([
            'id',
            'fullName',
            'email',
            'users.created_at',
            'roles.id_role',
            'roles.roleName',
            'roles.isAdmin'
        ])->leftJoin('roles','users.id_role', "=", 'roles.id_role')
        ->orderBy('id','desc')->get();
        return response()->json($users);
    }

    //Controller untuk Login Admin
    public function adminLogin (Request $request) {
     //validate admin
    $this->validate($request, [
        'email' => 'bail|required|email',            
        'password' => 'bail|required|min:8',
    ]);
    if(Auth::attempt(['email'=> $request->email, 'password' => $request->password ])) {
        $user = Auth::user();
        if($user->role->roleName == "Tidak") {
            Auth::logout();
            return response()->json([
                'msg' => 'Login tidak berhasil'
            ], 401);
        }
        return response()->json([
            'msg' => 'Kamu berhasil Login',
            'user' => $user
        ]);
    }else{
        return response()->json([
            'msg' => 'Login Gagal'
        ], 401);
    }
    }

}
