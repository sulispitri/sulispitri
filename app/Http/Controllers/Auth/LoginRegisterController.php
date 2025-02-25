<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Siswa;

class LoginRegisterController extends Controller
{
    public function index()
    {
        // Mengambil data user dengan pagination
        $users = User::latest()->paginate(10);
        return view('admin.akun.index', compact('users'));
    }

    public function create()
    {
        return view('admin.akun.create');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'usertype' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => $request->usertype
        ]);

        Auth::login($user); // Login otomatis setelah registrasi
        $request->session()->regenerate();

        if ($user->usertype == 'admin') {
            return redirect('admin/dashboard')->withSuccess('You have successfully registered & logged in!');
        }

        return redirect()->route('dashboard');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->usertype == 'admin') {
                return redirect('admin/dashboard')->withSuccess('You have successfully logged in!');
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Your provided credentials do not match in our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->withSuccess('You have logged out successfully!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:250',
            'usertype' => 'required'
        ]);

        $user = User::findOrFail($id);
        $user->update($validated);

        return redirect()->route('akun.edit', $id)->withSuccess('Data Berhasil Diubah!');
    }

    public function updateEmail(Request $request, $id)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:250|unique:users,email,' . $id
        ]);

        $user = User::findOrFail($id);
        $user->update(['email' => $request->email]);

        return redirect()->route('akun.edit', $id)->withSuccess('Email Berhasil Diubah!');
    }

    public function updatePassword(Request $request, $id)
    {
        $validated = $request->validate([
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::findOrFail($id);
        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('akun.edit', $id)->withSuccess('Password Berhasil Diubah!');
    }

    // Hapus Data
    public function destroy($id): RedirectResponse
    {
        // Cari id siswa
        $siswa = DB::table('siswas')->where('id_user', $id)->value('id');

        // Jika siswa ditemukan, hapus
        if ($siswa) {
            $this->destroySiswa($siswa);
        }

        // Hapus user berdasarkan ID
        $post = User::findOrFail($id);
        $post->delete();

        // Redirect ke halaman akun.index dengan pesan sukses
        return redirect()->route('akun.index')->with(['success' => 'Akun Berhasil Dihapus!']);
    }

    public function destroySiswa(string $id)
    {
        // Cari siswa berdasarkan ID
        $post = Siswa::findOrFail($id);

        // Hapus gambar jika ada
        if ($post->image && Storage::exists('public/siswas/' . $post->image)) {
            Storage::delete('public/siswas/' . $post->image);
        }

        // Hapus data siswa dari database
        $post->delete();
    }
}
