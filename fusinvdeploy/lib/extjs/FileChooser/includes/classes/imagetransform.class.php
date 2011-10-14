<?php

/**
 *  A PHP class providing a set of methods for doing basic transformation to an image like resizing,
 *  rotating, flipping and cropping
 *
 *  You don't need to do any checking on the files you work with - the class takes care of all error
 *  checking and you can find out what went wrong by reading the {@link error} property
 *
 *  The code is heavily documented so you can easily understand every aspect of it
 *
 *  See the documentation for more info.
 *
 *  Read the LICENSE file, provided with the package, to find out how you can use this PHP script.
 *
 *  If you don't find this file, please write an email to noname at nivelzero dot ro and you will be sent a copy of the license file
 *
 *  For more resources visit {@link http://stefangabos.blogspot.com}
 *
 *  @author     Stefan Gabos <ix@nivelzero.ro>
 *  @version    1.0.5 (last revision: August 23, 2007)
 *  @copyright  (c) 2006 - 2007 Stefan Gabos
 *  @package    imageTransform
 *  @example    example.php
 */

//error_reporting(E_ALL);

class imageTransform
{

    /**
     *  Path and name of image file to transform
     *
     *  @var    string
     */
    var $sourceFile;
    
    /**
     *  Path and name of transformed image file
     *
     *  @var    string
     */
    var $targetFile;
    
    /**
     *  Available only for the {@link resize} method
     *
     *  Width, in pixels, to resize the image to
     *
     *  the property will not be taken into account if is set to -1
     *
     *  default is -1
     *
     *  @var    integer
     */
    var $resizeToWidth;
    
    /**
     *  Available only for the {@link resize} method
     *
     *  Height, in pixels, to resize the image to
     *
     *  the property will not be taken into account if is set to -1
     *
     *  default is -1
     *
     *  @var    integer
     */
    var $resizeToHeight;
    
    /**
     *  Available only for the {@link resize} method
     *
     *  while resizing, image will keep it's aspect ratio if this property is set to TRUE, and only one of the
     *  {@link resizeToWidth} or {@link resizeToHeight} properties is set. if set to TRUE, and both
     *  {@link resizeToWidth} or {@link resizeToHeight} properties are set, the image will be resized to maximum width/height
     *  so that neither one of them will exceed given width/height while keeping the aspect ratio
     *
     *  default is TRUE
     *
     *  @var boolean
     */
    var $maintainAspectRatio;
    
    /**
     *  Available only for the {@link resize} method
     *
     *  image is resized only if image width/height is smaller than the values of
     *  {@link resizeToWidth}/{@link resizeToHeight} properties
     *
     *  default is TRUE
     *
     *  @var boolean
     */
    var $resizeIfSmaller;
    
    /**
     *  Available only for the {@link resize} method
     *
     *  image is resized only if image width/height is greater than the values of
     *  {@link resizeToWidth}/{@link resizeToHeight} properties
     *
     *  default is TRUE
     *
     *  @var boolean
     */
    var $resizeIfGreater;
    
    /**
     *  Available only for the {@link resize} method and only if the {@link targetFile}'s extension is jpg/jpeg
     *
     *  output quality of image (better quality means bigger file size).

     *
     *  range is 0 - 100
     *
     *  default is 75
     *
     *  @var integer
     */
    var $jpegOutputQuality;
    
    /**
     *  what rights should the transformed file have
     *
     *  by default a file created by a script will have the script as owner and you would not be able to edit, modify
     *  or delete the file. better is to leave this setting as it is
     *
     *  if you know what you're doing, here's is how you calculate the permission levels
     *
     *      - 400 Owner Read
     *      - 200 Owner Write
     *      - 100 Owner Execute
     *      - 40 Group Read
     *      - 20 Group Write
     *      - 10 Group Execute
     *      - 4 Global Read
     *      - 2 Global Write
     *      - 1 Global Execute
     *
     *  default is 0755
     *
     *  @var integer
     */
    var $chmodValue;
    
    /**
     *  Sets weather the target file should have have the same date/time as the source file
     *
     *  Default is TRUE
     *
     *  @since 1.0.4
     *
     *  @var boolean
     */
    var $preserveSourceFileTime;

