<?php
App::uses('CakeEmail', 'Network/Email');

class SitesController extends AppController
{

	var $name = 'Sites';

	public function beforeFilter()
	{
		parent::beforeFilter();
		//$this->Auth->allow('sitemap', 'suspended', 'under_maintenance', 'getSiteList', 'robot', 'routemap');
	}

	public function admin_home()
	{
		$this->layout = 'seller';

	}

	public function register($userId)
	{
		App::uses('User', 'Model');
		$user = new User();
		$user->recursive = -1;
		$userInfo = $user->findById($userId);

		if (empty($userInfo)) {
			$this->redirect('/users/enroll');
		}

		$userInfo['User'] = $this->Session->read('User');

		if ($this->request->is('post')) {
			$data = $this->request->data;

			$sData['Site'] = $data['Site'];
			$sData['Site']['domain_name'] = $data['Site']['name'] . '.' . Configure::read('Domain');
			$sData['Site']['id'] = null;
			$sData['Site']['user_id'] = $userInfo['User']['id'];
			$sData['Site']['contact_email'] = $userInfo['User']['email'];
			$sData['Site']['contact_phone'] = $userInfo['User']['phone'];

			if ($this->Site->save($sData)) {
				$siteInfo = $this->Site->read();
				$domainData['Domain']['name'] = $siteInfo['Site']['domain_name'];
				$domainData['Domain']['site_id'] = $siteInfo['Site']['id'];
				$domainData['Domain']['user_id'] = $siteInfo['Site']['user_id'];
				$domainData['Domain']['default'] = true;

				App::uses('Domain', 'Model');
				$domainModel = new Domain;
				if ($domainModel->save($domainData)) {
					$domainInfo = $domainModel->read();
					$this->sendSiteRegistrationMessage($siteInfo, $userInfo);
					// $this->successMsg('Congratulations! Your store <a href="http://' . $siteInfo['Site']['domain_name'] . '">' . $siteInfo['Site']['domain_name'] . '</a> is online now.');
					$this->redirect('http://' . $siteInfo['Site']['domain_name'] . '/pages/registered');
				} else {
					$this->Site->delete($siteInfo['Site']['id']);
					$this->errorMsg('Store URL could not be created. Please try again.');
				}
			} else {
				$this->errorMsg('An error occurred while communicating with the server. Please try again.');
			}
		}
	}

	private function sendSiteRegistrationMessage($siteInfo, $userInfo)
	{
		$storeTitle = $siteInfo['Site']['title'];
		$storeUrl = $siteInfo['Site']['domain_name'];
		$subject = "Your business - $storeTitle is online now";
		$toName = $toEmail = $userInfo['User']['email'];
		$mobile = $userInfo['User']['mobile'];

		$mailContent = "
<p>Congratulations!! Your business <b>$storeTitle</b> is now online. </p>
<p>Here is your online store url: $storeUrl</p>
<p>Use your registered mobile number <b>$mobile</b> to manage it.</p>
<p>Grow your business by sharing this store url <b>$storeUrl</b> with your customers, friends and family.</p>
<p>Wish you good luck!</p>
<br><p><i>This is a system generated message. Please do not respond to it.</i></p>
";
		$email = new CakeEmail('smtpNoReply');
		$email->emailFormat('html');
		$email->from([$this->noReplyEmail['fromEmail'] => $this->noReplyEmail['fromName']]);
		$email->to([$toEmail => $toName]);
		$email->subject($subject);
		$email->send($mailContent);
	}

	public function getSiteList()
	{
		return $this->Site->find('list');
	}

	public function sitemap()
	{
		$this->layout = null;
		$this->response->type('xml');
		$categoryProducts = $this->getSiteCategoriesProductsImages();
		$this->set(compact('categoryProducts'));
	}

	public function routemap()
	{
		$this->layout = 'default';

		$hideLeftMenu = true;
		$this->set(compact('hideLeftMenu'));
	}

	public function suspended()
	{
		$this->layout = 'suspended';
	}

	public function under_maintenance()
	{
		$this->layout = 'undermaintenance';
	}

