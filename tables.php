<?php
include 'db.php';

$conn = new mysqli("localhost", "root", "", "db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $number = $_POST['number'];
        $capacity = $_POST['capacity'];
        $status = $_POST['status'];
        $sql = "INSERT INTO Tables (Number, Capacity, Status) VALUES ('$number', '$capacity', '$status')";
        $conn->query($sql);
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $number = $_POST['number'];
        $capacity = $_POST['capacity'];
        $status = $_POST['status'];
        $sql = "UPDATE Tables SET Number='$number', Capacity='$capacity', Status='$status' WHERE TableID=$id";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM Tables WHERE TableID=$id";
        $conn->query($sql);
    }
}


$result = $conn->query("SELECT * FROM Tables");
$tables = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tables - Restaurant Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Tables</h1>
        <a href="index.php" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Back to Dashboard</a>


        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-semibold mb-4">Add New Table</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="number" name="number" placeholder="Table Number" required class="border p-2 rounded">
                <input type="number" name="capacity" placeholder="Capacity" required class="border p-2 rounded">
                <select name="status" class="border p-2 rounded">
                    <option value="Available">Available</option>
                    <option value="Occupied">Occupied</option>
                    <option value="Reserved">Reserved</option>
                </select>
                <button type="submit" name="add" class="bg-green-500 text-white px-4 py-2 rounded col-span-1 md:col-span-3">Add Table</button>
            </form>
        </div>


        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Tables List</h2>
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Number</th>
                        <th class="px-4 py-2">Capacity</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tables as $table): ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?php echo $table['TableID']; ?></td>
                        <td class="px-4 py-2"><?php echo $table['Number']; ?></td>
                        <td class="px-4 py-2"><?php echo $table['Capacity']; ?></td>
                        <td class="px-4 py-2"><?php echo $table['Status']; ?></td>
                        <td class="px-4 py-2">
                            <button onclick="editTable(<?php echo $table['TableID']; ?>, '<?php echo $table['Number']; ?>', '<?php echo $table['Capacity']; ?>', '<?php echo $table['Status']; ?>')" class="bg-yellow-500 text-white px-2 py-1 rounded mr-2">Edit</button>
                            <form method="POST" class="inline">
                                <input type="hidden" name="id" value="<?php echo $table['TableID']; ?>">
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
                <h2 class="text-xl font-semibold mb-4">Edit Table</h2>
                <form method="POST" id="editForm">
                    <input type="hidden" name="id" id="editId">
                    <input type="number" name="number" id="editNumber" placeholder="Table Number" required class="border p-2 rounded w-full mb-2">
                    <input type="number" name="capacity" id="editCapacity" placeholder="Capacity" required class="border p-2 rounded w-full mb-2">
                    <select name="status" id="editStatus" class="border p-2 rounded w-full mb-4">
                        <option value="Available">Available</option>
                        <option value="Occupied">Occupied</option>
                        <option value="Reserved">Reserved</option>
                    </select>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                        <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editTable(id, number, capacity, status) {
            document.getElementById('editId').value = id;
            document.getElementById('editNumber').value = number;
            document.getElementById('editCapacity').value = capacity;
            document.getElementById('editStatus').value = status;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>
</html>
