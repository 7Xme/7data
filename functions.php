<?php
function displayImage($path, $alt, $width = null, $height = null) {
    if (!empty($path) && file_exists($path)) {
        $widthAttr = ($width) ? "width='" . htmlspecialchars($width) . "'" : "";
        $heightAttr = ($height) ? "height='" . htmlspecialchars($height) . "'" : "";
        echo "<img src='" . htmlspecialchars($path) . "' alt='" . htmlspecialchars($alt) . "' " . $widthAttr . " " . $heightAttr . ">";
    } else {
        echo "<img src='images/placeholder.png' alt='صورة غير متوفرة' width='100'>";
    }
}
?>