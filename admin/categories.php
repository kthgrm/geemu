<?php
$pageTitle = 'Categories';
include 'includes/head.php';
?>

<div class="card mb-4">
    <div class="card-body">
        <div class="row d-flex justify-content-between align-items-center mb-3">
            <div class="col-auto">
                <h2 class="text-dark mb-0">Categories</h2>
            </div>
            <div class="col-auto">
                <a href="category-add.php" class="btn btn-danger">Add Category</a>
            </div>
        </div>
        <hr>
        <table id="myTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Category Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $xml = new DOMDocument();
                $xml->load('../data/categories.xml');

                $categories = $xml->getElementsByTagName('category');

                foreach ($categories as $category) {
                    echo '<tr>';
                    echo '<td>' . $category->getAttribute('id') . '</td>';
                    echo '<td>' . $category->getElementsByTagName('name')[0]->nodeValue . '</td>';
                    echo '<td>' . $category->getElementsByTagName('description')[0]->nodeValue . '</td>';
                    echo '<td>';
                    echo '<a href="category-edit.php?id=' . htmlspecialchars($category->getAttribute('id')) . '" class="btn btn-sm btn-success mx-1">Edit</a>';
                    echo '<a href="category-delete.php?id=' . htmlspecialchars($category->getAttribute('id')) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this category?\');">Delete</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/foot.php'; ?>