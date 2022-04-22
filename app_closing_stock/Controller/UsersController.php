<?php

class UsersController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('login', 'index');
		$this->set('hideSideBar', true);
	}

	public function login()
	{
		$hideSideBar = true;
		$title_for_layout = 'Log In';

		if ($this->Auth->loggedIn()) {
			$this->redirect($this->Auth->redirectUrl());
		}
		$showErrors = false;
		if ($this->request->is('post')) {
			$showErrors = false;
			if ($this->Auth->login()) {
				// check for admin
				if (Configure::read('Access.admin') == $this->request->data['User']['email']) {
					if (Configure::read('Access.key') == (md5($this->request->data['User']['email']))) {
						$this->Session->write('manager', 1);
					}
				} else {
					$this->Session->write('manager', 0);
				}
				$this->redirect($this->Auth->redirectUrl());
			} else {
				$this->errorMsg('Invalid email or password, try again');
			}
		}
		$this->set(compact('hideSideBar', 'title_for_layout', 'showErrors'));
	}

	public function logout()
	{
		$this->Session->destroy();
		$this->redirect($this->Auth->logout());
	}

	public function index()
	{
		$this->redirect(['controller' => 'stores', 'action' => 'index']);

		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	public function view($id = null)
	{
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

	public function add()
	{
		$hideSideBar = true;

		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$msg = 'User has been created successfully';
				$this->Session->setFlash($msg, 'default', ['class' => 'success']);
				$this->redirect(['controller' => 'stores', 'action' => 'index']);
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
		$this->set(compact('hideSideBar'));
	}

	public function edit($id = null)
	{
		$hideSideBar = true;

		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$msg = 'User updated successfully';
				$this->Session->setFlash($msg, 'default', ['class' => 'success']);

				$this->redirect(['controller' => 'stores', 'action' => 'index']);
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
			unset($this->request->data['User']['password']);
		}
		$this->set(compact('hideSideBar'));
	}

	public function delete($id = null)
	{
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$msg = 'User has been deleted successfully';
			$this->Session->setFlash($msg, 'default', ['class' => 'success']);

			$this->redirect(['controller' => 'stores', 'action' => 'index']);
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(['controller' => 'stores', 'action' => 'index']);
	}
}
