<?php
require_once 'config/database.php';
require_once 'includes/csrf.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    die("Produk tidak ditemukan.");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die("Validasi CSRF Gagal.");
    }

    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';

    if (strlen($name) < 3) {
        $errors[] = "Nama produk minimal harus 3 karakter.";
    }
    if (!is_numeric($price) || $price < 0) {
        $errors[] = "Harga tidak boleh negatif.";
    }
    if (!is_numeric($stock) || $stock < 0) {
        $errors[] = "Stok tidak boleh negatif.";
    }

    if (empty($errors)) {
        $stmtCheck = $pdo->prepare("SELECT id FROM products WHERE name = :name AND id != :id");
        $stmtCheck->execute(['name' => $name, 'id' => $id]);
        if ($stmtCheck->fetch()) {
            $errors[] = "Nama produk sudah digunakan oleh produk lain.";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE products SET name = :name, category = :category, price = :price, stock = :stock WHERE id = :id");
        $stmt->execute([
            'name' => $name,
            'category' => $category,
            'price' => $price,
            'stock' => $stock,
            'id' => $id
        ]);

        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bunga</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Bunga</h1>
        <?php if (!empty($errors)): ?>
            <div class="alert">
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= sanitize($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="name" required value="<?= sanitize($_POST['name'] ?? $product['name']) ?>">
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="category" required value="<?= sanitize($_POST['category'] ?? $product['category']) ?>">
            </div>
            <div class="form-group">
                <label>Harga</label>
                <input type="number" step="0.01" name="price" required value="<?= sanitize($_POST['price'] ?? $product['price']) ?>">
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stock" required value="<?= sanitize($_POST['stock'] ?? $product['stock']) ?>">
            </div>
            <button type="submit" class="btn">Update Produk</button>
            <a href="index.php" class="btn btn-warning">Batal</a>
        </form>
    </div>
</body>
</html>