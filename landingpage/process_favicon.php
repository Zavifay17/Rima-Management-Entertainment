<?php

$dir = 'C:\\Users\\LENOVO\\.gemini\\antigravity-ide\\brain\\tempmediaStorage\\';
$files = glob($dir . '*.png');
if (!$files) {
    $files = glob($dir . '*.jpg');
}
if (!$files) {
    die("No images found\n");
}

usort($files, function($a, $b) {
    return filemtime($b) - filemtime($a);
});
$latest_image = $files[0];
echo "Processing: $latest_image\n";

$ext = strtolower(pathinfo($latest_image, PATHINFO_EXTENSION));
if ($ext == 'png') {
    $img = imagecreatefrompng($latest_image);
} else {
    $img = imagecreatefromjpeg($latest_image);
}

// Convert white to transparent
$width = imagesx($img);
$height = imagesy($img);

$out = imagecreatetruecolor($width, $height);
imagealphablending($out, false);
imagesavealpha($out, true);

$transparent = imagecolorallocatealpha($out, 255, 255, 255, 127);
imagefill($out, 0, 0, $transparent);

for ($x = 0; $x < $width; $x++) {
    for ($y = 0; $y < $height; $y++) {
        $rgb = imagecolorat($img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        
        // If it's close to white, make it transparent
        if ($r > 240 && $g > 240 && $b > 240) {
            imagesetpixel($out, $x, $y, $transparent);
        } else {
            $color = imagecolorallocatealpha($out, $r, $g, $b, 0);
            imagesetpixel($out, $x, $y, $color);
        }
    }
}

imagepng($out, __DIR__ . '/public/favicon.png');
echo "Saved to public/favicon.png\n";
