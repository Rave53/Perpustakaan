<?php
include'../koneksi.php';
  $proses = $_GET['prabowo'];
  if($proses=='tambah'){
    $kd_kembali = trim($_POST['kd_kembali'] ?? '');
    $kd_pinjam = trim($_POST['kd_pinjam'] ?? '');
    $status = trim($_POST['status_kembali'] ?? '');
    $tgl_kembali = trim($_POST['tgl_kembali'] ?? '');
    if ($kd_kembali === '' || $kd_pinjam === '' || $status !== 'Dikembalikan' || $tgl_kembali === '') {
      throw new InvalidArgumentException('Kode pinjam dan tanggal wajib diisi, dengan status Dikembalikan.');
    }

    $koneksi->beginTransaction();
    try {
      $pinjamanQuery = $koneksi->prepare("SELECT nis, nama_peminjam, kelas_peminjam, kode_buku, jumlah, status, status_pinjam FROM peminjaman WHERE kode_pinjam = :kode_pinjam FOR UPDATE");
      $pinjamanQuery->execute(array(':kode_pinjam' => $kd_pinjam));
      $pinjaman = $pinjamanQuery->fetch(PDO::FETCH_ASSOC);
      if (!$pinjaman) {
        throw new RuntimeException('Data peminjaman tidak ditemukan.');
      }
      if ($pinjaman['status'] !== 'Diterima' || $pinjaman['status_pinjam'] === 'Dikembalikan') {
        throw new RuntimeException('Peminjaman belum disetujui atau sudah dikembalikan.');
      }

      $returnCheck = $koneksi->prepare("SELECT kode_kembali FROM pengembalian WHERE kode_pinjam = :kode_pinjam AND status_pengembalian = 'Dikembalikan' LIMIT 1");
      $returnCheck->execute(array(':kode_pinjam' => $kd_pinjam));
      if ($returnCheck->fetchColumn()) {
        throw new RuntimeException('Buku untuk peminjaman ini sudah tercatat dikembalikan.');
      }

      $nis = $pinjaman['nis'];
      $nama_peminjam = $pinjaman['nama_peminjam'];
      $kelas_peminjam = $pinjaman['kelas_peminjam'];
      if (($nama_peminjam === null || $nama_peminjam === '') && $nis !== null && $nis !== '') {
        $anggotaQuery = $koneksi->prepare("SELECT nama_anggota FROM anggota WHERE nis = :nis");
        $anggotaQuery->execute(array(':nis' => $nis));
        $nama_peminjam = $anggotaQuery->fetchColumn() ?: null;
      }
      $kd_buku = $pinjaman['kode_buku'];
      $jumlah = (int) $pinjaman['jumlah'];

      $query = $koneksi->prepare("SELECT stok FROM buku WHERE kode_buku = :kode_buku FOR UPDATE");
      $query->execute(array(':kode_buku' => $kd_buku));
      $buku = $query->fetch(PDO::FETCH_ASSOC);
      if (!$buku) {
        throw new RuntimeException('Buku tidak ditemukan.');
      }

      $hasil = (int) $buku['stok'] + (int) $jumlah;
      $tambah = $koneksi->prepare("INSERT INTO pengembalian(kode_kembali, kode_pinjam, nis, nama_peminjam, kelas_peminjam, kode_buku, status_pengembalian, tgl_pengembalian) VALUES(?,?,?,?,?,?,?,?)");
      $tambah->execute(array($kd_kembali, $kd_pinjam, $nis, $nama_peminjam, $kelas_peminjam, $kd_buku, $status, $tgl_kembali));
      $insert = $tambah->rowCount();

      $ubah = $koneksi->prepare("UPDATE buku SET stok = :stok WHERE kode_buku = :kode_buku");
      $ubah->execute(array(':stok' => $hasil, ':kode_buku' => $kd_buku));

      $ubah2 = $koneksi->prepare("UPDATE peminjaman SET status_pinjam = :status WHERE kode_pinjam = :kode_pinjam");
      $ubah2->execute(array(':status' => $status, ':kode_pinjam' => $kd_pinjam));
      $koneksi->commit();
    } catch (Throwable $error) {
      if ($koneksi->inTransaction()) {
        $koneksi->rollBack();
      }
      throw $error;
    }

    if($insert>0){
      echo "<script>
  					alert('pengembalian berhasil')
  					window.location='pengembalian.php'
  					</script>";
    }else{
      echo "<script>
  					alert('pengembalian gagal')
  					window.location='pengembalian.php'
  					</script>";
    }

  }

?>
