<?php
include 'session_check.php';
include 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Kullanıcı bilgilerini al
function getUserInfo($username) {
    global $conn;
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}
// Admin kullanıcı bilgilerini al
function getAdminInfo() {
    global $conn;
    $sql = "SELECT * FROM users WHERE username = 'Bahar'";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

// Yorumları al
function getComments() {
    global $conn;
    $sql = "SELECT * FROM comments";
    $result = $conn->query($sql);
    $comments = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
    }
    return $comments;
}

// Yorum ekle
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    $username = $_SESSION['username'];
    // Burada kullanıcı girdisini doğrudan SQL sorgusuna ekliyoruz
    $comment = $_POST['comment'];
    $sql = "INSERT INTO comments (username, comment) VALUES ('$username', '$comment')";
    $conn->query($sql);
}

$user = getUserInfo($_SESSION['username']);
$admin = getAdminInfo();
$comments = getComments();
?>
<!DOCTYPE html>
<html>
<head>
    <title>İletişim</title>
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
        <h1>İletişim</h1>
        <p>Admin kullanıcımız olan yetkili Bahar kullanıcısına yorum ve şikayetlerinizi yazabilrisiniz.</p>
        <h2>Admin Kullanıcı Bilgileri</h2>
        <?php if ($admin): ?>
            <p><strong>Kullanıcı Adı:</strong> <?php echo htmlspecialchars($admin['username'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Rol:</strong> <?php echo htmlspecialchars($admin['role'], ENT_QUOTES, 'UTF-8'); ?></p>
        <?php else: ?>
            <p>Admin kullanıcı bulunamadı.</p>
        <?php endif; ?>s
        <h1>ADRESİMİZ</h1>
        <p>Sahaf Caddesi 22. Sokak Roman Binası 1. Kat Kaş/Antalya</p>
        <h2>Kullanıcı Bilgileri</h2>
        <!---------------------------------------Güvensiz--------------------------------------->
        
        <p><strong>Kullanıcı Adı:</strong> <?php echo $user['username']; ?></p>
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        <p><strong>Doğum Tarihi:</strong> <?php echo $user['do_tar']; ?></p>
        <p><strong>Rol:</strong> <?php echo $user['role']; ?></p>

 
        <!--Güvenli Hale gelmiş XSS-->
<!--
        <p><strong>Kullanıcı Adı:</strong> <?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Doğum Tarihi:</strong> <?php echo htmlspecialchars($user['do_tar'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Rol:</strong> <?php echo htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8'); ?></p>
        
        -->


        <h2>Yorum ve Şikayet</h2>
        <form method="post" >
            <textarea name="comment" rows="4" cols="50" placeholder="Yorumunuzu yazın"></textarea><br>
            <button type="submit">Gönder</button>
        </form>

        <h2>Yorumlar</h2>
        <?php if (count($comments) > 0): ?>
            <!-----------------Güvensiz------------------->
            
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p><strong><?php echo $comment['username']; ?>:</strong> <?php echo $comment['comment']; ?></p>
                </div>
            <?php endforeach; ?>
           

                <!---------Güvenli----------->
            <!--yorum satırını ekrana yazdırırken alınan güvenlik-->
            <!--
            <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                    <p><strong><?php echo htmlspecialchars($comment['username'], ENT_QUOTES, 'UTF-8'); ?>:</strong> <?php echo htmlspecialchars($comment['comment'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                <?php endforeach; ?>
            -->
            
        <?php else: ?>
            <p>Henüz yorum yapılmamış.</p>
        <?php endif; ?>
    </div>
    <div class="footer">
        &copy; 2024 Baharın Kitapçısı. Tüm hakları saklıdır.
    </div>
</body>
</html>
