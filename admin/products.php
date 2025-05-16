<?php
$pageTitle = 'Products';
include 'includes/head.php';
?>

<div class="card mb-4">
    <div class="card-body">
        <div class="row d-flex justify-content-between align-items-center mb-3">
            <div class="col-auto">
                <h2 class="text-dark mb-0">Products</h2>
            </div>
            <div class="col-auto">
                <a href="product-add.php" class="btn btn-danger">Add Product</a>
            </div>
        </div>
        <hr>
        <table id="myTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Category</th>
                    <th>Tags</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $xml = new DOMDocument();
                $xml->load('../data/games.xml');

                $products = $xml->getElementsByTagName('game');

                foreach ($products as $product) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($product->getAttribute('id')) . '</td>';
                    echo '<td>' . htmlspecialchars($product->getElementsByTagName('title')[0]->nodeValue) . '</td>';
                    echo '<td>' . htmlspecialchars($product->getElementsByTagName('description')[0]->nodeValue) . '</td>';
                    echo '<td>' . htmlspecialchars($product->getElementsByTagName('price')[0]->nodeValue) . '</td>';
                    echo '<td>' . htmlspecialchars($product->getElementsByTagName('quantity')[0]->nodeValue) . '</td>';
                    echo '<td>' . htmlspecialchars($product->getElementsByTagName('category')[0]->nodeValue) . '</td>';
                    echo '<td>' . htmlspecialchars($product->getElementsByTagName('tag')[0]->nodeValue) . '</td>';
                    $image = $product->getElementsByTagName('image')[0]->nodeValue ?? '';
                    echo '<td>';
                    if ($image) {
                        echo '<img src="../assets/images/cover/' . htmlspecialchars($image) . '" alt="Product Image" style="max-width:60px;max-height:60px;">';
                    }
                    echo '</td>';
                    echo '<td>';
                    echo '<div style="display: flex; flex-direction: column; gap: 5px;">';
                    echo '<a href="product-edit.php?id=' . htmlspecialchars($product->getAttribute('id')) . '" class="btn btn-sm btn-success">Edit</a>';
                    echo '<a href="product-delete.php?id=' . htmlspecialchars($product->getAttribute('id')) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this product?\');">Delete</a>';
                    echo '</div>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/foot.php'; ?>