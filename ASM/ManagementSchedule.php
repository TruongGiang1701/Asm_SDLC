<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Schedule</title>
    <link rel="stylesheet" href="ManagementSchedule.css">
</head>
<body>
<div class="schedule-container">
    <div class="header">
        <h1>Management Schedule</h1>
        <a href="Home.php" class="home-btn">Home</a>
    </div>

    <?php
    // Kết nối cơ sở dữ liệu
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "SchoolManagement";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Xử lý thêm Schedule
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $scheduleID = $_POST["scheduleID"];
        $classID = $_POST["classID"];
        $startTime = $_POST["startTime"];
        $endTime = $_POST["endTime"];
        $room = $_POST["room"];
        $date = $_POST["date"];
        $note = $_POST["note"];

        $sql = "INSERT INTO Management_Schedule (ScheduleID, ClassID, StartTime, EndTime, Room, Date, Note) 
                VALUES ('$scheduleID', '$classID', '$startTime', '$endTime', '$room', '$date', '$note')";

        if ($conn->query($sql) === TRUE) {
            echo "<p>Schedule added successfully!</p>";
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }

    // Truy vấn danh sách Schedule
    $sql = "SELECT s.ScheduleID, c.ClassName, s.StartTime, s.EndTime, s.Room, s.Date, s.Note 
            FROM Management_Schedule s
            JOIN Management_Class c ON s.ClassID = c.ClassID";
    $result = $conn->query($sql);
    ?>

    <!-- Form thêm Schedule -->
    <form action="" method="POST">
        <label for="scheduleID">Schedule ID:</label>
        <input type="number" name="scheduleID" required>

        <label for="classID">Class ID:</label>
        <input type="number" name="classID" required>

        <label for="startTime">Start Time:</label>
        <input type="time" name="startTime" required>

        <label for="endTime">End Time:</label>
        <input type="time" name="endTime" required>

        <label for="room">Room:</label>
        <input type="text" name="room" required>

        <label for="date">Date:</label>
        <input type="date" name="date" required>

        <label for="note">Note:</label>
        <textarea name="note" rows="4"></textarea>

        <button type="submit">Add Schedule</button>
    </form>

    <!-- Danh sách Schedule -->
    <h2>Schedule List</h2>
    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Schedule ID</th>
                    <th>Class Name</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Room</th>
                    <th>Date</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row["ScheduleID"]; ?></td>
                        <td><?php echo $row["ClassName"]; ?></td>
                        <td><?php echo $row["StartTime"]; ?></td>
                        <td><?php echo $row["EndTime"]; ?></td>
                        <td><?php echo $row["Room"]; ?></td>
                        <td><?php echo $row["Date"]; ?></td>
                        <td><?php echo $row["Note"]; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No schedules found.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</div>
</body>
</html>