	public function admin_edit()
	{
		$siteID = $this->Session->read('Site.id');
		$siteInfo = $this->Site->findById($siteID);
		$serviceTypes = [];
		$serviceTypes = $this->Site->find('all', ['conditions' => ['Site.service_type NOT' => 'NULL'], 'fields' => ['DISTINCT Site.service_type'], 'recursive' => '-1']);

		$errorMsg = [];
		if ($this->request->isPut() or $this->request->isPost()) {
			$data['Site'] = $this->data['Site'];

			// validate Site Title
			if (Validation::blank($data['Site']['title'])) {
				$errorMsg[] = 'Enter Site Title';
			} else if (!Validation::between($data['Site']['title'], 3, 50)) {
				$errorMsg[] = 'Site title should be 3 to 50 chars long';
			}
			if (!Validation::blank($data['Site']['caption'])) {
				if (!Validation::between($data['Site']['caption'], 3, 100)) {
					$errorMsg[] = 'Site caption should be 3 to 100 chars long';
				}
			}

			// validate phone
			if (Validation::blank($data['Site']['contact_phone'])) {
				$errorMsg[] = 'Enter Phone No.';
			} else if (!Validation::between($data['Site']['contact_phone'], 10, 55)) {
				$errorMsg[] = 'Phone No. should contain min 10 & max 55 digits';
			}

			// validate user email
			if (Validation::blank($data['Site']['contact_email'])) {
				$errorMsg[] = 'Enter Email Address';
			} else if (!(Validation::email($data['Site']['contact_email']))) {
				$errorMsg[] = 'Invalid Email Address';
			}

			// validate address
			if (Validation::blank($data['Site']['address'])) {
				$errorMsg[] = 'Enter Contact Address';
			}

			if (empty($errorMsg)) {
				// Sanitize data
				$data['Site']['id'] = $siteID;
				$data['Site']['title'] = htmlentities($this->data['Site']['title']);
				$data['Site']['caption'] = htmlentities($this->data['Site']['caption']);
				$data['Site']['service_type'] = htmlentities($this->data['Site']['service_type']);
				$data['Site']['meta_keywords'] = htmlentities($this->data['Site']['meta_keywords']);
				$data['Site']['meta_description'] = htmlentities($this->data['Site']['meta_description']);
				$data['Site']['address'] = $this->data['Site']['address'];

				if (!$data['Site']['show_landing_page'] and !$data['Site']['show_products']) {
					$data['Site']['image_gallery'] = '0';
				}

				if ($this->Site->save($data)) {
					$this->Session->setFlash('Data saved successfully', 'default', ['class' => 'success']);
					$this->redirect('/admin/sites/');
				} else {
					$errorMsg[] = 'Failed to save data';
				}
			}
		} else {
			$this->data = $siteInfo;
		}
		$errorMsg = implode('<br/>', $errorMsg);
		$this->set(compact('siteInfo', 'errorMsg', 'serviceTypes'));
	}

	public function admin_addDomain($siteID)
	{
		if ($this->request->isPost()) {
			App::uses('Domain', 'Model');
			$domainModel = new Domain;
			$error = false;
			$data['Domain'] = $this->request->data['Domain'];
			if (empty($data['Domain']['name'])) {
				$error = true;
				$this->Session->setFlash('Enter Domain Name', 'default', ['class' => 'error']);
			} else if ($domainModel->findByName($data['Domain']['name'])) {
				$error = true;
				$this->Session->setFlash('Domain "' . $data['Domain']['name'] . '" is already registered', 'default', ['class' => 'error']);
			}

			if (!$error) {
				$data['Domain']['id'] = null;
				$data['Domain']['site_id'] = $siteID;
				if ($domainModel->save($data)) {
					$this->Session->setFlash('Domain name added successfully', 'default', ['class' => 'success']);
				} else {
					$this->Session->setFlash('Failed to create domain name', 'default', ['class' => 'error']);
				}
			}
		} else {
			$this->Session->setFlash('You are not authorized to perform this action', 'default', ['class' => 'error']);
		}
		$this->redirect('/admin/sites/edit/' . $siteID);
	}

