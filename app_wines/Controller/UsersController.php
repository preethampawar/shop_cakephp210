<?php
class UsersController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login', 'index');
		$this->set('hideSideBar', true);
    }

	public function login() {
		$hideSideBar = true;
		$title_for_layout = 'Log In';

		if($this->Auth->loggedIn()) {
			$this->redirect($this->Auth->redirectUrl());
		}
		$showErrors = false;
		if ($this->request->is('post')) {
			$showErrors = false;
			if ($this->Auth->login()) {
				// check for admin
				if(Configure::read('Access.admin') == $this->request->data['User']['email']) {
					if(Configure::read('Access.key') == (md5($this->request->data['User']['email']))) {
						$this->Session->write('manager', 1);
					}
				}
				else {
					$this->Session->write('manager', 0);
				}

				debug($this->Session->read());
				$this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Session->setFlash(__('Invalid email or password, try again'));
			}
		}
		$this->set(compact('hideSideBar', 'title_for_layout', 'showErrors'));
	}

	public function logout() {
		$this->Session->destroy();
		$this->redirect($this->Auth->logout());
	}

    public function index() {
        $this->onlyManagerCanAccess();

        $this->User->recursive = 0;
        $this->set('users', $this->User->find('all'));
    }

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    public function add() {
        $this->onlyManagerCanAccess();

        $hideSideBar = true;

        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
				$msg = 'User has been created successfully';
                $this->successMsg($msg);
                $this->redirect(array('controller' => 'users', 'action'=>'index'));
            } else {
                $msg = "The user could not be saved. Please, try again.";
                $this->errorMsg($msg);
            }
        }
		$this->set(compact('hideSideBar'));
    }

    public function edit($id = null) {
        $this->onlyManagerCanAccess();

		$hideSideBar = true;

        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $data['User']['id'] = $id;
            if($this->request->data['User']['password']) {
                $data['User']['password'] = $this->request->data['User']['password'];
            }
            if($this->request->data['User']['name']) {
                $data['User']['name'] = $this->request->data['User']['name'];
            }
            if($this->request->data['User']['email']) {
                $data['User']['email'] = $this->request->data['User']['email'];
            }
            if($this->request->data['User']['store_password']) {
                $data['User']['store_password'] = $this->request->data['User']['store_password'];
            }

            $data['User']['feature_store_access_passwords'] = $this->request->data['User']['feature_store_access_passwords'];

            if ($this->User->save($data)) {
				$msg = 'User updated successfully';
				$this->successMsg($msg);
                $this->redirect(array('controller' => 'users', 'action'=>'index'));
            } else {
                $msg = 'The user could not be saved. Please, try again.';
                $this->errorMsg($msg);
                $this->Session->setFlash(__($msg));
            }
        } else {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
		$this->set(compact('hideSideBar'));
    }

    public function delete($id = null) {
        $this->onlyManagerCanAccess();

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
			$this->Session->setFlash($msg, 'default', array('class'=>'success'));

            $this->redirect(array('controller' => 'stores', 'action'=>'index'));
        }
        $this->Session->setFlash(__('User was not deleted'));
        $this->redirect(array('controller' => 'stores', 'action'=>'index'));
    }

	public function changePassword($id = null) {
		$hideSideBar = true;

		$this->User->id = $id;
		$password = null;
		$confirmPassword = null;

		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		} elseif(!$this->userIsManager() && $this->Session->read('Auth.User.id') != $id) {
			throw new NotFoundException(__('Unauthorized access.'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			$data['User']['id'] = $id;

			if($this->request->data['User']['password']) {
				$password = trim($this->request->data['User']['password']);
			}
			if($this->request->data['User']['confirmPassword']) {
				$confirmPassword = trim($this->request->data['User']['confirmPassword']);
			}

			if (empty($password) || empty($confirmPassword)) {
				$msg = 'Password and Re-enter Passwords fields cannot be empty.';
				$this->errorMsg($msg);
			} elseif($password != $confirmPassword) {
				$msg = 'Password and Re-enter New Password values do not match.';
				$this->errorMsg($msg);
			} else {
				$tmp['User']['id'] =  $id;
				$tmp['User']['password'] =  $password;

				if ($this->User->save($tmp)) {
					$msg = 'Password updated successfully';
					$this->successMsg($msg);
					$this->redirect(array('controller' => 'stores', 'action'=>'index'));
				} else {
					$msg = 'New Password could not be saved. Please, try again.';
					$this->errorMsg($msg);
				}
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
			unset($this->request->data['User']['password']);
		}

		$this->set(compact('hideSideBar'));
	}
}
