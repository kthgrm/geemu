<?php
$pageTitle = 'Edit Category';
include 'includes/head.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $xml = new DOMDocument();
    $xml->preserveWhiteSpace = false;
    $xml->formatOutput = true;
    $xml->load('../data/games.xml');
    $productId = $_GET['id'];
    $products = $xml->getElementsByTagName('game');

    $title = $_POST['product_name'];
    $description = $_POST['product_description'];
    $price = $_POST['product_price'];
    $quantity = $_POST['product_quantity'];
    $category = $_POST['product_category'];
    $tags = $_POST['product_tag'];

    // Handle image upload
    // Load current image before handling upload
    $currentImage = '';
    foreach ($products as $prod) {
        if ($prod->getAttribute('id') == $productId) {
            $currentImage = $prod->getElementsByTagName('image')[0]->nodeValue;
            break;
        }
    }
    $image = '';
    if (isset($_FILES['product_image_upload']) && $_FILES['product_image_upload']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/images/cover/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileTmp = $_FILES['product_image_upload']['tmp_name'];
        $fileName = basename($_FILES['product_image_upload']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($fileExt, $allowed)) {
            $newFileName = uniqid('img_', true) . '.' . $fileExt;
            $destPath = $uploadDir . $newFileName;
            if (move_uploaded_file($fileTmp, $destPath)) {
                // Unlink previous image if it exists and is not empty
                if (!empty($currentImage)) {
                    $oldImagePath = $uploadDir . $currentImage;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $image = $newFileName;
            } else {
                $error = 'Failed to upload image.';
            }
        } else {
            $error = 'Invalid image file type.';
        }
    } else {
        // No new upload, keep existing image
        $image = $currentImage;
    }

    // Only proceed if no upload error
    if (empty($error)) {
        $found = false;
        foreach ($products as $prod) {
            if ($prod->getAttribute('id') == $productId) {
                // Update fields
                $prod->getElementsByTagName('title')[0]->nodeValue = $title;
                $prod->getElementsByTagName('description')[0]->nodeValue = $description;
                $prod->getElementsByTagName('price')[0]->nodeValue = $price;
                $prod->getElementsByTagName('quantity')[0]->nodeValue = $quantity;
                $prod->getElementsByTagName('category')[0]->nodeValue = $category;
                // Save tags as comma-separated string
                $prod->getElementsByTagName('tag')[0]->nodeValue = $tags;
                $prod->getElementsByTagName('image')[0]->nodeValue = $image;
                $found = true;
                break;
            }
        }
        if ($found) {
            $xml->save('../data/games.xml');
            $success = 'Product updated successfully!';
            echo "<script>alert('Product updated successfully!'); window.location.href='products.php';</script>";
            exit;
        } else {
            $error = 'Product not found.';
        }
    }
}

// Load current product data for form population from games.xml
$xml = new DOMDocument();
$xml->load('../data/games.xml');
$productId = $_GET['id'];
$products = $xml->getElementsByTagName('game');
$currentName = '';
$currentDescription = '';
$currentPrice = '';
$currentQuantity = '';
$currentCategory = '';
$currentTags = '';
$currentImage = '';
foreach ($products as $prod) {
    if ($prod->getAttribute('id') == $productId) {
        $currentName = $prod->getElementsByTagName('title')[0]->nodeValue;
        $currentDescription = $prod->getElementsByTagName('description')[0]->nodeValue;
        $currentPrice = $prod->getElementsByTagName('price')[0]->nodeValue;
        $currentQuantity = $prod->getElementsByTagName('quantity')[0]->nodeValue;
        $currentCategory = $prod->getElementsByTagName('category')[0]->nodeValue;
        $currentTags = $prod->getElementsByTagName('tag')[0]->nodeValue;
        $currentImage = $prod->getElementsByTagName('image')[0]->nodeValue;
        break;
    }
}
?>

<div class="container" style="max-width: 600px;">
    <div class="card">
        <div class="card-body">
            <h2 class="mb-4">Edit Product</h2>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" id="product_name" name="product_name" class="form-control" required value="<?php echo isset($_POST['product_name']) ? htmlspecialchars($_POST['product_name']) : htmlspecialchars($currentName); ?>">
                </div>
                <div class="mb-3">
                    <label for="product_description" class="form-label">Description</label>
                    <textarea id="product_description" name="product_description" rows="3" class="form-control" required><?php echo isset($_POST['product_description']) ? htmlspecialchars($_POST['product_description']) : htmlspecialchars($currentDescription); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="product_price" class="form-label">Price</label>
                    <input type="number" step="0.01" id="product_price" name="product_price" class="form-control" required value="<?php echo isset($_POST['product_price']) ? htmlspecialchars($_POST['product_price']) : htmlspecialchars($currentPrice); ?>">
                </div>
                <div class="mb-3">
                    <label for="product_quantity" class="form-label">Quantity</label>
                    <input type="number" id="product_quantity" name="product_quantity" class="form-control" required value="<?php echo isset($_POST['product_quantity']) ? htmlspecialchars($_POST['product_quantity']) : htmlspecialchars($currentQuantity); ?>">
                </div>
                <div class="mb-3">
                    <label for="product_category" class="form-label">Category</label>
                    <select id="product_category" name="product_category" class="form-select" required>
                        <?php
                        // Load categories for dropdown
                        $catXml = new DOMDocument();
                        $catXml->load('../data/categories.xml');
                        $categories = $catXml->getElementsByTagName('category');
                        foreach ($categories as $cat) {
                            $catId = $cat->getAttribute('id');
                            $catName = $cat->getElementsByTagName('name')[0]->nodeValue;
                            $selected = (isset($_POST['product_category']) && $_POST['product_category'] == $catId) || (!isset($_POST['product_category']) && $currentCategory == $catId) ? 'selected' : '';
                            echo "<option value=\"" . htmlspecialchars($catName) . "\" $selected>" . htmlspecialchars($catName) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="product_tags" class="form-label">Tags</label>
                    <input type="text" id="product_tag" name="product_tag" class="form-control" value="<?php echo isset($_POST['product_tag']) ? htmlspecialchars($_POST['product_tag']) : htmlspecialchars($currentTags); ?>">
                </div>
                <div class="mb-3">
                    <label for="product_image" class="form-label">Image</label>
                    <?php if (!empty($currentImage)): ?>
                        <div class="mb-2">
                            <img src="../assets/images/cover/<?php echo htmlspecialchars($currentImage); ?>" alt="Product Image" style="max-width: 150px; max-height: 150px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="product_image_upload" name="product_image_upload" class="form-control">
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-danger">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/foot.php'; ?>