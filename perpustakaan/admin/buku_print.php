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
      <th scope="col">Kode Buku</th>
      <th scope="col">Judul</th>
      <th scope="col">Kelas Buku</th>
      <th scope="col">Pengarang</th>
      <th scope="col">Kategori</th>
      <th scope="col">Stok</th>
      <th scope="col">Tahun</th>
      <th scope="col">Status Buku</th>
    </tr>
    </thead>
    <?php
    $i = 1;
    $asd = $koneksi -> prepare("SELECT * FROM buku ");
    $asd -> execute();
    while($data = $asd -> fetch()){
    $id = $data['id_kategori'];
    $kat = $koneksi -> prepare("SELECT * FROM kategori WHERE id_kategori = :kategori");
    $kat -> bindParam(':kategori', $id);
    $kat -> execute();
    $hasil = $kat -> fetch();
    ?>
    <tbody>
      <tr>
        <td><?php echo $i ?></td>
        <td><?php echo $data['kode_buku']; ?></td>
        <td><?php echo $data['judul']; ?></td>
        <td><?php echo $data['kelas_buku'] !== null && $data['kelas_buku'] !== '' ? htmlspecialchars((string) $data['kelas_buku'], ENT_QUOTES, 'UTF-8') : 'Belum diisi'; ?></td>
        <td><?php echo $data['pengarang']; ?></td>
        <td><?php echo $hasil ? $hasil['nama_kategori'] : ($data['klasifikasi'] !== null && $data['klasifikasi'] !== '' ? $data['klasifikasi'] : 'Belum diklasifikasikan'); ?></td>
        <td><?php echo $data['stok']; ?></td>
        <td><?php echo $data['tahun']; ?></td>
        <td><?php echo $data['status']; ?></td>
      </tr>
    </tbody>
    <?php $i++; } ?>
  </table>
