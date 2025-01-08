<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Siswa</title>
  <link href="<?php echo e(asset('css/create.css')); ?>" rel="stylesheet">
  
</head>

<body>
  <div class="form-container">
    <h1>Tambah Siswa</h1>
    <a href="<?php echo e(route('siswa.index')); ?>" class="back-button">Kembali</a>

    <!-- Error Alert -->
    <?php if($errors->any()): ?>
    <div class="alert">
      <ul>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div>
    <?php endif; ?>

    <!-- Form Tambah Siswa -->
    <form action="<?php echo e(route('siswa.store')); ?>" method="POST" enctype="multipart/form-data">
      <?php echo csrf_field(); ?>

      <h2>Akun Siswa</h2>
      <label>Nama Lengkap</label>
      <input type="text" id="name" name="name" value="<?php echo e(old('name')); ?>" placeholder="Masukkan nama lengkap">

      <label>Email Address</label>
      <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="Masukkan email">

      <label>Password</label>
      <input type="password" id="password" name="password" placeholder="Masukkan password">

      <label>Confirm Password</label>
      <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password">

      <h2>Data Siswa</h2>
      <label>Foto Siswa</label>
      <input type="file" name="image" accept="image/*" required>

      <label>NIS Siswa</label>
      <input type="text" name="nis" value="<?php echo e(old('nis')); ?>" placeholder="Masukkan NIS" required>

      <label>Tingkatan</label>
      <select name="tingkatan" required>
        <option value="">Pilih Tingkatan</option>
        <option value="X">X</option>
        <option value="XI">XI</option>
        <option value="XII">XII</option>
      </select>

      <label>Jurusan</label>
      <select name="jurusan" required>
        <option value="">Pilih Jurusan</option>
        <option value="TBSM">TBSM</option>
        <option value="TJKT">TJKT</option>
        <option value="PPLG">PPLG</option>
        <option value="DKV">DKV</option>
        <option value="TOI">TOI</option>
      </select>

      <label>Kelas</label>
      <select name="kelas" required>
        <option value="">Pilih Kelas</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
      </select>

      <label>No Hp</label>
      <input type="text" name="hp" value="<?php echo e(old('hp')); ?>" placeholder="Masukkan nomor HP" required>

      <!-- Button Container -->
      <div class="btn-container">
        <button type="submit">SIMPAN DATA</button>
        <button type="reset">RESET FORM</button>
      </div>
    </form>
  </div>
</body>
</html>








<?php /**PATH C:\laragon\www\sulis_pitri\resources\views/auth/register.blade.php ENDPATH**/ ?>