    /**
     *  in case of an error read this property's value to find out what went wrong
     *
     *  possible error values are:
     *
     *      - 1:  source file could not be found
     *      - 2:  source file can not be read
     *      - 3:  could not write target file
     *      - 4:  unsupported source file
     *      - 5:  unsupported target file
     *      - 6:  available version of GD does not support target file extension
     *
     *  default is 0
     *
     *  @var integer
     */
    var $error;
    
    /**
     *  Constructor of the class.
     *
     *  @access private
     */
    function imageTransform()
    {
    
        // Sets default values of the class' properties
        // We need to do it this way for the variables to have default values PHP 4
        // public properties
        $this->chmodValue = 0755;
        $this->preserveSourceFileTime = true;
        $this->error = 0;
        $this->jpegOutputQuality = 75;
        $this->resizeIfGreater = true;
        $this->resizeIfSmaller = true;
        $this->maintainAspectRatio = true;
        $this->resizeToHeight = -1;
        $this->resizeToWidth = -1;
        $this->targetFile = "";
        $this->sourceFile = "";
        
    }
    
    /**
     *  returns an image identifier representing the image obtained from sourceFile, the image's width and height
     *  and the image's type
     *
     *  @access private
     */
    function create_image_from_source_file()
    {
    
        // performs some error checking first
        // if source file does not exists
        if (!is_file($this->sourceFile) || !file_exists($this->sourceFile)) {
        
            // save the error level and stop the execution of the script
            $this->error = 1;
            return false;
            
        // if source file is not readable
        } elseif (!is_readable($this->sourceFile)) {
        
            // save the error level and stop the execution of the script
            $this->error = 2;
            return false;
            
        // if target file is same as source file and source file is not writable
        } elseif ($this->targetFile == $this->sourceFile && !is_writable($this->sourceFile)) {
        
            // save the error level and stop the execution of the script
            $this->error = 3;
            return false;
            
        // get source file width, height and type
        // and if it founds a not-supported file type
        } elseif (!list($sourceImageWidth, $sourceImageHeight, $sourceImageType) = @getimagesize($this->sourceFile)) {
        
            // save the error level and stop the execution of the script
            $this->error = 4;
            return false;
            
        // if no errors so far
        } else {

            // creates an image from file using extension dependant function
            // checks for file extension
            switch ($sourceImageType) {
            
                // if gif
                case 1:
                
                    // the following part gets the transparency color for a gif file
                    // this code is from the PHP manual and is written by
                    // fred at webblake dot net and webmaster at webnetwizard dotco dotuk, thanks!
                    $fp = fopen($this->sourceFile, "rb");
                    $result = fread($fp, 13);
                    $colorFlag = ord(substr($result,10,1)) >> 7;
                    $background = ord(substr($result,11));
                    if ($colorFlag) {
                        $tableSizeNeeded = ($background + 1) * 3;
                        $result = fread($fp, $tableSizeNeeded);
                        $this->transparentColorRed = ord(substr($result, $background * 3, 1));
                        $this->transparentColorGreen = ord(substr($result, $background * 3 + 1, 1));
                        $this->transparentColorBlue = ord(substr($result, $background * 3 + 2, 1));
                    }
                    fclose($fp);
                    // -- here ends the code related to transparency handling
                    // creates an image from file
                    
                    $sourceImageIdentifier = @imagecreatefromgif($this->sourceFile);
                    break;
                    
                // if jpg
                case 2:
                
                    // creates an image from file
                    $sourceImageIdentifier = @imagecreatefromjpeg($this->sourceFile);
                    break;
                    
                // if png
                case 3:
                
                    // creates an image from file
                    $sourceImageIdentifier = @imagecreatefrompng($this->sourceFile);
                    break;
                    
                default:
                
                    // if file has an unsupported extension
                    // note that we call this if the file is not gif, jpg or png even though the getimagesize function
                    // handles more image types
                    $this->error = 4;
                    return false;
                    
            }
            
        }
        
        // if the date/time of the target file should be the same as the source file's
        if ($this->preserveSourceFileTime) {
        
            // read the source file's date/time
            $this->sourceFileTime = filemtime($this->sourceFile);
        
        }

        // returns an image identifier representing the image obtained from sourceFile and the image's width and height
        return array($sourceImageIdentifier, $sourceImageWidth, $sourceImageHeight, $sourceImageType);
        
    }

