<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserController extends Controller
{
    public function index(){
        $users = User::whereNotIn('id', [1])->get();

        return view("user.index", compact("users"));
    }

    public function createPost(Request $request){
        $request->validate([
            "nama" => ["required", "max:255"],
            "email" => ["required", "unique:users"],
            "password" => ["required", "min:6"],
        ]);

        try {
            $user = User::create([
                "name" => $request->input("nama"),
                "email" => $request->input("email"),
                "password" => $request->input("password"),
            ]);

            Log::notice("akun $user->email berhasil dibuat", [
                "user" => "email"
            ]);

            return back()->with("success", "akun $user->email berhasil dibuat");
        } catch (Throwable $th) {
            
            Log::error("user akun gagal dibuat", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            dd($th);
            return back()->with("error", "gagal membuat akun baru");
        }
    }

    /**
     * Detail di desain
     */
    public function detail($uid){
        $user = User::where('user_uid', $uid)->first();

        abort_if(!$user, 404, 'data not found');

        return view("user.detail", compact("user"));
    }

    public function updateAkunPost($uid, Request $request){

        $user = User::where('user_uid', $uid)->first();

        abort_if(!$user, 404);

        $request->validate([
            "nama" => ["required", "max:255"],
            "email" => ["required", "max:255", "unique:users,email," . $user->id]
        ]);

        try{
            $user->name = $request->input("nama");
            $user->email = $request->input("email");
            $user->save();

            Log::notice("user akun $user->email, berhasil diperbarui", [
                "user" => "email"
            ]);

            return redirect(url()->previous())->with("success", "akun berhasil diperbarui");
        } catch(Throwable $th){
            Log::error("user akun gagal di update datanya", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "gagal dalam update data akun");
        }
    }

    public function updatePasswordPost($uid, Request $request){
        $request->validate([
            "password" => ["required", "min:6"]
        ]);

        $user = User::where('user_uid', $uid)->first();

        abort_if(!$user, 404);

        try{
            $user->password = $request->input("password");
            $user->save();

            Log::notice("user akun $user->email password di reset", [
                "user" => "email"
            ]);

            return redirect(url()->previous())->with("success", "password akun berhasil direset");
        } catch(Throwable $th){
            Log::error("user akun gagal di reset password", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "gagal reset password akun");
        }
    }

    // block user akun post
    public function updateBlockPost($uid, Request $request){

        $user = User::where('user_uid', $uid)->first();

        abort_if(!$user || $user->id == 1, 404, 'data not found');

        if($user->block == ($request->has("block") ? 1 : 0)){
            return redirect(url()->previous())->with("info", "tidak ada data yang dirubah pada akun ini");
        }

        try{

            $user->block = $request->has("block") ? 1 : 0;
            $user->save();

            Log::notice("user akun $user->email berhasil di block", [
                "user" => "email"
            ]);
            
            return redirect(url()->previous())->with("success", "berhasil memblock akun ini");
        } catch(Throwable $th){
            Log::error("user akun gagal di block", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("gagal block akun ini. Hubungi admin");
        }
    }

    /**
     * DELETE User
     * 
     * pada delete, parameter yang digunakan ialah id
     */
    public function deleteUser($id){
        $user = User::findOrFail($id);

        // out jika id super admin
        abort_if($user->id == 1, 403);

        try {
            $email = $user->email;
            $user->delete();

            Log::notice("user akun $email telah dihapus", [
                "user" => "email"
            ]);

            return redirect()->route("user")->with("success", "akun berhasil dihapus");
        } catch (Throwable $th) {
            Log::error("user akun gagal di block", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("gagal block akun ini. Hubungi admin");
        }
    }
}
