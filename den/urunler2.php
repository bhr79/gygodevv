<?php
include 'session_check.php';

include 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Kitapları veritabanından al
function getBooks($limit = 4, $search = '') {
    global $conn;
    $sql = "SELECT * FROM kitaplar";
    if ($search) {
        $sql .= " WHERE kitap_adi LIKE '%$search%' OR yazar LIKE '%$search%' OR turu LIKE '%$search%'";
    }
    $sql .= " LIMIT $limit";
    $result = $conn->query($sql);
    $books = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }
    return $books;
}
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$books = getBooks(4, $search);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ürünler</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .book {
            border: 1px solid #333; /* Siyah sınır */
            padding: 10px;
            margin: 10px;
            border-radius: 5px;
            background-color: #444; /* Koyu gri arka plan */
            color: #fff; /* Beyaz yazı rengi */
            text-align: left; /* Yazıları sola yasla */
        }

        .book h3 {
            margin: 0;
            padding: 0;
            color: #ffc107; /* Sarı başlık */
            font-size: 20px; /* Büyütülmüş yazı boyutu */
        }

        .book p {
            margin: 5px 0;
            font-size: 16px; /* Büyütülmüş yazı boyutu */
        }

        .header, .footer {
            background-color: #333; /* Siyah arka plan */
            color: #ffc107; /* Sarı yazı rengi */
            padding: 10px;
            text-align: center;
        }
/*
        .navbar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            background-color: #333; /* Siyah arka plan */
            overflow: hidden;
        }

        .navbar li {
            float: left;
        }

        .navbar li a {
            display: block;
            color: #ffc107; /* Sarı yazı rengi */
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar li a:hover {
            background-color: #444; /* Koyu gri hover efekti */
        }*/

        .content {
            padding: 20px;
        }

        form {
            margin-bottom: 20px;
        }
        */
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
        <h1>Ürünler</h1>
        <form method="get" action="urunler.php">
            <input type="text" name="search" placeholder="Kitap adı, yazar veya tür ara" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Ara</button>
        </form>
        <?php if (count($books) > 0): ?>
            <?php foreach ($books as $book): ?>
                <div class="book">
                    <h3><?php echo htmlspecialchars($book['kitap_adi']); ?></h3>
                    <p><strong>Yazar:</strong> <?php echo htmlspecialchars($book['yazar']); ?></p>
                    <p><strong>Fiyat:</strong> <?php echo htmlspecialchars($book['fiyat']); ?> TL</p>
                    <p><strong>Türü:</strong> <?php echo htmlspecialchars($book['turu']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Kitap bulunamadı.</p>
        <?php endif; ?>
    </div>
    <div class="footer">
        &copy; 2024 Baharın Kitapçısı. Tüm hakları saklıdır.
    </div>
</body>
</html>
