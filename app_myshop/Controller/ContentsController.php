<?php
App::uses('CakeEmail', 'Network/Email');

class ContentsController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('show', 'getTopNavContent', 'getFooterNavContent', 'getAllContent', 'showcase');
	}

	function getAllContent()
	{
		$siteID = $this->Session->read('Site.id');
		$conditions = ['Content.site_id' => $siteID, 'Content.active' => '1'];
		$contents = $this->Content->find('all', ['conditions' => $conditions, 'recursive' => '-1']);

		return $contents;
	}

	function getTopNavContent()
	{
		$siteID = $this->Session->read('Site.id');
		$conditions = ['Content.site_id' => $siteID, 'Content.top_nav_menu' => '1', 'Content.active' => '1'];
		$contents = $this->Content->find('all', ['conditions' => $conditions, 'recursive' => '-1', 'order' => 'Content.priority']);

		return $contents;
	}

	function getFooterNavContent()
	{
		$siteID = $this->Session->read('Site.id');
		$conditions = ['Content.site_id' => $siteID, 'Content.footer_menu' => '1', 'Content.active' => '1'];
		$contents = $this->Content->find('all', ['conditions' => $conditions, 'order' => 'Content.priority']);
		return $contents;
	}

	function show($contentID)
	{
		$hideLeftMenu = true;
		if (!$contentInfo = $this->isSiteContent($contentID)) {
			$this->Session->setFlash('Page Not Found', 'default', ['class' => 'error']);
			$this->redirect('/admin/contents/');
		}
		App::uses('Image', 'Model');
		$this->Image = new Image;
		$this->Image->recursive = -1;
		$images = $this->Image->findAllByContentId($contentInfo['Content']['id']);
		$title_for_layout = $contentInfo['Content']['title'];
		$this->set(compact('contentInfo', 'title_for_layout', 'hideLeftMenu', 'images'));
	}

	/**
	 * Function to show landing page
	 */
	function showcase()
	{
		$hideLeftMenu = true;
		if (!$this->checkLandingPage()) {
			$this->redirect('/');
		}
		$contentInfo = $this->getLandingPageInfo();

		App::uses('Image', 'Model');
		$this->Image = new Image;
		$this->Image->recursive = -1;
		$images = $this->Image->findAllByContentId($contentInfo['Content']['id']);
		$contentInfo['Images'] = ($images) ? $images : [];

		$this->set(compact('contentInfo', 'hideLeftMenu'));

	}

	function admin_index()
	{
		$sort = ['Content.title' => 'ASC'];
		$pagesLinkActive = true;

		$conditions = ['Content.site_id' => $this->Session->read('Site.id'), 'Content.landing_page' => '0'];
		$contents = $this->Content->find('all', ['conditions' => $conditions, 'order' => $sort]);

		// get landing page info
		$conditions = [];
		$conditions = ['Content.site_id' => $this->Session->read('Site.id'), 'Content.landing_page' => '1'];
		$landingPageContent = $this->Content->find('first', ['conditions' => $conditions]);

		$this->set(compact('contents', 'pagesLinkActive', 'landingPageContent'));
	}

	function admin_add()
	{
		// check no of pages
		$contents = $this->Content->find('all', ['conditions' => ['Content.site_id' => $this->Session->read('Site.id'), 'Content.landing_page' => '0']]);
		$defaultMaxPages = Configure::read('MaxPages');
		$sitePages = count($contents);
		if ($defaultMaxPages) {
			if (!$this->Session->read('SuperAdmin')) {
				if ($sitePages >= $defaultMaxPages) {
					$this->Session->setFlash('You have reached your limit to create dynamic pages. Contact eNursery admin to create a new page.', 'default', ['class' => 'error']);
					$this->redirect($this->request->referer());
				}
			}
		}

		$pagePriority = $this->Content->find('all', ['conditions' => ['Content.site_id' => $this->Session->read('Site.id')], 'fields' => ['(MAX(Content.priority)+1) as highest_priority']]);
		$pagePriority = $pagePriority[0][0]['highest_priority'];

		$errorMsg = null;
		$pagesLinkActive = true;
		if ($this->request->isPost()) {
			$data = $this->request->data;
			$allowed = [' ', '-', '.', '+', '@', ',', '/', '&', '#', ':', '_'];

			if (empty($data['Content']['title'])) {
				$errorMsg = 'Title is a required field';
			} else if (empty($data['Content']['description'])) {
				$errorMsg = 'Description is required';
			} else {
				$conditions = ['Content.title' => Sanitize::paranoid($data['Content']['title'], $allowed), 'Content.site_id' => $this->Session->read('Site.id')];
				if ($this->Content->find('first', ['conditions' => $conditions])) {
					$errorMsg = "Page with same title already exist's";
				}
			}

			if (!$errorMsg) {
				$data['Content']['title'] = Sanitize::paranoid($data['Content']['title'], $allowed);
				$data['Content']['meta_keywords'] = Sanitize::paranoid($data['Content']['meta_keywords'], $allowed);
				$data['Content']['meta_description'] = Sanitize::paranoid($data['Content']['meta_description'], $allowed);
				$data['Content']['site_id'] = $this->Session->read('Site.id');

				if ($this->Content->save($data)) {
					$this->set('successMsg', 'Page Created Succesfully');
					$this->redirect('/admin/contents/');
				} else {
					$errorMsg = 'An errorMsg occured while updating data';
				}
			}
		}
		$this->set(compact('errorMsg', 'pagesLinkActive', 'pagePriority'));
	}

	function admin_editLandingPage()
	{
		$pagesLinkActive = true;
		$contentInfo = $this->getLandingPageInfo();
		$errorMsg = null;

		if (!empty($contentInfo)) {
			if ($this->request->isPut() or $this->request->isPost()) {
				$data = $this->request->data;
				if (!$errorMsg) {
					$data['Content']['id'] = $contentInfo['Content']['id'];
					$data['Content']['meta_keywords'] = Sanitize::paranoid($data['Content']['meta_keywords'], $allowed = [' ', '-', ',']);
					$data['Content']['meta_description'] = Sanitize::paranoid($data['Content']['meta_description'], $allowed = [' ', '-', ',']);

					if ($this->Content->save($data)) {
						$this->Session->setFlash('Page modified succesfully', 'default', ['class' => 'success']);
						$this->redirect('/admin/contents/');
					} else {
						$errorMsg = 'An errorMsg occured while updating data';
					}
				}
			} else {
				$this->data = $contentInfo;
			}
		} else {
			$this->Session->setFlash('Landing Page Not Found', 'default', ['class' => 'error']);
			$this->redirect('/admin/contents/');
		}

		$this->set(compact('errorMsg', 'contentInfo', 'pagesLinkActive'));
	}

	function admin_edit($contentID)
	{
		$pagesLinkActive = true;
		if (!$contentInfo = $this->isSiteContent($contentID)) {
			$this->Session->setFlash('Page Not Found', 'default', ['class' => 'error']);
			$this->redirect('/admin/contents/');
		}

		$pagePriority = $this->Content->find('all', ['conditions' => ['Content.site_id' => $this->Session->read('Site.id')], 'fields' => ['(MAX(Content.priority)+1) as highest_priority']]);
		$pagePriority = $pagePriority[0][0]['highest_priority'];

		$errorMsg = null;
		$allowed = [' ', '-', '.', '+', '@', ',', '/', '&', '#', ':', '_'];
		if ($this->request->isPut()) {
			$data = $this->request->data;

			if (empty($data['Content']['title'])) {
				$errorMsg = 'Title is a required field';
			} else if (empty($data['Content']['description'])) {
				$errorMsg = 'Description is required';
			} else {
				$conditions = ['Content.title' => $data['Content']['title'], 'Content.id NOT' => $contentID, 'Content.site_id' => $this->Session->read('Site.id')];
				if ($this->Content->find('first', ['conditions' => $conditions])) {
					$errorMsg = "Page with same title already exist's";
				}
			}

			if (!$errorMsg) {
				$data['Content']['id'] = $contentID;
				$data['Content']['title'] = Sanitize::paranoid($data['Content']['title'], $allowed);
				$data['Content']['meta_keywords'] = Sanitize::paranoid($data['Content']['meta_keywords'], $allowed);
				$data['Content']['meta_description'] = Sanitize::paranoid($data['Content']['meta_description'], $allowed);

				if ($this->Content->save($data)) {
					$this->Session->setFlash('Page modified succesfully', 'default', ['class' => 'success']);
					$this->redirect('/admin/contents/');
				} else {
					$errorMsg = 'An errorMsg occured while updating data';
				}
			}
		} else {
			$this->data = $contentInfo;
		}
		$this->set(compact('errorMsg', 'contentInfo', 'pagesLinkActive', 'pagePriority'));
	}

	function admin_activate($contentID, $type)
	{
		if (!$contentInfo = $this->isSiteContent($contentID)) {
			$this->Session->setFlash('Page Not Found', 'default', ['class' => 'error']);
			$this->redirect('/admin/contents/');
		}

		$data['Content']['id'] = $contentID;
		$data['Content']['active'] = ($type == 'true') ? '1' : '0';

		App::uses('Content', 'Model');
		$this->Content = new Content;

		if ($this->Content->save($data)) {
			$this->Session->setFlash('Page modified succesfully', 'default', ['class' => 'success']);
		} else {
			$this->Session->setFlash('An errorMsg occured while updating data');
		}
		$this->redirect('/admin/contents/');
	}

	function admin_delete($contentID, $type)
	{
		if (!$contentInfo = $this->isSiteContent($contentID)) {
			$this->Session->setFlash('Page Not Found', 'default', ['class' => 'error']);
		} else {
			$this->Content->delete($contentID);
			$this->Session->setFlash('Page deleted succesfully', 'default', ['class' => 'success']);
		}
		$this->redirect('/admin/contents/');
	}

}

?>