    /**
     *  Creates a target image identifier
     *
     *  @access private
     */
    function create_target_image_identifier($width, $height)
    {
    
        // creates a blank image
        $targetImageIdentifier = imagecreatetruecolor((int)$width <= 0 ? 1 : (int)$width, (int)$height <= 0 ? 1 : (int)$height);
        
        // if we have transparency in the image
        if (isset($this->transparentColorRed) && isset($this->transparentColorGreen) && isset($this->transparentColorBlue)) {
        
            $transparent = imagecolorallocate($targetImageIdentifier, $this->transparentColorRed, $this->transparentColorGreen, $this->transparentColorBlue);
            imagefilledrectangle($targetImageIdentifier, 0, 0, $width, $height, $transparent);
            imagecolortransparent($targetImageIdentifier, $transparent);
            
        }
        
        // return target image identifier
        return $targetImageIdentifier;
        
    }

    /**
     *  creates a new image from a given image identifier
     *
     *  @access private
     */
    function output_target_image($targetImageIdentifier)
    {
    
        // get target file extension
        $targetFileExtension = strtolower(substr($this->targetFile, strrpos($this->targetFile, ".") + 1));
        
        // image saving process goes according to required extension
        switch ($targetFileExtension) {
        
            // if gif
            case "gif":
            
                // if gd support for this file type is not available
                if (!function_exists("imagegif")) {
                
                    // save the error level and stop the execution of the script
                    $this->error = 6;
                    return false;
                    
                // if, for some reason, file could not be created
                } elseif (@!imagegif($targetImageIdentifier, $this->targetFile)) {
                
                    // save the error level and stop the execution of the script
                    $this->error = 3;
                    return false;
                    
                }
                
                break;
                
            // if jpg
            case "jpg":
            case "jpeg":
            
                // if gd support for this file type is not available
                if (!function_exists("imagejpeg")) {
                
                    // save the error level and stop the execution of the script
                    $this->error = 6;
                    return false;
                    
                // if, for some reason, file could not be created
                } elseif (@!imagejpeg($targetImageIdentifier, $this->targetFile, $this->jpegOutputQuality)) {
                
                    // save the error level and stop the execution of the script
                    $this->error = 3;
                    return false;
                    
                }
                
                break;
                
            case "png":
            
                // if gd support for this file type is not available
                if (!function_exists("imagepng")) {
                
                    // save the error level and stop the execution of the script
                    $this->error = 6;
                    return false;
                    
                // if, for some reason, file could not be created
                } elseif (@!imagepng($targetImageIdentifier, $this->targetFile)) {
                
                    // save the error level and stop the execution of the script
                    $this->error = 3;
                    return false;
                    
                }
                
                break;
                
            // if not a supported file extension
            default:
            
                // save the error level and stop the execution of the script
                $this->error = 5;
                return false;
                
        }
        
        // if file was created successfully
        // chmod the file
        chmod($this->targetFile, intval($this->chmodValue, 8));
        
        
        // if the date/time of the target file should be the same as the source file's
        if ($this->preserveSourceFileTime) {
        
            // touch the newly created file
            @touch($this->targetFile, $this->sourceFileTime);
            
        }
        
        // and return true
        return true;
        
    }

