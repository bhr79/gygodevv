<?php
session_start();
include 'config.php';


/*

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Güvenli olmayan SQL sorgusu (SQL enjeksiyonuna açık)
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $row['role'];
        $_SESSION['login_time'] = time();
        $_SESSION['login_time'] = time(); // Kullanıcının giriş zamanı

        // Rol bazlı yönlendirme
        if ($row['role'] == 'viewer') {
            header("Location: viewer.php");
        } else {
            header("Location: adedan.php");
        }
        exit;
    } else {
        echo "Geçersiz kullanıcı adı veya şifre";
    }
}
*/

// Güvenli hale getirilmiş kodlar

if (!isset($_SESSION['login_attempts'])) {//başarısız giriş denemeleri
    $_SESSION['login_attempts'] = 0;
}

if (!isset($_SESSION['lockout_time'])) {
    $_SESSION['lockout_time'] = 0;
}

$max_attempts = 3;
$lockout_duration = 180; // 3 dakika kısıtlama veriyor

// CAPTCHA oluşturma
if (!isset($_SESSION['captcha'])) {
    $_SESSION['captcha'] = rand(1000, 9999);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CAPTCHA doğrulaması
    if ($_SESSION['captcha'] != $_POST['captcha']) {
        echo "Invalid CAPTCHA";
        exit;
    }

    // Geçici kilit kontrolü
    if ($_SESSION['login_attempts'] >= $max_attempts && time() < $_SESSION['lockout_time']) {
        echo "Çok fazla giriş denemesi. Lütfen daha sonra tekrar deneyiniz.";
        exit;
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL enjeksiyonunu önlemek için hazırlanan ifadeler kullan
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Şifre doğrulaması
        if ($password == $row['password']) { 
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role'];
            $_SESSION['login_time'] = time();
            $_SESSION['session_timeout'] = 600; // Oturum süresi 10 dakika
            $_SESSION['login_attempts'] = 0; // Giriş başarılı, deneme sayısını sıfırla

            // Rol bazlı yönlendirme
            if ($row['role'] == 'viewer') {
                header("Location: viewer.php");
            } else {
                header("Location: adedan.php");
            }
            exit;
        } else {
            $_SESSION['login_attempts']++;
            if ($_SESSION['login_attempts'] >= $max_attempts) {
                $_SESSION['lockout_time'] = time() + $lockout_duration;
            }
            echo "Geçersiz kullanıcı adı veya şifre";
        }
    } else {
        $_SESSION['login_attempts']++;
        if ($_SESSION['login_attempts'] >= $max_attempts) {
            $_SESSION['lockout_time'] = time() + $lockout_duration;
        }
        echo "Geçersiz kullanıcı adı veya şifre";
    }
}

// Yeni CAPTCHA oluşturma
$_SESSION['captcha'] = rand(1000, 9999);


?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css?v=1.0">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        
        <!-- Güvensiz kod -->
       <!--
        <form method="POST" action="login.php">
            <label>Kullanıcı Adı:</label>
            <input type="text" name="username" required><br>
            <label>Şifre:</label>
            <input type="password" name="password" required><br>
            <input type="submit" value="Login">
        </form>
        <form action="register.php">
            <input type="submit" value="Kayıt Sayfasına Git">
        </form>
-->
        

        <!-- Güvenli hale getirilmiş kodlar -->

        <?php if ($_SESSION['login_attempts'] >= $max_attempts && time() < $_SESSION['lockout_time']): ?>
            <div class="lockout-message">
                <p>Çok fazla giriş denemesi. Lütfen daha sonra tekrar deneyiniz.</p>
                <p>Kalan süre: <?php echo ceil(($_SESSION['lockout_time'] - time()) / 60); ?> dakika</p>
            </div>
        <?php else: ?>
            <form method="POST" action="login.php">
                <label>Kullanıcı Adı:</label>
                <input type="text" name="username" required><br>
                <label>Şifre:</label>
                <input type="password" name="password" required><br>
                <label>CAPTCHA: <?php echo $_SESSION['captcha']; ?></label>
                <input type="text" name="captcha" required><br>
                <input type="submit" value="Login">
            </form>
        <?php endif; ?>
        <form action="register.php">
            <input type="submit" value="Kayıt Sayfasına Git">
        </form>
    </div>
    <script>
        var countdown = document.querySelector('.countdown');
        var remainingTime = <?php echo ($_SESSION['lockout_time'] - time()); ?>;
        
        function updateCountdown() {//kitlenmeden sonraki süreyi gösterir açılma süresini
            var minutes = Math.ceil(remainingTime / 60);
            countdown.innerHTML = "Kalan süre: " + minutes + " dakika";
            remainingTime--;
            if (remainingTime < 0) {
                clearInterval(interval);
                countdown.innerHTML = "Kalan süre: 0 dakika";
            }
        }
        
        var interval = setInterval(updateCountdown, 1000);
    </script>
        
</body>
</html>
