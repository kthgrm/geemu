<?php
$pageTitle = 'Edit Category';
include 'includes/head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $xml = new DOMDocument();
    $xml->load('../data/categories.xml');
    $categories = $xml->getElementsByTagName('category');

    $categoryId = $_GET['id'];
    $name = $_POST['category_name'];
    $description = $_POST['category_description'];

    foreach ($categories as $category) {
        if ($category->getAttribute('id') == $categoryId) {

            $categoryName = $category->getElementsByTagName('name')[0]->nodeValue;
            $categoryDescription = $category->getElementsByTagName('description')[0]->nodeValue;

            $newNode = $xml->createElement('category');
            $newNode->setAttribute('id', $categoryId);
            $newNode->appendChild($xml->createElement('name', $name));
            $newNode->appendChild($xml->createElement('description', $description));
            $xml->getElementsByTagName("categories")[0]->replaceChild($newNode, $category);
            $xml->save('../data/categories.xml');
            echo "<script>alert('Category updated successfully!'); window.location.href='categories.php';</script>";
            exit;
            break;
        }
    }
}

?>

<div class="container" style="max-width: 600px;">
    <div class="card">
        <div class="card-body">
            <h2 class="mb-4">Edit Category</h2>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label for="category_name" class="form-label">Category Name</label>
                    <?php
                    // Load current category data for form population
                    $xml = new DOMDocument();
                    $xml->load('../data/categories.xml');
                    $categoryId = $_GET['id'];
                    $categories = $xml->getElementsByTagName('category');
                    $currentName = '';
                    $currentDescription = '';
                    foreach ($categories as $cat) {
                        if ($cat->getAttribute('id') == $categoryId) {
                            $currentName = $cat->getElementsByTagName('name')[0]->nodeValue;
                            $currentDescription = $cat->getElementsByTagName('description')[0]->nodeValue;
                            break;
                        }
                    }
                    ?>
                    <input type="text" id="category_name" name="category_name" class="form-control" required value="<?php echo isset($_POST['category_name']) ? htmlspecialchars($_POST['category_name']) : htmlspecialchars($currentName); ?>">
                </div>
                <div class="mb-3">
                    <label for="category_description" class="form-label">Description</label>
                    <textarea id="category_description" name="category_description" rows="3" class="form-control" required><?php echo isset($_POST['category_description']) ? htmlspecialchars($_POST['category_description']) : htmlspecialchars($currentDescription); ?></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-danger">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/foot.php'; ?>