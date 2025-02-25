<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Data Siswa</h1>

        <div class="nav-links">
            <a href="{{ route('admin/dashboard') }}">Menu Utama</a>
            <a href="{{ route('siswa.create') }}">Tambah Siswa</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <!-- Success Message -->
        @if(Session::has('success'))
        <div class="alert-success">
            {{ Session::get('success') }}
        </div>
        @endif

        <!-- Search Form -->
        <div class="search-container">
            <form action="" method="get">
                <label for="search">Cari :</label>
                <input type="text" name="cari" placeholder="Cari data siswa..." id="search">
                <input type="submit" value="Cari">
            </form>
        </div>

        <!-- Table Data Siswa -->
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Kelas</th>
                    <th>No HP</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswas as $siswa)
                <tr>
                    <td>
                        <img src="{{ asset('storage/siswas/'.$siswa->image) }}" alt="Foto Siswa">
                    </td>
                    <td>{{ $siswa->nis }}</td>
                    <td><strong>{{ $siswa->name }}</strong></td>
                    <td>{{ $siswa->email }}</td>
                    <td>{{ $siswa->tingkatan }} {{ $siswa->jurusan }} {{ $siswa->kelas }}</td>
                    <td>{{ $siswa->hp }}</td>
                    <td>{{ $siswa->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                    <td>
                        <a href="{{ route('siswa.show', $siswa->id) }}" class="btn btn-dark">SHOW</a>
                        <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-primary">EDIT</a>
                        <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">HAPUS</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td>
                        <p>Data tidak ditemukan</p>
                        </td>
                        <td>
                            <a href="{{ route('siswa.index') }}">Kembali</a>
                        </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="pagination">
            {{ $siswas->links() }}
        </div>
    </div>
</body>

</html>



