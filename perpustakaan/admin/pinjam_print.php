<?php include'../koneksi.php'; ?>
 <link rel="stylesheet" href="../css/bootstrap.css">
 <script>
         window.print();
     </script>

<h1 align=center>Data Peminjam</h1>

<table class="table table-hover">
  <thead align=center>
    <tr>
      <th scope="col">No</th>
      <th scope="col">Kode Pinjam</th>
      <th scope="col">Nis</th>
      <th scope="col">Nama Peminjam</th>
      <th scope="col">Kelas</th>
      <th scope="col">Kode Buku</th>
      <th scope="col">Tanggal Pinjam</th>
      <th scope="col">Jumlah</th>
      <th scope="col">Status Pinjam</th>
    </tr>
  </thead>
  <?php
  $i = 1;
  $asd = $koneksi -> prepare("SELECT p.*, COALESCE(NULLIF(p.nama_peminjam, ''), a.nama_anggota, '') AS nama_tampil, COALESCE(p.kelas_peminjam, '') AS kelas_tampil FROM peminjaman p LEFT JOIN anggota a ON a.nis = p.nis");
  $asd -> execute();
  while($data = $asd -> fetch()){
  ?>
  <tbody>
    <tr>
      <td><?php echo $i ?></td>
      <td><?php echo $data['kode_pinjam']; ?></td>
      <td><?php echo $data['nis']; ?></td>
      <td><?php echo htmlspecialchars($data['nama_tampil'], ENT_QUOTES, 'UTF-8'); ?></td>
      <td><?php echo htmlspecialchars($data['kelas_tampil'], ENT_QUOTES, 'UTF-8'); ?></td>
      <td><?php echo $data['kode_buku']; ?></td>
      <td><?php echo $data['tgl_pinjam']; ?></td>
      <td><?php echo $data['jumlah']; ?></td>
      <td><?php echo $data['status_pinjam']; ?></td>
    </tr>
  </tbody>
  <?php $i++; } ?>
</table>
