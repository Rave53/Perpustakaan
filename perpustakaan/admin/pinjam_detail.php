<?php
include'../koneksi.php';
include'../proteksi.php';
include'sidebar.php';
 ?>

 <link rel="stylesheet" href="../img/csskonten.css">
 <link rel="stylesheet" href="../css/bootstrap.css">

 <?php
 $metode = $_GET['agung'];
 $edit = $koneksi -> prepare("SELECT p.*, COALESCE(NULLIF(p.nama_peminjam, ''), a.nama_anggota, '') AS nama_tampil, COALESCE(p.kelas_peminjam, '') AS kelas_tampil FROM peminjaman p LEFT JOIN anggota a ON a.nis = p.nis WHERE p.kode_pinjam = :kode");
 $edit -> bindParam(':kode', $metode);
 $edit -> execute();
 $hasil = $edit -> fetch();
 ?>

 <div class="main">
   <button type="button" name="button" onclick="history.back(-1)" class="btn btn-danger btn-lg"><i class="fa fa-fw fa-reply"></i> Kembali</button>
   <div class="container col-md-8">
     <h1 align=center>Detail Peminjaman</h1>
     <div class="row">
       <div class="col">
         <label for="kode">Kode Pinjam</label>
       </div>
       <div class="col">
         <input type="text" class="form-control" name="kd_pinjam" value="<?php echo $hasil['kode_pinjam']; ?>" readonly>
       </div>
     </div>

     <div class="row">
       <div class="col">
         <label for="nama_peminjam">Nama Peminjam</label>
       </div>
       <div class="col">
         <input type="text" class="form-control" id="nama_peminjam" value="<?php echo htmlspecialchars($hasil['nama_tampil'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
       </div>
     </div>

     <div class="row">
       <div class="col">
         <label for="kelas_peminjam">Kelas</label>
       </div>
       <div class="col">
         <input type="text" class="form-control" id="kelas_peminjam" value="<?php echo htmlspecialchars($hasil['kelas_tampil'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
       </div>
     </div>

     <div class="row">
       <div class="col">
         <label for="kode">NIS</label>
       </div>
       <div class="col">
         <input type="text" class="form-control" name="kd_pinjam" value="<?php echo $hasil['nis']; ?>" readonly>
       </div>
     </div>

     <div class="row">
       <div class="col">
         <label for="kode">Kode Buku</label>
       </div>
       <div class="col">
         <input type="text" class="form-control" name="kd_buku" value="<?php echo $hasil['kode_buku'];?>" readonly>
       </div>
     </div>

     <div class="row">
       <div class="col">
         <label for="kode">Tanggal Pinjam</label>
       </div>
       <div class="col">
         <input type="date" class="form-control" name="tgl_pinjam" value="<?php echo $hasil['tgl_pinjam'];?>" readonly>
       </div>
     </div>

     <div class="row">
       <div class="col">
         <label for="kode">Jumlah Pinjam</label>
       </div>
       <div class="col">
         <input type="text" class="form-control" name="jmlh_pinjam" value="<?php echo $hasil['jumlah'];?>" readonly>
       </div>
     </div>

     <div class="row">
       <div class="col">
         <label for="kode">Status Pinjam</label>
       </div>
       <div class="col">
         <input type="text" class="form-control" name="jmlh_pinjam" value="<?php echo $hasil['status_pinjam'];?>" readonly>
       </div>
     </div>

     <br>
   </div>
 </div>
