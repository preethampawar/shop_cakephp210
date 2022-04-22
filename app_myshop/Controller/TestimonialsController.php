<?php
App::uses('CakeEmail', 'Network/Email');

class TestimonialsController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	function index()
	{
		$conditions = ['Testimonial.site_id' => $this->Session->read('Site.id')];
		$this->paginate = [
			'limit' => 50,
			'order' => ['Testimonial.created' => 'DESC'],
			'conditions' => $conditions,
			'recursive' => -1
		];
		$testimonials = $this->paginate();
		$title_for_layout = 'Testimonials';
		$this->set(compact('testimonials', 'title_for_layout'));
	}

	function show($testimonialId)
	{
		if (!$testimonialInfo = $this->isSiteTestimonial($testimonialId)) {
			$this->errorMsg('Page Not Found', 'default', ['class' => 'error']);
			$this->redirect('/admin/testimonials/');
		}

		$this->set(compact('testimonialInfo'));
	}

	function admin_index()
	{
		$sort = ['Testimonial.created' => 'DESC'];

		$conditions = ['Testimonial.site_id' => $this->Session->read('Site.id')];
		$testimonials = $this->Testimonial->find('all', ['conditions' => $conditions, 'order' => $sort]);

		$this->set(compact('testimonials'));
	}

	function admin_add()
	{
		$errorMsg = null;

		if ($this->request->isPost()) {
			$data = $this->request->data;

			if (empty($data['Testimonial']['title'])) {
				$errorMsg = 'Testimonial is a required field';
			} else {
				$conditions = ['Testimonial.title' => $data['Testimonial']['title'], 'Testimonial.site_id' => $this->Session->read('Site.id')];

				if ($this->Testimonial->find('first', ['conditions' => $conditions])) {
					$errorMsg = "Testimonial with same content already exist.";
				}
			}

			if (!$errorMsg) {
				$data['Testimonial']['title'] = $data['Testimonial']['title'];
				$data['Testimonial']['site_id'] = $this->Session->read('Site.id');

				if ($this->Testimonial->save($data)) {
					$testimonialInfo = $this->Testimonial->read();

					$this->successMsg('Testimonial created successfully.');
					$this->redirect('/admin/testimonials/');
				} else {
					$errorMsg = 'An error occurred while updating data';
				}
			}
		}

		($errorMsg) ? $this->errorMsg($errorMsg) : '';

		$this->set(compact('errorMsg'));
	}

	function admin_edit($testimonialId)
	{
		if (!$contentInfo = $this->isSiteTestimonial($testimonialId)) {
			$this->errorMsg('Testimonial Not Found');
			$this->redirect('/admin/testimonials/');
		}

		$errorMsg = null;

		if ($this->request->isPut()) {
			$data = $this->request->data;

			if (empty($data['Testimonial']['title'])) {
				$errorMsg = 'Title is a required field';
			} else {
				$conditions = ['Testimonial.title' => $data['Testimonial']['title'], 'Testimonial.id NOT' => $testimonialId, 'Testimonial.site_id' => $this->Session->read('Site.id')];

				if ($this->Testimonial->find('first', ['conditions' => $conditions])) {
					$errorMsg = "Testimonial with same title already exist.";
				}
			}

			if (!$errorMsg) {
				$data['Testimonial']['id'] = $testimonialId;

				if ($this->Testimonial->save($data)) {
					$this->successMsg('Testimonial updated successfully');
					$this->redirect('/admin/testimonials/edit/'.$testimonialId);
				} else {
					$errorMsg = 'An errorMsg occurred while updating data';
				}
			}
		} else {
			$this->data = $contentInfo;
		}
		$this->set(compact('errorMsg', 'contentInfo'));
	}

	public function admin_activate($testimonialId, $type)
	{
		if (!$contentInfo = $this->isSiteTestimonial($testimonialId)) {
			$this->errorMsg('Testimonial Not Found');
			$this->redirect('/admin/testimonials/');
		}

		$data['Testimonial']['id'] = $testimonialId;
		$data['Testimonial']['active'] = ($type == 'true') ? '1' : '0';

		if ($this->Testimonial->save($data)) {
			$this->successMsg('Testimonial modified successfully');
		} else {
			$this->errorMsg('An error occurred while updating data');
		}
		$this->redirect('/admin/testimonials/');
	}

	public function admin_delete($testimonialId)
	{
		if (!$contentInfo = $this->isSiteTestimonial($testimonialId)) {
			$this->errorMsg('Testimonial Not Found');
		} else {
			$this->Testimonial->delete($testimonialId);
			$this->successMsg('Testimonial deleted successfully');
		}
		$this->redirect('/admin/testimonials/');
	}

	public function slideShow($isAjax = 0)
	{
		if ((int)$isAjax === 1) {
			$this->layout = false;
		}

		$siteId = $this->Session->read('Site.id');

		$conditions = [
			'Testimonial.site_id' => $siteId,
			'Testimonial.active' => 1,
		];
		$fields = [
			'Testimonial.id',
			'Testimonial.title',
			'Testimonial.customer_name',
			'Testimonial.url',
		];
		$testimonials = $this->Testimonial->find('all', ['conditions' => $conditions, 'fields'=>$fields, 'order'=>'Testimonial.created DESC', 'recursive'=> -1]);

		$this->set('testimonials', $testimonials);
	}

}

?>
