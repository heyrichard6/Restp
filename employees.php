<?php
include 'db.php';

$conn = new mysqli("localhost", "root", "", "db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $role = $_POST['role'];
        $contact = $_POST['contact'];
        $sql = "INSERT INTO Employees (Name, Role, Contact) VALUES ('$name', '$role', '$contact')";
        $conn->query($sql);
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $role = $_POST['role'];
        $contact = $_POST['contact'];
        $sql = "UPDATE Employees SET Name='$name', Role='$role', Contact='$contact' WHERE EmployeeID=$id";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM Employees WHERE EmployeeID=$id";
        $conn->query($sql);
    }
}


$result = $conn->query("SELECT * FROM Employees");
$employees = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees - Restaurant Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Employees</h1>
        <a href="index.php" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Back to Dashboard</a>


        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-semibold mb-4">Add New Employee</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="name" placeholder="Name" required class="border p-2 rounded">
                <input type="text" name="role" placeholder="Role" class="border p-2 rounded">
                <input type="text" name="contact" placeholder="Contact" class="border p-2 rounded">
                <button type="submit" name="add" class="bg-green-500 text-white px-4 py-2 rounded col-span-1 md:col-span-3">Add Employee</button>
            </form>
        </div>


        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Employee List</h2>
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Role</th>
                        <th class="px-4 py-2">Contact</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee): ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?php echo $employee['EmployeeID']; ?></td>
                        <td class="px-4 py-2"><?php echo $employee['Name']; ?></td>
                        <td class="px-4 py-2"><?php echo $employee['Role']; ?></td>
                        <td class="px-4 py-2"><?php echo $employee['Contact']; ?></td>
                        <td class="px-4 py-2">
                            <button onclick="editEmployee(<?php echo $employee['EmployeeID']; ?>, '<?php echo $employee['Name']; ?>', '<?php echo $employee['Role']; ?>', '<?php echo $employee['Contact']; ?>')" class="bg-yellow-500 text-white px-2 py-1 rounded mr-2">Edit</button>
                            <form method="POST" class="inline">
                                <input type="hidden" name="id" value="<?php echo $employee['EmployeeID']; ?>">
                                <button type="submit" name="delete" class="bg-red-500 text-white px-2 py-1 rounded" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        
        <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg w-96">
                <h2 class="text-xl font-semibold mb-4">Edit Employee</h2>
                <form method="POST" id="editForm">
                    <input type="hidden" name="id" id="editId">
                    <input type="text" name="name" id="editName" placeholder="Name" required class="border p-2 rounded w-full mb-2">
                    <input type="text" name="role" id="editRole" placeholder="Role" class="border p-2 rounded w-full mb-2">
                    <input type="text" name="contact" id="editContact" placeholder="Contact" class="border p-2 rounded w-full mb-4">
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                        <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editEmployee(id, name, role, contact) {
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editRole').value = role;
            document.getElementById('editContact').value = contact;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>
</html>
