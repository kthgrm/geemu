<?php
$xml = new DOMDocument();
$xml->load('../data/categories.xml');
$categories = $xml->getElementsByTagName('category');

$id = $_GET['id'];

foreach ($categories as $category) {
    if ($category->getAttribute('id') == $id) {
        $xml->documentElement->removeChild($category);
        $xml->save('../data/categories.xml');
        echo '<script>alert("Category deleted successfully!"); window.location.href="categories.php";</script>';
        break;
    }
}
