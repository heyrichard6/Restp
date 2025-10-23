<?php
include 'db.php';

$conn = new mysqli("localhost", "root", "", "db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $customerid = intval($_POST['customerid']);
        $employeeid = intval($_POST['employeeid']);
        $totalamount = floatval($_POST['totalamount']);
        
        $stmt = $conn->prepare("INSERT INTO Orders (CustomerID, EmployeeID, TotalAmount, OrderDate) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iid", $customerid, $employeeid, $totalamount);
        $stmt->execute();
        $stmt->close();
        header("Location: orders.php");
        exit;
    } elseif (isset($_POST['update'])) {
        $id = intval($_POST['id']);
        $customerid = intval($_POST['customerid']);
        $employeeid = intval($_POST['employeeid']);
        $totalamount = floatval($_POST['totalamount']);
        
        $stmt = $conn->prepare("UPDATE Orders SET CustomerID=?, EmployeeID=?, TotalAmount=? WHERE OrderID=?");
        $stmt->bind_param("iidi", $customerid, $employeeid, $totalamount, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: orders.php");
        exit;
    } elseif (isset($_POST['delete'])) {
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("DELETE FROM Orders WHERE OrderID=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: orders.php");
        exit;
    }
}


$result = $conn->query("SELECT o.*, c.Name as CustomerName, e.Name as EmployeeName FROM Orders o LEFT JOIN Customers c ON o.CustomerID = c.CustomerID LEFT JOIN Employees e ON o.EmployeeID = e.EmployeeID");
$orders = $result->fetch_all(MYSQLI_ASSOC);


$customers_result = $conn->query("SELECT * FROM Customers");
$customers = $customers_result->fetch_all(MYSQLI_ASSOC);


$employees_result = $conn->query("SELECT * FROM Employees");
$employees = $employees_result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Restaurant Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Orders</h1>
        <a href="index.php" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Back to Dashboard</a>


        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-semibold mb-4">Add New Order</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <select name="customerid" class="border p-2 rounded" required>
                    <option value="">Select Customer</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo htmlspecialchars($customer['CustomerID']); ?>">
                            <?php echo htmlspecialchars($customer['Name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="employeeid" class="border p-2 rounded" required>
                    <option value="">Select Employee</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?php echo htmlspecialchars($employee['EmployeeID']); ?>">
                            <?php echo htmlspecialchars($employee['Name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="number" step="0.01" name="totalamount" placeholder="Total Amount" required class="border p-2 rounded">
                <button type="submit" name="add" class="bg-green-500 text-white px-4 py-2 rounded col-span-1 md:col-span-3">Add Order</button>
            </form>
        </div>


        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Orders List</h2>
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Customer</th>
                        <th class="px-4 py-2">Employee</th>
                        <th class="px-4 py-2">Order Date</th>
                        <th class="px-4 py-2">Total Amount</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?php echo $order['OrderID']; ?></td>
                        <td class="px-4 py-2"><?php echo $order['CustomerName']; ?></td>
                        <td class="px-4 py-2"><?php echo $order['EmployeeName']; ?></td>
                        <td class="px-4 py-2"><?php echo $order['OrderDate']; ?></td>
                        <td class="px-4 py-2">$<?php echo number_format($order['TotalAmount'], 2); ?></td>
                        <td class="px-4 py-2">
                            <button onclick="editOrder(<?php echo $order['OrderID']; ?>, '<?php echo $order['CustomerID']; ?>', '<?php echo $order['EmployeeID']; ?>', '<?php echo $order['TotalAmount']; ?>')" class="bg-yellow-500 text-white px-2 py-1 rounded mr-2">Edit</button>
                            <form method="POST" class="inline">
                                <input type="hidden" name="id" value="<?php echo $order['OrderID']; ?>">
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
                <h2 class="text-xl font-semibold mb-4">Edit Order</h2>
                <form method="POST" id="editForm">
                    <input type="hidden" name="id" id="editId">
                    <select name="customerid" id="editCustomerID" class="border p-2 rounded w-full mb-2">
                        <option value="">Select Customer</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?php echo $customer['CustomerID']; ?>"><?php echo $customer['Name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="employeeid" id="editEmployeeID" class="border p-2 rounded w-full mb-2">
                        <option value="">Select Employee</option>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?php echo $employee['EmployeeID']; ?>"><?php echo $employee['Name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" step="0.01" name="totalamount" id="editTotalAmount" placeholder="Total Amount" required class="border p-2 rounded w-full mb-4">
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                        <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editOrder(id, customerid, employeeid, totalamount) {
            document.getElementById('editId').value = id;
            document.getElementById('editCustomerID').value = customerid;
            document.getElementById('editEmployeeID').value = employeeid;
            document.getElementById('editTotalAmount').value = totalamount;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>
</html>
