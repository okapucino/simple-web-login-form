<?php
require_once 'connection.php';
$view_detail = isset($_GET['id']) ? (int)$_GET['id'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa Terdaftar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <div class="data-card">
            
            <?php if ($view_detail > 0): ?>
                <!-- HALAMAN DETAIL MAHASISWA -->
                <?php
                $stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE id = ?");
                $stmt->bind_param("i", $view_detail);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_assoc();
                ?>
                <div class="login-header" style="text-align: left;">
                    <a href="data.php" style="text-decoration:none; color:#3b82f6;">&larr; Kembali ke Tabel Data</a>
                    <h2 style="margin-top:15px;">Detail Mahasiswa</h2>
                </div>

                <?php if($data): ?>
                <div class="detail-grid">
                    <div>
                        <img src="uploads/<?= htmlspecialchars($data['foto_profil']) ?>" alt="Foto" style="width:100%; max-width:250px; border-radius: 12px; border: 3px solid #e5e7eb;">
                    </div>
                    <div class="detail-info">
                        <p><strong>Nama Lengkap</strong>: <?= htmlspecialchars($data['nama']) ?></p>
                        <p><strong>NIM</strong>: <?= htmlspecialchars($data['nim']) ?></p>
                        <p><strong>Email</strong>: <?= htmlspecialchars($data['email']) ?></p>
                        <p><strong>Program Studi</strong>: <?= htmlspecialchars($data['prodi']) ?></p>
                        <p><strong>Jenis Kelamin</strong>: <?= htmlspecialchars($data['gender']) ?></p>
                        <p><strong>Tanggal Lahir</strong>: <?= date('d M Y', strtotime($data['tgl_lahir'])) ?></p>
                        <p><strong>Status Mhs</strong>: <?= htmlspecialchars($data['jenis_mahasiswa']) ?></p>
                        <p><strong>UKT</strong>: <span style="color:#2563eb; font-weight:bold;"><?= htmlspecialchars($data['ukt']) ?></span></p>
                        <p><strong>Alamat</strong>: <?= nl2br(htmlspecialchars($data['alamat'])) ?></p>
                    </div>
                </div>
                <?php else: ?>
                    <p style="color:red; text-align:center;">Data tidak ditemukan!</p>
                <?php endif; ?>

            <?php else: ?>
                <!-- HALAMAN LIST MAHASISWA -->
                <div class="login-header" style="text-align: left; display:flex; justify-content: space-between; align-items:center;">
                    <div>
                        <h2>Data Mahasiswa</h2>
                        <p>Daftar seluruh mahasiswa yang telah diregistrasi.</p>
                    </div>
                    <a href="register.php" class="submit-btn" style="width: auto; text-decoration: none;">+ Tambah Data</a>
                </div>

                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>NIM</th>
                                <th>Nama Lengkap</th>
                                <th>Program Studi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM mahasiswa ORDER BY id DESC";
                            $result = mysqli_query($conn, $query);
                            $no = 1;

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td><img src='uploads/".$row['foto_profil']."' style='width:40px; height:40px; border-radius:50%; object-fit:cover;'></td>";
                                    echo "<td>" . htmlspecialchars($row['nim']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['prodi']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['jenis_mahasiswa']) . "</td>";
                                    echo "<td><a href='data.php?id=" . $row['id'] . "' class='btn-detail'>Detail</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' style='text-align:center;'>Belum ada data mahasiswa</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>
</body>
</html>