    /**
     *  Resizes the image given as {@link sourceFile} and outputs the resulted image as {@link targetFile}
     *  while following user specified properties
     *
     *  @return boolean     TRUE on success, FALSE on error.
     *                      If FALSE is returned, check the {@link error} property to see what went wrong
     */
    function resize()
    {

        // tries to create an image from sourceFile
        $result = $this->create_image_from_source_file();
        
        // if operation was successful
        if (is_array($result)) {
        
            list($sourceImageIdentifier, $sourceImageWidth, $sourceImageHeight, $sourceImageType) = $result;
        
            // if aspect ratio needs to be maintained
            if ($this->maintainAspectRatio) {
            
                // calculates image's aspect ratio
                $aspectRatio =
                    $sourceImageWidth <= $sourceImageHeight ?
                        $sourceImageHeight / $sourceImageWidth :
                        $sourceImageWidth / $sourceImageHeight;
                $targetImageWidth = $sourceImageWidth;
                $targetImageHeight = $sourceImageHeight;

                // if resizeToWidth is set
                if ($this->resizeToWidth >= 0) {

                    // set the (temporary) width of target image
                    // this can be later altered by the script but we need it for
                    // the case when the script skips the following if() block
                    $lockedTargetImageWidth = $this->resizeToWidth;

                }

                // if width of image is greater than resizeToWidth property and resizeIfGreater property is TRUE
                // or width of image is smaller than resizeToWidth property and resizeIfSmaller property is TRUE
                if (
                    ($this->resizeToWidth >= 0 && $targetImageWidth > $this->resizeToWidth && $this->resizeIfGreater) ||
                    ($this->resizeToWidth >= 0 && $targetImageWidth < $this->resizeToWidth && $this->resizeIfSmaller)
                ) {
                
                    // set the width of target image
                    $targetImageWidth = $this->resizeToWidth;
                    
                    // set the height of target image so that the image will keep its aspect ratio
                    $targetImageHeight =
                        $sourceImageWidth <= $sourceImageHeight ?
                            $targetImageWidth * $aspectRatio :
                            $targetImageWidth / $aspectRatio;
                            
                    // saves the got width in case the next section wants to change it
                    $lockedTargetImageWidth = $targetImageWidth;
                    
                }
                
                // if height of image is greater than resizeToHeight property and resizeIfGreater property is TRUE
                // or height of image is smaller than resizeToHeight property and resizeIfSmaller property is TRUE
                if (
                    ($this->resizeToHeight >= 0 && $targetImageHeight > $this->resizeToHeight && $this->resizeIfGreater) ||
                    ($this->resizeToHeight >= 0 && $targetImageHeight < $this->resizeToHeight && $this->resizeIfSmaller)
                ) {
                
                    // set the width of target image
                    $targetImageHeight = $this->resizeToHeight;
                    
                    // set the width of target image so that the image will keep its aspect ratio
                    $targetImageWidth =
                        $sourceImageWidth <= $sourceImageHeight ?
                            $targetImageHeight / $aspectRatio :
                            $targetImageHeight * $aspectRatio;
                            
                    // if maximum width was already set but has changed now
                    if (
                        isset($lockedTargetImageWidth) &&
                        $targetImageWidth > $lockedTargetImageWidth
                    ) {
                    
                        // adjust the height so that the width remains as set before
                        while ($targetImageWidth > $lockedTargetImageWidth) {
                        
                            $targetImageHeight--;
                            $targetImageWidth =
                                $sourceImageWidth <= $sourceImageHeight ?
                                    $targetImageHeight / $aspectRatio :
                                    $targetImageHeight * $aspectRatio;
                                    
                        }
                        
                    }
                    
                }
                
            // if aspect ratio does not need to be maintained
            } else {
            
                $targetImageWidth = ($this->resizeToWidth >= 0 ? $this->resizeToWidth : $sourceImageWidth);
                $targetImageHeight = ($this->resizeToHeight >= 0 ? $this->resizeToHeight : $sourceImageHeight);
                
            }
            
            // prepares the target image
            $targetImageIdentifier = $this->create_target_image_identifier($targetImageWidth, $targetImageHeight);

            // resizes image
            // but first if source image is png take care of transparency
            // this is to maintain transparency of png24 files
            if ($sourceImageType == 3) {
            
                imagealphablending($targetImageIdentifier, false);
                imagecopyresampled($targetImageIdentifier, $sourceImageIdentifier, 0, 0, 0, 0, $targetImageWidth, $targetImageHeight, $sourceImageWidth, $sourceImageHeight);
                imagesavealpha($targetImageIdentifier, true);
                
            // if image is something other than png
            } else {
            
                imagecopyresampled($targetImageIdentifier, $sourceImageIdentifier, 0, 0, 0, 0, $targetImageWidth, $targetImageHeight, $sourceImageWidth, $sourceImageHeight);
                
            }
            
            // writes image
            return $this->output_target_image($targetImageIdentifier);
            
        // if new image resource could not be created
        } else {
        
            // return false
            // note that we do not set the error level as it has been already set
            // by the create_image_from_source_file() method earlier
            return false;
            
        }
    }
    
