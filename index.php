<?php
// --- DATABASE CONNECTION ---
$host = "localhost";
$user = "root";
$pass = "";
$db   = "school";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// --- SAVE DATA WHEN FORM IS SUBMITTED ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_teacher'])) {
    $teacher_id   = $_POST['teacher_id'];
    $full_name    = $_POST['full_name'];
    $department   = $_POST['department'];
    $contact_info = $_POST['contact_info'];

    $sql = "INSERT INTO teachers (teacher_code, full_name, department, contact_info)
            VALUES ('$teacher_id', '$full_name', '$department', '$contact_info')";

    if ($conn->query($sql) === TRUE) {
        $msg = "Teacher record added successfully!";
        $msg_class = "success";
        // Auto-refresh after 2 seconds
        echo "<script>
                setTimeout(function() {
                    window.location.href = window.location.href;
                }, 2000);
              </script>";
    } else {
        $msg = "Error: " . $conn->error;
        $msg_class = "danger";
    }
}


// --- EXPORT TO EXCEL ---
if (isset($_POST["export"])) {
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=teachers.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    $query = $conn->query("SELECT * FROM teachers");
    echo "Teacher ID\tFull Name\tDepartment\tContact Info\n";

    while ($row = $query->fetch_assoc()) {
        echo $row['teacher_code'] . "\t" .
             $row['full_name'] . "\t" .
             $row['department'] . "\t" .
             $row['contact_info'] . "\n";
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Registration & Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f3f3f3;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 30px;
        }
        .form-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #aaaaaa;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="container">

    <div class="form-container">
        <h2 class="text-center mb-4">Teacher Registration</h2>

        <?php if(isset($msg)): ?>
            <div class="alert alert-<?php echo $msg_class; ?>">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label>Teacher ID:</label>
                <input type="number" name="teacher_id" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Full Name:</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Department:</label>
                <select name="department" class="form-control" required>
              <option value="">-- Select Department --</option>
                    <option value="Computer Science">Computer Science</option>
                    <option value="Information Technology">Information Technology</option>
                    <option value="Engineering">Engineering</option>
                    <option value="Business Administration">Business Administration</option>
                    <option value="Accountancy">Accountancy</option>
                    <option value="Education">Education</option>
                    <option value="Psychology">Psychology</option>
                    <option value="Nursing">Nursing</option>
                    <option value="Hospitality Management">Hospitality Management</option>
                    <option value="Criminology">Criminology</option>
                    <option value="Law">Law</option>
                    <option value="Arts and Sciences">Arts and Sciences</option>
                    <option value="Agriculture">Agriculture</option>

                </select>
            </div>

            <div class="mb-3">
                <label>Contact Info:</label>
                <input type="number" name="contact_info" class="form-control" required>
            </div>

            <button type="submit" name="save_teacher" class="btn btn-primary w-100">Save</button>
        </form>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Teacher Records</h4>
        </div>
        <div class="card-body">
            <form method="POST" class="mb-3">
                <button name="export" class="btn btn-success">Export to Excel</button>
            </form>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Teacher ID</th>
                        <th>Full Name</th>
                        <th>Department</th>
                        <th>Contact Info</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM teachers ORDER BY teacher_id DESC");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>".$row['teacher_code']."</td>
                                    <td>".$row['full_name']."</td>
                                    <td>".$row['department']."</td>
                                    <td>".$row['contact_info']."</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>
