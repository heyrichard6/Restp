<?php
include 'db.php';

$conn = new mysqli("localhost", "root", "", "db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $categoryid = $_POST['categoryid'];
        $sql = "INSERT INTO MenuItems (Name, Description, Price, CategoryID) VALUES ('$name', '$description', '$price', '$categoryid')";
        $conn->query($sql);
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $categoryid = $_POST['categoryid'];
        $sql = "UPDATE MenuItems SET Name='$name', Description='$description', Price='$price', CategoryID='$categoryid' WHERE ItemID=$id";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM MenuItems WHERE ItemID=$id";
        $conn->query($sql);
    }
}


$result = $conn->query("SELECT m.*, c.Name as CategoryName FROM MenuItems m LEFT JOIN Categories c ON m.CategoryID = c.CategoryID");
$menuitems = $result->fetch_all(MYSQLI_ASSOC);


$categories_result = $conn->query("SELECT * FROM Categories");
$categories = $categories_result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Items - Restaurant Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Menu Items</h1>
        <a href="index.php" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Back to Dashboard</a>


        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-semibold mb-4">Add New Menu Item</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="name" placeholder="Name" required class="border p-2 rounded">
                <input type="number" step="0.01" name="price" placeholder="Price" required class="border p-2 rounded">
                <textarea name="description" placeholder="Description" class="border p-2 rounded col-span-1 md:col-span-2"></textarea>
                <select name="categoryid" class="border p-2 rounded">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['CategoryID']; ?>"><?php echo $category['Name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="add" class="bg-green-500 text-white px-4 py-2 rounded col-span-1 md:col-span-2">Add Menu Item</button>
            </form>
        </div>


        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Menu Items List</h2>
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Price</th>
                        <th class="px-4 py-2">Category</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($menuitems as $item): ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?php echo $item['ItemID']; ?></td>
                        <td class="px-4 py-2"><?php echo $item['Name']; ?></td>
                        <td class="px-4 py-2"><?php echo $item['Description']; ?></td>
                        <td class="px-4 py-2">$<?php echo number_format($item['Price'], 2); ?></td>
                        <td class="px-4 py-2"><?php echo $item['CategoryName']; ?></td>
                        <td class="px-4 py-2">
                            <button onclick="editMenuItem(<?php echo $item['ItemID']; ?>, '<?php echo $item['Name']; ?>', '<?php echo addslashes($item['Description']); ?>', '<?php echo $item['Price']; ?>', '<?php echo $item['CategoryID']; ?>')" class="bg-yellow-500 text-white px-2 py-1 rounded mr-2">Edit</button>
                            <form method="POST" class="inline">
                                <input type="hidden" name="id" value="<?php echo $item['ItemID']; ?>">
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
                <h2 class="text-xl font-semibold mb-4">Edit Menu Item</h2>
                <form method="POST" id="editForm">
                    <input type="hidden" name="id" id="editId">
                    <input type="text" name="name" id="editName" placeholder="Name" required class="border p-2 rounded w-full mb-2">
                    <input type="number" step="0.01" name="price" id="editPrice" placeholder="Price" required class="border p-2 rounded w-full mb-2">
                    <textarea name="description" id="editDescription" placeholder="Description" class="border p-2 rounded w-full mb-2"></textarea>
                    <select name="categoryid" id="editCategoryID" class="border p-2 rounded w-full mb-4">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['CategoryID']; ?>"><?php echo $category['Name']; ?></option>
                        <?php endforeach; ?>
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
        function editMenuItem(id, name, description, price, categoryid) {
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description;
            document.getElementById('editPrice').value = price;
            document.getElementById('editCategoryID').value = categoryid;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>
</html>
