<?php
// Inisialisasi variabel dan array error
$nama = $nim = $email = $tgl_lahir = $gender = $alamat = $prodi = "";
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
    // Validasi Nama
    if (empty($_POST["nama"])) {
        $errors['nama'] = "Nama tidak boleh kosong.";
    } else {
        $nama = test_input($_POST["nama"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $nama)) {
            $errors['nama'] = "Hanya huruf dan spasi yang diizinkan.";
        }
    }

    // Validasi NIM
    if (empty($_POST["nim"])) {
        $errors['nim'] = "NIM tidak boleh kosong.";
    } else {
        $nim = test_input($_POST["nim"]);
        if (!preg_match("/^[0-9]*$/", $nim)) {
            $errors['nim'] = "NIM hanya boleh berisi angka.";
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

    // Validasi Tanggal Lahir
    if (empty($_POST["tgl_lahir"])) {
        $errors['tgl_lahir'] = "Tanggal lahir wajib diisi.";
    } else {
        $tgl_lahir = test_input($_POST["tgl_lahir"]);
    }

    // Validasi Gender
    if (empty($_POST["gender"])) {
        $errors['gender'] = "Pilih salah satu gender.";
    } else {
        $gender = test_input($_POST["gender"]);
    }

    // Validasi Alamat
    if (empty($_POST["alamat"])) {
        $errors['alamat'] = "Alamat tidak boleh kosong.";
    } else {
        $alamat = test_input($_POST["alamat"]);
    }

    // Validasi Program Studi
    if (empty($_POST["prodi"])) {
        $errors['prodi'] = "Program Studi wajib dipilih.";
    } else {
        $prodi = test_input($_POST["prodi"]);
    }

    // Validasi Password
    if (empty($_POST["password"])) {
        $errors['password'] = "Password tidak boleh kosong.";
    } elseif (strlen($_POST["password"]) < 6) {
        $errors['password'] = "Password minimal 6 karakter.";
    }

    // Validasi Konfirmasi Password
    if (empty($_POST["confirm_password"])) {
        $errors['confirm_password'] = "Konfirmasi password tidak boleh kosong.";
    } else {
        if ($_POST["password"] !== $_POST["confirm_password"]) {
            $errors['confirm_password'] = "Password dan Konfirmasi Password tidak cocok.";
        }
    }

    // Validasi Upload Foto Profil
    if (!isset($_FILES['foto_profil']) || $_FILES['foto_profil']['error'] == UPLOAD_ERR_NO_FILE) {
        $errors['foto_profil'] = "Foto profil wajib diunggah.";
    } else {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $file_type = $_FILES['foto_profil']['type'];
        $file_size = $_FILES['foto_profil']['size'];
        
        if (!in_array($file_type, $allowed_types)) {
            $errors['foto_profil'] = "Hanya file JPG, JPEG, atau PNG yang diizinkan.";
        } elseif ($file_size > 2 * 1024 * 1024) { // Max 2MB
            $errors['foto_profil'] = "Ukuran file maksimal 2MB.";
        }
    }

    // Validasi Checkbox Agreement
    if (empty($_POST["agreement"])) {
        $errors['agreement'] = "Anda harus menyetujui syarat dan ketentuan.";
    }

    if (empty($errors)) {
        // Buat folder uploads jika belum ada
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }
        
        $target_dir = "uploads/";
        // Buat nama file dengan uniqid untuk menghindari konflik nama file
        $file_extension = pathinfo($_FILES["foto_profil"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $target_file)) {
            $uploaded_file_path = $target_file;
            $success = true;
        } else {
            $errors['foto_profil'] = "Gagal mengunggah foto profil.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi Mahasiswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="login-container">
        <?php if ($success): ?>
            <div class="login-card success-card">
                <div class="login-header">
                    <h2 style="color: #10b981;">Registrasi Berhasil!</h2>
                    <p>Berikut adalah data yang telah Anda inputkan:</p>
                </div>
                
                <div class="result-data">
                    <div class="profile-preview">
                        <img src="<?= $uploaded_file_path ?>" alt="Foto Profil" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #4f46e5; margin-bottom: 20px;">
                    </div>
                    <table class="result-table">
                        <tr><td><strong>Nama</strong></td><td>: <?= $nama ?></td></tr>
                        <tr><td><strong>NIM</strong></td><td>: <?= $nim ?></td></tr>
                        <tr><td><strong>Email</strong></td><td>: <?= $email ?></td></tr>
                        <tr><td><strong>Tanggal Lahir</strong></td><td>: <?= date('d F Y', strtotime($tgl_lahir)) ?></td></tr>
                        <tr><td><strong>Gender</strong></td><td>: <?= $gender == 'L' ? 'Laki-laki' : 'Perempuan' ?></td></tr>
                        <tr><td><strong>Program Studi</strong></td><td>: <?= $prodi ?></td></tr>
                        <tr><td><strong>Alamat</strong></td><td>: <?= nl2br($alamat) ?></td></tr>
                    </table>
                </div>
                
                <div class="login-footer">
                    <a href="register.php" class="submit-btn" style="display:inline-block; text-decoration:none; margin-top:20px; text-align:center;">Daftar Lagi</a>
                </div>
            </div>

        <?php else: ?>
            <div class="login-card register-card">
                <div class="login-header">
                    <h2>Form Registrasi</h2>
                    <p>Silakan lengkapi data diri Anda</p>
                    <?php if(!empty($errors)): ?>
                        <div class="general-error">Terdapat input yang tidak valid. Silakan periksa kembali.</div>
                    <?php endif; ?>
                </div>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="login-form" enctype="multipart/form-data">
                    
                    <div class="input-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" placeholder="Masukkan nama Anda" value="<?= $nama ?>">
                        <?php if(isset($errors['nama'])) echo "<span class='error-text'>".$errors['nama']."</span>"; ?>
                    </div>

                    <div class="input-group">
                        <label for="nim">NIM</label>
                        <input type="text" id="nim" name="nim" placeholder="Masukkan NIM Anda" value="<?= $nim ?>">
                        <?php if(isset($errors['nim'])) echo "<span class='error-text'>".$errors['nim']."</span>"; ?>
                    </div>

                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Masukkan email Anda" value="<?= $email ?>">
                        <?php if(isset($errors['email'])) echo "<span class='error-text'>".$errors['email']."</span>"; ?>
                    </div>

                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Masukkan password Anda">
                        <?php if(isset($errors['password'])) echo "<span class='error-text'>".$errors['password']."</span>"; ?>
                    </div>

                    <div class="input-group">
                        <label for="confirm_password">Konfirmasi Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi password Anda">
                        <?php if(isset($errors['confirm_password'])) echo "<span class='error-text'>".$errors['confirm_password']."</span>"; ?>
                    </div>

                    <div class="input-group">
                        <label for="tgl_lahir">Tanggal Lahir</label>
                        <input type="date" id="tgl_lahir" name="tgl_lahir" value="<?= $tgl_lahir ?>">
                        <?php if(isset($errors['tgl_lahir'])) echo "<span class='error-text'>".$errors['tgl_lahir']."</span>"; ?>
                    </div>

                    <div class="input-group">
                        <label>Gender</label>
                        <div class="radio-group">
                            <label><input type="radio" name="gender" value="L" <?= (isset($gender) && $gender=="L") ? "checked" : "" ?>> Laki-laki</label>
                            <label><input type="radio" name="gender" value="P" <?= (isset($gender) && $gender=="P") ? "checked" : "" ?>> Perempuan</label>
                        </div>
                        <?php if(isset($errors['gender'])) echo "<span class='error-text'>".$errors['gender']."</span>"; ?>
                    </div>

                    <div class="input-group">
                        <label for="prodi">Program Studi</label>
                        <select id="prodi" name="prodi">
                            <option value="">-- Pilih Program Studi --</option>
                            <option value="Teknik Informatika" <?= ($prodi == "Teknik Informatika") ? "selected" : "" ?>>Teknik Informatika</option>
                            <option value="Sistem Informasi" <?= ($prodi == "Sistem Informasi") ? "selected" : "" ?>>Sistem Informasi</option>
                            <option value="Ilmu Komputer" <?= ($prodi == "Ilmu Komputer") ? "selected" : "" ?>>Ilmu Komputer</option>
                        </select>
                        <?php if(isset($errors['prodi'])) echo "<span class='error-text'>".$errors['prodi']."</span>"; ?>
                    </div>

                    <div class="input-group">
                        <label for="alamat">Alamat Lengkap</label>
                        <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap Anda"><?= $alamat ?></textarea>
                        <?php if(isset($errors['alamat'])) echo "<span class='error-text'>".$errors['alamat']."</span>"; ?>
                    </div>

                    <div class="input-group">
                        <label for="foto_profil">Upload Foto Profil</label>
                        <input type="file" id="foto_profil" name="foto_profil" accept="image/png, image/jpeg, image/jpg">
                        <small style="color:#6b7280; font-size: 12px;">Format: JPG, PNG. Maks: 2MB.</small><br>
                        <?php if(isset($errors['foto_profil'])) echo "<span class='error-text'>".$errors['foto_profil']."</span>"; ?>
                    </div>

                    <div class="input-group checkbox-group">
                        <label>
                            <input type="checkbox" name="agreement" value="yes" <?= isset($_POST['agreement']) ? 'checked' : '' ?>>
                            Saya menyetujui seluruh syarat dan ketentuan yang berlaku.
                        </label>
                        <?php if(isset($errors['agreement'])) echo "<span class='error-text'>".$errors['agreement']."</span>"; ?>
                    </div>

                    <button type="submit" class="submit-btn">Daftar Sekarang</button>
                </form>

                <div class="login-footer">  
                    <p>Sudah punya akun? <a href="login.html">Login di sini</a></p>
                </div>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>