<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Lakukan join antara tabel 'user' dan 'roles' untuk mendapatkan nama role
        $users = User::
            join('roles', 'user.roles_id', '=', 'roles.id')
            ->select('user.*', 'roles.name as roles_name')
            ->get();

        // Tampilkan view dengan data users
        return view('user.register', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = Role::all();
        return view('user.create', compact('role'));
    }

    /**
     * Store a newly created user in storage with the 'customer' role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user,email',
            'password' => 'required|string|min:8',
        ]);

        $nm = $request->roles_id;
        $namaFile = $nm;
        // Buat user dan tetapkan roles_id
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles_id' => $namaFile, // Misal ini untuk role owner
        ]);

        // Ambil role dan permission
        $role = Role::find($namaFile); // Ganti 2 dengan ID role yang sesuai jika perlu
        if ($role) {
            // Assign permission ke user berdasarkan role
            $permissions = $role->permissions; // Mendapatkan semua permissions untuk role ini
            foreach ($permissions as $permission) {
                $user->givePermissionTo($permission->name);
            }
        }

        // Alihkan kembali ke halaman daftar pengguna dengan pesan sukses
        return redirect()->route('register')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id); // Include roles in the response
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:user,email,' . $id,
            'password' => 'sometimes|string|min:8',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User deleted successfully.');
    }
}