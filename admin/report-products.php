<?php
$pageTitle = 'Products Report';
include 'includes/head.php';

// Load XML
$xml = new DOMDocument();
$xml->load('../data/games.xml');
$products = $xml->getElementsByTagName('game');

// Get filter inputs
$minStock = isset($_GET['min_stock']) && $_GET['min_stock'] !== '' && is_numeric($_GET['min_stock']) ? (int)$_GET['min_stock'] : null;
$maxStock = isset($_GET['max_stock']) && $_GET['max_stock'] !== '' && is_numeric($_GET['max_stock']) ? (int)$_GET['max_stock'] : null;
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
?>

<style>
    @media print {

        .print-btn,
        .filter-form {
            display: none !important;
        }
    }
</style>

<div class="container">
    <div class="card mt-4">
        <div class="card-body">
            <h2 class="text-center mb-4">Products Report</h2>

            <!-- Filter Form -->
            <form method="GET" class="filter-form mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="min_stock" class="form-label">Min Stock</label>
                        <input type="number" name="min_stock" id="min_stock" class="form-control" placeholder="Min Stock" value="<?= $minStock !== null ? htmlspecialchars($minStock) : '' ?>" min="0" step="1">
                    </div>
                    <div class="col-md-3">
                        <label for="max_stock" class="form-label">Max Stock</label>
                        <input type="number" name="max_stock" id="max_stock" class="form-control" placeholder="Max Stock" value="<?= $maxStock !== null ? htmlspecialchars($maxStock) : '' ?>" min="0" step="1">
                    </div>
                    <div class="col-md-3">
                        <label for="category" class="form-label">Category</label>
                        <?php
                        // Load categories from categories.xml
                        $categoriesXml = new DOMDocument();
                        $categoriesXml->load('../data/categories.xml');
                        $categoryNodes = $categoriesXml->getElementsByTagName('category');
                        ?>
                        <select name="category" id="category" class="form-control">
                            <option value="">All Categories</option>
                            <?php
                            foreach ($categoryNodes as $catNode) {
                                $catName = $catNode->getElementsByTagName('name')->length ? $catNode->getElementsByTagName('name')[0]->nodeValue : '';
                                $selected = ($category !== '' && $category === $catName) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($catName) . '" ' . $selected . '>' . htmlspecialchars($catName) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-success flex-fill">Filter</button>
                        <a href="report-products.php" class="btn btn-secondary flex-fill">Reset</a>
                    </div>
                </div>
            </form>

            <button class="btn btn-primary print-btn mb-4" onclick="printTable()">Print Report</button>
            <div id="print-area">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class=" table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($products as $product) {
                                $id = $product->getAttribute('id');
                                $name = $product->getElementsByTagName('title')->length ? $product->getElementsByTagName('title')[0]->nodeValue : '';
                                $cat = $product->getElementsByTagName('category')->length ? $product->getElementsByTagName('category')[0]->nodeValue : '';
                                $stock = $product->getElementsByTagName('quantity')->length ? (int)$product->getElementsByTagName('quantity')[0]->nodeValue : 0;
                                $price = $product->getElementsByTagName('price')->length ? $product->getElementsByTagName('price')[0]->nodeValue : '';
                                // No added date in games.xml, so skip date filtering

                                // Apply filters
                                if ($minStock !== null && $stock < $minStock) {
                                    continue;
                                }
                                if ($maxStock !== null && $stock > $maxStock) {
                                    continue;
                                }
                                if ($category !== '' && stripos($cat, $category) === false) {
                                    continue;
                                }

                                echo "<tr>
                                    <td>" . htmlspecialchars($id) . "</td>
                                    <td>" . htmlspecialchars($name) . "</td>
                                    <td>" . htmlspecialchars($cat) . "</td>
                                    <td>" . htmlspecialchars($stock) . "</td>
                                    <td>" . htmlspecialchars($price) . "</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printTable() {
        const printContents = document.getElementById('print-area').innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload();
    }
</script>
<?php include 'includes/foot.php'; ?>