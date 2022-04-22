<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
	public $components = array(
		'Session',
		'CommonFunctions',
		'Auth' => array(
			'authenticate' => array(
				'Form' => array(
					'fields' => array('username' => 'email')
				)
			),
			'loginRedirect' => array('controller' => 'stores', 'action' => 'index'),
			//'loginRedirect' => array('controller' => 'stores', 'action' => 'access'),
			'logoutRedirect' => array('controller' => 'users', 'action' => 'login')
		)
	);

	public function beforeFilter()
	{
		if ($this->Session->check('showExpiryNotice') && $this->Session->check('showExpiryNotice') === true) {
			return true;
		}

        // check if store is expired
        if ($this->Session->check('Store.created')) {
            $storeExpiryDate = $this->Session->read('Store.expiry_date');
            $unixTimeStoreExpiry = strtotime($storeExpiryDate . " +1 day");
            $unixTimeNow = strtotime("now");
            if ($unixTimeNow > $unixTimeStoreExpiry) {
                if (($this->request->params['controller'] != 'reports') and ($this->request->params['controller'] != 'stores') and ($this->request->params['controller'] != 'users')) {
                    $this->errorMsg("This Store is expired. Contact software owner to renew this store. <br> You are only allowed to access 'Reports'.");
                    $this->redirect(array('controller' => 'reports', 'action' => 'home'));
                }
            } else {
                // check if store is about to expire in 1 month
                $unixTimeStoreExpiryNotice = strtotime($storeExpiryDate . " -1 month");
                if ($unixTimeNow > $unixTimeStoreExpiryNotice) {
					$this->Session->write('showExpiryNotice', true);
                }
            }
        }
	}

	/** function to check if store information is set */
	public function getStoreInfo()
	{
		$storeInfo = array();
		if ($this->Session->check('Store')) {
			$storeInfo = $this->Session->read('Store');
		}

		return $storeInfo;
	}

	public function checkStoreInfo()
	{
		if (!$storeInfo = $this->getStoreInfo()) {
			$this->Session->setFlash('Select a Store');
			$this->redirect(array('controller' => 'stores', 'action' => 'index'));
		}
		return true;
	}

	public function userIsManager() {
        if ($this->Session->read('manager') == '1') {
            return true;
        }
        return false;
    }

    public function onlyManagerCanAccess() {
	    if(!$this->userIsManager()) {
	        $this->redirect('/stores/');
        }
	    return true;
    }

	public function deleteStoreInfo()
	{
		App::uses('Store', 'Model');
		$this->Store = new Store;

		$this->Store->query("delete from cashbook where store_id='$storeID'");    // remove records from cashbook table
		$this->Store->query("delete from categories where store_id='$storeID'");    // remove records from categories table
		$this->Store->query("delete from employees where store_id='$storeID'");    // remove records from employees table
		$this->Store->query("delete from invoices where store_id='$storeID'");    // remove records from invoices table
		$this->Store->query("delete from product_categories where store_id='$storeID'");    // remove records from product_categories table
		$this->Store->query("delete from products where store_id='$storeID'");    // remove records from products table
		$this->Store->query("delete from purchases where store_id='$storeID'");    // remove records from purchases table
		$this->Store->query("delete from salaries where store_id='$storeID'");    // remove records from salaries table
		$this->Store->query("delete from sales where store_id='$storeID'");    // remove records from sales table
		$this->Store->query("delete from suppliers where store_id='$storeID'");    // remove records from suppliers table
		// $this->Store->query("delete from stores where id='$storeID'");	// remove records from stores table
	}

	public function errorMsg($msg)
	{
		if ($msg) {
			$this->Session->setFlash($msg, 'default', array('class' => 'error alert alert-danger'));
		}
		return true;
	}

	public function noticeMsg($msg)
	{
		if ($msg) {
			$this->Session->setFlash($msg, 'Flash/notice');
		}
		return true;
	}

	public function successMsg($msg)
	{
		if ($msg) {
			$this->Session->setFlash($msg, 'default', array('class' => 'success alert alert-success'));
		}
		return true;
	}

	public function updateInvoice($invoiceID)
	{
		if ($invoiceID) {
			App::uses('Invoice', 'Model');
			$this->Invoice = new Invoice();
			App::uses('Purchase', 'Model');
			$this->Purchase = new Purchase();

			$invoice_info = $this->Invoice->findById($invoiceID);

			$this->Purchase->clear();
			$this->Purchase->recursive = -1;
			$purchase_products = $this->Purchase->findAllByInvoiceId($invoiceID);
			if ($purchase_products) {
				$invoice_value = 0;
				$tcs_value = (float)$invoice_info['Invoice']['tcs_value'];
				$retail_shop_excise_turnover_tax = (float)$invoice_info['Invoice']['retail_shop_excise_turnover_tax'];
				$special_excise_cess = (float)$invoice_info['Invoice']['special_excise_cess'];
				$newRetailerProfessionalTax = (float)$invoice_info['Invoice']['new_retailer_prof_tax'];
				$mrpRoundingOff = (float)$invoice_info['Invoice']['mrp_rounding_off'];
				$ddAmount = (float)$invoice_info['Invoice']['dd_amount'];
				$prevCredit = (float)$invoice_info['Invoice']['prev_credit'];
				$special_margin = 0;

				foreach ($purchase_products as $row) {
					$invoice_value += $row['Purchase']['total_amount'];
				}

				$invoice_data['Invoice']['id'] = $invoiceID;
				$invoice_data['Invoice']['invoice_value'] = $invoice_value;
				$invoice_data['Invoice']['special_margin'] = $special_margin;

				$dd_purchase = $invoice_value
					+ $mrpRoundingOff
					+ $special_excise_cess
					+ $tcs_value
					+ $retail_shop_excise_turnover_tax
					+ $newRetailerProfessionalTax;

				$newCreditBalance = $ddAmount
					+ $prevCredit
					- $dd_purchase;

				$invoice_data['Invoice']['dd_purchase'] = $dd_purchase;
				$invoice_data['Invoice']['credit_balance'] = $newCreditBalance;
				$this->Invoice->save($invoice_data);
			}
		}
	}

}
