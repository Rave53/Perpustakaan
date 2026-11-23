<?php include'../koneksi.php'; ?>
 <link rel="stylesheet" href="../css/bootstrap.css">
 <script>
         window.print();
     </script>

<h1 align=center>Data Buku</h1>

<table class="table table-hover">
  <thead align=center>
    <tr>
      <th scope="col">No</th>
      <th scope="col">Kode Kembali</th>
      <th scope="col">Kode Pinjam</th>
      <th scope="col">NIS</th>
      <th scope="col">Nama Peminjam</th>
      <th scope="col">Kelas</th>
      <th scope="col">Kode Buku</th>
      <th scope="col">Tanggal Pengembalian</th>
      <th scope="col">Status Pengembalian</th>
    </tr>
  </thead>
  <?php
  $i = 1;
  $asd = $koneksi -> prepare("SELECT r.*, COALESCE(NULLIF(r.nama_peminjam, ''), NULLIF(p.nama_peminjam, ''), a.nama_anggota, '') AS nama_tampil, COALESCE(NULLIF(r.kelas_peminjam, ''), p.kelas_peminjam, '') AS kelas_tampil FROM pengembalian r LEFT JOIN peminjaman p ON p.kode_pinjam = r.kode_pinjam LEFT JOIN anggota a ON a.nis = COALESCE(NULLIF(r.nis, ''), p.nis)");
  $asd -> execute();
  while($data = $asd -> fetch()){
  ?>
  <tbody>
    <tr>
      <td><?php echo $i ?></td>
      <td><?php echo $data['kode_kembali']; ?></td>
      <td><?php echo $data['kode_pinjam']; ?></td>
      <td><?php echo $data['nis']; ?></td>
      <td><?php echo htmlspecialchars($data['nama_tampil'], ENT_QUOTES, 'UTF-8'); ?></td>
      <td><?php echo htmlspecialchars($data['kelas_tampil'], ENT_QUOTES, 'UTF-8'); ?></td>
      <td><?php echo $data['kode_buku']; ?></td>
      <td><?php echo $data['tgl_pengembalian']; ?></td>
      <td><?php echo $data['status_pengembalian']; ?></td>
    </tr>
  </tbody>
  <?php $i++; } ?>
</table>
