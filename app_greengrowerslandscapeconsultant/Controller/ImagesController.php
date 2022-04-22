<?php
class ImagesController extends AppController
{
	var $name = 'Images';
	var $imgPath = 'img/images/';
	var $cachePath = 'img/cache/';

	function uploadImage($params)
	{
		if($this->isValidFile($params))
		{			
			return $this->saveFile($params);
		}
		return false;
	}
	
	function isValidFile($params)
	{		
		if ((isset($params['error']) && $params['error'] == 0) || (!empty( $params['tmp_name']) && $params['tmp_name'] != 'none')) 
		{
			return is_uploaded_file($params['tmp_name']);
		}
		return false;
	}
	
	function saveFile($params, $caption='')
	{		
		$image['Image']['caption'] = $caption;
		$image['Image']['type'] = $params['type'];
		$image['Image']['extension'] = $this->getFileExtension($params['name']);
		if($this->Image->save($image))
		{
			$imageInfo = $this->Image->read();
			$filename = $imageInfo['Image']['id'];
			if(move_uploaded_file($params['tmp_name'], $this->imgPath.$filename))
			{				
				return $filename;
			}
			else
			{
				$this->Image->delete($filename);
			}
		}
		return false;
	}
	
	function getFileExtension($filename)
	{
		return substr($filename, strrpos($filename, '.'));
	}
	
	function get($imageId, $resizeType)
	{
		if($imgPath = $this->resize($imageId, $resizeType))
		{
			if(file_exists($imgPath))
			{
				return $imgPath;
			}			
		}		
		return $_SERVER['HTTP_HOST'].'/img/noimage.jpg';
	}
	
	function admin_manageProductImages($productID, $categoryID = null) {
		$productInfoLinkActive = true;
		// check if product belongs to the selected site
		if(!$productInfo = $this->isSiteProduct($productID)) {
			$this->Session->setFlash('Product Not Found', 'default', array('class'=>'error'));
			if(!empty($categoryID)) {
				$this->redirect('/admin/categories/showProducts/'.$categoryID);			
			}
			else {
				$this->redirect('/admin/products/');
			}
		}
		
		$errorMsg = null;
		$successMsg = null;
		if($this->request->isPost()) {			
			$data = $this->request->data;
			if(!empty($data['Image']['file']['name'])) {
				$results = $this->admin_uploadImage($data);
				
				if($results['errorMsg']) {
					$errorMsg = $results['errorMsg'];					
				}
				else {
					$imageID = $results['imageID'];
					$tmp['Image']['id'] = $imageID;
					$tmp['Image']['product_id'] = $productID;
					$tmp['Image']['caption'] = $data['Image']['caption'];
					$tmp['Image']['highlight'] = $data['Image']['highlight'];
					if($this->Image->save($tmp)) {
						$successMsg = 'Image uploaded successfully';
					}
					else {
						$errorMsg = 'An error occured while communicating with the server';
					}
				}	
			}
		}
		
		$productImages = $this->Image->findAllByProductId($productID);		
		$this->set(compact('productInfo', 'successMsg', 'errorMsg', 'productImages', 'categoryID', 'productInfoLinkActive'));
	}
	
	function admin_manageLandingPageImages() {
		$pagesLinkActive = true;
		
		// check if product belongs to the selected site
		if(!$contentInfo = $this->getLandingPageInfo()) {
			$this->Session->setFlash('Landing Page Not Found', 'default', array('class'=>'error'));			
			$this->redirect('/admin/contents/');			
		}
		
		$errorMsg = null;
		$successMsg = null;
		if($this->request->isPost()) {	
			// Check for max images allowed in landing page
			$maxImages = '50';			
			$uploadedImagesCount = $this->Image->find('count', array('conditions'=>array('Image.content_id'=>$contentInfo['Content']['id'])));
						
			if($uploadedImagesCount >= $maxImages) {
				$this->Session->setFlash('You have reached your maximum limit to upload images. Contact eNursery Admin to add more images.', 'default', array('class'=>'error'));			
				$this->redirect('/admin/contents/');
			}
		
			$data = $this->request->data;
			if(!empty($data['Image']['file']['name'])) {
				$results = $this->admin_uploadImage($data);
				
				if($results['errorMsg']) {
					$errorMsg = $results['errorMsg'];					
				}
				else {
					$imageID = $results['imageID'];
					$tmp['Image']['id'] = $imageID;
					$tmp['Image']['content_id'] = $contentInfo['Content']['id'];
					$tmp['Image']['caption'] = $data['Image']['caption'];
					$tmp['Image']['highlight'] = $data['Image']['highlight'];
					if($this->Image->save($tmp)) {
						$successMsg = 'Image uploaded successfully';
						$this->redirect('/admin/images/manageLandingPageImages');	
					}
					else {
						$errorMsg = 'An error occured while communicating with the server';
					}
				}	
			}
		}
		
		$contentImages = $this->Image->findAllByContentId($contentInfo['Content']['id']);		
		$this->set(compact('contentInfo', 'successMsg', 'errorMsg', 'contentImages', 'pagesLinkActive'));
	}
	
