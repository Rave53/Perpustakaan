<?php
include'../koneksi.php';
  $proses = $_GET['prabowo'];
  if($proses=='tambah'){
    $kd         = $_POST['kd_buku'];
    $judul      = $_POST['judul'];
    $kelasBuku  = isset($_POST['kelas_buku']) && $_POST['kelas_buku'] !== '' ? trim($_POST['kelas_buku']) : null;
    $pengarang  = $_POST['pengarang'];
    $kategori   = isset($_POST['kat']) && $_POST['kat'] !== '' ? (int) $_POST['kat'] : null;
    $stok       = $_POST['stok'];
    $tahun      = $_POST['tahun'];
    $status     = $_POST['status'];

    $tambah = $koneksi -> prepare("INSERT INTO buku (kode_buku, judul, kelas_buku, pengarang, id_kategori, stok, tahun, status) VALUES (?,?,?,?,?,?,?,?)");
    $tambah -> execute(array($kd, $judul, $kelasBuku, $pengarang, $kategori, $stok, $tahun, $status));
    $insert = $tambah -> rowCount();

    if($insert>0){
			echo "<script>
					alert('tambah berhasil')
					window.location='buku.php'
					</script>";
		}else{
			echo "<script>
					alert('tambah gagal')
					window.locatin='buku.php'\
					</script>";
		}
	}

  if($proses=='edit'){
    $kd         = $_POST['kd_buku'];
    $judul      = $_POST['judul'];
    $kelasBuku  = isset($_POST['kelas_buku']) && $_POST['kelas_buku'] !== '' ? trim($_POST['kelas_buku']) : null;
    $pengarang  = $_POST['pengarang'];
    $kategori   = isset($_POST['kat']) && $_POST['kat'] !== '' ? (int) $_POST['kat'] : null;
    $stok       = $_POST['stok'];
    $tahun      = $_POST['tahun'];
    $status     = $_POST['status'];

    $query = $koneksi -> prepare("UPDATE buku SET judul = :judul, kelas_buku = :kelas_buku, pengarang = :pengarang, id_kategori = :kategori, stok = :stok, tahun = :tahun, status = :status WHERE kode_buku = :kode_buku");
    $query -> execute(array(
      ':judul' => $judul,
      ':kelas_buku' => $kelasBuku,
      ':pengarang' => $pengarang,
      ':kategori' => $kategori,
      ':stok' => $stok,
      ':tahun' => $tahun,
      ':status' => $status,
      ':kode_buku' => $kd
    ));

    $update = $query -> rowCount();

		if($update > 0){
			echo
			'<script>
				alert("Edit Berhasil");
				window.location ="buku.php"
			</script>';

		}else{
			echo
			'<script>
				alert("Edit Gagal");
				window.location ="buku.php"
			</script>';

		}
  }

  if($proses=='hapus'){
    $metode = $_GET['app'];
    $hapus = $koneksi -> prepare("DELETE FROM buku WHERE kode_buku = :kode");
    $hapus -> bindParam(':kode', $metode);
    $hapus -> execute();
    $delete = $hapus -> fetch();

    $delete = $hapus -> rowCount();

    if($delete > 0) {
			echo
			'<script>
				alert("Hapus Berhasil");
				window.location = "buku.php";
			</script>';
		}else{
			echo
			'<script>
				alert("Hapus Gagal");
				window.location = "buku.php";
			</script>';
		}
  }

?>