	public function admin_deleteDomain($domainID, $siteID)
	{
		if ($this->request->isGet()) {
			App::uses('Domain', 'Model');
			$domainModel = new Domain;
			$domainInfo = $domainModel->findById($domainID);
			if ($domainInfo) {
				if ($domainInfo['Domain']['default']) {
					$this->Session->setFlash('You cannot delete a default domain', 'default', ['class' => 'error']);
				} else {
					if ($domainModel->delete($domainID)) {
						$this->Session->setFlash('Domain name deleted successfully', 'default', ['class' => 'success']);
					} else {
						$this->Session->setFlash('Failed to delete domain name', 'default', ['class' => 'error']);
					}
				}
			} else {
				$this->Session->setFlash('Domain not found', 'default', ['class' => 'error']);
			}
		} else {
			$this->Session->setFlash('You are not authorized to perform this action', 'default', ['class' => 'error']);
		}
		$this->redirect('/admin/sites/edit/' . $siteID);
	}

	public function admin_setDefaultDomain($domainID, $siteID)
	{
		if ($this->request->isGet()) {
			App::uses('Domain', 'Model');
			$domainModel = new Domain;
			$domainInfo = $domainModel->findById($domainID);
			if ($domainInfo) {
				if ($domainInfo['Domain']['default']) {
					$this->Session->setFlash('You cannot delete a default domain', 'default', ['class' => 'error']);
				} else {
					// reset all domains
					$conditions = ['Domain.site_id' => $siteID];
					$domains = $domainModel->findAllBySiteId($siteID);
					foreach ($domains as $row) {
						$data = [];
						$data['Domain']['id'] = $row['Domain']['id'];
						$data['Domain']['site_id'] = $siteID;
						$data['Domain']['default'] = '0';
						$domainModel->save($data);
					}

					// make the selected domain default
					$data = [];
					$data['Domain']['id'] = $domainID;
					$data['Domain']['default'] = true;
					if ($domainModel->save($data)) {
						$this->Session->setFlash('Domain successfully set to default', 'default', ['class' => 'success']);
					} else {
						$this->Session->setFlash('Failed to set domain as default', 'default', ['class' => 'error']);
					}
				}
			} else {
				$this->Session->setFlash('Domain not found', 'default', ['class' => 'error']);
			}
		} else {
			$this->Session->setFlash('You are not authorized to perform this action', 'default', ['class' => 'error']);
		}
		$this->redirect('/admin/sites/edit/' . $siteID);
	}

	public function admin_index()
	{
		if ($siteInfo = $this->Site->findByUserId($this->Session->read('User.id'))) {
			$this->data = $siteInfo;
			$this->set(compact('siteInfo'));
		} else {
			$this->Session->setFlash('Site not found', 'default', ['class' => 'error']);
			$this->redirect('/');
		}
	}

	public function robot()
	{
		error_reporting(0);
		$this->layout = false;

		$url = 'http://';
		$url .= $this->request->host();
		$url .= '/sitemap';
		$sitemapUrl = 'Sitemap: ' . $url;


		header("Content-Type:text/plain");
		echo $sitemapUrl . "

User-agent: *
Disallow:
		";
		exit;
	}

	public function admin_settings()
	{
		$siteID = $this->Session->read('Site.id');
		$siteInfo = $this->Site->findById($siteID);

		$errorMsg = [];
		if ($this->request->isPut() or $this->request->isPost()) {
			$data['Site'] = $this->data['Site'];

			// validate Site Title
			if (Validation::blank($data['Site']['title'])) {
				$errorMsg[] = 'Enter Site Title';
			} else if (!Validation::between($data['Site']['title'], 3, 50)) {
				$errorMsg[] = 'Site title should be 3 to 50 chars long';
			}

			if (empty($errorMsg)) {
				// Sanitize data
				$data['Site']['id'] = $siteID;
				$data['Site']['title'] = htmlentities($this->data['Site']['title']);

				if ($this->Site->save($data)) {
					$this->successMsg('Store details updated successfully');
					$this->redirect('/admin/sites/settings');
				} else {
					$errorMsg[] = 'Failed to save data';
				}
			}
		} else {
			$this->data = $siteInfo;
		}
		$errorMsg = implode('<br/>', $errorMsg);

		$this->errorMsg($errorMsg);

		$this->set(compact('siteInfo'));
	}

	public function contact()
	{

	}

	public function paymentInfo()
	{

	}

	public function tos()
	{

	}
}

?>
