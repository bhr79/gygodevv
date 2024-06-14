<?php

include 'session_check.php';


//session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
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
        <h1>Kitapçımıza Hoşgeldiniz.</h1>
        <p>Merhaba, <?php echo $_SESSION['username']; ?>! Bahar'ın kitap sayfasına hoş geldiniz.</p>
    </div>
    <div class="footer">
        &copy; 2024 Baharın Kitapçısı. Tüm hakları saklıdır.
    </div>
</body>
</html>
