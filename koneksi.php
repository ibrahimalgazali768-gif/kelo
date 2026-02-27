<?php
$host = "localhost";
$user = "root"; 
$pass = "";
// Pastikan nama database benar (tadi ada typo "apkikasi", saya asumsikan "aplikasi")
$db_name = "apkikasi_mobil"; 

// Membuat koneksi
$con = new mysqli($host, $user, $pass, $db_name);

// MEMERIKSA KONEKSI
// Gunakan properti 'connect_error'. Jika ada isinya, berarti gagal.
if ($con->connect_error) {
    die("Koneksi gagal: " . $con->connect_error);
}

// Jika berhasil, variabel $con siap digunakan di file lain
?>