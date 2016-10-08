<?php
/**
 * @package     SimpleImage class
 * @version     2.6.0
 * @author      Cory LaViska for A Beautiful Site, LLC (http://www.abeautifulsite.net/)
 * @author      Nazar Mokrynskyi <nazar@mokrynskyi.com> - merging of forks, namespace support, PhpDoc editing, adaptive_resize() method, other fixes
 * @license     This software is licensed under the MIT license: http://opensource.org/licenses/MIT
 * @copyright   A Beautiful Site, LLC
 *
 */

namespace abeautifulsite;
use Exception;

/**
 * Class SimpleImage
 * This class makes image manipulation in PHP as simple as possible.
 * @package SimpleImage
 *
 */
class SimpleImage {

    /**
     * @var int Default output image quality
     *
     */
    public $quality = 80;

    protected $image, $filename, $original_info, $width, $height, $imagestring, $mimetype;

    /**
     * Create instance and load an image, or create an image from scratch
     *
     * @param null|string   $filename   Path to image file (may be omitted to create image from scratch)
     * @param int           $width      Image width (is used for creating image from scratch)
     * @param int|null      $height     If omitted - assumed equal to $width (is used for creating image from scratch)
     * @param null|string   $color      Hex color string, array(red, green, blue) or array(red, green, blue, alpha).
     *                                  Where red, green, blue - integers 0-255, alpha - integer 0-127<br>
     *                                  (is used for creating image from scratch)
     *
     * @return SimpleImage
     * @throws Exception
     *
     */
    function __construct($filename = null, $width = null, $height = null, $color = null) {
        if ($filename) {
            $this->load($filename);
        } elseif ($width) {
            $this->create($width, $height, $color);
        }
        return $this;
    }

    /**
     * Destroy image resource
     *
     */
    function __destruct() {
        if( $this->image !== null && get_resource_type($this->image) === 'gd' ) {
            imagedestroy($this->image);
        }
    }

    /**
     * Create an image from scratch
     *
     * @param int           $width  Image width
     * @param int|null      $height If omitted - assumed equal to $width
     * @param null|string   $color  Hex color string, array(red, green, blue) or array(red, green, blue, alpha).
     *                              Where red, green, blue - integers 0-255, alpha - integer 0-127
     *
     * @return SimpleImage
     *
     */
    function create($width, $height = null, $color = null) {
        $height = $height ?: $width;
        $this->width = $width;
        $this->height = $height;
        $this->image = imagecreatetruecolor($width, $height);
        $this->original_info = array(
            'width' => $width,
            'height' => $height,
            'orientation' => $this->get_orientation(),
            'exif' => null,
            'format' => 'png',
            'mime' => 'image/png'
        );
        if ($color) {
            $this->fill($color);
        }
        return $this;
    }

    /**
     * Load an image
     *
     * @param string        $filename   Path to image file
     *
     * @return SimpleImage
     * @throws Exception
     *
     */
    function load($filename) {
        // Require GD library
        if (!extension_loaded('gd')) {
            throw new Exception('Required extension GD is not loaded.');
        }
        $this->filename = $filename;
        return $this->get_meta_data();
     }

    /**
    * Get meta data of image or base64 string
    *
    * @param string|null       $imagestring    If omitted treat as a normal image
    *
    * @return SimpleImage
    * @throws Exception
    *
    */
    protected function get_meta_data() {
       //gather meta data
       if(empty($this->imagestring)) {
           $info = getimagesize($this->filename);
           switch ($info['mime']) {
               case 'image/gif':
                   $this->image = imagecreatefromgif($this->filename);
                   break;
               case 'image/jpeg':
                   $this->image = imagecreatefromjpeg($this->filename);
                   break;
               case 'image/png':
                   $this->image = imagecreatefrompng($this->filename);
                   break;
               default:
                   throw new Exception('Invalid image: '.$this->filename);
                   break;
           }
       } elseif (function_exists('getimagesizefromstring')) {
           $info = getimagesizefromstring($this->imagestring);
       } else {
           throw new Exception('PHP 5.4 is required to use method getimagesizefromstring');
       }
       $this->original_info = array(
           'width' => $info[0],
           'height' => $info[1],
           'orientation' => $this->get_orientation(),
           'exif' => function_exists('exif_read_data') && $info['mime'] === 'image/jpeg' && $this->imagestring === null ? $this->exif = @exif_read_data($this->filename) : null,
           'format' => preg_replace('/^image\//', '', $info['mime']),
           'mime' => $info['mime']
       );
       $this->width = $info[0];
       $this->height = $info[1];
       imagesavealpha($this->image, true);
       imagealphablending($this->image, true);
       return $this;
    }

    /**
     * Get the current orientation
     *
     * @return string   portrait|landscape|square
     *
     */
    function get_orientation() {
        if (imagesx($this->image) > imagesy($this->image)) {
            return 'landscape';
        }
        if (imagesx($this->image) < imagesy($this->image)) {
            return 'portrait';
        }
        return 'square';
    }

    /**
     * Crop an image
     *
     * @param int           $x1 Left
     * @param int           $y1 Top
     * @param int           $x2 Right
     * @param int           $y2 Bottom
     *
     * @return SimpleImage
     *
     */
    function crop($x1, $y1, $x2, $y2) {

        // Determine crop size
        if ($x2 < $x1) {
            list($x1, $x2) = array($x2, $x1);
        }
        if ($y2 < $y1) {
            list($y1, $y2) = array($y2, $y1);
        }
        $crop_width = $x2 - $x1;
        $crop_height = $y2 - $y1;

        // Perform crop
        $new = imagecreatetruecolor($crop_width, $crop_height);
        imagealphablending($new, false);
        imagesavealpha($new, true);
        imagecopyresampled($new, $this->image, 0, 0, $x1, $y1, $crop_width, $crop_height, $crop_width, $crop_height);

        // Update meta data
        $this->width = $crop_width;
        $this->height = $crop_height;
        $this->image = $new;

        return $this;

    }

