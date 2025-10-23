<?php
include 'db.php';

// Reconnect to the database
$conn = new mysqli("localhost", "root", "", "db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create tables
$tables = [
    "CREATE TABLE IF NOT EXISTS Customers (
        CustomerID INT PRIMARY KEY AUTO_INCREMENT,
        Name VARCHAR(100) NOT NULL,
        Contact VARCHAR(50),
        Email VARCHAR(100) UNIQUE
    )",
    "CREATE TABLE IF NOT EXISTS Employees (
        EmployeeID INT PRIMARY KEY AUTO_INCREMENT,
        Name VARCHAR(100) NOT NULL,
        Role VARCHAR(50),
        Contact VARCHAR(50)
    )",
    "CREATE TABLE IF NOT EXISTS Categories (
        CategoryID INT PRIMARY KEY AUTO_INCREMENT,
        Name VARCHAR(100) NOT NULL,
        Description TEXT
    )",
    "CREATE TABLE IF NOT EXISTS MenuItems (
        ItemID INT PRIMARY KEY AUTO_INCREMENT,
        Name VARCHAR(100) NOT NULL,
        Description TEXT,
        Price DECIMAL(10, 2) NOT NULL,
        CategoryID INT,
        FOREIGN KEY (CategoryID) REFERENCES Categories(CategoryID)
            ON DELETE SET NULL
            ON UPDATE CASCADE
    )",
    "CREATE TABLE IF NOT EXISTS Tables (
        TableID INT PRIMARY KEY AUTO_INCREMENT,
        Number INT NOT NULL UNIQUE,
        Capacity INT NOT NULL,
        Status VARCHAR(20)
    )",
    "CREATE TABLE IF NOT EXISTS Orders (
        OrderID INT PRIMARY KEY AUTO_INCREMENT,
        CustomerID INT,
        EmployeeID INT,
        OrderDate DATETIME DEFAULT CURRENT_TIMESTAMP,
        TotalAmount DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (CustomerID) REFERENCES Customers(CustomerID)
            ON DELETE SET NULL
            ON UPDATE CASCADE,
        FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID)
            ON DELETE SET NULL
            ON UPDATE CASCADE
    )",
    "CREATE TABLE IF NOT EXISTS OrderDetails (
        OrderDetailID INT PRIMARY KEY AUTO_INCREMENT,
        OrderID INT,
        ItemID INT,
        Quantity INT NOT NULL,
        Price DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (OrderID) REFERENCES Orders(OrderID)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
        FOREIGN KEY (ItemID) REFERENCES MenuItems(ItemID)
            ON DELETE SET NULL
            ON UPDATE CASCADE
    )"
];

foreach ($tables as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
}

$conn->close();
echo "Database setup complete.";
?>
