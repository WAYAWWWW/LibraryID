<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Loan;
use App\Models\Book;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        Loan::syncOverdueFines();
        Loan::syncReturnedFines();
        $users = User::paginate(10);
        $loans = Loan::latest()->paginate(10);
        return view('admin.dashboard', compact('users','loans'));
    }

    public function createPetugas(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:user',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => 'petugas',
            'registered_by' => auth()->id(),
        ]);

        return redirect()->back();
    }

    public function userList()
    {
        $petugas = User::where('role', 'petugas')->get();
        return view('admin.users', compact('petugas'));
    }

    public function deleteUser(User $user)
    {
        if ($user->role !== 'user') {
            return redirect()->back()->with('status', 'Hanya akun user yang bisa dihapus');
        }

        $user->delete();
        return redirect()->back()->with('status', 'User dihapus');
    }

    public function manageUsers()
    {
        $users = User::where('role', 'user')->orderBy('name')->paginate(20);
        return view('admin.users-manage', compact('users'));
    }

    public function editUser(User $user)
    {
        if ($user->role !== 'user') {
            abort(404);
        }

        return view('admin.user-edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        if ($user->role !== 'user') {
            abort(404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . $user->id,
            'password' => 'nullable|min:6',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);
        return redirect()->route('admin.users.manage')->with('status', 'Data user diperbarui');
    }

    public function toggleUserStatus(User $user)
    {
        if ($user->role !== 'user') {
            return redirect()->back()->with('status', 'Hanya akun user yang bisa dinonaktifkan');
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        return redirect()->back()->with(
            'status',
            $user->is_active ? 'Akun user diaktifkan' : 'Akun user dinonaktifkan'
        );
    }

    public function petugasDashboard()
    {
        Loan::syncOverdueFines();
        Loan::syncReturnedFines();
        $users = User::paginate(10);
        $loans = Loan::with(['user', 'book'])->latest()->paginate(15);
        return view('petugas.dashboard', compact('users', 'loans'));
    }
}
