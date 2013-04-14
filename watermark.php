<?php

/**
 *
 * PHP Image Watermark
 * Copyright (c) 2013 André Catita http://andrecatita.com
 * http://github.com/andreCatita/
 *
 * GNU General Public License, version 2 (GPL-2.0)
 * http://opensource.org/licenses/GPL-2.0
 *
 */

// Configuration
$options = array(
    'WATERMARK_IMAGE'	    => 'watermark.png',		// The location and name of the watermark  (If using ready a ready .PNG or .GIF set WATERMARK_IS_READY to TRUE)
    'WATERMARK_OPACITY'	    => '45',			// The opacity the image will be merged with, this doesn't apply to WATERMARK_IS_READY
    'WATERMARK_QUALITY'	    => '100',			// Image Quality - 0 to 100 - Higher is better
    'WATERMARK_IS_READY'	    => FALSE,		// If your watermark image is already a .png or .gif with transparency set, set this to TRUE
    'WATERMARK_PLACE'	    => 'BOTTOM_RIGHT',	// This value accepts -> BOTTOM_RIGHT, BOTTOM_LEFT, TOP_LEFT, TOP_RIGHT, CENTER, CENTER_LEFT, CENTER_RIGHT
    'WATERMARK_MARGIN'	    => '10',
);

function createImageFromFile($full_or_relative_path_to_image) {

    list(,, $image_type) = getimagesize($full_or_relative_path_to_image);

    if ($image_type === NULL) {
	return null;
    }

    switch ($image_type) {
	case IMAGETYPE_GIF:
	    return imagecreatefromgif($full_or_relative_path_to_image);
	    break;
	case IMAGETYPE_JPEG:
	    return imagecreatefromjpeg($full_or_relative_path_to_image);
	    break;
	case IMAGETYPE_PNG:
	    return imagecreatefrompng($full_or_relative_path_to_image);
	    break;
	default:
	    return null;
	    break;
    }
}

// Overwrite Defaults
$original_image	= (isset($_GET['image'])	? $_GET['image']	    : null);
$watermark_image	= (isset($_GET['watermark'])	? $_GET['watermark']	    : $options['WATERMARK_IMAGE']);
$place		= (isset($_GET['place'])	? $_GET['place']	    : $options['WATERMARK_PLACE']);
$margin		= (isset($_GET['margin'])	? $_GET['margin']	    : $options['WATERMARK_MARGIN']);
$quality		= (isset($_GET['quality'])	? $_GET['quality']	    : $options['WATERMARK_QUALITY']);
$is_watermark_ready	= (isset($_GET['is_ready'])	? ($_GET['is_ready'] == 'true' ? TRUE : FALSE) : $options['WATERMARK_IS_READY']);

// Load Image & Watermark
$image = createImageFromFile($original_image);
$watermark = createImageFromFile($watermark_image);
if (!$image || !$watermark)
    exit;

/*
 *  Math for watermark positions
 * - to avoid use down the road of php: imagesx and imagesy
 * - which can be used to obtain both height and width of the $image and $watermark element
 */

$watermark_width	= imagesx($watermark);
$watermark_height	= imagesy($watermark);

$image_width	= imagesx($image);
$image_height	= imagesy($image);

$watermark_pos_x	= $image_width	-   $watermark_width;
$watermark_pos_y	= $image_height	-   $watermark_height;

switch ($place) {
    case 'BOTTOM_LEFT':
	if ($is_watermark_ready) {
	    imagecopy($image, $watermark, 0 + $margin, $watermark_pos_y - $margin, 0, 0, $watermark_width, $watermark_height);
	} else {
	    imagecopymerge($image, $watermark, 0 + $margin, $watermark_pos_y - $margin, 0, 0, $watermark_width, $watermark_height, $options['WATERMARK_OPACITY']);
	}
	break;
    case 'BOTTOM_CENTER':
	if ($is_watermark_ready) {
	    imagecopy($image, $watermark, ($watermark_pos_x / 2), $watermark_pos_y - $margin, 0, 0, $watermark_width, $watermark_height);
	} else {
	    imagecopymerge($image, $watermark, $watermark_pos_x - $margin, $watermark_pos_y - $margin, 0, 0, $watermark_width, $watermark_height, $options['WATERMARK_OPACITY']);
	}
	break;	
    case 'BOTTOM_RIGHT':
	if ($is_watermark_ready) {
	    imagecopy($image, $watermark, $watermark_pos_x - $margin, $watermark_pos_y - $margin, 0, 0, $watermark_width, $watermark_height);
	} else {
	    imagecopymerge($image, $watermark, $watermark_pos_x - $margin, $watermark_pos_y - $margin, 0, 0, $watermark_width, $watermark_height, $options['WATERMARK_OPACITY']);
	}
	break;
    case 'TOP_LEFT':
	if ($is_watermark_ready) {
	    imagecopy($image, $watermark, 0 + $margin, 0 + $margin, 0, 0, $watermark_width, $watermark_height);
	} else {
	    imagecopymerge($image, $watermark, 0 + $margin, 0 + $margin, 0, 0, $watermark_width, $watermark_height, $options['WATERMARK_OPACITY']);
	}
	break;
    case 'TOP_CENTER':
	if ($is_watermark_ready) {
	    imagecopy($image, $watermark, ($watermark_pos_x / 2), 0 + $margin, 0, 0, $watermark_width, $watermark_height);
	} else {
	    imagecopymerge($image, $watermark, $watermark_pos_x - $margin, 0 + $margin, 0, 0, $watermark_width, $watermark_height, $options['WATERMARK_OPACITY']);
	}
	break;	
    case 'TOP_RIGHT':
	if ($is_watermark_ready) {
	    imagecopy($image, $watermark, $watermark_pos_x - $margin, 0 + $margin, 0, 0, $watermark_width, $watermark_height);
	} else {
	    imagecopymerge($image, $watermark, $watermark_pos_x - $margin, 0 + $margin, 0, 0, $watermark_width, $watermark_height, $options['WATERMARK_OPACITY']);
	}
	break;
    case 'CENTER':
	if ($is_watermark_ready) {
	    imagecopy($image, $watermark, ($watermark_pos_x / 2), ($watermark_pos_y / 2), 0, 0, $watermark_width, $watermark_height);
	} else {
	    imagecopymerge($image, $watermark, ($watermark_pos_x / 2), ($watermark_pos_y / 2), 0, 0, $watermark_width, $options['WATERMARK_OPACITY']);
	}
	break;
    case 'CENTER_LEFT':
	if ($is_watermark_ready) {
	    imagecopy($image, $watermark, 0 + $margin, ($watermark_pos_y / 2), 0, 0, $watermark_width, $watermark_height);
	} else {
	    imagecopymerge($image, $watermark, 0 + $margin, ($watermark_pos_y / 2), 0, 0, $watermark_width, $watermark_height, $options['WATERMARK_OPACITY']);
	}
	break;
    case 'CENTER_RIGHT':
	if ($is_watermark_ready) {
	    imagecopy($image, $watermark, $watermark_pos_x - $margin, ($watermark_pos_y / 2), 0, 0, $watermark_width, $watermark_height);
	} else {
	    imagecopymerge($image, $watermark, $watermark_pos_x - $margin, ($watermark_pos_y / 2), 0, 0, $watermark_width, $options['WATERMARK_OPACITY']);
	}
	break;
}

// Output JPEG
header('Content-Type: image/jpeg');
imagejpeg($image, null, $quality);

// Clear Memory
imagedestroy($image);
imagedestroy($watermark);
?>