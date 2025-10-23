<?php
include 'db.php';

$conn = new mysqli("localhost", "root", "", "db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $orderid = $_POST['orderid'];
        $itemid = $_POST['itemid'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $sql = "INSERT INTO OrderDetails (OrderID, ItemID, Quantity, Price) VALUES ('$orderid', '$itemid', '$quantity', '$price')";
        $conn->query($sql);
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $orderid = $_POST['orderid'];
        $itemid = $_POST['itemid'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $sql = "UPDATE OrderDetails SET OrderID='$orderid', ItemID='$itemid', Quantity='$quantity', Price='$price' WHERE OrderDetailID=$id";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM OrderDetails WHERE OrderDetailID=$id";
        $conn->query($sql);
    }
}


$result = $conn->query("SELECT od.*, o.OrderID as OrderNumber, m.Name as ItemName FROM OrderDetails od LEFT JOIN Orders o ON od.OrderID = o.OrderID LEFT JOIN MenuItems m ON od.ItemID = m.ItemID");
$orderdetails = $result->fetch_all(MYSQLI_ASSOC);


$orders_result = $conn->query("SELECT * FROM Orders");
$orders = $orders_result->fetch_all(MYSQLI_ASSOC);


$menuitems_result = $conn->query("SELECT * FROM MenuItems");
$menuitems = $menuitems_result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Restaurant Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Order Details</h1>
        <a href="index.php" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Back to Dashboard</a>

        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-semibold mb-4">Add New Order Detail</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <select name="orderid" class="border p-2 rounded">
                    <option value="">Select Order</option>
                    <?php foreach ($orders as $order): ?>
                        <option value="<?php echo $order['OrderID']; ?>">Order <?php echo $order['OrderID']; ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="itemid" class="border p-2 rounded">
                    <option value="">Select Item</option>
                    <?php foreach ($menuitems as $item): ?>
                        <option value="<?php echo $item['ItemID']; ?>"><?php echo $item['Name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="quantity" placeholder="Quantity" required class="border p-2 rounded">
                <input type="number" step="0.01" name="price" placeholder="Price" required class="border p-2 rounded">
                <button type="submit" name="add" class="bg-green-500 text-white px-4 py-2 rounded col-span-1 md:col-span-4">Add Order Detail</button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Order Details List</h2>
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Order</th>
                        <th class="px-4 py-2">Item</th>
                        <th class="px-4 py-2">Quantity</th>
                        <th class="px-4 py-2">Price</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderdetails as $detail): ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?php echo $detail['OrderDetailID']; ?></td>
                        <td class="px-4 py-2">Order <?php echo $detail['OrderNumber']; ?></td>
                        <td class="px-4 py-2"><?php echo $detail['ItemName']; ?></td>
                        <td class="px-4 py-2"><?php echo $detail['Quantity']; ?></td>
                        <td class="px-4 py-2">$<?php echo number_format($detail['Price'], 2); ?></td>
                        <td class="px-4 py-2">
                            <button onclick="editOrderDetail(<?php echo $detail['OrderDetailID']; ?>, '<?php echo $detail['OrderID']; ?>', '<?php echo $detail['ItemID']; ?>', '<?php echo $detail['Quantity']; ?>', '<?php echo $detail['Price']; ?>')" class="bg-yellow-500 text-white px-2 py-1 rounded mr-2">Edit</button>
                            <form method="POST" class="inline">
                                <input type="hidden" name="id" value="<?php echo $detail['OrderDetailID']; ?>">
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
                <h2 class="text-xl font-semibold mb-4">Edit Order Detail</h2>
                <form method="POST" id="editForm">
                    <input type="hidden" name="id" id="editId">
                    <select name="orderid" id="editOrderID" class="border p-2 rounded w-full mb-2">
                        <option value="">Select Order</option>
                        <?php foreach ($orders as $order): ?>
                            <option value="<?php echo $order['OrderID']; ?>">Order <?php echo $order['OrderID']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="itemid" id="editItemID" class="border p-2 rounded w-full mb-2">
                        <option value="">Select Item</option>
                        <?php foreach ($menuitems as $item): ?>
                            <option value="<?php echo $item['ItemID']; ?>"><?php echo $item['Name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="quantity" id="editQuantity" placeholder="Quantity" required class="border p-2 rounded w-full mb-2">
                    <input type="number" step="0.01" name="price" id="editPrice" placeholder="Price" required class="border p-2 rounded w-full mb-4">
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                        <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editOrderDetail(id, orderid, itemid, quantity, price) {
            document.getElementById('editId').value = id;
            document.getElementById('editOrderID').value = orderid;
            document.getElementById('editItemID').value = itemid;
            document.getElementById('editQuantity').value = quantity;
            document.getElementById('editPrice').value = price;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>
</html>
