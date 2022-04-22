<?php

/**
 * Class ImageUploader
 */
class ImageUploader
{
    public const ASSETS_URL = '/';
    public const IMAGE_BASE_PATH = 'croppie_uploads';
    public const IMAGE_TYPE_ORI = 'ori';
    public const IMAGE_TYPE_THUMB = 'thumb';
    public const SOURCE_UNKNOWN = '/unknown';
    public const IMAGE_EXTENSION = 'webp';

    public $action; // post or delete
    public $postImageData;
    public $postImageName;
    public $postImageType;
    public $postImageUploadRelativePath;
    public $imageUploadPath;
    public $deleteImagePath;

    public function __construct($postData)
    {
        $this->action = $postData['action'] ?? null;
        $this->postImageData = $postData['image'] ?? null;
        $this->deleteImagePath = $postData['delete_path'] ?? null;

        $this->validateRequest();

        $time = (string)time();
        $this->postImageName = $time;
        if (isset($postData['image_name']) and !empty($postData['image_name'])) {
            $this->postImageName = $postData['image_name'] . '_' . $time;
        }

        $this->postImageType = $postData['type'] ?? self::IMAGE_TYPE_THUMB;
        $this->postImageUploadRelativePath = $postData['relative_path'] ?? self::SOURCE_UNKNOWN;
        $this->imageUploadPath = self::IMAGE_BASE_PATH . $this->postImageUploadRelativePath . '/' . $this->postImageType;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function validateRequest()
    {
        if (empty($this->action)) {
            throw new Exception("Invalid request. What's the purpose?");
        }

        if ($this->action == 'upload') {
            if (!isset($postData['image']) || empty($postData['image'])) {
                throw new Exception("Invalid request. Where's the data");
            }
        }

        if ($this->action == 'delete') {
            if (empty($this->deleteImagePath)) {
                throw new Exception("Invalid request. Can't take any action without data.");
            }
        }

        return true;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function upload()
    {
        $imageData = $this->getImageData();
        $imageName = $this->getImageName();
        $imageUploadDir = $this->getUploadDirectory();

        if (empty($imageData) || empty($imageName) || empty($imageUploadDir)) {
            throw new Exception('Data not available. Please make sure the following parameters and values are included image, image_name, relative_path, type(thumb/ori)');
        }

        $imageUploadPath = $imageUploadDir . '/' . $imageName;

        file_put_contents($imageUploadPath, $imageData);

        if (file_exists($imageUploadPath)) {
            return $imageUploadPath;
        }

        throw new Exception('Image could not be uploaded');
    }

    /**
     * @return false|mixed|string
     */
    public function getImageData()
    {
        $data = $this->postImageData;
        $image_array_1 = explode(';', $data);
        $image_array_2 = explode(',', $image_array_1[1]);
        $data = base64_decode($image_array_2[1]);

        return $data;
    }

    /**
     * @return mixed|string
     */
    public function getImageName()
    {
        $imageName = $this->postImageName;

        $tmp = explode('.', $imageName);

        if ($tmp[array_key_last($tmp)] !== self::IMAGE_EXTENSION) {
            $imageName .= '.' . self::IMAGE_EXTENSION;
        }

        return $imageName;
    }

    /**
     * @return bool|string
     */
    public function getUploadDirectory()
    {
        $imageUploadPath = $this->imageUploadPath;

        if ($this->makeDir($imageUploadPath)) {
            return $imageUploadPath;
        }

        return false;
    }

    /**
     * @param string $path Image path in format '/directory1/directory2/'
     * @param int $permissions Directory permissions
     *
     * @return bool
     */
    private function makeDir($path, $permissions = 0777)
    {
        return is_dir($path) || mkdir($path, $permissions, true) || is_dir($path);
    }

    public function delete()
    {
        if (file_exists($this->deleteImagePath)) {
            unlink($this->deleteImagePath);
        }

        return $this->deleteImagePath;
    }
}