	function admin_manageCustomPageImages($contentID) {
		$pagesLinkActive = true;
		App::uses('Content', 'Model');
		$this->Content = new Content;
		$contentInfo = $this->Content->findById($contentID);
		
		// check if product belongs to the selected site
		if(empty($contentInfo)) {
			$this->Session->setFlash('Page Not Found', 'default', array('class'=>'error'));			
			$this->redirect('/admin/contents/');			
		}
		
		$errorMsg = null;
		$successMsg = null;
		if($this->request->isPost()) {	
			// Check for max images allowed in landing page
			$maxImages = '30';			
			$uploadedImagesCount = $this->Image->find('count', array('conditions'=>array('Image.content_id'=>$contentInfo['Content']['id'])));
						
			if($uploadedImagesCount >= $maxImages) {
				$this->Session->setFlash('You have reached your maximum limit to upload images. Contact eNursery Admin to add more images.', 'default', array('class'=>'error'));			
				$this->redirect('/admin/contents/');
			}
		
			$data = $this->request->data;
			if(!empty($data['Image']['file']['name'])) {
				$results = $this->admin_uploadImage($data);
				
				if($results['errorMsg']) {
					$errorMsg = $results['errorMsg'];					
				}
				else {
					$imageID = $results['imageID'];
					$tmp['Image']['id'] = $imageID;
					$tmp['Image']['content_id'] = $contentInfo['Content']['id'];
					$tmp['Image']['caption'] = $data['Image']['caption'];
					$tmp['Image']['highlight'] = $data['Image']['highlight'];
					if($this->Image->save($tmp)) {
						$successMsg = 'Image uploaded successfully';
						$this->redirect('/admin/images/manageCustomPageImages/'.$contentInfo['Content']['id']);	
					}
					else {
						$errorMsg = 'An error occured while communicating with the server';
					}
				}	
			}
		}
		
		$contentImages = $this->Image->findAllByContentId($contentInfo['Content']['id']);		
		$this->set(compact('contentInfo', 'successMsg', 'errorMsg', 'contentImages', 'pagesLinkActive'));
	}
	
	function admin_uploadImage($data) {
		$status = array();
		$imageID = null;
		$errorMsg = null;
		
		// upload image
		if(!empty($data['Image']['file']['name']))
		{
			if(!$this->isValidImageSize($data['Image']['file']['size'])) {	
				$errorMsg =  'Image size exceeded '.Configure::read('MaxImageSize').'Mb limit';
			}
			elseif(!$this->isValidImage($data['Image']['file'])) {
				$errorMsg = 'Not a valid image';
			}
			else {					
				$imageID = $this->uploadImage($data['Image']['file']);										
				$tmp['Image']['id'] = $imageID;
				$tmp['Image']['site_id'] = $this->Session->read('Site.id');
				App::uses('Image', 'Model');
				$this->Image = new Image;
				if(!$this->Image->save($tmp))
				{
					$Images->delete($imageID);
					$errorMsg = 'Image could not be uploaded';
				}				
			}
		}
		else
		{
			$errorMsg = 'Select an Image to upload';
		}
		
		$status['errorMsg'] = $errorMsg;
		$status['imageID'] = $imageID;
		return $status;
	}
	
