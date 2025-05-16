<?php
$pageTitle = 'Add Game';
include 'includes/head.php';

// Handle form submission
$error = '';
$success = '';
$id = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Load XML
    $xml = new DOMDocument();
    $xml->preserveWhiteSpace = false;
    $xml->formatOutput = true;
    $xmlPath = '../data/games.xml';
    if (!file_exists($xmlPath)) {
        $error = 'Games data file not found.';
    } else {
        $xml->load($xmlPath);

        // Find the last game id and increment by 1
        $games = $xml->getElementsByTagName('game');
        $lastId = 0;
        foreach ($games as $game) {
            $idAttr = $game->getAttribute('id');
            if (is_numeric($idAttr) && (int)$idAttr > $lastId) {
                $lastId = (int)$idAttr;
            }
        }
        $id = $lastId + 1;

        // Get form data
        $title = trim($_POST['product_title'] ?? '');
        $description = trim($_POST['product_description'] ?? '');
        $price = trim($_POST['product_price'] ?? '');
        $quantity = trim($_POST['product_quantity'] ?? '');
        $category = trim($_POST['product_category'] ?? '');
        $tags = trim($_POST['product_tags'] ?? '');

        // Validate required fields
        if ($title === '' || $price === '' || $quantity === '' || $category === '') {
            $error = 'Please fill in all required fields.';
        } else {
            // Handle image upload
            $imageName = '';
            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../assets/images/cover/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $tmpName = $_FILES['product_image']['tmp_name'];
                $originalName = basename($_FILES['product_image']['name']);
                $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (in_array($ext, $allowed)) {
                    $imageName = uniqid('game_', true) . '.' . $ext;
                    move_uploaded_file($tmpName, $uploadDir . $imageName);
                } else {
                    $error = 'Invalid image file type.';
                }
            }

            if ($error === '') {
                // Create new game element
                $game = $xml->createElement('game');
                $game->setAttribute('id', $id);

                $game->appendChild($xml->createElement('title', htmlspecialchars($title)));
                $game->appendChild($xml->createElement('description', htmlspecialchars($description)));
                $game->appendChild($xml->createElement('price', htmlspecialchars($price)));
                $game->appendChild($xml->createElement('quantity', htmlspecialchars($quantity)));
                $game->appendChild($xml->createElement('category', htmlspecialchars($category)));
                $game->appendChild($xml->createElement('tag', htmlspecialchars($tags)));
                $game->appendChild($xml->createElement('image', htmlspecialchars($imageName)));

                $gamesNode = $xml->getElementsByTagName('games')->item(0);
                if ($gamesNode) {
                    $gamesNode->appendChild($game);
                    $xml->save($xmlPath);
                    $success = 'Game added successfully!';
                    // Optionally redirect:
                    // header('Location: products.php');
                    // exit;
                } else {
                    $error = 'Invalid XML structure.';
                }
            }
        }
    }
} else {
    // Set next ID for display
    $xmlPath = '../data/games.xml';
    if (file_exists($xmlPath)) {
        $xml = new DOMDocument();
        $xml->load($xmlPath);
        $games = $xml->getElementsByTagName('game');
        $lastId = 0;
        foreach ($games as $game) {
            $idAttr = $game->getAttribute('id');
            if (is_numeric($idAttr) && (int)$idAttr > $lastId) {
                $lastId = (int)$idAttr;
            }
        }
        $id = $lastId + 1;
    } else {
        $id = 1;
    }
}
?>

<div class="container" style="max-width: 600px;">
    <div class="card">
        <div class="card-body">
            <h2 class="mb-4">Add Game</h2>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="product_id" class="form-label">ID</label>
                    <input type="number" id="product_id" name="product_id" class="form-control" value="<?php echo isset($id) ? htmlspecialchars($id) : ''; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="product_title" class="form-label">Title</label>
                    <input type="text" id="product_title" name="product_title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="product_description" class="form-label">Description</label>
                    <textarea id="product_description" name="product_description" rows="3" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label for="product_price" class="form-label">Price</label>
                    <input type="number" step="0.01" id="product_price" name="product_price" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="product_quantity" class="form-label">Quantity</label>
                    <input type="number" id="product_quantity" name="product_quantity" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="product_category" class="form-label">Category</label>
                    <?php
                    // Load categories from XML
                    $categoriesXmlPath = '../data/categories.xml';
                    $categories = [];
                    if (file_exists($categoriesXmlPath)) {
                        $categoriesXml = new DOMDocument();
                        $categoriesXml->load($categoriesXmlPath);
                        foreach ($categoriesXml->getElementsByTagName('category') as $cat) {
                            $catName = $cat->getElementsByTagName('name')->item(0);
                            if ($catName) {
                                $categories[] = $catName->nodeValue;
                            }
                        }
                    }
                    ?>
                    <select id="product_category" name="product_category" class="form-control" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="product_tags" class="form-label">Tags (comma separated)</label>
                    <input type="text" id="product_tags" name="product_tags" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="product_image" class="form-label">Image</label>
                    <input type="file" id="product_image" name="product_image" class="form-control" accept="image/*">
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-danger">Add Game</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/foot.php'; ?>