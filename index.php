<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Restaurant Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">


    <header class="bg-white shadow">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="index.php" class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold">R</div>
                <div>
                    <h1 class="text-lg font-semibold">Resto</h1>
                    <p class="text-xs text-gray-500">Management</p>
                </div>
            </a>
            <nav class="hidden md:flex items-center space-x-6 text-sm">
                <a href="categories.php" class="hover:text-orange-500">Categories</a>
                <a href="menuitems.php" class="hover:text-orange-500">Menu Items</a>
                <a href="orders.php" class="hover:text-orange-500">Orders</a>
                <a href="customers.php" class="hover:text-orange-500">Customers</a>
            </nav>
            <a href="#" class="md:inline-block hidden bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 text-sm">New Order</a>
            <button id="mobileMenuBtn" class="md:hidden p-2 rounded hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>


        <div id="mobileNav" class="md:hidden hidden border-t">
            <div class="px-4 py-3 space-y-2">
                <a href="categories.php" class="block hover:text-orange-500">Categories</a>
                <a href="menuitems.php" class="block hover:text-orange-500">Menu Items</a>
                <a href="orders.php" class="block hover:text-orange-500">Orders</a>
                <a href="customers.php" class="block hover:text-orange-500">Customers</a>
            </div>
        </div>
    </header>


    <main class="max-w-6xl mx-auto px-4 py-10">
        <section class="bg-gradient-to-r from-orange-50 to-white rounded-lg p-8 flex flex-col md:flex-row items-center gap-6 shadow-sm">
            <div class="flex-1">
                <h2 class="text-2xl md:text-3xl font-bold">Manage your restaurant with ease</h2>
                <p class="mt-2 text-gray-600">Quick access to categories, menu items, tables, orders and customers. Clean UI for fast operations.</p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="categories.php" class="bg-white border border-orange-200 text-orange-600 px-4 py-2 rounded shadow-sm hover:bg-orange-50">Manage Categories</a>
                    <a href="menuitems.php" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">Manage Menu</a>
                </div>
            </div>
            <div class="w-full md:w-56">
                <img src="images/hey.jpg" alt="Restaurant" class="rounded-lg shadow-md object-cover w-full h-40">
            </div>
        </section>


        <section class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="customers.php" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Customers</h3>
                        <p class="text-sm text-gray-500 mt-1">Manage customer information</p>
                    </div>
                    <div class="text-3xl text-orange-500">👥</div>
                </div>
            </a>

            <a href="employees.php" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Employees</h3>
                        <p class="text-sm text-gray-500 mt-1">Manage employee details</p>
                    </div>
                    <div class="text-3xl text-orange-500">🧑‍🍳</div>
                </div>
            </a>

            <a href="categories.php" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Categories</h3>
                        <p class="text-sm text-gray-500 mt-1">Manage menu categories</p>
                    </div>
                    <div class="text-3xl text-orange-500">📚</div>
                </div>
            </a>

            <a href="menuitems.php" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Menu Items</h3>
                        <p class="text-sm text-gray-500 mt-1">Manage menu items</p>
                    </div>
                    <div class="text-3xl text-orange-500">🍽️</div>
                </div>
            </a>

            <a href="tables.php" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Tables</h3>
                        <p class="text-sm text-gray-500 mt-1">Manage restaurant tables</p>
                    </div>
                    <div class="text-3xl text-orange-500">🪑</div>
                </div>
            </a>

            <a href="orders.php" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Orders</h3>
                        <p class="text-sm text-gray-500 mt-1">Manage orders</p>
                    </div>
                    <div class="text-3xl text-orange-500">🧾</div>
                </div>
            </a>
        </section>
    </main>


    <footer class="bg-white border-t mt-10">
        <div class="max-w-6xl mx-auto px-4 py-6 flex flex-col md:flex-row justify-between items-center text-sm text-gray-600">
            <div>© <?php echo date('Y'); ?> Resto — Simple restaurant management</div>
            <div class="mt-3 md:mt-0">
                <a href="#" class="hover:text-orange-500 mx-2">Privacy</a>
                <a href="#" class="hover:text-orange-500 mx-2">Terms</a>
            </div>
        </div>
    </footer>

    <script>

        document.getElementById('mobileMenuBtn').addEventListener('click', function () {
            var nav = document.getElementById('mobileNav');
            nav.classList.toggle('hidden');
        });
    </script>
</body>
</html>