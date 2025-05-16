<?php
$pageTitle = 'Add Category';
include 'includes/head.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $xml = new DOMDocument();
    $xml->load('../data/categories.xml');

    // Create a new category element
    // Find the last category id and increment by 1
    $categories = $xml->getElementsByTagName('category');
    $lastId = 0;
    foreach ($categories as $category) {
        $idAttr = $category->getAttribute('id');
        if (is_numeric($idAttr) && (int)$idAttr > $lastId) {
            $lastId = (int)$idAttr;
        }
    }
    $id = $lastId + 1;
    $name = $_POST['category_name'];
    $description = $_POST['category_description'];

    $category = $xml->createElement('category');
    $categoryName = $xml->createElement('name', $name);
    $categoryDescription = $xml->createElement('description', $description);

    $category->setAttribute('id', $id);
    $category->appendChild($categoryName);
    $category->appendChild($categoryDescription);

    $xml->getElementsByTagName("categories")[0]->appendChild($category);
    $xml->save('../data/categories.xml');
    echo "<script>alert('Category added successfully!'); window.location.href='categories.php';</script>";
    exit;
}
?>

<div class="container" style="max-width: 600px;">
    <div class="card">
        <div class="card-body">
            <h2 class="mb-4">Add Category</h2>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label for="category_name" class="form-label">Category Name</label>
                    <input type="text" id="category_name" name="category_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="category_description" class="form-label">Description</label>
                    <textarea id="category_description" name="category_description" rows="3" class="form-control"></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-danger">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/foot.php'; ?>