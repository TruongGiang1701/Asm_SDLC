<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes</title>
    <link rel="stylesheet" href="ManageClass.css"> <!-- Tạo file CSS riêng -->
</head>
<body>
    <div class="class-container">
        <header class="class-header">
            <h1>Manage Classes</h1>
            <nav class="navbar">
                <ul>
                    <li><a href="Home.php">Home</a></li>
                    <li><a href="Subject.php">Subjects</a></li>
                    <li><a href="Attendance.php">Attendance</a></li>
                    <li><a href="Login.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <main class="class-main">
            <h2>Class List</h2>

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

            // Kiểm tra quyền truy cập người dùng
            session_start();
            $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] == 'Admin';
            $isTeacher = isset($_SESSION['role']) && $_SESSION['role'] == 'Teacher';
            $isStudent = isset($_SESSION['role']) && $_SESSION['role'] == 'Student';

            // Xử lý thêm lớp học (Admin mới có quyền này)
            if ($isAdmin && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_class'])) {
                $classID = $_POST['classID']; // Cung cấp ClassID cho Admin
                $className = $_POST['className'];
                $description = $_POST['description'];
                $teacherID = $_POST['teacherID'];
                $semester = $_POST['semester'];
                $subjectID = $_POST['subjectID'];
                $status = $_POST['status'];

                $sql = "INSERT INTO Management_Class (ClassID, ClassName, Description, TeacherID, Semester, SubjectID, Status) 
                        VALUES ('$classID', '$className', '$description', '$teacherID', '$semester', '$subjectID', '$status')";
                
                if (mysqli_query($conn, $sql)) {
                    echo "<p>Class added successfully!</p>";
                } else {
                    echo "<p>Error: " . mysqli_error($conn) . "</p>";
                }
            }

            // Xử lý xóa lớp học (Chỉ Admin mới có quyền)
            if ($isAdmin && isset($_GET['delete'])) {
                $classID = $_GET['delete'];
                $sql = "DELETE FROM Management_Class WHERE ClassID = $classID";
                if (mysqli_query($conn, $sql)) {
                    echo "<p>Class deleted successfully!</p>";
                } else {
                    echo "<p>Error: " . mysqli_error($conn) . "</p>";
                }
            }

            // Xử lý sửa lớp học (Chỉ Admin mới có quyền)
            if ($isAdmin && isset($_GET['edit'])) {
                $classID = $_GET['edit'];
                $sql = "SELECT * FROM Management_Class WHERE ClassID = $classID";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
            }

            if ($isAdmin && isset($_POST['update_class'])) {
                $classID = $_POST['classID'];
                $className = $_POST['className'];
                $description = $_POST['description'];
                $teacherID = $_POST['teacherID'];
                $semester = $_POST['semester'];
                $subjectID = $_POST['subjectID'];
                $status = $_POST['status'];

                $sql = "UPDATE Management_Class SET ClassName = '$className', Description = '$description', TeacherID = '$teacherID', 
                        Semester = '$semester', SubjectID = '$subjectID', Status = '$status' WHERE ClassID = $classID";
                if (mysqli_query($conn, $sql)) {
                    echo "<p>Class updated successfully!</p>";
                } else {
                    echo "<p>Error: " . mysqli_error($conn) . "</p>";
                }
            }

            ?>

            <!-- Form thêm lớp học (Chỉ Admin mới thấy được) -->
            <?php if ($isAdmin): ?>
            <form method="POST" action="">
                <h3>Add New Class</h3>
                <label for="classID">Class ID:</label>
                <input type="text" name="classID" required>
                <label for="className">Class Name:</label>
                <input type="text" name="className" required>
                <label for="description">Description:</label>
                <textarea name="description" required></textarea>
                <label for="teacherID">Teacher ID:</label>
                <input type="text" name="teacherID" required>
                <label for="semester">Semester:</label>
                <input type="text" name="semester" required>
                <label for="subjectID">Subject ID:</label>
                <input type="text" name="subjectID" required>
                <label for="status">Status:</label>
                <select name="status">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
                <button type="submit" name="add_class">Add Class</button>
            </form>
            <?php endif; ?>

            <!-- Hiển thị danh sách lớp học (Dành cho tất cả người dùng) -->
            <?php
            // Truy vấn và hiển thị danh sách lớp học
            $sql = "
                SELECT 
                    mc.ClassID, 
                    mc.ClassName, 
                    mc.Description, 
                    ma.FirstName AS TeacherName, 
                    ms.SubjectName, 
                    mc.Semester, 
                    mc.Status
                FROM 
                    Management_Class mc
                LEFT JOIN Management_Account ma ON mc.TeacherID = ma.AccountID
                LEFT JOIN Management_Subject ms ON mc.SubjectID = ms.SubjectID
            ";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<tr>
                        <th>Class ID</th>
                        <th>Class Name</th>
                        <th>Description</th>
                        <th>Teacher</th>
                        <th>Subject</th>
                        <th>Semester</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>";

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["ClassID"] . "</td>";
                    echo "<td>" . $row["ClassName"] . "</td>";
                    echo "<td>" . $row["Description"] . "</td>";
                    echo "<td>" . $row["TeacherName"] . "</td>";
                    echo "<td>" . $row["SubjectName"] . "</td>";
                    echo "<td>" . $row["Semester"] . "</td>";
                    echo "<td>" . $row["Status"] . "</td>";
                    echo "<td>";

                    // Chỉ Admin mới có quyền sửa và xóa
                    if ($isAdmin) {
                        echo "<a href='?edit=" . $row["ClassID"] . "'>Edit</a> | ";
                        echo "<a href='?delete=" . $row["ClassID"] . "'>Delete</a>";
                    } else {
                        echo "View only";
                    }
                    echo "</td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "<p>No classes found.</p>";
            }
            ?>

            <!-- Form sửa lớp học (Chỉ Admin mới thấy được) -->
            <?php
            // Hiển thị form sửa lớp học nếu có
            if ($isAdmin && isset($row)) {
                echo "<h3>Edit Class</h3>";
                echo "<form method='POST' action=''>
                        <input type='hidden' name='classID' value='" . $row['ClassID'] . "'>
                        <label for='className'>Class Name:</label>
                        <input type='text' name='className' value='" . $row['ClassName'] . "' required>
                        <label for='description'>Description:</label>
                        <textarea name='description' required>" . $row['Description'] . "</textarea>
                        <label for='teacherID'>Teacher ID:</label>
                        <input type='text' name='teacherID' value='" . $row['TeacherID'] . "' required>
                        <label for='semester'>Semester:</label>
                        <input type='text' name='semester' value='" . $row['Semester'] . "' required>
                        <label for='subjectID'>Subject ID:</label>
                        <input type='text' name='subjectID' value='" . $row['SubjectID'] . "' required>
                        <label for='status'>Status:</label>
                        <select name='status'>
                            <option value='Active' " . ($row['Status'] == 'Active' ? 'selected' : '') . ">Active</option>
                            <option value='Inactive' " . ($row['Status'] == 'Inactive' ? 'selected' : '') . ">Inactive</option>
                        </select>
                        <button type='submit' name='update_class'>Update Class</button>
                    </form>";
            }
            ?>
        </main>

        <footer class="class-footer">
            <p>&copy; 2024 School Management System</p>
        </footer>
    </div>
</body>
</html>
