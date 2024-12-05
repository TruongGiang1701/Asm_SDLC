<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="Login.css">
</head>
<body>
<div class="login-container">
    <div class="login-box">
        <h2>Sign-in</h2>
        <form action="" method="POST">
            <div class="input-group">
                <span class="icon">👤</span>
                <input type="text" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <span class="icon">🔒</span>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-btn">LOGIN</button>
        </form>
        <p>Or Sign-in with social platform</p>
        <div class="social-icons">
            <a href="#">G</a>
            <a href="#">🍎</a>
            <a href="#">f</a>
        </div>
        <p>You do not have an account? <a href="Register.php">Sign up</a></p>

        <?php
        // Hiển thị thông báo nếu có
        if (isset($login_message)) {
            echo "<p class='login-message'>$login_message</p>";
        }
        ?>
    </div>

    <?php
    // Thông tin kết nối
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "SchoolManagement";

    // Kết nối cơ sở dữ liệu
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Xử lý khi form được gửi
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $password = $_POST["password"];
    
        // Lấy thông tin người dùng từ cơ sở dữ liệu
        $sql = "SELECT * FROM Management_Account WHERE Email = '$email'";
        $result = mysqli_query($conn, $sql);
    
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
    
            // Kiểm tra mật khẩu
            if (password_verify($password, $row["Password"])) {
                // Khởi tạo session và phân quyền
                session_start();
                $_SESSION["user_id"] = $row["AccountID"];
                $_SESSION["user_name"] = $row["FirstName"];
                $_SESSION["role"] = $row["AccountRole"]; // Lưu phân quyền vào session

                // Chuyển hướng đến Home.php
                header("Location: Home.php");
                exit();
            } else {
                $login_message = "Invalid password.";
            }
        } else {
            $login_message = "No account found with that email.";
        }
    }

    // Đóng kết nối
    mysqli_close($conn);
    ?>
</div>
</body>
</html>
