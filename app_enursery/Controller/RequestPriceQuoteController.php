<?php
App::uses('CakeEmail', 'Network/Email');

class RequestPriceQuoteController extends AppController
{
	var $name = 'RequestPriceQuote';

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('index');
	}

	function index()
	{
		$hideLeftMenu = true;
		$shoppingCart = $this->getShoppingCartProducts();
		$errorMsg = array();
		if ($this->request->isPost() and !empty($this->request->data)) {
			$data = $this->request->data;

			// Validate name
			if (Validation::blank($data['ShoppingCart']['name'])) {
				$errorMsg[] = 'Enter your name';
			}
			// Validate phone
			if (Validation::blank($data['ShoppingCart']['phone'])) {
				$errorMsg[] = 'Enter your phone';
			}
			// validate user email
			if (Validation::blank($data['ShoppingCart']['email'])) {
				$errorMsg[] = 'Enter Email Address';
			} elseif (!(Validation::email($data['ShoppingCart']['email']))) {
				$errorMsg[] = 'Invalid Email Address';
			}
			// validate address
			if (Validation::blank($data['ShoppingCart']['address'])) {
				$errorMsg[] = 'Address field cannot be empty';
			}

			// Sanitize data
			$data['ShoppingCart']['name'] = Sanitize::paranoid($data['ShoppingCart']['name'], array(' ', '-', '.'));
			$data['ShoppingCart']['phone'] = Sanitize::paranoid($data['ShoppingCart']['phone'], array(' ', '+'));
			$data['ShoppingCart']['address'] = Sanitize::clean($data['ShoppingCart']['address']);
			$data['ShoppingCart']['message'] = Sanitize::clean($data['ShoppingCart']['message']);


			// products list
			$items = 'Products List:<br>';
			$sms_items = '';
			if (isset($shoppingCart['ShoppingCartProduct']) and !empty($shoppingCart['ShoppingCartProduct'])) {
				$i = 0;
				foreach ($shoppingCart['ShoppingCartProduct'] as $row) {
					$i++;
					$items .= '
' . $i . ') ' . $row['product_name'] . ', Size: ' . $row['size'] . ', Age: ' . $row['age'] . ', Quantity: ' . $row['quantity'] . '
';
					$sms_items .= $row['product_name'] . '(' . $row['quantity'] . ') ';

				}
			}

			// Send SMS
			$sms_message = array(
				'Price Quote Request: ' . $data['ShoppingCart']['name'],
				$data['ShoppingCart']['phone'],
				trim($sms_items),
				$data['ShoppingCart']['message']
			);
			$sms_message = implode(',', $sms_message);
			// $sms_user_msg = substr($sms_message, 0, 465);
			$sms_message .= ' - ' . $this->Session->read('Domain.name');
			$to = $this->Session->read('Site.contact_phone');
			//$this->sendSMS($to, $sms_message);

			// End of Send SMS

			if (empty($errorMsg)) {
				try {

					// Send email to user
					$mailContent = '
Dear ' . $data['ShoppingCart']['name'] . ',
<br><br>
Thank you for your request.
<br><br>
We have received your request and is under process. Our representative will get in contact with you regarding price quote.
<br><br>
Below are the product details for which you have requested price quote.
<br><br>
' . $items . '
<br><br>
-<br>
' . $this->Session->read('Domain.name') . '
<br><br>
*This is a system generated message. Please do not reply.
<br>
';
					$fromName = Configure::read('NoReply.name');
					$fromEmail = Configure::read('NoReply.email');
					$userEmail = $data['ShoppingCart']['email'];
					$email = new CakeEmail('smtpNoReply');
					//$email->from(array($fromEmail => $fromName));
					$email->to($userEmail);
					$email->subject('Request Price Quote');
					$email->replyTo(array('no-reply@letsgreenify.com' => 'Do not reply'));
					$email->emailFormat('both');
					$email->send($mailContent);


					// Send email to admin
					$mailContent = '
Dear Admin,
<br><br>
A person has requested for price quote on ' . Configure::read('Domain') . '.
<br><br>
Contact Details:<br>
----------------------------------------
Name: ' . $data['ShoppingCart']['name'] . '
Email: ' . $data['ShoppingCart']['email'] . '
Phone: ' . $data['ShoppingCart']['phone'] . '
Address: ' . htmlentities($data['ShoppingCart']['address']) . '
Message: ' . htmlentities($data['ShoppingCart']['message']) . '
<br><br>
' . $items . '
<br><br>
-<br>
' . $this->Session->read('Domain.name') . '
<br><br>
*This is a system generated message. Please do not reply.
<br>
';
					$fromName = Configure::read('NoReply.name');
					$fromEmail = Configure::read('NoReply.email');
					$supportEmail = Configure::read('SupportEmail');
					$baseSupportEmail = Configure::read('BaseSupportEmail');
					$email = new CakeEmail('smtpNoReply');
					//$email->from(array($fromEmail => $fromName));
					$email->replyTo(array($data['ShoppingCart']['email'] => $data['ShoppingCart']['name']));
					$email->to($supportEmail);
					$email->bcc($baseSupportEmail); // send email to enursery support team
					$email->subject('Request Price Quote');
					$email->emailFormat('both');
					$email->send($mailContent);

					// set this transaction as request price quote
					App::uses('ShoppingCart', 'Model');
					$this->ShoppingCart = new ShoppingCart;

					$tmp['ShoppingCart'] = $data['ShoppingCart'];
					$tmp['ShoppingCart']['id'] = $shoppingCart['ShoppingCart']['id'];
					$tmp['ShoppingCart']['request_price_quote'] = '1';
					$this->ShoppingCart->save($tmp);

					$this->Session->delete('ShoppingCart');
					$this->Session->setFlash('Thank you for your request.', 'default', array('class' => 'success'));
					$this->redirect('/pages/price_quote_request_message');
				} catch (Exception $ex) {
					$errorMsg[] = 'An error occured while communicating with the server. Please try again.' . $ex->getMessage();
				}
			}
		}
		$errorMsg = implode('<br>', $errorMsg);
		$this->set(compact('hideLeftMenu', 'shoppingCart', 'errorMsg'));
	}

	public function admin_index()
	{
		App::uses('ShoppingCart', 'Model');
		$this->ShoppingCart = new ShoppingCart;

		$conditions = array('ShoppingCart.site_id' => $this->Session->read('Site.id'), 'request_price_quote' => '1');
		$this->paginate = array(
			'conditions' => $conditions,
			'limit' => 25,
			'recursive' => '1',
			'order' => 'ShoppingCart.created DESC'
		);
		$priceQuotes = $this->paginate('ShoppingCart');
		$this->set(compact('priceQuotes'));
	}

	public function admin_delete($shoppingCartID = null)
	{
		if ($this->request->isPost()) {
			App::uses('ShoppingCart', 'Model');
			$this->ShoppingCart = new ShoppingCart;
			$conditions = array('ShoppingCart.id' => $shoppingCartID, 'ShoppingCart.site_id' => $this->Session->read('Site.id'));
			$cartInfo = $this->ShoppingCart->find('first', array('conditions' => $conditions));

			if (empty($cartInfo)) {
				$this->Session->setFlash('Information unavailable', 'default', array('class' => 'error'));
			} else {
				// delete shopping cart products
				$conditions = array('ShoppingCartProduct.shopping_cart_id' => $shoppingCartID);
				App::uses('ShoppingCartProduct', 'Model');
				$this->ShoppingCartProduct = new ShoppingCartProduct;
				$this->ShoppingCartProduct->deleteAll($conditions);

				// delete cart info
				$this->ShoppingCart->delete($shoppingCartID);

				$this->Session->setFlash('Price quote has been removed', 'default', array('class' => 'success'));
			}
		} else {
			$this->Session->setFlash('Invalid request', 'default', array('class' => 'error'));
		}

		$this->redirect($this->request->referer());
	}

}

?>
