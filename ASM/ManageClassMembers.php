<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Class Members</title>
    <link rel="stylesheet" href="ManageClassMembers.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Manage Class Members</h1>
            <nav>
                <a href="Home.php" class="btn-home">Home</a>
            </nav>
        </header>

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

        // Kiểm tra quyền người dùng
        session_start();
        $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] == 'Admin';
        $isTeacher = isset($_SESSION['role']) && $_SESSION['role'] == 'Teacher';
        $isStudent = isset($_SESSION['role']) && $_SESSION['role'] == 'Student';

        // Xử lý thêm thành viên mới (Chỉ Admin có quyền thêm)
        if ($isAdmin && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_member"])) {
            $ClassID = $_POST["ClassID"];
            $AccountID = $_POST["AccountID"];
            $JoinDate = $_POST["JoinDate"];
            $Status = $_POST["Status"];

            $sql_insert = "INSERT INTO Management_ClassMembers (ClassID, AccountID, JoinDate, Status) 
                           VALUES ('$ClassID', '$AccountID', '$JoinDate', '$Status')";

            if (mysqli_query($conn, $sql_insert)) {
                echo "<p class='success-msg'>Member added successfully!</p>";
            } else {
                echo "<p class='error-msg'>Error: " . mysqli_error($conn) . "</p>";
            }
        }
        ?>

        <!-- Hiển thị bảng danh sách thành viên -->
        <table>
            <thead>
                <tr>
                    <th>Class Member ID</th>
                    <th>Class Name</th>
                    <th>Member Name</th>
                    <th>Join Date</th>
                    <th>Status</th>
                    <?php if ($isAdmin): ?>
                    <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "
                    SELECT 
                        cm.ClassMemberID,
                        c.ClassName,
                        CONCAT(a.FirstName, ' ', a.LastName) AS MemberName,
                        cm.JoinDate,
                        cm.Status
                    FROM 
                        Management_ClassMembers cm
                    JOIN Management_Class c ON cm.ClassID = c.ClassID
                    JOIN Management_Account a ON cm.AccountID = a.AccountID
                ";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['ClassMemberID']}</td>";
                        echo "<td>{$row['ClassName']}</td>";
                        echo "<td>{$row['MemberName']}</td>";
                        echo "<td>{$row['JoinDate']}</td>";
                        echo "<td>{$row['Status']}</td>";
                        
                        if ($isAdmin) {
                            echo "<td>
                                    <a href='EditClassMember.php?id={$row['ClassMemberID']}'>Edit</a> |
                                    <a href='DeleteClassMember.php?id={$row['ClassMemberID']}'>Delete</a>
                                  </td>";
                        } else {
                            echo "<td>View only</td>";
                        }

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No members found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Form thêm thành viên (Chỉ Admin mới thấy được) -->
        <?php if ($isAdmin): ?>
        <h2>Add New Class Member</h2>
        <form action="" method="POST">
            <label for="ClassID">Class:</label>
            <select name="ClassID" required>
                <?php
                $sql_classes = "SELECT ClassID, ClassName FROM Management_Class";
                $classes = mysqli_query($conn, $sql_classes);
                while ($class = mysqli_fetch_assoc($classes)) {
                    echo "<option value='{$class['ClassID']}'>{$class['ClassName']}</option>";
                }
                ?>
            </select>
            <label for="AccountID">Member:</label>
            <select name="AccountID" required>
                <?php
                $sql_accounts = "SELECT AccountID, CONCAT(FirstName, ' ', LastName) AS FullName FROM Management_Account";
                $accounts = mysqli_query($conn, $sql_accounts);
                while ($account = mysqli_fetch_assoc($accounts)) {
                    echo "<option value='{$account['AccountID']}'>{$account['FullName']}</option>";
                }
                ?>
            </select>
            <label for="JoinDate">Join Date:</label>
            <input type="date" name="JoinDate" required>
            <label for="Status">Status:</label>
            <select name="Status" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
            <button type="submit" name="add_member">Add Member</button>
        </form>
        <?php endif; ?>

        <?php mysqli_close($conn); ?>
    </div>
</body>
</html>
