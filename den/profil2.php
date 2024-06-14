<?php
include 'session_check.php';


//session_start();

// Oturum açmış kullanıcıyı kontrol et
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
       /* Sayfa Genel Stili */
       body {
            background-color: #000; /* Siyah arka plan */
            color: #fff; /* Beyaz metin renkleri */
        }

        /* Header Stili */
        .header {
            background-color: #222; /* Koyu gri arka plan */
            color: #ffcc00; /* Sarı renk */
        }

        /* Navbar Stili */
        .navbar ul li a {
            color: #ffcc00; /* Sarı renk */
        }

        .navbar ul li a:hover {
            color: #fff; /* Beyaz renk hover durumunda */
        }

        /* İçerik Stili */
        .content {
            background-color: #111; /* Koyu gri arka plan */
            color: #fff; /* Beyaz metin renkleri */
        }

        /* Profil Bilgileri Stili */
        .profile-info {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #333; /* Koyu gri arka plan */
            color: #fff; /* Beyaz metin renkleri */
        }

        .profile-info p {
            margin-bottom: 10px;
        }

        /* Profil Bilgileri Başlık Stili */
        .profile-info h2 {
            color: #ffcc00; /* Sarı renk */
            border-bottom: 2px solid #ffcc00; /* Sarı alt çizgi */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        /* Profil Bilgileri Detay Stili */
        .profile-info p strong {
            color: #999; /* Gri renk */
        }
    </style>
</head>
<body>
    <div class="header">
        Baharın Kitapçısı
    </div>
    <div class="navbar">
    <ul>
            <li><a href="adedan.php">Anasayfa</a></li>
            <li><a href="urunler2.php">Kitaplar</a></li>
            <li><a href="kullaniciis.php">Kullanıcı İşlemleri</a></li>
            <li><a href="kitapis.php">Kitap İşlemleri</a></li>
            <li><a href="hakkimda2.php">Hakkımda</a></li>
            <li><a href="iletisim2.php">İletişim</a></li>
            <li><a href="profil2.php">Profil</a></li>
            <li><a href="cikis.php">Çıkış Yap</a></li>
        </ul>
    </div>
    <div class="content">
        <h1>Profil</h1>
        <div class="profile-info">
            <?php
            // Veritabanı bağlantısı
            include 'config.php';

            // Session'dan kullanıcı adını al
            $username = $_SESSION['username'];

            // Kullanıcı bilgilerini veritabanından al
            $query = "SELECT * FROM users WHERE username='$username'";
            $result = $conn->query($query);

            // Sonuçları kontrol et ve profil bilgilerini görüntüle
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<h2>Profil Bilgileri</h2>";
                echo "<p><strong>Kullanıcı Adı:</strong> " . $row['username'] . "</p>";
                echo "<p><strong>E-posta:</strong> " . $row['email'] . "</p>";
                echo "<p><strong>Doğum Tarihi:</strong> " . $row['do_tar'] . "</p>";
                echo "<p><strong>Rol:</strong> " . $row['role'] . "</p>";
            } else {
                echo "Profil bilgileri bulunamadı.";
            }

            // Veritabanı bağlantısını kapat
            $conn->close();
            ?>
        </div>
    </div>
    <div class="footer">
        &copy; 2024 Baharın Kitapçısı. Tüm hakları saklıdır.
    </div>
</body>
</html>

