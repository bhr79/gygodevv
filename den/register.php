<?php
//session_start();
include 'session_check.php';
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $do_tar = $_POST['do_tar'];

    // Admin ve Editor için sayaçları kontrol et
    if ($role == 'admin' || $role == 'editor') {
        $count_query = "SELECT count FROM role_counters WHERE role = '$role'";
        $count_result = $conn->query($count_query);

        if ($count_result->num_rows > 0) {
            $row = $count_result->fetch_assoc();
            $count = $row['count'];

            if ($count >= 1) {
                echo ucfirst($role) . " rolü için kayıt limiti dolmuştur.";
                exit;
            }
        }
    }

    // Kullanıcıyı ekle
    $insert_query = "INSERT INTO users (username, password, email, do_tar, role) 
                    VALUES ('$username', '$password', '$email', '$do_tar', '$role')";
    if ($conn->query($insert_query) === TRUE) {
        echo "Kayıt başarılı!";
    } else {
        echo "Error: " . $insert_query . "<br>" . $conn->error;
    }

    // Admin ve Editor için sayaçları güncelle
    if ($role == 'admin' || $role == 'editor') {
        $update_query = "UPDATE role_counters SET count = count + 1 WHERE role = '$role'";
        $conn->query($update_query);
    }

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kayıt</title>
    <link rel="stylesheet" type="text/css" href="style.css?v=1.0">


</head>
<body>
    <div class="container">
        <h2>Kayıt Ol</h2>
        <form method="POST" action="register.php">
            <label>Kullanıcı Adı:</label>
            <input type="text" name="username" required><br>
            <label>Şifre:</label>
            <input type="password" name="password" required><br>
            <label>E-posta:</label>
            <input type="email" name="email" required><br>
            <label>Doğum Tarihi:</label>
            <input type="date" name="do_tar" required><br>
            <label>Rol:</label>
            <select name="role" required>
                <option value="viewer">Viewer</option>
                <option value="editor">Editor</option>
                <option value="admin">Admin</option>
            </select><br>
            <input type="submit" value="Kayıt Ol">
        </form>
        <form action="login.php">
            <input type="submit" value="Giriş Sayfası">
        </form>
    </div>
</body>
</html>
