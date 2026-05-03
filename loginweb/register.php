<?php
require_once 'connection.php';

$create_table = "CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    nim VARCHAR(20),
    email VARCHAR(100),
    tgl_lahir DATE,
    gender VARCHAR(20),
    alamat TEXT,
    prodi VARCHAR(50),
    jenis_mahasiswa VARCHAR(20),
    ukt VARCHAR(50),
    foto_profil VARCHAR(255)
)";
mysqli_query($conn, $create_table);

// Inisialisasi variabel dan array error
$nama = $nim = $email = $tgl_lahir = $gender = $alamat = $prodi = $jenis_mahasiswa = $ukt = "";
$errors = [];
$success = false;
$uploaded_file_path = "";

// Fungsi untuk membersihkan input data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi Nama (preg_match)
    if (empty($_POST["nama"])) {
        $errors['nama'] = "Nama tidak boleh kosong.";
    } else {
        $nama = test_input($_POST["nama"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $nama)) {
            $errors['nama'] = "Hanya huruf dan spasi yang diizinkan.";
        }
    }

    // Validasi NIM (is_numeric)
    if (empty($_POST["nim"])) {
        $errors['nim'] = "NIM tidak boleh kosong.";
    } else {
        $nim = test_input($_POST["nim"]);
        if (!is_numeric($nim)) {
            $errors['nim'] = "NIM harus berupa angka (is_numeric).";
        }
    }

    // Validasi Email
    if (empty($_POST["email"])) {
        $errors['email'] = "Email tidak boleh kosong.";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Format email tidak valid.";
        }
    }

    if (empty($_POST["tgl_lahir"])) { $errors['tgl_lahir'] = "Tanggal lahir wajib diisi."; }
    else { $tgl_lahir = test_input($_POST["tgl_lahir"]); }

    if (empty($_POST["gender"])) { $errors['gender'] = "Gender wajib dipilih."; }
    else { $gender = test_input($_POST["gender"]); }

    if (empty($_POST["alamat"])) { $errors['alamat'] = "Alamat wajib diisi."; }
    else { $alamat = test_input($_POST["alamat"]); }

    if (empty($_POST["prodi"])) { $errors['prodi'] = "Program studi wajib dipilih."; }
    else { $prodi = test_input($_POST["prodi"]); }

    // Validasi Jenis Mahasiswa & UKT
    if (empty($_POST["jenis_mahasiswa"])) {
        $errors['jenis_mahasiswa'] = "Jenis mahasiswa wajib dipilih.";
    } else {
        $jenis_mahasiswa = test_input($_POST["jenis_mahasiswa"]);
        if ($jenis_mahasiswa == "Beasiswa") {
            $ukt = "Gratis";
        } else if ($jenis_mahasiswa == "Reguler") {
            if (empty($_POST["ukt"])) {
                $errors['ukt'] = "Jenis UKT wajib dipilih untuk mahasiswa reguler.";
            } else {
                $ukt = test_input($_POST["ukt"]);
            }
        }
    }

    // Validasi Upload Foto Profil
    if (!isset($_FILES['foto_profil']) || $_FILES['foto_profil']['error'] == UPLOAD_ERR_NO_FILE) {
        $errors['foto_profil'] = "Foto profil wajib diupload.";
    } else {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $file_type = $_FILES['foto_profil']['type'];
        $file_size = $_FILES['foto_profil']['size'];
        
        if (!in_array($file_type, $allowed_types)) {
            $errors['foto_profil'] = "Hanya file JPG atau PNG yang diperbolehkan.";
        } else if ($file_size > 2 * 1024 * 1024) { // 2MB Limit
            $errors['foto_profil'] = "Ukuran file maksimal 2MB.";
        } else {
            if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
            $file_ext = pathinfo($_FILES["foto_profil"]["name"], PATHINFO_EXTENSION);
            $file_name = time() . "_" . $nim . "." . $file_ext;
            $target_file = "uploads/" . $file_name;
        }
    }

    if (!isset($_POST['agreement'])) {
        $errors['agreement'] = "Anda harus menyetujui syarat & ketentuan.";
    }

    // JIKA TIDAK ADA ERROR -> INSERT KE DATABASE
    if (empty($errors)) {
        if (move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $target_file)) {
            
            $stmt = $conn->prepare("INSERT INTO mahasiswa (nama, nim, email, tgl_lahir, gender, alamat, prodi, jenis_mahasiswa, ukt, foto_profil) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", $nama, $nim, $email, $tgl_lahir, $gender, $alamat, $prodi, $jenis_mahasiswa, $ukt, $file_name);
            
            if ($stmt->execute()) {
                $success = true;
            } else {
                $errors['general'] = "Gagal menyimpan ke database: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors['foto_profil'] = "Gagal mengupload foto profil.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="login-container">
        <?php if ($success): ?>
            <div class="login-card success-card" style="text-align: center;">
                <h2 style="color: #10b981; margin-bottom: 20px;">Registrasi Berhasil!</h2>
                <p style="margin-bottom: 30px;">Data mahasiswa atas nama <strong><?= htmlspecialchars($nama) ?></strong> telah tersimpan di database.</p>
                <a href="data.php" class="submit-btn" style="text-decoration: none; display: inline-block;">Lihat Data Mahasiswa</a>
                <br><br>
                <a href="register.php" style="color: #6b7280; font-size:14px;">Kembali ke Form Registrasi</a>
            </div>
        <?php else: ?>
            <div class="login-card register-card">
                <div class="login-header">
                    <h2>Registrasi Mahasiswa Baru</h2>
                    <p>Silakan isi data diri Anda dengan lengkap</p>
                </div>

                <?php if(isset($errors['general'])) echo "<div class='general-error'>".$errors['general']."</div>"; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                    
                    <div class="input-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($nama) ?>">
                        <?php if(isset($errors['nama'])) echo "<span class='error-text'>".$errors['nama']."</span>"; ?>
                    </div>

                    <div class="input-group">
                        <label for="nim">NIM</label>
                        <input type="text" id="nim" name="nim" value="<?= htmlspecialchars($nim) ?>">
                        <?php if(isset($errors['nim'])) echo "<span class='error-text'>".$errors['nim']."</span>"; ?>
                    </div>

                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
                        <?php if(isset($errors['email'])) echo "<span class='error-text'>".$errors['email']."</span>"; ?>
                    </div>

                    <div class="input-group">
                        <label for="tgl_lahir">Tanggal Lahir</label>
                        <input type="date" id="tgl_lahir" name="tgl_lahir" value="<?= htmlspecialchars($tgl_lahir) ?>" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px;">
                        <?php if(isset($errors['tgl_lahir'])) echo "<span class='error-text'>".$errors['tgl_lahir']."</span>"; ?>
                    </div>

                    <div class="input-group">
                        <label>Jenis Kelamin</label>
                        <div style="display:flex; gap: 15px; margin-top:5px;">
                            <label><input type="radio" name="gender" value="Laki-laki" <?= ($gender == 'Laki-laki') ? 'checked' : '' ?>> Laki-laki</label>
                            <label><input type="radio" name="gender" value="Perempuan" <?= ($gender == 'Perempuan') ? 'checked' : '' ?>> Perempuan</label>
                        </div>
                        <?php if(isset($errors['gender'])) echo "<span class='error-text'>".$errors['gender']."</span>"; ?>
                    </div>

                    <div class="input-group">
                        <label for="prodi">Program Studi</label>
                        <select id="prodi" name="prodi" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px;">
                            <option value="">-- Pilih Program Studi --</option>
                            <option value="Teknik Informatika" <?= ($prodi == 'Teknik Informatika') ? 'selected' : '' ?>>Teknik Informatika</option>
                            <option value="Sistem Informasi" <?= ($prodi == 'Sistem Informasi') ? 'selected' : '' ?>>Sistem Informasi</option>
                            <option value="Bisnis Digital" <?= ($prodi == 'Bisnis Digital') ? 'selected' : '' ?>>Bisnis Digital</option>
                        </select>
                        <?php if(isset($errors['prodi'])) echo "<span class='error-text'>".$errors['prodi']."</span>"; ?>
                    </div>

                    <div class="input-group">
                        <label for="jenis_mahasiswa">Jenis Mahasiswa</label>
                        <select id="jenis_mahasiswa" name="jenis_mahasiswa" onchange="toggleUKT()" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px;">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Reguler" <?= ($jenis_mahasiswa == 'Reguler') ? 'selected' : '' ?>>Reguler</option>
                            <option value="Beasiswa" <?= ($jenis_mahasiswa == 'Beasiswa') ? 'selected' : '' ?>>Beasiswa</option>
                        </select>
                        <?php if(isset($errors['jenis_mahasiswa'])) echo "<span class='error-text'>".$errors['jenis_mahasiswa']."</span>"; ?>
                    </div>

                    <div class="input-group" id="div_ukt">
                        <label for="ukt">Jenis UKT</label>
                        <select id="ukt" name="ukt" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px;">
                            <option value="">-- Pilih Golongan UKT --</option>
                            <option value="Golongan 1" <?= ($ukt == 'Golongan 1') ? 'selected' : '' ?>>Golongan 1</option>
                            <option value="Golongan 2" <?= ($ukt == 'Golongan 2') ? 'selected' : '' ?>>Golongan 2</option>
                            <option value="Golongan 3" <?= ($ukt == 'Golongan 3') ? 'selected' : '' ?>>Golongan 3</option>
                        </select>
                        <?php if(isset($errors['ukt'])) echo "<span class='error-text'>".$errors['ukt']."</span>"; ?>
                    </div>

                    <div class="input-group">
                        <label for="alamat">Alamat Lengkap</label>
                        <textarea id="alamat" name="alamat" rows="3" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px;"><?= htmlspecialchars($alamat) ?></textarea>
                        <?php if(isset($errors['alamat'])) echo "<span class='error-text'>".$errors['alamat']."</span>"; ?>
                    </div>

                    <div class="input-group">
                        <label for="foto_profil">Upload Foto Profil</label>
                        <input type="file" id="foto_profil" name="foto_profil" accept="image/png, image/jpeg, image/jpg" style="padding-top: 10px;">
                        <small style="color:#6b7280; font-size: 12px;">Format: JPG, PNG. Maks: 2MB.</small><br>
                        <?php if(isset($errors['foto_profil'])) echo "<span class='error-text'>".$errors['foto_profil']."</span>"; ?>
                    </div>

                    <div class="input-group checkbox-group" style="margin-top: 15px;">
                        <label style="display:flex; align-items:center; gap: 8px;">
                            <input type="checkbox" name="agreement" value="yes" <?= isset($_POST['agreement']) ? 'checked' : '' ?>>
                            Saya menyetujui seluruh syarat dan ketentuan.
                        </label>
                        <?php if(isset($errors['agreement'])) echo "<span class='error-text'>".$errors['agreement']."</span>"; ?>
                    </div>

                    <button type="submit" class="submit-btn" style="margin-top: 20px;">Daftar Sekarang</button>
                </form>

                <div class="login-footer" style="margin-top: 20px; text-align: center;">  
                    <p>Cek data yang telah terdaftar? <a href="data.php">Lihat Data</a></p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    // Script untuk toggle UKT berdasarkan jenis mahasiswa
    <script>
        function toggleUKT() {
            var jenis = document.getElementById("jenis_mahasiswa").value;
            var divUkt = document.getElementById("div_ukt");
            if(jenis === "Beasiswa") {
                divUkt.style.display = "none";
            } else {
                divUkt.style.display = "block";
            }
        }
        window.onload = toggleUKT;
    </script>
</body>
</html>