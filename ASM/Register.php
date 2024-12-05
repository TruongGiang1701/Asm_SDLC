<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account</title>
    <link rel="stylesheet" href="RegisterForm.css">
</head>
<body>
<div class="form-container">
        <h2>Register Account</h2>
        <form action="" method="POST">

            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" name="firstName" required>
            </div>

            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" name="lastName" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" name="phone">
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob">
            </div>

            <div class="form-group">
                <label for="major">Major:</label>
                <input type="text" name="major">
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="accountRole">Account Role:</label>
                <select name="accountRole">
                    <option value="Admin">Admin</option>
                    <option value="Teacher">Teacher</option>
                    <option value="Student">Student</option>
                </select>
            </div>

            <div class="form-group">
                <input type="submit" name="submit" value="Register">
                <p>You do not have an account? <a class="Link" href="Login.php"> Here</a></p>
                <p>Need more information?<a class="Link" href="UserManagement.php">Go to User Management form</a></p>

            </div>
        </form>
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
// Xử lý khi form được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Xóa $accountID vì AccountID sẽ tự tăng
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $dob = $_POST["dob"];
    $major = $_POST["major"];
    $status = $_POST["status"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Mã hóa mật khẩu
    $accountRole = $_POST["accountRole"];

    // Xây dựng câu truy vấn
    $sql = "INSERT INTO Management_Account (FirstName, LastName, Email, Phone, DateOfBirth, Major, Status, Password, AccountRole) 
            VALUES ('$firstName', '$lastName', '$email', '$phone', '$dob', '$major', '$status', '$password', '$accountRole')";

    // Thực thi truy vấn
    if (mysqli_query($conn, $sql)) {
        echo "<p>Registration successful!</p>";
    } else {
        echo "<p>Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
    }
}


    // Đóng kết nối
    mysqli_close($conn);
    ?>
    </div>
</body>
</html>
