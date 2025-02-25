<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index(): View
    
    {
        // Ambil data dari database
        $siswas = DB::table('siswas')
            ->join('users', 'siswas.id_user', '=', 'users.id')
            ->select('siswas.*', 'users.name', 'users.email')
            ->paginate(10);

        if (request('cari')) {
            $siswas = $this->search(request('cari'));
        }

        return view('admin.siswa.index', compact('siswas'));
    }

    public function create(): View
    {
        return view('admin.siswa.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validasi input formulir
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nis' => 'required|numeric',
            'tingkatan' => 'required',
            'jurusan' => 'required',
            'kelas' => 'required',
            'hp' => 'required|numeric'
        ]);

        // Unggah gambar
        $image = $request->file('image');
        $imagePath = $image->storeAs('siswas', $image->hashName(), 'public');

        // Insert akun pengguna
        $id_akun = $this->insertAccount($request->name, $request->email, $request->password);

        // Buat catatan Siswa baru
        Siswa::create([
            'id_user' => $id_akun,
            'image' => $image->hashName(),
            'nis' => $request->nis,
            'tingkatan' => $request->tingkatan,
            'jurusan' => $request->jurusan,
            'kelas' => $request->kelas,
            'hp' => $request->hp,
            'status' => 1
        ]);

        // Redirect ke indeks dengan pesan sukses
        return redirect()->route('siswa.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function insertAccount(string $nama, string $email, string $password)
    {
        User::create([
            'name' => $nama,
            'email' => $email,
            'password' => Hash::make($password),
            'usertype' => 'siswa',
        ]);

        // Mengambil ID pengguna berdasarkan email
        $id = DB::table('users')->where('email', $email)->value('id');
        return $id;
    }

    public function show(string $id): View
    {
        // Ambil data Siswa dari database
        $siswa = DB::table('siswas')
            ->join('users', 'siswas.id_user', '=', 'users.id')
            ->select('siswas.*', 'users.name', 'users.email')
            ->where('siswas.id', $id)
            ->first();

        return view('admin.siswa.show', compact('siswa'));
    }

    public function search(string $cari)
    {
        return DB::table('siswas')
            ->join('users', 'siswas.id_user', '=', 'users.id')
            ->select('siswas.*', 'users.name', 'users.email')
            ->where('users.name', 'like', '%' . $cari . '%')
            ->orWhere('siswas.nis', 'like', '%' . $cari . '%')
            ->orWhere('users.email', 'like', '%' . $cari . '%')
            ->paginate(10);
    }

    public function edit($id): View
    {
        // Ambil data siswa yang akan diedit
        $siswa = Siswa::findOrFail($id);
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        // Validasi input formulir
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'nullable|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nis' => 'required|numeric',
            'tingkatan' => 'required',
            'jurusan' => 'required',
            'kelas' => 'required',
            'hp' => 'required|numeric'
        ]);

        // Dapatkan data siswa
        $siswa = Siswa::findOrFail($id);

        // Update akun pengguna
        $this->editAccount($request->name, $id);

        // Periksa apakah gambar baru diunggah
        if ($request->hasFile('image')) {
            // Unggah gambar baru
            $image = $request->file('image');
            $imagePath = $image->storeAs('public/siswa', $image->hashName());

            // Hapus gambar lama
            Storage::delete('public/siswa/' . $siswa->image);

            // Update data siswa dengan gambar baru
            $siswa->update([
                'image' => $image->hashName(),
                'nis' => $request->nis,
                'tingkatan' => $request->tingkatan,
                'jurusan' => $request->jurusan,
                'kelas' => $request->kelas,
                'hp' => $request->hp,
                'status' => $request->status
            ]);
        } else {
            // Update tanpa mengganti gambar
            $siswa->update([
                'nis' => $request->nis,
                'tingkatan' => $request->tingkatan,
                'jurusan' => $request->jurusan,
                'kelas' => $request->kelas,
                'hp' => $request->hp,
                'status' => $request->status
            ]);
        }

        // Redirect ke indeks dengan pesan sukses
        return redirect()->route('siswa.index')->with(['success' => 'Data Berhasil Diubah']);
    }

    public function editAccount(string $name, string $id)
    {
        // Dapatkan ID pengguna
        $siswa = DB::table('siswas')->where('id', $id)->value('id_user');
        $user = User::findOrFail($siswa);

        // Jika ada perubahan nama
        $user->update([
            'name' => $name
        ]);
    }
    //Hapus data
    public function destroy($id): RedirectResponse
    {
        //delete pelanggar
        $this->destroy($id);

        //get post bt ID
        $post = Siswa::findOrfail($id);

        //delete image
        Storage::delete('public/siswas/'. $post->image);

        //delete post
        $post->delete();

        //redirect to index
        return redirect()->route('siswa.index')->with(['succes' => 'Data Berhasil Dihapus']);
    }
    public function destroyUser(string $id)
    {
        //get id user
        $siswa =DB::table('siswas')->where('id',$id);
        $user = User::findOrfail($siswa);

        //delete post
        $user->delete();
    }
}
