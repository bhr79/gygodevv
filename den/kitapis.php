<?php
include 'session_check.php';

include 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
// Kullanıcı rolünü kontrol et
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editor') {
    header("Location: viewer.php"); // Ana sayfaya yönlendir
    exit;
}

// Veritabanı bağlantısı ve kitap işlemleri
function getBooks() {
    global $conn;
    $sql = "SELECT * FROM kitaplar";
    $result = $conn->query($sql);
    $books = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }
    return $books;
}

function addBook($kitap_adi, $yazar, $fiyat, $turu) {
    global $conn;
    $sql = "INSERT INTO kitaplar (kitap_adi, yazar, fiyat, turu) VALUES ('$kitap_adi', '$yazar', '$fiyat', '$turu')";
    return $conn->query($sql);
}

function deleteBook($id) {
    global $conn;
    $sql = "DELETE FROM kitaplar WHERE id=$id";
    return $conn->query($sql);
}

function updateBook($id, $kitap_adi, $yazar, $fiyat, $turu) {
    global $conn;
    $sql = "UPDATE kitaplar SET kitap_adi='$kitap_adi', yazar='$yazar', fiyat='$fiyat', turu='$turu' WHERE id=$id";
    return $conn->query($sql);
}

if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM kitaplar WHERE kitap_adi LIKE '%$search%' OR yazar LIKE '%$search%' OR turu LIKE '%$search%'";
    $result = $conn->query($sql);
    $books = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }
    displayBooks($books);
    exit;
}

if(isset($_POST['add'])) {
    $kitap_adi = $_POST['kitap_adi'];
    $yazar = $_POST['yazar'];
    $fiyat = $_POST['fiyat'];
    $turu = $_POST['turu'];
    if(addBook($kitap_adi, $yazar, $fiyat, $turu)) {
        echo "Kitap başarıyla eklendi.";
    } else {
        echo "Kitap eklenirken bir hata oluştu.";
    }
    exit;
}

if(isset($_POST['delete'])) {
    $id = $_POST['delete'];
    if(deleteBook($id)) {
        echo "Kitap başarıyla silindi.";
    } else {
        echo "Kitap silinirken bir hata oluştu.";
    }
    exit;
}

if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $kitap_adi = $_POST['kitap_adi'];
    $yazar = $_POST['yazar'];
    $fiyat = $_POST['fiyat'];
    $turu = $_POST['turu'];
    if(updateBook($id, $kitap_adi, $yazar, $fiyat, $turu)) {
        echo "Kitap başarıyla güncellendi.";
    } else {
        echo "Kitap güncellenirken bir hata oluştu.";
    }
    exit;
}

if (isset($_GET['fetchBooks'])) {
    $books = getBooks();
    displayBooks($books);
    exit;
}

