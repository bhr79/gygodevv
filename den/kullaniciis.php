<?php
include 'session_check.php';
include 'config.php';
include 'csrf_token.php'; 
// CSRF token doğrulaması
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($csrf_token)) {
        echo "Geçersiz CSRF token. İşlem iptal edildi.";
        exit;
    }
}




if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
// Kullanıcı rolünü kontrol et
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editor') {
    header("Location: viewer.php"); // Ana sayfaya yönlendir
    exit;
}
// Oturum kontrolü
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "Bu sayfaya erişim yetkiniz yok.";
    exit;
}

// Kullanıcıları getirme fonksiyonu
function getUsers($search = '') {
    global $conn;
    $users = array();
    if ($search) {
        $sql = "SELECT * FROM users WHERE username LIKE '%$search%' OR email LIKE '%$search%'";
    } else {
        $sql = "SELECT * FROM users";
    }
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    return $users;
}
// Admin ve Editor sayısını kontrol eden fonksiyon--------------------

function checkRoleLimit($role) {
    global $conn;
    if ($role === 'viewer') {
        return true; // Viewer rolü için sınırsız kayıt yapılabilir
    }
    $sql = "SELECT COUNT(*) as count FROM users WHERE role='$role'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'] < 2;
}
    


// Yeni kullanıcı ekleme fonksiyonu-------------------------
function addUser($username, $password, $email, $do_tar, $role) {
    global $conn;
    
    if (!checkRoleLimit($role)) {
        echo "En fazla 2 tane $role kullanıcısı ekleyebilirsiniz.";
        return false;
    }
        
    $sql = "INSERT INTO users (username, password, email, do_tar, role) VALUES ('$username', '$password', '$email', '$do_tar', '$role')";
    return $conn->query($sql);
}

// Kullanıcı güncelleme fonksiyonu-----------------
function updateUser($id, $username, $password, $email, $do_tar, $role) {
    global $conn;
    
    if ($row['role'] !== $role && !checkRoleLimit($role)) {
        echo "En fazla 2 tane $role kullanıcısı ekleyebilirsiniz.";
        return false;
    }
        
        
    $sql = "UPDATE users SET username='$username', password='$password', email='$email', do_tar='$do_tar', role='$role' WHERE id=$id";
    return $conn->query($sql);
}

// Kullanıcı silme fonksiyonu
function deleteUser($id) {
    global $conn;
    $sql = "DELETE FROM users WHERE id=$id";
    return $conn->query($sql);
}

// Kullanıcı ekleme işlemi
if (isset($_POST['add'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $do_tar = $_POST['do_tar'];
    $role = $_POST['role'];
    if (addUser($username, $password, $email, $do_tar, $role)) {
        echo "Kullanıcı başarıyla eklendi.";
    } else {
        echo "Kullanıcı eklenirken bir hata oluştu.";
    }
    exit;
}

// Kullanıcı güncelleme işlemi
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $do_tar = $_POST['do_tar'];
    $role = $_POST['role'];
    if (updateUser($id, $username, $password, $email, $do_tar, $role)) {
        echo "Kullanıcı başarıyla güncellendi.";
    } else {
        echo "Kullanıcı güncellenirken bir hata oluştu.";
    }
    exit;
}

// Kullanıcı silme işlemi
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    if (deleteUser($id)) {
        echo "Kullanıcı başarıyla silindi.";
    } else {
        echo "Kullanıcı silinirken bir hata oluştu.";
    }
    exit;
}

// Tüm kullanıcıları getirme işlemi
if (isset($_GET['fetchUsers'])) {
    $users = getUsers();
    displayUsers($users);
    exit;
}

// Kullanıcıları arama işlemi
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $users = getUsers($search);
    displayUsers($users);
    exit;
}

