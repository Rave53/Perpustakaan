<?php
include'../koneksi.php';
include'../proteksi.php';
include'sidebar.php';
 ?>

 <link rel="stylesheet" href="../img/csskonten.css">
 <link rel="stylesheet" href="../css/bootstrap.css">

 <?php
 $metode = $_GET['agung'];
 $edit = $koneksi -> prepare("SELECT * FROM buku WHERE kode_buku = :kode");
 $edit -> bindParam(':kode', $metode);
 $edit -> execute();
 $hasil = $edit -> fetch();
 $stokTersedia = $hasil ? (int) $hasil['stok'] : 0;

 $peminjamTersimpan = array();
 $riwayatPeminjam = $koneksi->prepare("SELECT nama_peminjam, kelas_peminjam FROM peminjaman WHERE nama_peminjam IS NOT NULL AND nama_peminjam <> '' ORDER BY kode_pinjam DESC");
 $riwayatPeminjam->execute();
 while ($peminjam = $riwayatPeminjam->fetch(PDO::FETCH_ASSOC)) {
   $key = mb_strtolower(trim($peminjam['nama_peminjam']), 'UTF-8');
   if (!isset($peminjamTersimpan[$key])) {
     $peminjamTersimpan[$key] = array(
       'nama' => $peminjam['nama_peminjam'],
       'kelas' => $peminjam['kelas_peminjam'] ?? ''
     );
   }
 }

 $anggotaTersimpan = $koneksi->prepare("SELECT nama_anggota FROM anggota ORDER BY nama_anggota");
 $anggotaTersimpan->execute();
 while ($anggota = $anggotaTersimpan->fetch(PDO::FETCH_ASSOC)) {
   $key = mb_strtolower(trim($anggota['nama_anggota']), 'UTF-8');
   if (!isset($peminjamTersimpan[$key])) {
     $peminjamTersimpan[$key] = array('nama' => $anggota['nama_anggota'], 'kelas' => '');
   }
 }
 ?>

 <div class="main">
   <button type="button" name="button" onclick="history.back(-1)" class="btn btn-danger btn-lg"><i class="fa fa-fw fa-reply"></i> Kembali</button>
   <?php
    $sql = $koneksi -> prepare("SELECT max(kode_pinjam) as kd_pinjam FROM peminjaman");
    $sql -> execute();
    $hsl = $sql -> fetch();
    $kode = $hsl['kd_pinjam'];
    $nourut = (int) substr($kode, 3, 3);
    $nourut++;
    $char = "PJM";
    $new = $char . sprintf("%03s", $nourut);
   ?>
   <div class="container col-md-8">
     <h1 align=center>Pinjam Buku</h1>
     <br>
     <form action="pinjam_prosess.php?prabowo=tambah" method="post">
       <div class="row">
         <div class="col">
           <label for="kode">Kode Pinjam</label>
         </div>
         <div class="col">
           <input type="text" class="form-control" name="kd_pinjam" value="<?php echo $new; ?>" readonly>
         </div>
       </div>

       <div class="row">
         <div class="col">
           <label for="kode">Nama Peminjam</label>
         </div>
         <div class="col">
           <input type="text" class="form-control" id="nama_peminjam" name="nama_peminjam" list="saran_peminjam" autocomplete="off" placeholder="Ketik nama siswa/peminjam" required>
           <datalist id="saran_peminjam">
             <?php foreach ($peminjamTersimpan as $peminjam): ?>
               <option value="<?php echo htmlspecialchars($peminjam['nama'], ENT_QUOTES, 'UTF-8'); ?>"></option>
             <?php endforeach; ?>
           </datalist>
           <small>Pilih saran atau ketik nama baru.</small>
         </div>
       </div>

       <div class="row">
         <div class="col">
           <label for="kelas_peminjam">Kelas</label>
         </div>
         <div class="col">
           <input type="text" class="form-control" id="kelas_peminjam" name="kelas_peminjam" placeholder="Contoh 5B" required>
         </div>
       </div>

       <div class="row">
         <div class="col">
           <label for="kode">Kode Buku</label>
         </div>
         <div class="col">
           <input type="text" class="form-control" name="kd_buku" value="<?php echo $hasil['kode_buku']; ?>" readonly>
         </div>
       </div>

       <div class="row">
         <div class="col">
           <label for="stok_tersedia">Stok Tersedia</label>
         </div>
         <div class="col">
           <input type="text" class="form-control" id="stok_tersedia" value="<?php echo $stokTersedia; ?>" readonly>
         </div>
       </div>

       <div class="row">
         <div class="col">
           <label for="kelas_buku">Kelas Buku</label>
         </div>
         <div class="col">
           <input type="text" class="form-control" id="kelas_buku" value="<?php echo htmlspecialchars((string) (($hasil['kelas_buku'] ?? '') !== '' ? $hasil['kelas_buku'] : 'Belum diisi'), ENT_QUOTES, 'UTF-8'); ?>" readonly>
         </div>
       </div>

       <div class="row">
         <div class="col">
           <label for="kode">Tanggal Pinjam</label>
         </div>
         <div class="col">
           <input type="date" class="form-control" name="tgl_pinjam" required>
         </div>
       </div>

       <div class="row">
         <div class="col">
           <label for="kode">Jumlah Pinjam</label>
         </div>
         <div class="col">
           <input type="number" class="form-control" name="jmlh_pinjam" min="1" max="<?php echo $stokTersedia; ?>" required>
         </div>
       </div>

       <br>
       <button type="submit" class="btn btn-success" <?php echo $stokTersedia > 0 ? '' : 'disabled'; ?>>
         <?php echo $stokTersedia > 0 ? 'Pinjam Buku' : 'Stok Habis'; ?>
       </button>
     </form>
   </div>
 </div>
 <script>
   var peminjamTersimpan = <?php echo json_encode(array_values($peminjamTersimpan), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
   var namaPeminjamInput = document.getElementById('nama_peminjam');
   var kelasPeminjamInput = document.getElementById('kelas_peminjam');
   namaPeminjamInput.addEventListener('change', function () {
     var nama = this.value.trim().toLocaleLowerCase();
     var sebelumnya = peminjamTersimpan.find(function (peminjam) {
       return peminjam.nama.trim().toLocaleLowerCase() === nama;
     });
     if (sebelumnya && sebelumnya.kelas) {
       kelasPeminjamInput.value = sebelumnya.kelas;
     }
   });
 </script>
