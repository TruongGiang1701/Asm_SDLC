<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Management</title>
    <!-- Liên kết đến file CSS -->
    <link rel="stylesheet" href="Subject.css">
</head>
<body>
    <div class="subject-container">
        <header class="subject-header">
            <h1>Subject Management</h1>
            <nav class="navbar">
                <ul>
                    <li><a href="Home.php">Home</a></li>
                    <li><a href="Attendance.php">Attendance</a></li>
                    <li><a href="ManageClass.php">Classes</a></li>
                    <li><a href="Login.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <main class="subject-main">
            <h2>Subjects List</h2>

            <?php
            // Kết nối cơ sở dữ liệu
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "SchoolManagement";

            $conn = mysqli_connect($servername, $username, $password, $dbname);

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Kiểm tra nếu người dùng đã đăng nhập và quyền của họ
            session_start();
            $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] == 'Admin';

            // Xử lý thêm môn học (Admin mới được quyền này)
            if ($isAdmin && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_subject'])) {
                $subjectID = $_POST['subjectID']; // Cung cấp SubjectID cho Admin
                $subjectName = $_POST['subjectName'];
                $major = $_POST['major'];

                $sql = "INSERT INTO Management_Subject (SubjectID, SubjectName, Major) VALUES ('$subjectID', '$subjectName', '$major')";
                if (mysqli_query($conn, $sql)) {
                    echo "<p>Subject added successfully!</p>";
                } else {
                    echo "<p>Error: " . mysqli_error($conn) . "</p>";
                }
            }

            // Xử lý xóa môn học (Admin mới có quyền)
            if ($isAdmin && isset($_GET['delete'])) {
                $subjectID = $_GET['delete'];
                $sql = "DELETE FROM Management_Subject WHERE SubjectID = $subjectID";
                if (mysqli_query($conn, $sql)) {
                    echo "<p>Subject deleted successfully!</p>";
                } else {
                    echo "<p>Error: " . mysqli_error($conn) . "</p>";
                }
            }

            // Xử lý sửa môn học (Admin mới có quyền)
            if ($isAdmin && isset($_GET['edit'])) {
                $subjectID = $_GET['edit'];
                $sql = "SELECT * FROM Management_Subject WHERE SubjectID = $subjectID";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
            }

            if ($isAdmin && isset($_POST['update_subject'])) {
                $subjectID = $_POST['subjectID'];
                $subjectName = $_POST['subjectName'];
                $major = $_POST['major'];

                $sql = "UPDATE Management_Subject SET SubjectName = '$subjectName', Major = '$major' WHERE SubjectID = $subjectID";
                if (mysqli_query($conn, $sql)) {
                    echo "<p>Subject updated successfully!</p>";
                } else {
                    echo "<p>Error: " . mysqli_error($conn) . "</p>";
                }
            }
            ?>

            <!-- Form thêm môn học (Chỉ Admin mới thấy được) -->
            <?php if ($isAdmin): ?>
            <form method="POST" action="">
                <h3>Add New Subject</h3>
                <label for="subjectID">Subject ID:</label>
                <input type="text" name="subjectID" required>
                <label for="subjectName">Subject Name:</label>
                <input type="text" name="subjectName" required>
                <label for="major">Major:</label>
                <input type="text" name="major" required>
                <button type="submit" name="add_subject">Add Subject</button>
            </form>
            <?php endif; ?>

            <!-- Hiển thị danh sách môn học (Dành cho tất cả người dùng) -->
            <?php
            // Truy vấn và hiển thị danh sách môn học
            $sql = "SELECT * FROM Management_Subject";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<tr><th>Subject ID</th><th>Subject Name</th><th>Major</th><th>Actions</th></tr>";

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["SubjectID"] . "</td>";
                    echo "<td>" . $row["SubjectName"] . "</td>";
                    echo "<td>" . $row["Major"] . "</td>";
                    echo "<td>";

                    // Chỉ Admin mới có quyền sửa và xóa
                    if ($isAdmin) {
                        echo "<a href='?edit=" . $row["SubjectID"] . "'>Edit</a> | ";
                        echo "<a href='?delete=" . $row["SubjectID"] . "'>Delete</a>";
                    } else {
                        echo "View only";
                    }
                    echo "</td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "<p>No subjects found.</p>";
            }
            ?>

            <!-- Form sửa môn học (Chỉ Admin mới thấy được) -->
            <?php
            // Hiển thị form sửa môn học nếu có
            if ($isAdmin && isset($row)) {
                echo "<h3>Edit Subject</h3>";
                echo "<form method='POST' action=''>
                        <input type='hidden' name='subjectID' value='" . $row['SubjectID'] . "'>
                        <label for='subjectName'>Subject Name:</label>
                        <input type='text' name='subjectName' value='" . $row['SubjectName'] . "' required>
                        <label for='major'>Major:</label>
                        <input type='text' name='major' value='" . $row['Major'] . "' required>
                        <button type='submit' name='update_subject'>Update Subject</button>
                      </form>";
            }
            ?>

        </main>

        <footer class="subject-footer">
            <p>&copy; 2024 School Management System</p>
        </footer>
    </div>

</body>
</html>

<?php
// Đóng kết nối cơ sở dữ liệu
mysqli_close($conn);
?>
