<?php
require'koneksi.php';

	$user = trim($_POST['name'] ?? '');
	$pw = $_POST['pw'] ?? '';

	$row = $koneksi -> prepare('SELECT * FROM user WHERE id = :id AND password = :pw');
	$row -> execute(array(':id' => $user, ':pw' => $pw));
	$cek = $row -> fetch(PDO::FETCH_ASSOC);

	if ($cek && $cek['hak'] === 'admin'){
		session_start();
		$_SESSION["username"]=$cek;
		echo "<script>
			alert('Login Berhasil')</script>
			<script>window.location = 'admin/utama_admin.php'
			</script>";
	}else{
		$pesan = $cek && $cek['hak'] === 'anggota'
			? 'Akses anggota sudah dinonaktifkan'
			: 'Login Gagal';
		echo "<script>
			alert(" . json_encode($pesan) . ")</script>
			<script>window.location = 'index.php'
			</script>";
	}
?>
