<?php
include'../koneksi.php';
  $proses = $_GET['prabowo'];
  if($proses=='tambah'){
    $kd_pinjam = trim($_POST['kd_pinjam'] ?? '');
    $nama_peminjam = trim($_POST['nama_peminjam'] ?? '');
    $kelas_peminjam = trim($_POST['kelas_peminjam'] ?? '');
    $kd_buku = trim($_POST['kd_buku'] ?? '');
    $tgl_pinjam = trim($_POST['tgl_pinjam'] ?? '');
    $jumlah = filter_var($_POST['jmlh_pinjam'] ?? null, FILTER_VALIDATE_INT);
    if ($kd_pinjam === '' || $kd_buku === '' || $nama_peminjam === '' || $kelas_peminjam === '' || $jumlah === false || $jumlah < 1 || $tgl_pinjam === '') {
      echo "<script>alert('Nama, kelas, tanggal, dan jumlah pinjaman wajib diisi dengan benar.'); window.history.back();</script>";
      exit;
    }

    $koneksi->beginTransaction();
    try {
      $query = $koneksi->prepare("SELECT stok FROM buku WHERE kode_buku = :kode_buku FOR UPDATE");
      $query->execute(array(':kode_buku' => $kd_buku));
      $buku = $query->fetch(PDO::FETCH_ASSOC);
      if (!$buku) {
        throw new RuntimeException('Buku tidak ditemukan.');
      }

      $hasil = (int) $buku['stok'] - $jumlah;
      if ($hasil < 0) {
        throw new RuntimeException('Stok buku tidak mencukupi.');
      }

      $tambah = $koneksi->prepare("INSERT INTO peminjaman(kode_pinjam, nis, nama_peminjam, kelas_peminjam, kode_buku, tgl_pinjam, jumlah, status) VALUES(?, NULL, ?, ?, ?, ?, ?, 'Diterima')");
      $tambah->execute(array($kd_pinjam, $nama_peminjam, $kelas_peminjam, $kd_buku, $tgl_pinjam, $jumlah));
      $insert = $tambah->rowCount();

      $ubah = $koneksi->prepare("UPDATE buku SET stok = :stok WHERE kode_buku = :kode_buku");
      $ubah->execute(array(':stok' => $hasil, ':kode_buku' => $kd_buku));
      $koneksi->commit();
    } catch (Throwable $error) {
      if ($koneksi->inTransaction()) {
        $koneksi->rollBack();
      }
      throw $error;
    }

    if($insert>0){
      echo "<script>
					alert('pinjam berhasil')
					window.location='buku.php'
					</script>";
		}else{
			echo "<script>
					alert('pinjam gagal')
					window.locatin='buku.php'\
					</script>";
		}
    }
  if($proses=='tambah2' || $proses=='tolak'){
    $metode = trim($_GET['app'] ?? '');
    $status = $proses == 'tambah2' ? 'Diterima' : 'Ditolak';
    if ($metode === '') {
      throw new InvalidArgumentException('Kode peminjaman tidak valid.');
    }

    $koneksi->beginTransaction();
    try {
      $pinjamanQuery = $koneksi->prepare("SELECT kode_buku, jumlah, status FROM peminjaman WHERE kode_pinjam = :kode_pinjam FOR UPDATE");
      $pinjamanQuery->execute(array(':kode_pinjam' => $metode));
      $pinjaman = $pinjamanQuery->fetch(PDO::FETCH_ASSOC);
      if (!$pinjaman || ($pinjaman['status'] !== null && $pinjaman['status'] !== '')) {
        throw new RuntimeException('Peminjaman tidak ditemukan atau sudah diproses.');
      }

      if ($status === 'Ditolak') {
        $bukuQuery = $koneksi->prepare("SELECT stok FROM buku WHERE kode_buku = :kode_buku FOR UPDATE");
        $bukuQuery->execute(array(':kode_buku' => $pinjaman['kode_buku']));
        $buku = $bukuQuery->fetch(PDO::FETCH_ASSOC);
        if (!$buku) {
          throw new RuntimeException('Buku untuk peminjaman ini tidak ditemukan.');
        }

        $stokBaru = (int) $buku['stok'] + (int) $pinjaman['jumlah'];
        $stokUpdate = $koneksi->prepare("UPDATE buku SET stok = :stok WHERE kode_buku = :kode_buku");
        $stokUpdate->execute(array(':stok' => $stokBaru, ':kode_buku' => $pinjaman['kode_buku']));
      }

      $edit = $koneksi->prepare("UPDATE peminjaman SET status = :status WHERE kode_pinjam = :kode_pinjam");
      $edit->execute(array(':status' => $status, ':kode_pinjam' => $metode));
      $koneksi->commit();
    } catch (Throwable $error) {
      if ($koneksi->inTransaction()) {
        $koneksi->rollBack();
      }
      throw $error;
    }

    $pesan = $status === 'Diterima' ? 'Peminjaman disetujui' : 'Peminjaman ditolak';
    echo "<script>alert('" . $pesan . "'); window.location='peminjaman.php';</script>";
  }
?>