function displayBooks($books) {
    foreach ($books as $book) {
        echo "<tr>";
        echo "<td>{$book['id']}</td>";
        echo "<td>{$book['kitap_adi']}</td>";
        echo "<td>{$book['yazar']}</td>";
        echo "<td>{$book['fiyat']}</td>";
        echo "<td>{$book['turu']}</td>";
        echo "<td><button onclick=\"deleteBook({$book['id']})\">Sil</button>";
        echo "<button onclick=\"openUpdateForm({$book['id']}, '{$book['kitap_adi']}', '{$book['yazar']}', '{$book['fiyat']}', '{$book['turu']}')\">Güncelle</button></td>";
        echo "</tr>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Kitap İşlemleri</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
      table {
    width: 100%;
    border-collapse: collapse;
    background-color: #333; /* Dark grey background for the table */
}

th, td {
    border: 1px solid #000; /* Black border for the table cells */
    padding: 8px;
    text-align: left;
    color: #fff; /* White text color for table cells */
}

th {
    background-color: #ffc107; /* Yellow background for the headers */
    color: #000; /* Black text color for the headers */
}

tr:nth-child(even) {
    background-color: #444; /* Slightly darker grey for even rows */
}

tr:hover {
    background-color: #555; /* Darker grey highlight for hovered rows */
}

#book-list button {
    background-color: #ffc107; /* Yellow background for buttons */
    border: none;
    color: black; /* Black text color for buttons */
    padding: 5px 10px;
    cursor: pointer;
}

#book-list button:hover {
    background-color: #ffca28; /* Darker yellow on hover */
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
        <h2>Kitap İşlemleri</h2>

        <!-- Kitap Arama Formu -->
        <form id="search-form">
            <input type="text" id="search" name="search" placeholder="Kitap Adı, Yazar veya Tür ile arama yap">
            <button type="submit">Ara</button>
        </form>

        <!-- Kitap Ekleme Formu -->
        <form id="add-form">
            <input type="text" id="kitap_adi" name="kitap_adi" placeholder="Kitap Adı" required>
            <input type="text" id="yazar" name="yazar" placeholder="Yazar" required>
            <input type="number" id="fiyat" name="fiyat" placeholder="Fiyat" required>
            <input type="text" id="turu" name="turu" placeholder="Türü" required>
            <button type="submit" name="add">Kitap Ekle</button>
        </form>

        <!-- Kitap Listesi -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kitap Adı</th>
                    <th>Yazar</th>
                    <th>Fiyat</th>
                    <th>Türü</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody id="book-list">
            </tbody>
        </table>

        <!-- Kitap Güncelleme Formu -->
        <div id="update-form" style="display: none;">
            <h3>Kitap Bilgisini Güncelle</h3>
            <form id="update-book-form">
                <input type="hidden" id="update-id" name="id">
                <input type="text" id="update-kitap_adi" name="kitap_adi" placeholder="Yeni Kitap Adı" required>
                <input type="text" id="update-yazar" name="yazar" placeholder="Yeni Yazar" required>
                <input type="number" id="update-fiyat" name="fiyat" placeholder="Yeni Fiyat" required>
                <input type="text" id="update-turu" name="turu" placeholder="Yeni Türü" required>
                <button type="submit" name="update">Güncelle</button>
                <button type="button" id="cancel-update">İptal</button>
            </form>
        </div>

        <script>
        $(document).ready(function(){
            fetchBooks();
        });

        $('#search-form').submit(function(e) {
            e.preventDefault();
            var search = $('#search').val();
            $.ajax({
                url: 'kitapis.php?search=' + search,
                type: 'GET',
                success: function(response) {
                    $('#book-list').html(response);
                }
            });
        });

        $('#add-form').submit(function(e) {
            e.preventDefault();
            var kitap_adi = $('#kitap_adi').val();
            var yazar = $('#yazar').val();
            var fiyat = $('#fiyat').val();
            var turu = $('#turu').val();
            $.ajax({
                url: 'kitapis.php',
                type: 'POST',
                data: {
                    add: true,
                    kitap_adi: kitap_adi,
                    yazar: yazar,
                    fiyat: fiyat,
                    turu: turu
                },
                success: function(response) {
                    alert(response);
                    fetchBooks();
                }
            });
        });

        function deleteBook(id) {
            if (confirm('Kitabı silmek istediğinizden emin misiniz?')) {
                $.ajax({
                    url: 'kitapis.php',
                    type: 'POST',
                    data: { delete: id },
                    success: function(response) {
                        alert(response);
                        fetchBooks();
                    }
                });
            }
        }

        function openUpdateForm(id, kitap_adi, yazar, fiyat, turu) {
            $('#update-id').val(id);
            $('#update-kitap_adi').val(kitap_adi);
            $('#update-yazar').val(yazar);
            $('#update-fiyat').val(fiyat);
            $('#update-turu').val(turu);
            $('#update-form').show();
        }

        $('#update-book-form').submit(function(e) {
            e.preventDefault();
            var id = $('#update-id').val();
            var kitap_adi = $('#update-kitap_adi').val();
            var yazar = $('#update-yazar').val();
            var fiyat = $('#update-fiyat').val();
            var turu = $('#update-turu').val();
            $.ajax({
                url: 'kitapis.php',
                type: 'POST',
                data: {
                    update: true,
                    id: id,
                    kitap_adi: kitap_adi,
                    yazar: yazar,
                    fiyat: fiyat,
                    turu: turu
                },
                success: function(response) {
                    alert(response);
                    fetchBooks();
                    $('#update-form').hide();
                }
            });
        });

        $('#cancel-update').click(function() {
            $('#update-form').hide();
        });

        function fetchBooks() {
            $.ajax({
                url: 'kitapis.php?fetchBooks=true',
                type: 'GET',
                success: function(response) {
                    $('#book-list').html(response);
                }
            });
        }
        </script>
    </div>
    <div class="footer">
        &copy; 2024 Baharın Kitapçısı. Tüm hakları saklıdır.
    </div>
</body>
</html>
