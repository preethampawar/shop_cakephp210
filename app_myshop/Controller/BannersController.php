<?php
App::uses('CakeEmail', 'Network/Email');

class BannersController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	function index()
	{
		$hideLeftMenu = true;
		$conditions = ['Banner.site_id' => $this->Session->read('Site.id')];
		$this->paginate = [
			'limit' => 10,
			'order' => ['Banner.created' => 'DESC'],
			'conditions' => $conditions,
		];
		$banners = $this->paginate();
		$title_for_layout = 'Banner';
		$this->set(compact('banners', 'title_for_layout', 'hideLeftMenu'));
	}

	function show($bannerId)
	{
		if (!$bannerInfo = $this->isSiteBanner($bannerId)) {
			$this->errorMsg('Page Not Found', 'default', ['class' => 'error']);
			$this->redirect('/admin/banners/');
		}

		$this->set(compact('bannerInfo'));
	}

	function admin_index()
	{
		$sort = ['Banner.created' => 'DESC'];

		$conditions = ['Banner.site_id' => $this->Session->read('Site.id')];
		$banners = $this->Banner->find('all', ['conditions' => $conditions, 'order' => $sort]);

		$this->set(compact('banners'));
	}

	function admin_add()
	{
		$errorMsg = null;

		if ($this->request->isPost()) {
			$data = $this->request->data;

			if (empty($data['Banner']['title'])) {
				$errorMsg = 'Title is a required field';
			} else {
				$conditions = ['Banner.title' => $data['Banner']['title'], 'Banner.site_id' => $this->Session->read('Site.id')];

				if ($this->Banner->find('first', ['conditions' => $conditions])) {
					$errorMsg = "Banner with same title already exist.";
				}
			}

			if (!$errorMsg) {
				$data['Banner']['title'] = $data['Banner']['title'];
				$data['Banner']['site_id'] = $this->Session->read('Site.id');

				if ($this->Banner->save($data)) {
					$bannerInfo = $this->Banner->read();

					$this->successMsg('Banner created successfully.');
					$this->redirect('/admin/banners/edit/'.$bannerInfo['Banner']['id']);
				} else {
					$errorMsg = 'An errorMsg occurred while updating data';
				}
			}
		}

		($errorMsg) ? $this->errorMsg($errorMsg) : '';

		$this->set(compact('errorMsg'));
	}

	function admin_edit($bannerId)
	{
		if (!$contentInfo = $this->isSiteBanner($bannerId)) {
			$this->errorMsg('Banner Not Found');
			$this->redirect('/admin/banners/');
		}

		$errorMsg = null;

		if ($this->request->isPut()) {
			$data = $this->request->data;

			if (empty($data['Banner']['title'])) {
				$errorMsg = 'Title is a required field';
			} else {
				$conditions = ['Banner.title' => $data['Banner']['title'], 'Banner.id NOT' => $bannerId, 'Banner.site_id' => $this->Session->read('Site.id')];

				if ($this->Banner->find('first', ['conditions' => $conditions])) {
					$errorMsg = "Banner with same title already exist.";
				}
			}

			if (!$errorMsg) {
				$data['Banner']['id'] = $bannerId;

				if ($this->Banner->save($data)) {
					$this->successMsg('Banner updated successfully');
					$this->redirect('/admin/banners/edit/'.$bannerId);
				} else {
					$errorMsg = 'An errorMsg occurred while updating data';
				}
			}
		} else {
			$this->data = $contentInfo;
		}
		$this->set(compact('errorMsg', 'contentInfo'));
	}

	public function admin_updateImage($bannerId)
	{
		$this->layout = false;
		$msg = 'Invalid request';
		$error = true;

		$isImageUrlSet = $this->request->data['imagePath'] ?? false;

		if ($isImageUrlSet && ($this->request->isPost() || $this->request->isPut())) {
			if ($bannerInfo = $this->isSiteBanner($bannerId)) {
				$images = [];

				if ($bannerInfo['Banner']['images']) {
					$images = json_decode($bannerInfo['Banner']['images']);
				}
				$images[] = [
					'imagePath' => $this->request->data['imagePath'],
					'type' => $this->request->data['imageType'],
					'commonId' => $this->request->data['commonId'] ?? rand(1, 10000),
					'caption' => '',
					'highlight' => 0,
				];

				$tmp['Banner']['id'] = $bannerId;
				$tmp['Banner']['images'] = json_encode($images);

				if ($this->Banner->save($tmp)) {
					$error = false;
					$msg = 'Banner image updated successfully';
				} else {
					$msg = 'Banner image update failed';
				}
			} else {
				$msg = 'Banner not found';
			}
		}

		$this->response->header('Content-type', 'application/json');
		$this->response->body(json_encode([
				'error' => $error,
				'msg' => $msg,
			], JSON_THROW_ON_ERROR)
		);
		$this->response->send();
		exit;
	}


	public function admin_highlightImage($bannerId, $imageCommonId)
	{
		$redirectURL = $this->request->referer();
		if (!$bannerInfo = $this->isSiteBanner($bannerId)) {
			$this->errorMsg('Image not found');
		} else {

			if (!$bannerInfo['Banner']['images']) {
				$this->redirect($redirectURL);
			}

			$images = json_decode($bannerInfo['Banner']['images']);

			foreach ($images as &$image) {
				$image->highlight = 0;
				if ($image->commonId == $imageCommonId) {
					$image->highlight = 1;
				}
			}

			$tmp['Banner']['id'] = $bannerId;
			$tmp['Banner']['images'] = json_encode($images);

			if ($this->Banner->save($tmp)) {
				$msg = 'Banner image updated successfully';
				$this->successMsg($msg);
			} else {
				$msg = 'Banner image update failed';
				$this->errorMsg($msg);
			}

		}

		$this->redirect($redirectURL);
	}

	public function admin_deleteImage($bannerId, $imageCommonId)
	{
		$redirectURL = $this->request->referer();
		if (!$bannerInfo = $this->isSiteBanner($bannerId)) {
			$this->errorMsg('Image not found');
		} else {

			if (!$bannerInfo['Banner']['images']) {
				$this->redirect($redirectURL);
			}

			$images = json_decode($bannerInfo['Banner']['images']);
			$tmpImages = [];

			foreach ($images as $index => $image) {
				if ($image->commonId != $imageCommonId) {
					$tmpImages[] = $image;
				}
			}

			$tmp['Banner']['id'] = $bannerId;
			$tmp['Banner']['images'] = $tmpImages ? json_encode($tmpImages) : null;
			if ($this->Banner->save($tmp)) {
				$msg = 'Banner image updated successfully';
				$this->successMsg($msg);
			} else {
				$msg = 'Banner image update failed';
				$this->errorMsg($msg);
			}

		}
		$this->redirect($redirectURL);
	}

	public function admin_activate($bannerId, $type)
	{
		if (!$contentInfo = $this->isSiteBanner($bannerId)) {
			$this->errorMsg('Article Not Found');
			$this->redirect('/admin/banners/');
		}

		$data['Banner']['id'] = $bannerId;
		$data['Banner']['active'] = ($type == 'true') ? '1' : '0';

		if ($this->Banner->save($data)) {
			$this->successMsg('Article modified successfully');
		} else {
			$this->errorMsg('An errorMsg occurred while updating data');
		}
		$this->redirect('/admin/banners/');
	}

	public function admin_delete($bannerId)
	{
		if (!$contentInfo = $this->isSiteBanner($bannerId)) {
			$this->errorMsg('Article Not Found');
		} else {
			$this->Banner->delete($bannerId);
			$this->successMsg('Article deleted successfully');
		}
		$this->redirect('/admin/banners/');
	}

	public function slideShow($isAjax = 0)
	{
		if ((int)$isAjax === 1) {
			$this->layout = false;
		}

		$siteId = $this->Session->read('Site.id');

		$conditions = [
			'Banner.site_id' => $siteId,
			'Banner.active' => 1,
		];
		$fields = [
			'Banner.id',
			'Banner.title',
			'Banner.description',
			'Banner.images',
			'Banner.url',
		];
		$banners = $this->Banner->find('all', ['conditions' => $conditions, 'fields'=>$fields, 'order'=>'Banner.created DESC', 'recursive'=> -1]);

		$this->set('banners', $banners);
	}

}

?>
