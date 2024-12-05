<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="Home.css"> 
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
</head>
<body>
    <div class="home-container">
        <header class="home-header">
            <h1>Welcome to Student Management</h1>
            <nav class="navbar">
                <ul>
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="UserManagement.php">UserManagement</a></li>
                    <li><a href="#">Classes</a></li>
                    <li><a href="#">Attendance</a></li>
                    <li><a href="Login.php">Logout</a></li>
                    <li><i href="#" class="fa-regular fa-user"></i></li>
                </ul>
            </nav>
        </header>

        <main class="home-main">
        <section class="feature-section">
                <h2>Subject</h2>
                <p>Track and manage current courses, add courses or edit or delete them</p>
                <a href="Subject.php" class="btn">Go to Subject</a>
            </section>

            <section class="feature-section">
                <h2>Manage Classes</h2>
                <p>View, add, update, or delete student information.</p>
                <a href="ManageClass.php" class="btn">Go to Management Classes</a>
            </section>

            <section class="feature-section">
                <h2>Manage Class Members</h2>
                <p>View class schedules, assign teachers, and manage class members.</p>
                <a href="ManageClassMembers.php" class="btn">Go to Class Management</a>
            </section>

            <section class="feature-section">
                <h2>Schedule</h2>
                <p>Track and manage attendance records.</p>
                <a href="ManagementSchedule.php" class="btn">Go to Schedule</a>
            </section>

            <section class="feature-section">
                <h2>Attendance</h2>
                <p>Track and manage attendance records.</p>
                <a href="Attendance.php" class="btn">Go to Attendance</a>
            </section>
        </main>

        <footer class="home-footer">
            <p>&copy; 2024 School Management System</p>
        </footer>
    </div>
</body>
</html>
