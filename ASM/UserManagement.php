<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Menu</title>
    <link rel="stylesheet" href="UserManagement.css">
</head>
<body>
<div class="menu-container">
    <h2>Student Management</h2>

    <?php
    // Thông tin kết nối cơ sở dữ liệu
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "SchoolManagement";

    // Kết nối cơ sở dữ liệu
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Xử lý khi form được gửi để cập nhật hoặc xóa
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update'])) {
            // Xử lý cập nhật sinh viên
            $accountID = $_POST["accountID"];
            $firstName = $_POST["firstName"];
            $lastName = $_POST["lastName"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $dob = $_POST["dob"];
            $major = $_POST["major"];
            $status = $_POST["status"];

            $sqlUpdate = "UPDATE Management_Account SET FirstName='$firstName', LastName='$lastName', Email='$email', Phone='$phone', DateOfBirth='$dob', Major='$major', Status='$status' WHERE AccountID='$accountID'";
            if (mysqli_query($conn, $sqlUpdate)) {
                echo "Update successful for AccountID: $accountID.<br>";
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        } elseif (isset($_POST['delete'])) {
            // Xử lý xóa sinh viên
            $accountID = $_POST["accountID"];
            $sqlDelete = "DELETE FROM Management_Account WHERE AccountID='$accountID'";
            if (mysqli_query($conn, $sqlDelete)) {
                echo "Delete successful for AccountID: $accountID.<br>";
            } else {
                echo "Error deleting record: " . mysqli_error($conn);
            }
        }
    }

    // Lấy danh sách sinh viên
    $sql = "SELECT AccountID, FirstName, LastName, Email, Phone, DateOfBirth, Major, Status FROM Management_Account WHERE AccountRole = 'Student'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>AccountID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Phone</th><th>Date of Birth</th><th>Major</th><th>Status</th><th>Actions</th></tr>";
        
        // Hiển thị từng sinh viên
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<form action='' method='POST'>";
            echo "<td><input type='hidden' name='accountID' value='" . $row["AccountID"] . "'>" . $row["AccountID"] . "</td>";
            echo "<td><input type='text' name='firstName' value='" . $row["FirstName"] . "'></td>";
            echo "<td><input type='text' name='lastName' value='" . $row["LastName"] . "'></td>";
            echo "<td><input type='email' name='email' value='" . $row["Email"] . "'></td>";
            echo "<td><input type='text' name='phone' value='" . $row["Phone"] . "'></td>";
            echo "<td><input type='date' name='dob' value='" . $row["DateOfBirth"] . "'></td>";
            echo "<td><input type='text' name='major' value='" . $row["Major"] . "'></td>";
            echo "<td><select name='status'>
                      <option value='Active' " . ($row["Status"] == 'Active' ? 'selected' : '') . ">Active</option>
                      <option value='Inactive' " . ($row["Status"] == 'Inactive' ? 'selected' : '') . ">Inactive</option>
                  </select></td>";
            echo "<td>";
            echo "<button type='submit' name='update'>Update</button>";
            echo "<button type='submit' name='delete' onclick='return confirm(\"Are you sure you want to delete this student?\")'>Delete</button>";
            echo "</td>";
            echo "</form>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No students found.</p>";
    }

    // Đóng kết nối
    mysqli_close($conn);
    ?>
    <p>Need more information?<a class="Link" href="Register.php">Go to Register form</a></p>
</div>
</body>
</html>
