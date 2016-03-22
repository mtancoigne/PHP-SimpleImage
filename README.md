# PHP-SimpleImage

Simple Image manipulation class written by Simon Jarvis and updated with some new methods (I don't remember which, two years old...)

## Methods :
### load

```PHP
/**
 * Loads an image
 *
 * @param string $filename Image to load
 * @return boolean True in case of success, false on failure.
 */
function load($filename){}
```

### save

```PHP
/**
 * Saves an image
 *
 * @param string $filename Image file name
 * @param boolean $original Save the original image if true, modified if false.
 * @param string $image_type Image format
 * @param integer $compression Compression rate (mainly for Jpgs)
 * @param integer $permissions Permissions for the new file
 *
 * @return boolean True in case of success, false on failure.
 */
function save($filename, $original = false, $image_type = null, $compression = 75, $permissions = null){}
```

### output

```PHP
/**
 * Returns the image without saving it. Useful for direct rendering
 *
 * @param string $image_type Image format
 * @param boolean $original Display original image if true, modified image if false.
 *
 * @return void
 */
function output($image_type = IMAGETYPE_JPEG, $original = true) {}
```

### getWidth

```PHP
/**
 * Gets the image width
 *
 * @return integer Image width
 */
function getWidth() {}
```

### getHeight

```PHP
/**
 * Gets the current image height
 *
 * @returns integer image height
 */
function getHeight() {}
```

### resizeToHeight

```PHP
/**
 * Resize image to desired height. Width will be resized with a ratio.
 *
 * @param integer $height Desired height
 *
 * @return void
 */
function resizeToHeight($height) {}
```

### resizeToWidth

```PHP
/**
 * Resize image to desired width. Height will be resized with a ratio.
 *
 * @param integer $width Desired width
 *
 * @return void
 */
function resizeToWidth($width) {}
```

### scale

```PHP
/**
 * Resize an image using a certain scale.
 *
 * @param integer $scale Scale factor
 *
 * @return void
 */
function scale($scale) {}
```

### resize

```PHP
/**
 * Resize image to given width and height
 *
 * @param integer $width Desired width
 * @param integer $height Desired height
 *
 * @return void
 */
function resize($width, $height) {}
```

###crop

```PHP
/**
 * Crops the current image to the desired dimensions
 *
 * @param integer $width Desired width
 * @param integer $height Desired height
 * @param integer $startX Crop horizontal start point
 * @param integer $startY Crop vertical start point
 *
 * @return void
 */
function crop($width, $height, $startX = 0, $startY = 0) {}
```

###centerCrop

```PHP
/**
 * Center crops an image to the desired width and height.
 * The cropped image will be in the center of the original image.
 *
 * @param integer $width Image width, in pixels
 * @param integer $height Image height, in pixels
 *
 * @return void
 */
function centerCrop($width, $height) {}
```

### log

```PHP
/**
 * Adds a message to the modification log
 *
 * @param string $message Message to add
 */
function log($message) {}
```

### reset

```PHP
/**
 * Resets the current image to original image.
 *
 * @return boolean
 */
function reset() {}
```

### waterMark

```PHP
/**
 * Watermarks an image
 *
 * @param string $source Watermark image path
 * @param string $position Position on image (can be top-left, top right, bottom-right, bottom-left)
 */
function waterMark($source, $position = 'bottom-left', $type = 'hover', $options = array()) {}
```

### centerCropFull

```PHP
function centerCropFull($width, $height) {}
```

### cropFull

```PHP
function cropFull($width, $height) {}
/**
 * Returns the log array.
 *
 * @return array
 */
function getLog() {}
```

### resizeSmallestTo

```PHP
/**
 * Resize the image, based on th smallest side.
 *
 * @param integer $size Desired size for the smallest side
 *
 * @return void
 */
function resizeSmallestTo($size) {}
```

### resizeBiggestTo

```PHP
/**
 * Resize the image, based on th biggest side.
 *
 * @param integer $size Desired size for the biggest side
 */
function resizeBiggestTo($size) {}
```

### rotate

```PHP
/**
 * Rotates an image
 *
 * @param integer $angle Desired angle to rotate (clockwise)
 */
function rotate($angle = 90) {}
```

### rotateTo

```PHP
/**
 * Rotates an image horizontally or vertically
 *
 * @param string $direction 'x' for horizontal (default), 'y' for vertical
 */
 function rotateTo($direction = 'x') {}
```