// Kullanıcıları ekrana yazdırma fonksiyonu
function displayUsers($users) {
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>{$user['id']}</td>";
        echo "<td>{$user['username']}</td>";
        echo "<td>{$user['password']}</td>";
        echo "<td>{$user['email']}</td>";
        echo "<td>{$user['do_tar']}</td>";
        echo "<td>{$user['role']}</td>";
        echo "<td><button onclick=\"deleteUser({$user['id']})\">Sil</button>";
        echo "<button onclick=\"openUpdateForm({$user['id']}, '{$user['username']}', '{$user['password']}', '{$user['email']}', '{$user['do_tar']}', '{$user['role']}')\">Güncelle</button></td>";
        echo "</tr>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
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
            <li><a href="urunler2.php">Ürünler</a></li>
            <li><a href="kullaniciis.php">Kullanıcı İşlemleri</a></li>
            <li><a href="kitapis.php">Kitap İşlemleri</a></li>
            <li><a href="hakkimda2.php">Hakkımda</a></li>
            <li><a href="iletisim2.php">İletişim</a></li>
            <li><a href="profil2.php">Profil</a></li>
            <li><a href="cikis.php">Çıkış Yap</a></li>
        </ul>
    </div>
    <div class="content">

        <!-- Kullanıcı İşlemleri İçeriği -->
        <h2>Kullanıcı İşlemleri</h2>

        <!-- Kullanıcı Arama Formu -->
        <form id="search-form">
            <input type="text" id="search" name="search" placeholder="Kullanıcı Adı veya E-posta ile arama yap">
            <button type="submit">Ara</button>
        </form>

        <!-- Kullanıcı Ekleme Formu -->
        <form id="add-form">
        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="text" id="username" name="username" placeholder="Kullanıcı Adı" required>
            <input type="password" id="password" name="password" placeholder="Şifre" required>
            <input type="email" id="email" name="email" placeholder="E-posta" required>
            <input type="date" id="do_tar" name="do_tar" placeholder="Doğum Tarihi" required>
            <select id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="editor">Editor</option>
                <option value="viewer">Viewer</option>
            </select>
            <button type="submit" name="add">Kullanıcı Ekle</button>
        </form>
        <!-----------------------------Güvensiz CSRF kodu ekleme--------------------->
        <!-- CSRF Saldırısı Formu -->
         <!--
<form id="csrf-attack-form" action="http://localhost:73/kullaniciis.php" method="POST" style="display:none;">
    <input type="hidden" name="add" value="true">
    <input type="hidden" name="username" value="saldırgan">
    <input type="hidden" name="password" value="123">
    <input type="hidden" name="email" value="saldırgan_kullanıcı@example.com">
    <input type="hidden" name="do_tar" value="2000-01-01">
    <input type="hidden" name="role" value="admin">
</form>
-->
<!-- Saldırıyı Tetikleyen Buton -->
 <!--
<button onclick="triggerCSRFAttack()">Saldırıyı Tetikle</button>

<script>
    // CSRF Saldırısını Tetikleyen Fonksiyon
    function triggerCSRFAttack() {
        document.getElementById("csrf-attack-form").submit();
    }
</script>
-->
        <!-- Kullanıcı Listesi -->
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kullanıcı Adı</th>
                    <th>Şifre</th>
                    <th>E-posta</th>
                    <th>Doğum Tarihi</th>
                    <th>Rol</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody id="user-list">
            </tbody>
        </table>

        <!-- Kullanıcı Güncelleme Formu -->
        <div id="update-form" style="display: none;">
            <h3>Kullanıcı Bilgisini Güncelle</h3>
            <form id="update-user-form">
                <input type="hidden" id="update-id" name="id">
                <input type="text" id="update-username" name="username" placeholder="Yeni Kullanıcı Adı" required>
                <input type="password" id="update-password" name="password" placeholder="Yeni Şifre" required>
                <input type="email" id="update-email" name="email" placeholder="Yeni E-posta" required>
                <input type="date" id="update-do_tar" name="do_tar" placeholder="Yeni Doğum Tarihi" required>
                <select id="update-role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                    <option value="viewer">Viewer</option>
                </select>
                <button type="submit" name="update">Güncelle</button>
                <button type="button" id="cancel-update">İptal</button>
            </form>
        </div>

        <script>
        // Sayfa yüklendiğinde kullanıcıları getir
        $(document).ready(function(){
            fetchUsers();
        });

        // Kullanıcı arama işlemi
        $('#search-form').submit(function(e) {
            e.preventDefault();
            var search = $('#search').val();
            $.ajax({
                url: 'kullaniciis.php?search=' + search,
                type: 'GET',
                success: function(response) {
                    $('#user-list').html(response);
                }
            });
        });

        // Kullanıcı ekleme
        $('#add-form').submit(function(e) {
            e.preventDefault();
            var username = $('#username').val();
            var password = $('#password').val();
            var email = $('#email').val();
            var do_tar = $('#do_tar').val();
            var role = $('#role').val();
            $.ajax({
                url: 'kullaniciis.php',
                type: 'POST',
                data: {
                    add: true,
                    username: username,
                    password: password,
                    email: email,
                    do_tar: do_tar,
                    role: role
                },
                success: function(response) {
                    alert(response);
                    fetchUsers();
                }
            });
        });

        // Kullanıcı silme
        function deleteUser(id) {
            if (confirm('Kullanıcıyı silmek istediğinizden emin misiniz?')) {
                $.ajax({
                    url: 'kullaniciis.php',
                    type: 'POST',
                    data: { 
                        delete: true,
                        id: id 
                    },
                    success: function(response) {
                        alert(response);
                        fetchUsers();
                    }
                });
            }
        }

        // Kullanıcı güncelleme formunu açma
        function openUpdateForm(id, username, password, email, do_tar, role) {
            $('#update-id').val(id);
            $('#update-username').val(username);
            $('#update-password').val(password);
            $('#update-email').val(email);
            $('#update-do_tar').val(do_tar);
            $('#update-role').val(role);
            $('#update-form').show();
        }

        // Kullanıcı güncelleme
        $('#update-user-form').submit(function(e) {
            e.preventDefault();
            var id = $('#update-id').val();
            var username = $('#update-username').val();
            var password = $('#update-password').val();
            var email = $('#update-email').val();
            var do_tar = $('#update-do_tar').val();
            var role = $('#update-role').val();
            $.ajax({
                url: 'kullaniciis.php',
                type: 'POST',
                data: {
                    update: true,
                    id: id,
                    username: username,
                    password: password,
                    email: email,
                    do_tar: do_tar,
                    role: role
                },
                success: function(response) {
                    alert(response);
                    fetchUsers();
                    $('#update-form').hide();
                }
            });
        });

        // Güncelleme formunu iptal etme
        $('#cancel-update').click(function() {
            $('#update-form').hide();
        });

        // Kullanıcıları getirme
        function fetchUsers() {
            $.ajax({
                url: 'kullaniciis.php?fetchUsers=true',
                type: 'GET',
                success: function(response) {
                    $('#user-list').html(response);
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
