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
    <title>Hakkımda</title>
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
        <h1>Hakkımda</h1>
        <p>Bahar Kitapçı, kitapseverlere zengin bir okuma deneyimi sunmayı hedefleyen bir çevrimiçi kitap mağazasıdır. 2010 yılında kurulan firmamız, yıllar boyunca kaliteli hizmet ve geniş bir ürün yelpazesiyle müşterilerine güvenilir bir alışveriş ortamı sağlamıştır.</p>
        <h1>Misyonumuz</h1>
        <p>Misyonumuz, her yaştan ve her kesimden okuyucunun ihtiyaçlarına uygun kitapları en uygun fiyatlarla sunarak, onların okuma alışkanlıklarını desteklemek ve kitap okuma tutkusunu yaygınlaştırmaktır.</p>
        <h1>KİTAP ERİŞİMLERİMİZ</h1>
        <p>Kitaplarımızda satış sadece şubemizden yapılmaktadır. Ayrıca kitap kafe uygulaması da bulunmaktadır. Ödünç alma işlemleri vardır. Kitapçımıza erişebilmek için iletişim bölümümüzdeki adrese bakabilirisniz.</p>
    </div>

    <div class="footer">
        &copy; 2024 Baharın Kitapçısı. Tüm hakları saklıdır.
    </div>
</body>
</html>
