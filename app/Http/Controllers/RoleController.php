<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Throwable;

class RoleController extends Controller
{
    public function index(){
        $roles = Role::get();

        return view("role.index", compact("roles"));
    }

    public function create(){
        return view("role.create");
    }

    public function createPost(Request $request){
        $request->validate([
            "name" => ["required", "max:20", "min:4", "unique:roles"],
            "permission" => ["required"]
        ]);

        try{
            DB::beginTransaction();

            $role = Role::create(["name" => $request->input("name")]);
            $role->givePermissionTo($request->input("permission"));

            DB::commit();
            return redirect()->route("role")->with("success", "berhasil membuat role baru");
        } catch(Throwable $th){
            DB::rollBack();

            Log::error("role permission gagal dibuat", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return back()->with("error", "gagal membuat role baru");
        }
    }

    public function detail($role){
        $role = Role::with('users', 'permissions')->where("name", $role)->first();

        abort_if(!$role, 404);

        $users = \App\Models\User::where("id", "!=", 1)->get();

        $permission = [];
        foreach($role->permissions->pluck('name') as $key => $item){
            $permission[] = $item;
        }

        $role->permission = $permission;

        return view("role.detail", compact("role", "users"));
    }

    public function updateNamaRolePost($role, Request $request){

        $role = Role::where("name", $role)->first();

        abort_if(!$role, 404);

        $request->validate([
            "name" => ["required", "max:20", "min:4", "unique:roles," . $role->id]
        ]);


        try{

            $role->name = $request->input("name");
            $role->save();

            Log::notice("role nama berhasil di update", [
                "user" => "email"
            ]);

            return redirect()->route("role.detail", ['role' => $role->name])->with("success", "role berhasil diupdate");
        } catch(Throwable $th){
            Log::error("role permission gagal dibuat", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "role gagal diupdate");
        }
    }

    public function updatePermissionRolePost($role, Request $request){
        $role = Role::where("name", $role)->first();

        abort_if(!$role, 404);

        $request->validate([
            "permission" => "required"
        ]);

        try{
            DB::beginTransaction();

            $role->syncPermissions([$request->input("permission")]);

            DB::commit();

            Log::notice("role permission berhasil di update", [
                "user" => "email"
            ]);

            return redirect(url()->previous())->with("success", "permission berhasil diupdate");
        } catch(Throwable $th){
            DB::rollBack();

            Log::error("role permission gagal dibuat", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "permission gagal diupdate");
        }
    }

    public function addUserToRole($role, Request $request){
        $request->validate([
            "user-id" => ["required", "numeric", "exists:users,id"]
        ]);

        $role = Role::where("name", $role)->first();

        abort_if(!$role, 404);

        $user = \App\Models\User::findOrFail($request->input("user-id"));


        try{

            $user->syncRoles($role->name);

            Log::notice("role user berhasil ditambahkan. user: $user->email, role: $role->name", [
                "user" => "email"
            ]);

            return redirect(url()->previous())->with("success", "role berhasil ditambahkan ke user");
        } catch(Throwable $th){
            Log::error("role user gagal ditambahkan", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "user role gagal ditambahkan");
        }
    }

    public function deleteUserToRole($role, $id){

        $role = Role::where("name", $role)->first();

        abort_if(!$role, 404);

        $user = \App\Models\User::findOrFail($id);


        try{

            $user->removeRole($role->name);

            Log::notice("role user berhasil dihapus. user: $user->email, role: $role->name", [
                "user" => "email"
            ]);

            return redirect(url()->previous())->with("success", "role berhasil dihapus dari user");
        } catch(Throwable $th){
            Log::error("role user gagal dihapus", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "user role gagal dihapus");
        }
    }

    public function deleteRole($role){
        $role = Role::with('users', 'permissions')->where("name", $role)->first();

        abort_if(!$role, 404);

        try{
            $nama = $role->name;
            DB::beginTransaction();
            // Hapus user-role terkait dengan role
            $role->users()->detach();

            // Hapus permission yang terkait dengan role
            $role->permissions()->detach();

            // Hapus role
            $role->delete();

            DB::commit();

            Log::notice("role $nama berhasil dihapus", [
                "user" => "email"
            ]);

            return redirect()->route("role")->with("success", "Role berhasil dihapus");
        } catch(Throwable $th){
            DB::rollBack();

            Log::error("role permission gagal dibuat", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "role gagal dihapus");
        }
    }
}