    /**
     *  Crops a portion of the source file and puts it in target file
     *
     *  @param  integer     $src_x  x coordinate to start cropping from
     *  @param  integer     $src_y  y coordinate to start cropping from
     *  @param  integer     $dst_x  x coordinate where to end the cropping
     *  @param  integer     $dst_y  y coordinate where to end the cropping
     *
     *  @since  1.0.4
     *
     *  @return boolean     TRUE on success, FALSE on error.
     *                      If FALSE is returned, check the {@link error} property to see what went wrong
     */
    function crop($src_x, $src_y, $dst_x, $dst_y)
    {
    
        // tries to create an image from sourceFile
        $result = $this->create_image_from_source_file();

        // if operation was successful
        if (is_array($result)) {

            list($sourceImageIdentifier, $sourceImageWidth, $sourceImageHeight) = $result;

            // prepares the target image
            $targetImageIdentifier = $this->create_target_image_identifier($dst_x - $src_x, $dst_y - $src_y);

            // crops the image
           imagecopyresampled($targetImageIdentifier, $sourceImageIdentifier, 0, 0, $src_x, $src_y, $dst_x - $src_x, $dst_y - $src_y, $dst_x - $src_x, $dst_y - $src_y);

            // writes image
            return $this->output_target_image($targetImageIdentifier);
            
        }

    }

    /**
     *  Flips horizontally the image given as {@link sourceFile} and outputs the resulted image as {@link targetFile}
     *
     *  @return boolean     TRUE on success, FALSE on error.
     *                      If FALSE is returned, check the {@link error} property to see what went wrong
     */
    function flip_horizontal()
    {
    
        // tries to create an image from sourceFile
        $result = $this->create_image_from_source_file();

        // if operation was successful
        if (is_array($result)) {

            list($sourceImageIdentifier, $sourceImageWidth, $sourceImageHeight) = $result;
        
            // prepares the target image
            $targetImageIdentifier = $this->create_target_image_identifier($sourceImageWidth, $sourceImageHeight);
            
            // flips image horizontally
            for ($x = 0; $x < $sourceImageWidth; $x++) {
               imagecopyresampled($targetImageIdentifier, $sourceImageIdentifier, $x, 0, $sourceImageWidth - $x - 1, 0, 1, $sourceImageHeight);
            }
            
            // writes image
            return $this->output_target_image($targetImageIdentifier);
            
        // if new image resource could not be created
        } else {
        
            // return false
            // note that we do not set the error level as it has been already set
            // by the create_image_from_source_file() method earlier
            return false;
            
        }
        
    }

    /**
     *  Flips vertically the image given as {@link sourceFile} and outputs the resulted image as {@link targetFile}
     *
     *  @return boolean     TRUE on success, FALSE on error.
     *                      If FALSE is returned, check the {@link error} property to see what went wrong
     */
    function flip_vertical()
    {
    
        // tries to create an image from sourceFile
        $result = $this->create_image_from_source_file();

        // if operation was successful
        if (is_array($result)) {

            list($sourceImageIdentifier, $sourceImageWidth, $sourceImageHeight) = $result;
        
            // prepares the target image
            $targetImageIdentifier = $this->create_target_image_identifier($sourceImageWidth, $sourceImageHeight);
            
            // flips image vertically
            for ($y = 0; $y < $sourceImageHeight; $y++) {
                imagecopyresampled($targetImageIdentifier, $sourceImageIdentifier, 0, $y, 0, $sourceImageHeight - $y - 1, $sourceImageWidth, 1);
            }
            
            // writes image
            return $this->output_target_image($targetImageIdentifier);
            
        // if new image resource could not be created
        } else {
        
            // return false
            // note that we do not set the error level as it has been already set
            // by the create_image_from_source_file() method earlier
            return false;
            
        }
        
    }

    /**
     *  Rotates the image given as {@link sourceFile} and outputs the resulted image as {@link targetFile}
     *
     *  this method implements PHP's imagerotate method which is buggy.
     *  an improved version of this method should be available soon
     *
     *  @param  double  $angle      angle to rotate the image by
     *  @param  mixed   $bgColor    the color of the uncovered zone after the rotation
     *
     *  @return boolean     TRUE on success, FALSE on error.
     *                      If FALSE is returned, check the {@link error} property to see what went wrong
     */
    function rotate($angle, $bgColor)
    {
    
        // tries to create an image from sourceFile
        $result = $this->create_image_from_source_file();

        // if operation was successful
        if (is_array($result)) {

            list($sourceImageIdentifier, $sourceImageWidth, $sourceImageHeight, $sourceImageType) = $result;
        
            // rotates image
            $targetImageIdentifier = imagerotate($sourceImageIdentifier, $angle, $bgColor);
            
            // writes image
            return $this->output_target_image($targetImageIdentifier);
            
        // if new image resource could not be created
        } else {
        
            // return false
            // note that we do not set the error level as it has been already set
            // by the create_image_from_source_file() method earlier
            return false;
            
        }
        
    }
    
}
