<?php
require_once 'config/database.php';
require_once 'includes/csrf.php';

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
        $errors[] = "Harga harus berupa angka dan tidak boleh negatif.";
    }

    if (!is_numeric($stock) || $stock < 0) {
        $errors[] = "Stok harus berupa angka dan tidak boleh negatif.";
    }

    if (empty($errors)) {
        $stmtCheck = $pdo->prepare("SELECT id FROM products WHERE name = :name");
        $stmtCheck->execute(['name' => $name]);
        if ($stmtCheck->fetch()) {
            $errors[] = "Nama produk sudah digunakan. Gunakan nama yang unik!";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, category, price, stock) VALUES (:name, :category, :price, :stock)");
        $stmt->execute([
            'name' => $name,
            'category' => $category,
            'price' => $price,
            'stock' => $stock
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
    <title>Tambah Bunga Baru</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Tambah Bunga Baru</h1>
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
                <label>Nama Produk (Min. 3 Karakter & Unik)</label>
                <input type="text" name="name" required value="<?= sanitize($_POST['name'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="category" required value="<?= sanitize($_POST['category'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Harga (Tidak Boleh Negatif)</label>
                <input type="number" step="0.01" name="price" required value="<?= sanitize($_POST['price'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Stok (Tidak Boleh Negatif)</label>
                <input type="number" name="stock" required value="<?= sanitize($_POST['stock'] ?? '') ?>">
            </div>
            <button type="submit" class="btn">Simpan Produk</button>
            <a href="index.php" class="btn btn-warning">Batal</a>
        </form>
    </div>
</body>
</html>