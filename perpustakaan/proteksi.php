<?php
session_start();
if(!isset($_SESSION["username"])) {
	echo
	"<script>
			alert ('anda harus login dulu');
			window.location = '../index.php';
	</script>";
	exit;
}

$id = $_SESSION["username"]["id"] ?? '';
if ($id === '') {
	session_unset();
	session_destroy();
	echo
	"<script>
			alert ('Sesi tidak valid, silakan login kembali');
			window.location = '../index.php';
	</script>";
	exit;
}

require_once __DIR__ . '/koneksi.php';
$query = $koneksi->prepare('SELECT * FROM user WHERE id = :id');
$query->execute(array(':id' => $id));
$username = $query->fetch(PDO::FETCH_ASSOC);
if (!$username) {
	session_unset();
	session_destroy();
	echo
	"<script>
			alert ('Sesi tidak valid, silakan login kembali');
			window.location = '../index.php';
	</script>";
	exit;
}

$_SESSION["username"] = $username;
if ($username["hak"] !== 'admin') {
	session_unset();
	session_destroy();
	echo
	"<script>
			alert ('Akses anggota sudah dinonaktifkan');
			window.location = '../index.php';
	</script>";
	exit;
}
?>
