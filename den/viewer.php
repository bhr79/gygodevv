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
            <li><a href="viewer.php">Anasayfa</a></li>
            <li><a href="urunler.php">Kitaplar</a></li>            

            <li><a href="hakkimda.php">Hakkımda</a></li>
            <li><a href="iletisim.php">İletişim</a></li>
            <li><a href="profil.php">Profil</a></li>
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