    /**
     * Fit to height (proportionally resize to specified height)
     *
     * @param int           $height
     *
     * @return SimpleImage
     *
     */
    function fit_to_height($height) {

        $aspect_ratio = $this->height / $this->width;
        $width = $height / $aspect_ratio;

        return $this->resize($width, $height);

    }

    /**
     * Fit to width (proportionally resize to specified width)
     *
     * @param int           $width
     *
     * @return SimpleImage
     *
     */
    function fit_to_width($width) {

        $aspect_ratio = $this->height / $this->width;
        $height = $width * $aspect_ratio;

        return $this->resize($width, $height);

    }

    /**
     * Resize an image to the specified dimensions
     *
     * @param int   $width
     * @param int   $height
     *
     * @return SimpleImage
     *
     */
    function resize($width, $height) {

        // Generate new GD image
        $new = imagecreatetruecolor($width, $height);

        if( $this->original_info['format'] === 'gif' ) {
            // Preserve transparency in GIFs
            $transparent_index = imagecolortransparent($this->image);
            $palletsize = imagecolorstotal($this->image);
            if ($transparent_index >= 0 && $transparent_index < $palletsize) {
                $transparent_color = imagecolorsforindex($this->image, $transparent_index);
                $transparent_index = imagecolorallocate($new, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                imagefill($new, 0, 0, $transparent_index);
                imagecolortransparent($new, $transparent_index);
            }
        } else {
            // Preserve transparency in PNGs (benign for JPEGs)
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }

        // Resize
        imagecopyresampled($new, $this->image, 0, 0, 0, 0, $width, $height, $this->width, $this->height);

        // Update meta data
        $this->width = $width;
        $this->height = $height;
        $this->image = $new;

        return $this;

    }

    /**
     * Save an image
     *
     * The resulting format will be determined by the file extension.
     *
     * @param null|string   $filename   If omitted - original file will be overwritten
     * @param null|int      $quality    Output image quality in percents 0-100
     * @param null|string   $format     The format to use; determined by file extension if null
     *
     * @return SimpleImage
     * @throws Exception
     *
     */
    function save($filename = null, $quality = null, $format = null) {

        // Determine quality, filename, and format
        $filename = $filename ?: $this->filename;
        if( !$format )
            $format = $this->file_ext($filename) ?: $this->original_info['format'];

        list( $mimetype, $imagestring ) = $this->generate( $format, $quality );

        // Save the image
        $result = file_put_contents( $filename, $imagestring );
        if (!$result)
            throw new Exception('Unable to save image: ' . $filename);

        return $this;
    }

    /**
     * Generates the image as a string it and sets mime type
     *
     * @param null|string   $format     If omitted or null - format of original file will be used, may be gif|jpg|png
     * @param int|null      $quality    Output image quality in percents 0-100
     *
     * @throws Exception
     *
     */
    protected function generate($format = null, $quality = null) {
       // Determine quality
       $quality = $quality ?: $this->quality;
       // Determine mimetype
       switch (strtolower($format)) {
           case 'gif':
               $mimetype = 'image/gif';
               break;
           case 'jpeg':
           case 'jpg':
               imageinterlace($this->image, true);
               $mimetype = 'image/jpeg';
               break;
           case 'png':
               $mimetype = 'image/png';
               break;
           default:
               $info = (empty($this->imagestring)) ? getimagesize($this->filename) : getimagesizefromstring($this->imagestring);
               $mimetype = $info['mime'];
               unset($info);
               break;
       }
       // Sets the image data
       ob_start();
       switch ($mimetype) {
           case 'image/gif':
               imagegif($this->image);
               break;
           case 'image/jpeg':
               imagejpeg($this->image, null, round($quality));
               break;
           case 'image/png':
               imagepng($this->image, null, round(9 * $quality / 100));
               break;
           default:
               throw new Exception('Unsupported image format: '.$this->filename);
               break;
       }
       $imagestring = ob_get_contents();
       ob_end_clean();
       return array($mimetype, $imagestring);
    }

    /**
     * Thumbnail
     *
     * This function attempts to get the image to as close to the provided dimensions as possible, and then crops the
     * remaining overflow (from the center) to get the image to be the size specified. Useful for generating thumbnails.
     *
     * @param int           $width
     * @param int|null      $height If omitted - assumed equal to $width
     * @param string        $focal
     *
     * @return SimpleImage
     *
     */
    public function thumbnail($width, $height = null, $focal = 'center') {

        // Determine height
        $height = $height ?: $width;

        // Determine aspect ratios
        $current_aspect_ratio = $this->height / $this->width;
        $new_aspect_ratio = $height / $width;

        // Fit to height/width
        if ($new_aspect_ratio > $current_aspect_ratio) {
            $this->fit_to_height($height);
        } else {
            $this->fit_to_width($width);
        }

        switch(strtolower($focal)) {
            case 'center':
            default:
                $left = floor(($this->width / 2) - ($width / 2));
                $right = $width + $left;
                $top = floor(($this->height / 2) - ($height / 2));
                $bottom = $height + $top;
                break;
        }

        // Return trimmed image
        return $this->crop($left, $top, $right, $bottom);
    }

    /**
     * Returns the file extension of the specified file
     *
     * @param string    $filename
     *
     * @return string
     *
     */
    protected function file_ext($filename) {

        if (!preg_match('/\./', $filename)) {
            return '';
        }

        return preg_replace('/^.*\./', '', $filename);

    }
}
?>
