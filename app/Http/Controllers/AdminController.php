<?php

namespace App\Http\Controllers;

use App\Member;
use App\Fare;
use App\Upload;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        //return 'here';
        
        // Jika sudah login.. akan dilakukan pengecekan jika login sebagai admin
        $user = Auth::user();
        if($user->userType == 'Member') {
            return redirect('/login');
        }
        if($request->path() == 'login') {
            return redirect('/');
        }
        return view('welcome');
    }
    //Controller untuk Logout
    public function logout() {
        Auth::logout();
        return redirect('/login');
    }

    //Controller untuk Member
    public function addMember(Request $request) {
        //validate request
        $this->validate($request, [
            'nama' => 'required',
            'email' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'nm_club' => 'required',
        ]);
        return Member::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'nm_club' => $request->nm_club
        ]);
    }
    public function editMember(Request $request) {
        //validate request
        $this->validate($request, [
            'nama' => 'required',
            'email' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'nm_club' => 'required',
            'id' => 'required',
        ]);
        return Member::where('id', $request->id)->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'nm_club' => $request->nm_club
        ]);
    }
    public function deleteMember(Request $request) {
        //validate request
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Member::where('id', $request->id)->delete();
    }
    public function getMember() {
        return Member::orderBy('id','desc')->get();
    }

    // Controller Untuk Tarif
    public function addFare(Request $request) {
        //validate request
        $this->validate($request, [
            'waktu' => 'required',
            'harga' => 'required',
        ]);
        return Fare::create([
            'waktu' => $request->waktu,
            'harga' => $request->harga,
        ]);
    }
    public function editFare(Request $request) {
        //validate request
        $this->validate($request, [
            'waktu' => 'required',
            'harga' => 'required',
            'id' => 'required',
        ]);
        return Fare::where('id', $request->id)->update([
            'waktu' => $request->waktu,
            'harga' => $request->harga,
        ]);
    }
    public function deleteFare(Request $request) {
        //validate request
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Fare::where('id', $request->id)->delete();
    }
    public function getFare() {
        return Fare::orderBy('id','desc')->get();
    }

    //Conotroller Untuk Uploads
    public function addUpload(Request $request) {
        //validate request
       
        $this->validate($request, [
            'tgl' => 'required',
            'bukti' => 'required',
            'keterangan' => 'required',
        ]);
        return Upload::create([
            'tgl' => $request->tgl,
            'bukti' => $request->bukti,
            'keterangan' => $request->keterangan,
        ]);
    }
    public function editUpload(Request $request) {
        //validate request
        $this->validate($request, [
            'tgl' => 'required',
            'bukti' => 'required',
            'keterangan' => 'required',
            'id' => 'required',
        ]);
        return Upload::where('id', $request->id)->update([
            'tgl' => $request->tgl,
            'bukti' => $request->bukti,
            'keterangan' => $request->keterangan,
        ]);
    }
    public function deleteUpload(Request $request) {
        $this->validate($request, [
            'id' => 'required',
        ]);
        $imageName = $request->bukti;
        $filePath = public_path().'/uploads/'.$imageName;
        if(file_exists($filePath)){
            @unlink($filePath);
        };
        return Upload::where('id', $request->id)->delete();
    }
    public function getUpload() {
        return Upload::orderBy('id','desc')->get();
    }
    public function upload(Request $request) {
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

    //Controller untuk AdminUsers
    public function createUser(Request $request) {
        //validate request
        $this->validate($request, [
            'fullName' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:8',
            'userType' => 'required',
        ]);
        $password = bcrypt($request->password);
        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => $password,
            'userType' => $request->userType,
        ]);
        return $user;
    }
    public function editAdmin(Request $request) {
        //validate request
        $this->validate($request, [
            'fullName' => 'required',
            'email' => "bail|required|email|unique:users,email,$request->id",            
            'password' => 'min:8',
            'userType' => 'required',
        ]);
        $data = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'userType' => $request->userType,
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
        //$date = User::all('created_at')->toArray();
        //return([$date]);
        return User::where('userType','!=','User')->get();
    }

    //Controller untuk Login
    public function adminLogin (Request $request) {
     //validate admin
    $this->validate($request, [
        'email' => 'bail|required|email',            
        'password' => 'bail|required|min:8',
    ]);
    if(Auth::attempt(['email'=> $request->email, 'password' => $request->password ])) {
        $user = Auth::user();
        if($user->userType == 'User') {
            Auth::logout();
            return response()->json([
                'msg' => 'Login Gagal'
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
