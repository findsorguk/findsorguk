<?php

class image
{
    public const IMAGEMAGICKBYTES = 8;
    public int $maxMemory = 2147483648;

    /**
     * Retrieves the dimensions of an image file.
     *
     * @param string $filePath The path to the image file.
     * @throws Pas_Exception If the file does not exist or if the image size cannot be determined.
     * @return array An array containing the width and height of the image.
     */
    public function getImageDimensions(string $filePath): array
    {
        if (!is_readable($filePath) || !is_file($filePath)) {
            throw new Pas_Exception("The temporary file '{$filePath}' was not readable, or is not a file.");
        }

        // Get the size of the image
        $imageInfo = @getimagesize($filePath);
        if (!$imageInfo) {
            throw new Pas_Exception("Could not get image size for the file '{$filePath}'.");
        }

        // As per https://www.php.net/manual/en/function.getimagesize.php, width and height may return 0 in some cases
        if ($imageInfo[0] == 0 || $imageInfo[1] == 0) {
            throw new Pas_Exception("Cannot read valid image height and or width for the file '{$filePath}'. 
        Please ensure file contains a single image.");
        }

        return $imageInfo;
    }

    /** Calculate the number of bytes that the image will take up in memory to resize
     * @param int $width
     * @param int $height
     * @return float|int
     */
    public function getBytesNeededToResizeImage(int $width, int $height)
    {
        return $width * $height * self::IMAGEMAGICKBYTES;
    }

    /** Checks if the file can be resized within the cache limit for ImageMagick
     * @param int $width
     * @param int $height
     * @return bool
     */
    public function checkFileCanBeResizedInCacheLimit(int $width, int $height): bool
    {
        $imageSize = $this->getBytesNeededToResizeImage($width, $height);
        if ($imageSize > $this->maxMemory) {
            return false;
        }
        return true;
    }

    /** Calculates the maximum dimensions for a given cache size while maintaining the aspect ratio.
     *
     * @param int $width The width of the cache size.
     * @param int $height The height of the cache size.
     * @return array<string, int> An associative array containing the maximum width and height.
     * @throws Pas_Exception
     */
    public function getMaxDimensionsForCacheSize(int $width, int $height): array
    {
        if ($width === 0 || $height === 0) {
            throw new Pas_Exception("Invalid image width and or height.");
        }

        $imageSize = $this->getBytesNeededToResizeImage($width, $height);
        $percentBigger = ($this->maxMemory / $imageSize);

        if ($percentBigger < 1) {
            # Calculate the new dimensions while maintaining the aspect ratio
            $maxWidth = (int)($width * $percentBigger);
            $maxHeight = (int)($height * $percentBigger);

            return ["width" => $maxWidth, "height" => $maxHeight];
        }
        return ["width" => $width, "height" => $height];
    }
}
