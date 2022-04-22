<?php

/**
 * Class ImageUploader
 */
class ImageUploader
{
	public const ASSETS_URL = 'http://assets.apnaaccounts.com/';
	public const IMAGE_BASE_PATH = 'croppie_uploads';
	public const IMAGE_TYPE_ORI = 'ori';
	public const IMAGE_TYPE_THUMB = 'thumb';
	public const SOURCE_UNKNOWN = '/unknown';
	public const IMAGE_EXTENSION = 'webp';

	public $postImageData;
	public $postImageName;
	public $postImageUploadPath;

	public function __construct($postData)
	{
		if (! isset($postData['image']) || empty($postData['image'])) {
			throw new Exception("Invalid request");
		}

		$this->postImageData = $postData['image'];
		$this->postImageName = $postData['image_name'] ?: '';
		$this->postImageUploadPath = $postData['relative_path'] ?: self::SOURCE_UNKNOWN;
	}

	/**
	 * @return bool|string
	 */
	public function upload()
	{
		$imageData = $this->getImageData();
		$imageName = $this->getImageName();
		$imageUploadDir = $this->getUploadDirectory();

		if (empty($imageData) || empty($imageName) || empty($imageUploadDir)) {
			return false;
		}

		$imageUploadPath = $imageUploadDir . '/' . $imageName;

		file_put_contents($imageUploadPath, $imageData);

		if (file_exists($imageUploadPath)) {
			return $imageUploadPath;
		}

		return false;
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
		$time = (string)time();
		$imageName = $this->postImageName ?: $time;

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
		$imageRelativePath = $_POST['relative_path'] ?: self::SOURCE_UNKNOWN;
		$type = $_POST['type'] ?: self::IMAGE_TYPE_THUMB;
		$imageUploadPath = self::IMAGE_BASE_PATH . $imageRelativePath . '/' . $type;

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
}

$imageUploader = new ImageUploader($_POST);





//// helper method
//$assetSiteUrl = "http://assets.apnaaccounts.com/";
//function make_dir( $path, $permissions = 0777 ) {
//	return is_dir($path) || mkdir($path, $permissions, true) || is_dir($path);
//}
//
//
//if (isset($_POST["image"])) {
//	$data = $_POST["image"];
//	$type = $_POST["type"] ?? 'thumb';
//
//	$imageBasePath = "croppie_uploads";
//	$imageRelativePath = "/site/products/1";
//	$imageDirectoryPath = $imageBasePath.$imageRelativePath.'/'.$type;
//
//	// create recursive directory path for image
//	make_dir($imageDirectoryPath);
//
//	$image_array_1 = explode(";", $data);
//	$image_array_2 = explode(",", $image_array_1[1]);
//
//	$data = base64_decode($image_array_2[1]);
//
//	$imageName = time() . '.webp';
//	$imageName = $imageDirectoryPath.'/'.$imageName;
//
//	file_put_contents($imageName, $data);
//
//	echo '<img src="' . $assetSiteUrl.$imageName . '" class="img-thumbnail" />';
//}
