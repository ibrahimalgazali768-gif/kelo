<?php
 include "koneksi.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Mobil - Kelompok 1</title>
    <!-- Memakai CDN boostsrap -->
    <!-- Framewoork CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Kenapa ada CSS Karena bisa Mengcustom CSS Kita */
        .hero-section { background: #f8f9fa; padding: 60px 0; margin-bottom: 40px; }
        .card-mobil { transition: transform 0.3s; cursor: pointer; }
        .card-mobil:hover { transform: translateY(-10px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        footer { background: #343a40; color: white; padding: 20px 0; margin-top: 50px; }
        .hasil-kalkulasi { background: #e9ecef; border-radius: 8px; padding: 15px; margin-top: 20px; border-left: 5px solid #198754; }

        .navbar-brand-img {
    height: 40px; /* Atur tinggi tetap agar proporsional */
    width: auto;
    border-radius: 8px; /* Sudut melengkung halus */
    margin-right: 10px;
}
    </style>
</head>
<body>
    

  <!-- Navbar -->
 <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="logo.jpeg" alt="Logo" class="navbar-brand-img">
            <span>Aplikasi Mobil</span>
        </a>
    </div>

</nav>  
    <!-- Beranda -->
<section class="py-5 mb-4 bg-primary text-white rounded-3 shadow-sm">
        <div class="container text-center">
            <h2 class="display-5 fw-bold">Selamat Datang Di Aplikasi Mobil Kami</h2>
            <p class="fs-5">Kami Menjual Mobil Berkualitas Dengan Harga Termurah & Proses Cepat</p>
            <hr class="my-4 border-white">
        </div>
    </section>

    <!-- Tampil data -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4 mb-5">
                <div class="card shadow-sm border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Simulasi Kredit</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="formKredit">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Harga Mobil (Rp)</label>
                                <input type="number" id="input_harga" name="harga_mobil" class="form-control" 
                                       value="<?php echo isset($_POST['harga_mobil']) ? $_POST['harga_mobil'] : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">DP (%)</label>
                                <input type="number" name="dp_persen" class="form-control" value="20" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tenor (Tahun)</label>
                                <select name="tenor" class="form-select">
                                    <option value="1">1 Tahun</option>
                                    <option value="3">3 Tahun</option>
                                    <option value="5" selected>5 Tahun</option>
                                </select>
                            </div>
                            <button type="submit" name="hitung" class="btn btn-success w-100">Hitung Angsuran</button>
                        </form>

                        <!-- memeriksa banyak variabel sekaligus -->
                        <?php
                        if (isset($_POST['hitung'])) {
                            $harga = $_POST['harga_mobil'];
                            $dp_persen = $_POST['dp_persen'];
                            $tenor = $_POST['tenor'];
                            // cara hitung di db
                            $nominal_dp = ($dp_persen / 100) * $harga;
                            $angsuran = ($harga - $nominal_dp) / ($tenor * 12);
                            
                            echo "<div class='hasil-kalkulasi'>";
                            echo "<h6>Estimasi Angsuran:</h6>";
                            echo "<h4>Rp " . number_format($angsuran, 0, ',', '.') . " <small>/bln</small></h4>";
                            echo "<hr><button class='btn btn-warning w-100 fw-bold' onclick='beliSekarang($harga)'>BELI SEKARANG</button>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>

          <div class="col-md-8">
    <div class="row">
        <?php
        $query = mysqli_query($con, "SELECT * FROM mobil");
        while($data = mysqli_fetch_array($query)){
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 card-mobil shadow-sm border-0">
                <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=800" 
                     class="card-img-top" 
                     alt="Porsche" 
                     style="height: 160px; object-fit: cover;">
                
                <div class="card-body p-3 text-center">
                    <h6 class="card-title fw-bold text-truncate"><?php echo $data['nama_mobil']; ?></h6>
                    <p class="text-danger fw-bold small mb-3">Rp. <?php echo number_format($data['harga'], 0, ',', '.'); ?></p>
                    <button onclick="pilihMobil('<?php echo $data['nama_mobil']; ?>', <?php echo $data['harga']; ?>)" 
                            class="btn btn-sm btn-primary w-100 shadow-sm">Pilih Mobil</button>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

    <div class="modal fade" id="modalBeli" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-warning text-dark">
            <h5 class="modal-title">Konfirmasi Pembelian</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <p>Anda akan melakukan pemesanan untuk unit dengan harga:</p>
            <h3 id="display_harga_beli" class="text-success"></h3>
            <p class="text-muted small">Tim admin Kelompok 1 akan segera menghubungi Anda.</p>
          </div>
          <div class="modal-footer">
           <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    <button type="button" class="btn btn-primary" onclick="prosesFinal()">Konfirmasi Bayar DP</button>

        </div> 
        </div>
        </div>
      </div>
    </div>

    <!-- Nama Kelompok -->
    <section class = "id  fade-in"> 
        <div class="Kelompok-id">
            <h2 class = "text-center">Nama Kelompok</h2>
        </div>

        <div class="id-kelompok">
            <nav>
                <ul>
                    <li>Gedong</li>
                    <li>Captain Esa</li>
                    <li>BaimWong</li>
                </ul>
            </nav>
        </div>
    </section>

    <style>
        /* Section utama */
.id {
    padding: 50px 20px;
    background: linear-gradient(135deg, #0077b6, #00b4d8);
    color: white;
    text-align: center;
}

/* Container judul */
.Kelompok-id h2 {
    font-size: 28px;
    margin-bottom: 30px;
    letter-spacing: 1px;
}

/* Container list */
.id-kelompok {
    display: flex;
    justify-content: center;
}

/* Hilangkan style default ul */
.id-kelompok ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

/* Style tiap nama */
.id-kelompok li {
    background: white;
    color: #023e8a;
    margin: 10px 0;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: bold;
    width: 200px;
    transition: 0.3s ease;
    cursor: pointer;
}

/* Efek hover */
.id-kelompok li:hover {
    background: #90e0ef;
    transform: scale(1.05);
}

/* Responsive */
@media (max-width: 600px) {
    .id-kelompok li {
        width: 100%;
    }
}

/* ===== ANIMASI GLOBAL ===== */
.fade-in {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease;
}

.fade-in.show {
    opacity: 1;
    transform: translateY(0);
}

/* Hero animation */
.hero-section,
.py-5 {
    animation: fadeHero 1.2s ease-in-out;
}

@keyframes fadeHero {
    from { opacity: 0; transform: translateY(-40px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Card mobil animation */
.card-mobil {
    opacity: 0;
    transform: translateY(40px);
    transition: all 0.6s ease;
}

.card-mobil.show {
    opacity: 1;
    transform: translateY(0);
}

/* Tombol glow effect */
.btn-success, .btn-primary {
    transition: 0.3s ease;
}

.btn-success:hover, .btn-primary:hover {
    box-shadow: 0 0 15px rgba(25,135,84,0.6);
    transform: scale(1.05);
}

/* Section Kelompok animation */
.id {
    opacity: 0;
    transform: translateY(50px);
    transition: all 1s ease;
}

.id.show {
    opacity: 1;
    transform: translateY(0);
}
    </style>

    <footer class="text-center bg-dark text-white py-4">
    <div class="container">
        <p class="mb-0">&copy; 2026 Aplikasi Mobil - Kelompok 1</p>
        <small class="text-muted">Sistem Simulasi Kredit Otomotif</small>
    </div>
   </footer>

    <script>
    // 1. Logika Memasukkan Data dari Card ke Form
    function pilihMobil(nama, harga) {
        document.getElementById('input_harga').value = harga;
        window.scrollTo({top: 0, behavior: 'smooth'});
        
        // Alert pemberitahuan
        console.log("Mobil dipilih: " + nama + " Harga: " + harga);
    }

    // 2. Logika Tombol Beli (Munculkan Modal)
    function beliSekarang(harga) {
        var modal = new bootstrap.Modal(document.getElementById('modalBeli'));
        document.getElementById('display_harga_beli').innerText = "Rp " + harga.toLocaleString('id-ID');
        modal.show();
    }

    // 3. Logika Final (Checkout)
    function prosesFinal() {
        alert("Terima Kasih! Pesanan Anda telah diterima. Silahkan cek email untuk instruksi pembayaran.");
        location.reload(); // Refresh halaman
    }

    function prosesFinal() {
    // 1. Ambil data harga dari elemen modal
    // Kita ambil angka saja dengan menghapus format mata uang
    const hargaTeks = document.getElementById('display_harga_beli').innerText;
    const hargaBersih = hargaTeks.replace(/[^0-9]/g, '');

    // 2. Tampilkan status loading pada tombol
    const btnKonfirmasi = document.querySelector('#modalBeli .btn-primary');
    const originalText = btnKonfirmasi.innerText;
    btnKonfirmasi.innerText = "Sedang Mengirim...";
    btnKonfirmasi.disabled = true;

    // 3. Kirim data ke file PHP menggunakan Fetch API
    fetch('proses_email.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'harga=' + hargaBersih
    })
    .then(response => response.text())
    .then(data => {
        alert("Pesan Berhasil Terkirim! Admin akan segera menghubungi Anda.");
        location.reload(); // Segarkan halaman setelah berhasil
    })
    .catch(error => {
        alert("Terjadi kesalahan saat mengirim data.");
        btnKonfirmasi.innerText = originalText;
        btnKonfirmasi.disabled = false;
    });
}

// Animasi muncul saat halaman load untuk card mobil
window.addEventListener("load", function() {
    const cards = document.querySelectorAll(".card-mobil");
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add("show");
        }, index * 200); // efek muncul satu per satu
    });
});

// Animasi saat scroll
window.addEventListener("scroll", function() {
    const fadeElements = document.querySelectorAll(".fade-in, .id");
    const triggerBottom = window.innerHeight * 0.85;

    fadeElements.forEach(el => {
        const boxTop = el.getBoundingClientRect().top;

        if (boxTop < triggerBottom) {
            el.classList.add("show");
        }
    });
});



    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>