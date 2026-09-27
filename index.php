<?php
require_once 'config/database.php';
require_once 'includes/csrf.php';

$search = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :query1 OR category LIKE :query2 ORDER BY id DESC");
    $stmt->execute([
        'query1' => "%$search%",
        'query2' => "%$search%"
    ]);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
}

$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Florist Product Manager</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Florist Product Manager</h1>
            <a href="create.php" class="btn">+ Tambah Bunga</a>
        </header>

        <form method="GET" class="search-form">
            <input type="text" name="q" value="<?= sanitize($search) ?>" placeholder="Cari nama atau kategori bunga...">
            <button type="submit" class="btn">Cari</button>
        </form>

        <div class="product-grid">
            <?php if (empty($products)): ?>
                <p>Tidak ada produk ditemukan.</p>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                    <div class="card">
                        <div>
                            <div class="card-title"><?= sanitize($p['name']) ?></div>
                            <div class="card-info">
                                <p><strong>Kategori:</strong> <?= sanitize($p['category']) ?></p>
                                <p><strong>Harga:</strong> Rp <?= number_format($p['price'], 0, ',', '.') ?></p>
                                <p><strong>Stok:</strong> <?= sanitize($p['stock']) ?></p>
                            </div>
                        </div>
                        <div class="card-actions">
                            <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-warning">Edit</a>
                            <form action="delete.php" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>