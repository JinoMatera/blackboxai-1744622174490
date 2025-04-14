<?php
require_once __DIR__.'/../config/db.php';
require_once __DIR__.'/../includes/product.php';
session_start();

$product = new Product();
$productData = $product->getProductById($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_GET['id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $category = trim($_POST['category']);
    
    // Handle image upload if new image is provided
    $image = $productData['image'];
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/$image");
    }

    $result = $product->updateProduct($id, $name, $description, $price, $image, $category);

    if ($result) {
        $_SESSION['message'] = 'Product updated successfully!';
        header('Location: products.php');
        exit();
    } else {
        $_SESSION['error'] = 'Failed to update product.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - eCommerce Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-12">
        <h2 class="text-3xl font-bold mb-6">Edit Product</h2>
        <form action="edit_product.php?id=<?= $_GET['id'] ?>" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                <input type="text" name="name" id="name" value="<?= $productData['name'] ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?= $productData['description'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" name="price" id="price" value="<?= $productData['price'] ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <input type="text" name="category" id="category" value="<?= $productData['category'] ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Current Image</label>
                <img src="../uploads/<?= $productData['image'] ?>" alt="<?= $productData['name'] ?>" class="h-20 w-20 object-cover rounded">
                <label for="image" class="block text-sm font-medium text-gray-700 mt-2">New Image (Leave blank to keep current)</label>
                <input type="file" name="image" id="image" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">Update Product</button>
        </form>
    </div>
</body>
</html>