	function admin_updateCaption() {
		$redirectURI = $this->request->referer();
		if($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;
			$imageID = $data['Image']['id'];
			$imageCaption = $data['Image']['caption'];
			
			$imageData['Image']['id'] = $imageID;
			$imageData['Image']['caption'] = $imageCaption;			
			if($this->Image->save($imageData)) {
				$this->Session->setFlash('Caption updated successfully.', 'default', array('class'=>'success'));	
			}
			else {
				$this->Session->setFlash('An error occured while communicating with the server.', 'default', array('class'=>'error'));	
			}			
		}
		else {
			$this->Session->setFlash('Invalid request.', 'default', array('class'=>'error'));			
		}
		$this->redirect($redirectURI);
		
	}
	
	function admin_highlight($imageID, $productID) {		
		$redirectURL = $this->request->referer();
		if(!$productInfo = $this->isSiteProduct($productID)) {
			$this->Session->setFlash('Image Not Found', 'default', array('class'=>'error'));			
		}
		else {
			$images = $this->Image->findAllByProductId($productID);
			if(!empty($images)) {
				foreach($images as $row) {
					$data = array();
					$data['Image']['id'] = $row['Image']['id'];
					$data['Image']['highlight'] = '0';
					$this->Image->save($data);
				}
				
				$data = array();
				$data['Image']['id'] = $imageID;
				$data['Image']['highlight'] = '1';
				$this->Image->save($data);
				$this->Session->setFlash('Image Successfully Highlighted', 'default', array('class'=>'success'));
			}
			else {
				$this->Session->setFlash('No Image Found', 'default', array('class'=>'error'));			
			}
		}	
		$this->redirect($redirectURL);
	}
	
	function admin_highlightContentImage($imageID, $contentID) {		
		$redirectURL = $this->request->referer();
		if(!$contentInto = $this->isSiteContent($contentID)) {
			$this->Session->setFlash('Image Not Found', 'default', array('class'=>'error'));			
		}
		else {
			$images = $this->Image->findAllByContentId($contentID);
			if(!empty($images)) {
				foreach($images as $row) {
					$data = array();
					$data['Image']['id'] = $row['Image']['id'];
					$data['Image']['highlight'] = '0';
					$this->Image->save($data);
				}
				
				$data = array();
				$data['Image']['id'] = $imageID;
				$data['Image']['highlight'] = '1';
				$this->Image->save($data);
				$this->Session->setFlash('Image Successfully Highlighted', 'default', array('class'=>'success'));
			}
			else {
				$this->Session->setFlash('No Image Found', 'default', array('class'=>'error'));			
			}
		}	
		$this->redirect($redirectURL);
	}
	
	function admin_highlightLandingPageImage($imageID, $contentID) {		
		$redirectURL = $this->request->referer();
		if(!$contentInto = $this->isSiteContent($contentID)) {
			$this->Session->setFlash('Image Not Found', 'default', array('class'=>'error'));			
		}
		else {
			$images = $this->Image->findAllByContentId($contentID);
			if(!empty($images)) {								
				$data = array();
				$data['Image']['id'] = $imageID;
				$data['Image']['highlight'] = '1';
				$this->Image->save($data);
				$this->Session->setFlash('Image Successfully Highlighted', 'default', array('class'=>'success'));
			}
			else {
				$this->Session->setFlash('No Image Found', 'default', array('class'=>'error'));			
			}
		}	
		$this->redirect($redirectURL);
	}
	function admin_removeHighlightLandingPageImage($imageID, $contentID) {		
		$redirectURL = $this->request->referer();
		if(!$contentInto = $this->isSiteContent($contentID)) {
			$this->Session->setFlash('Image Not Found', 'default', array('class'=>'error'));			
		}
		else {
			$images = $this->Image->findAllByContentId($contentID);
			if(!empty($images)) {				
				$data = array();
				$data['Image']['id'] = $imageID;
				$data['Image']['highlight'] = '0';
				$this->Image->save($data);
				$this->Session->setFlash('Image has been successfully removed from Highlights', 'default', array('class'=>'success'));
			}
			else {
				$this->Session->setFlash('No Image Found', 'default', array('class'=>'error'));			
			}
		}	
		$this->redirect($redirectURL);
	}
	
	function admin_deleteImage($imageID) {
		if($this->isSiteImage($imageID)) {
			$this->deleteImage($imageID);
			$this->Session->setFlash('Image Deleted Successfully', 'default', array('class'=>'success'));			
		}
		else {
			$this->Session->setFlash('Image Not Found', 'default', array('class'=>'error'));						
		}
		$this->redirect($this->request->referer());		
	}
	
}
?>