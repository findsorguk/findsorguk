<?php

class image
{
    // This is Q16 (16 bits per pixel) converted to bytes
    public const IMAGEMAGICKBYTES = 8;

    // Max size of the ImageMagick Cache, this is the combined size of both the disk cache and the memory
    private int $maxMemoryImageMagickBytes = 2_147_483_648;
    private int $width;
    private int $height;
    private string $filePath;
    private string $mimeType;

    private const ROUNDING_FACTOR = 1000;
    /**
     * Constructor to initialize the image path and set dimensions.
     *
     * @param string $filePath The path to the image file.
     * @throws Pas_Exception If the file does not exist or if the image size cannot be determined.
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $dimensions = $this->getImageDimensions();
        $this->width = $dimensions[0];
        $this->height = $dimensions[1];
        $this->mimeType = $this->getMimeType();
    }

    private function getMaxImageDimensions(): int
    {
        if ($this->mimeType != "image/jpeg") {
            // During image format conversion, ImageMagick uses cache for both the original and the converted images.
            // For non-JPEG to JPEG conversions, the cache must accommodate both images simultaneously, effectively
            // doubling the required size.
            // Therefore, when calculating max memory, divide by two for non-JPEG images.
            return $this->maxMemoryImageMagickBytes / 2;
        }
        return $this->maxMemoryImageMagickBytes;
    }

    /**
     * Retrieves the dimensions of an image file.
     *
     * @return array An array containing the width and height of the image.
     * @throws Pas_Exception If the file does not exist or if the image size cannot be determined.
     */
    public function getImageDimensions(): array
    {
        if (!is_readable($this->filePath) || !is_file($this->filePath)) {
            throw new Pas_Exception("The temporary file '{$this->filePath}' was not readable, or is not a file.");
        }

        // Get the size of the image
        $imageInfo = @getimagesize($this->filePath);
        if (!$imageInfo) {
            throw new Pas_Exception("Could not get image size for the file '{$this->filePath}'.");
        }

        // As per https://www.php.net/manual/en/function.getimagesize.php, width and height may return 0 in some cases
        if ($imageInfo[0] == 0 || $imageInfo[1] == 0) {
            throw new Pas_Exception("Cannot read valid image height and or width for the file '{$this->filePath}'. 
        Please ensure file contains a single image.");
        }

        return $imageInfo;
    }

    /** Get mime type from file
     * @param string $filePath
     * @return string
     */
    public function getMimeType(): string
    {
        return mime_content_type($this->filePath);
    }

    /** Calculate the number of bytes that the image will take up in memory to resize
     * @param int $width
     * @param int $height
     * @return float|int
     */
    public function getBytesNeededToResizeImage()
    {
        return $this->width * $this->height * self::IMAGEMAGICKBYTES;
    }

    /** Checks if the file can be resized within the cache limit for ImageMagick
     * @return bool
     */
    public function canFileBeResizedInCacheLimit(): bool
    {
        $imageSize = $this->getBytesNeededToResizeImage();

        if ($imageSize > $this->getMaxImageDimensions()) {
            return false;
        }
        return true;
    }

    /** Calculates the maximum dimensions for a given cache size while maintaining the aspect ratio.
     *
     * @return array<string, int> An associative array containing the maximum width and height.
     */
    public function getMaxDimensionsForCacheSize(): array
    {
        $imageSize = $this->getBytesNeededToResizeImage();
        $percentBigger = ($this->getMaxImageDimensions() / $imageSize);

        if ($percentBigger < 1) {
            # Calculate the new dimensions while maintaining the aspect ratio
            $maxWidth = (int)($this->width * $percentBigger);
            $maxHeight = (int)($this->height * $percentBigger);

            $roundedWidth = (int)floor($maxWidth / self::ROUNDING_FACTOR) * self::ROUNDING_FACTOR;
            $roundedHeight = (int)floor($maxHeight / self::ROUNDING_FACTOR) * self::ROUNDING_FACTOR;

            return ["width" => $roundedWidth, "height" => $roundedHeight];
        }
        return ["width" => $this->width, "height" => $this->height];
    }
}
