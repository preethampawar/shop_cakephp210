<?php
App::uses('CakeEmail', 'Network/Email');

class BlogController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('show', 'index');
	}

	function index()
	{
		$hideLeftMenu = true;
		$conditions = ['Blog.site_id' => $this->Session->read('Site.id')];
		$this->paginate = [
			'limit' => 10,
			'order' => ['Blog.created' => 'DESC'],
			'conditions' => $conditions,
		];
		$blogs = $this->paginate();
		$title_for_layout = 'Blog';
		$this->set(compact('blogs', 'title_for_layout', 'hideLeftMenu'));
	}

	function show($blogID)
	{
		$hideLeftMenu = true;
		if (!$blogInfo = $this->isSiteBlog($blogID)) {
			$this->Session->setFlash('Page Not Found', 'default', ['class' => 'error']);
			$this->redirect('/admin/blog/');
		}

		$views = ($blogInfo['Blog']['views'] > 0) ? $blogInfo['Blog']['views'] : 0;

		// save visits count. increase views counter by 1.
		$data['Blog']['id'] = $blogID;
		$data['Blog']['views'] = $views + 1;
		$this->Blog->save($data);

		App::uses('Image', 'Model');
		$this->Image = new Image;
		$this->Image->recursive = -1;
		$images = $this->Image->findAllByBlogId($blogInfo['Blog']['id']);

		$title_for_layout = $blogInfo['Blog']['title'];
		$this->set(compact('blogInfo', 'title_for_layout', 'hideLeftMenu', 'images'));
	}

	function admin_index()
	{
		$sort = ['Blog.created' => 'DESC'];
		$blogLinkActive = true;

		$conditions = ['Blog.site_id' => $this->Session->read('Site.id')];
		$contents = $this->Blog->find('all', ['conditions' => $conditions, 'order' => $sort]);

		$this->set(compact('contents', 'blogLinkActive'));
	}

	function admin_add()
	{
		$errorMsg = null;
		$blogLinkActive = true;
		if ($this->request->isPost()) {
			$data = $this->request->data;
			$allowed = [' ', '-', '.', '+', '@', ',', '/', '&', '#', ':', '_'];

			if (empty($data['Blog']['title'])) {
				$errorMsg = 'Title is a required field';
			} else if (empty($data['Blog']['description'])) {
				$errorMsg = 'Description is required';
			} else {
				$conditions = ['Blog.title' => Sanitize::paranoid($data['Blog']['title'], $allowed), 'Blog.site_id' => $this->Session->read('Site.id')];
				if ($this->Blog->find('first', ['conditions' => $conditions])) {
					$errorMsg = "Article with same title already exist's";
				}
			}

			if (!$errorMsg) {
				$data['Blog']['title'] = Sanitize::paranoid($data['Blog']['title'], $allowed);
				$data['Blog']['tags'] = Sanitize::paranoid($data['Blog']['tags'], $allowed);
				$data['Blog']['meta_keywords'] = Sanitize::paranoid($data['Blog']['meta_keywords'], $allowed);
				$data['Blog']['meta_description'] = Sanitize::paranoid($data['Blog']['meta_description'], $allowed);
				$data['Blog']['site_id'] = $this->Session->read('Site.id');
				$data['Blog']['user_id'] = $this->Session->read('User.id');

				if ($this->Blog->save($data)) {
					$this->set('successMsg', 'Article Created Succesfully');
					$this->redirect('/admin/blog/');
				} else {
					$errorMsg = 'An errorMsg occured while updating data';
				}
			}
		}
		$this->set(compact('errorMsg', 'blogLinkActive'));
	}

	function admin_edit($blogID)
	{
		$blogLinkActive = true;
		if (!$contentInfo = $this->isSiteBlog($blogID)) {
			$this->Session->setFlash('Article Not Found', 'default', ['class' => 'error']);
			$this->redirect('/admin/blog/');
		}

		$errorMsg = null;
		$allowed = [' ', '-', '.', '+', '@', ',', '/', '&', '#', ':', '_'];
		if ($this->request->isPut()) {
			$data = $this->request->data;

			if (empty($data['Blog']['title'])) {
				$errorMsg = 'Title is a required field';
			} else if (empty($data['Blog']['description'])) {
				$errorMsg = 'Description is required';
			} else {
				$conditions = ['Blog.title' => $data['Blog']['title'], 'Blog.id NOT' => $blogID, 'Blog.site_id' => $this->Session->read('Site.id')];
				if ($this->Blog->find('first', ['conditions' => $conditions])) {
					$errorMsg = "Article with same title already exist's";
				}
			}

			if (!$errorMsg) {
				$data['Blog']['id'] = $blogID;
				$data['Blog']['title'] = Sanitize::paranoid($data['Blog']['title'], $allowed);
				$data['Blog']['tags'] = Sanitize::paranoid($data['Blog']['tags'], $allowed);
				$data['Blog']['meta_keywords'] = Sanitize::paranoid($data['Blog']['meta_keywords'], $allowed);
				$data['Blog']['meta_description'] = Sanitize::paranoid($data['Blog']['meta_description'], $allowed);

				if ($this->Blog->save($data)) {
					$this->Session->setFlash('Article modified succesfully', 'default', ['class' => 'success']);
					$this->redirect('/admin/blog/');
				} else {
					$errorMsg = 'An errorMsg occured while updating data';
				}
			}
		} else {
			$this->data = $contentInfo;
		}
		$this->set(compact('errorMsg', 'contentInfo', 'blogLinkActive'));
	}

	function admin_activate($blogID, $type)
	{
		if (!$contentInfo = $this->isSiteBlog($blogID)) {
			$this->Session->setFlash('Article Not Found', 'default', ['class' => 'error']);
			$this->redirect('/admin/blog/');
		}

		$data['Blog']['id'] = $blogID;
		$data['Blog']['active'] = ($type == 'true') ? '1' : '0';

		if ($this->Blog->save($data)) {
			$this->Session->setFlash('Article modified succesfully', 'default', ['class' => 'success']);
		} else {
			$this->Session->setFlash('An errorMsg occured while updating data');
		}
		$this->redirect('/admin/blog/');
	}

	function admin_delete($blogID)
	{
		if (!$contentInfo = $this->isSiteBlog($blogID)) {
			$this->Session->setFlash('Article Not Found', 'default', ['class' => 'error']);
		} else {
			$this->Blog->delete($blogID);
			$this->Session->setFlash('Article deleted succesfully', 'default', ['class' => 'success']);
		}
		$this->redirect('/admin/blog/');
	}

}

?>
