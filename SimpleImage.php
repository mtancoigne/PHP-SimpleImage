<?php

/**
 * File: SimpleImage.php
 *
 * This class perform very basic images manipulation.
 *
 * ---------------------------------------------------------------------------
 * Modified by Manuel Tancoigne <m.tancoigne@gmail.com>
 * Date: 09/2013
 * ---
 * Original Author: Simon Jarvis
 * Copyright: 2006 Simon Jarvis
 * Original Date: 08/11/06
 * Original Link:
 *   http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details:
 * http://www.gnu.org/licenses/gpl.html
 *
 * @category Image_Manipulation
 * @author   Simon Jarvis, Manuel Tancoigne <m.tancoigne@gmail.com>
 * @license  GPL v2
 * @link     https://github.com/mtancoigne/PHP-SimpleImage
 *
 */
class SimpleImage
{

    /**
     * Original image
     */
    public $image;

    /**
     * Modified image
     */
    public $currentImage;

    /**
     * Image type
     */
    public $image_type;

    /**
     * Log of actions
     */
    protected $log = array();

    /**
     * Loads an image
     *
     * @param string $filename Image to load
     *
     * @return boolean True in case of success, false on failure.
     */
    public function load($filename)
    {
        if (!$image_info = getimagesize($filename)) {
            return false;
        }
        $this->image_type = $image_info[2];
        if ($this->image_type == IMAGETYPE_JPEG) {
            if (!$this->image = imagecreatefromjpeg($filename)) {
                return false;
            }
        } elseif ($this->image_type == IMAGETYPE_GIF) {
            if (!$this->image = imagecreatefromgif($filename)) {
                return false;
            }
        } elseif ($this->image_type == IMAGETYPE_PNG) {
            if (!$this->image = imagecreatefrompng($filename)) {
                return false;
            }
        }
        $this->currentImage = $this->image;
        // Logging this
        $this->log('Image loaded (' . $filename . ')');
        return true;
    }

    /**
     * Saves an image
     *
     * @param string  $filename     Image file name
     * @param boolean $original     Save the original image if true, modified
     *                              if false.
     * @param string  $image_type   Image format
     * @param integer $compression  Compression rate (mainly for Jpgs)
     * @param integer $permissions  Permissions for the new file
     *
     * @return boolean True in case of success, false on failure.
     */
    public function save($filename, $original = false, $image_type = null, $compression = 100, $permissions = null)
    {
        // Selecting image
        $image = ($original === true) ? $this->image : $this->currentImage;
        if ($image_type == null) {
            $image_type = $this->image_type;
        }
        if ($image_type == IMAGETYPE_JPEG) {
            if (!imagejpeg($image, $filename, $compression)) {
                return false;
            }
        } elseif ($image_type == IMAGETYPE_GIF) {
            if (!imagegif($image, $filename)) {
                return false;
            }
        } elseif ($image_type == IMAGETYPE_PNG) {
            if (!imagepng($image, $filename)) {
                return false;
            }
        }
        if ($permissions != null) {
            chmod($filename, $permissions);
        }
        $this->log((($original === true) ? 'Original' : 'Copy') . ' image saved successfully.');
        return true;
    }

    /**
     * Returns the image without saving it. Useful for direct rendering
     *
     * @param string  $image_type Image format
     * @param boolean $original   Display original image if true, modified image if false.
     *
     * @return void
     */
    public function output($image_type = IMAGETYPE_JPEG, $original = true)
    {
        $image = ($original == true) ? $this->image : $this->currentImage;
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($image);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($image);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($image);
        }
    }

    /**
     * Gets the image width
     *
     * @return integer Image width
     */
    public function getWidth()
    {
        return imagesx($this->currentImage);
    }

    /**
     * Gets the current image height
     *
     * @returns integer image height
     */
    public function getHeight()
    {
        return imagesy($this->currentImage);
    }

    /**
     * Resize image to desired height. Width will be resized with a ratio.
     *
     * @param integer $height Desired height
     *
     * @return void
     */
    public function resizeToHeight($height)
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
        $this->log('Image resized to height ' . $height);
    }

    /**
     * Resize image to desired width. Height will be resized with a ratio.
     *
     * @param integer $width Desired width
     *
     * @return void
     */
    public function resizeToWidth($width)
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
        $this->log('Image resized to width ' . $width);
    }

    /**
     * Resize an image using a certain scale.
     *
     * @param integer $scale Scale factor
     *
     * @return void
     */
    public function scale($scale)
    {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
        $this->log('(Scale of ' . $scale . ')');
    }

    /**
     * Resize image to given width and height
     *
     * @param integer $width Desired width
     * @param integer $height Desired height
     *
     * @return void
     */
    public function resize($width, $height)
    {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->currentImage, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight()); //@codingStandardsIgnoreLine
        $this->currentImage = $new_image;
    }

    /**
     * Crops the current image to the desired dimensions
     *
     * @param integer $width  Desired width
     * @param integer $height Desired height
     * @param integer $startX Crop horizontal start point
     * @param integer $startY Crop vertical start point
     *
     * @return void
     */
    public function crop($width, $height, $startX = 0, $startY = 0)
    {
        // Getting image width/height
        $imageX = $this->getHeight();
        $imageY = $this->getWidth();

        // Finding the longest side
        $biggestSide = ($imageX > $imageY) ? $imageX : $imageY;
        // Determinating the crop sizes
        $cropX = $width;
        $cropY = $height;
        //Creating image
        $new_image = imagecreatetruecolor($width, $height);
        // @codingStandardsIgnoreLine
        imagecopyresampled($new_image, $this->currentImage, 0, 0, $startX, $startY, $width, $height, $cropX, $cropY);
        $this->currentImage = $new_image;

        $this->log("Crop: X=$width, Y=$height");
    }

    /**
     * Center crops an image to the desired width and height.
     * The cropped image will be in the center of the original image.
     *
     * @param integer $width Image width, in pixels
     * @param integer $height Image height, in pixels
     *
     * @return void
     */
    public function centerCrop($width, $height)
    {
        // Determining start points
        $imgX = $this->getWidth();
        $imgY = $this->getHeight();

        $startX = ($imgX - $width) / 2;
        $startY = ($imgY - $height) / 2;
        $this->log("Center crop: W=$width, H=$height, SX=$startX, SY=$startY");
        $this->crop($width, $height, (int) $startX, (int) $startY);
    }

    /**
     * Adds a message to the modification log
     *
     * @param string $message Message to add
     *
     * @return void
     */
    protected function log($message)
    {
        $this->log[] = $message;
    }

    /**
     * Resets the current image to original image.
     *
     * @return boolean
     */
    public function reset()
    {
        $this->currentImage = $this->image;
        //$this->log=array();
        $this->log("\n--Re-using base image");
        return true;
    }

    /**
     * Watermarks an image
     *
     * @param string  $source   Watermark image path
     * @param string  $position Position on image. Can be
     *                          top-left, top right, bottom-right, bottom-left,
     *                          center, center-top, center-bottom
     * @param integer $margin Margin between the image and the watermark. Useless with the center position.
     *
     * @return void
     */
    public function waterMark($source, $position = 'bottom-left', $margins = 5)
    {
        // Getting the waterMark image:
        $w = new SimpleImage;
        $w->load($source);

        // Getting the watermark position:
        switch ($position) {
            case 'center':
                $startX = ($this->getWidth() - $w->getWidth()) / 2;
                $startY = ($this->getHeight() - $w->getHeight()) / 2;
                break;
            case 'center-top':
                $startX = ($this->getWidth() - $w->getWidth()) / 2;
                $startY = $margins;
                break;
            case 'center-bottom':
                $startX = ($this->getWidth() - $w->getWidth()) / 2;
                $startY = $this->getHeight() - $w->getHeight() - $margins;
                break;
            case 'top-left':
                $startX = $margins;
                $startY = $margins;
                break;
            case 'top-right':
                $startX = $this->getWidth() - $w->getWidth() - $margins;
                $startY = $margins;
                break;
            case 'bottom-right':
                $startX = $this->getWidth() - $w->getWidth() - $margins;
                $startY = $this->getHeight() - $w->getHeight() - $margins;
                break;
            // Bottom left:
            default:
                $startX = $margins;
                $startY = $this->getHeight() - $w->getHeight() - $margins;
                break;
        }

        $new_image = $this->currentImage;
        imagecopy($new_image, $w->currentImage, $startX, $startY, 0, 0, $w->getWidth(), $w->getHeight());
        $this->currentImage = $new_image;

        $this->log("Image watermarked with file $source ($position: $startX, $startY)");
    }

    /**
     * Crops the image, using image center as final center
     *
     * @param int $width  Final width
     * @param int $height Final height
     *
     * @return void
     */
    public function centerCropFull($width, $height)
    {
        // Checking for file sizes
        $this->resizeSmallestTo(($width > $height) ? $width : $height);
        $this->centerCrop($width, $height);
    }

    /**
     * Crops the image to given sizes after a resize
     *
     * @param int $width  Final width
     * @param int $height Final height
     *
     * @return void
     */
    public function cropFull($width, $height)
    {
        // Checking for file sizes
        $this->resizeSmallestTo(($width > $height) ? $width : $height);
        $this->crop($width, $height);
    }

    /**
     * Returns the log array.
     *
     * @return array
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Resize the image, based on th smallest side.
     *
     * @param integer $size Desired size for the smallest side
     *
     * @return void
     */
    public function resizeSmallestTo($size)
    {
        if ($this->getHeight() >= $this->getWidth()) {
            //Must resize width
            $this->resizeToWidth($size);
        } else {
            //Must resize height
            $this->resizeToHeight($size);
        }
    }

    /**
     * Resize the image, based on th biggest side.
     *
     * @param integer $size Desired size for the biggest side
     *
     * @return void
     */
    public function resizeBiggestTo($size)
    {
        if ($this->getHeight() <= $this->getWidth()) {
            //Must resize width
            $this->resizeToWidth($size);
        } else {
            //Must resize height
            $this->resizeToHeight($size);
        }
    }

    /**
     * Rotates an image
     *
     * @param integer $angle Desired angle to rotate (clockwise)
     *
     * @return void
     */
    public function rotate($angle = 90)
    {
        $this->currentImage = imagerotate($this->currentImage, $angle, 0);
    }

    /**
     * Rotates an image horizontally or vertically
     *
     * @param string $direction 'x' for horizontal (default), 'y' for vertical
     *
     * @return void
     */
    public function rotateTo($direction = 'x')
    {
        if ($direction == "x") {
            if ($this->getHeight() > $this->getWidth()) {
                $this->rotate(90);
            }
        } elseif ($direction == "y") {
            if ($this->getWidth() > $this->getHeight()) {
                $this->rotate(90);
            }
        }
    }
}
