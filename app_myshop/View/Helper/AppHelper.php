<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
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
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Helper', 'View');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class AppHelper extends Helper
{
	const DELETE_BTN_CLASS = "text-danger text-decoration-none";
	const EDIT_BTN_CLASS = "text-warning text-decoration-none";
	const DEFAULT_BTN_CLASS = 'text-primary text-decoration-none';
	const DELETE_CLASS = "text-danger";
	const EDIT_CLASS = "text-warning";
	const DEFAULT_CLASS = 'text-primary';

	public $helpers = ['Session', 'Html'];

	/**
	 * @return bool
	 */
	public function isSeller()
	{
		if ($this->Session->read('User.type') == 'seller') {
			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function isSellerForThisSite()
	{
		if ($this->Session->read('User.superadmin') == 1) {
			return true;
		}

		if ($this->isSeller()) {
			return true;
		}

		if ($this->Session->read('User.id') == $this->Session->read('Site.user_id')) {
			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function isSellerView()
	{
		if ($this->Session->check('inSellerView') && $this->Session->read('inSellerView') == true) {
			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function isBuyerView()
	{
		if ($this->Session->check('inBuyerView') && $this->Session->read('inBuyerView') == true) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $title
	 * @param string $url
	 * @param string $type
	 * @param null $confirmationMessage
	 *
	 * @return string
	 */
	public function getLinkButton($title, $url, $type = 'normal', $confirmationMessage = null)
	{
		switch ($type) {
			case 'edit':
				$class = self::EDIT_BTN_CLASS;
				break;
			case 'delete':
				$class = self::DELETE_BTN_CLASS;
				break;
			default:
				$class = self::DEFAULT_BTN_CLASS;
				break;
		}

		return $this->Html->link($title, $url, ['class' => $class, 'escape' => false], $confirmationMessage);

	}

	/**
	 * @param $title
	 * @param $url
	 * @param string $type
	 * @param null $confirmationMessage
	 *
	 * @return string
	 */
	public function getLink($title, $url, $type = 'normal', $confirmationMessage = null)
	{
		switch ($type) {
			case 'edit':
				$class = self::EDIT_CLASS;
				break;
			case 'delete':
				$class = self::DELETE_CLASS;
				break;
			default:
				$class = self::DEFAULT_CLASS;
				break;
		}

		return $this->Html->link($title, $url, ['class' => $class, 'escape' => false], $confirmationMessage);
	}

	public function price($value)
	{
		$tmp = explode('.', $value);

		if (isset($tmp[1]) && $tmp[1] == 0) {
			$value = $tmp[0];
		}

		return '&#8377;'. $value;
	}

	public function priceOfferInfo(int $saleValue, int $mrp, $type = null)
	{
		$value = $mrp - $saleValue;
		$percentage = ceil(($value * 100 / $mrp));

		if ($mrp !== $value && $percentage == 100) {
			$percentage = 99;
		}

		if ($type === 'amount') {
			return '&#8377;'. $value;
		}

		if ($type === 'percentage') {
			return $percentage.'%';
		}

		return '&#8377;'. $value. ' ('.$percentage.'%)';
	}

	public function getRearrangedImages($data)
	{
		if (!is_array($data) and !empty($data)) {
			$data = json_decode($data);
		}

		$images = [];
		if($data) {
			foreach ($data as $row) {
				$images[$row->commonId][$row->type] = $row;
			}
		}

		return $images;
	}

	public function getHighlightImage($data)
	{
		$highlightImage = [];

		if ($data) {
			$data = $this->getRearrangedImages($data);

			foreach ($data as $row) {
				$image = $row['thumb'];
				if ($image->highlight) {
					$highlightImage = $row;
					break;
				}
			}

			if (!$highlightImage) {
				$highlightImage = $data[array_key_last($data)];
			}
		}

		return $highlightImage;
	}

	public function convertTimeToDays($date) {
		$current_date = date("Y-m-d H:m:s");
		$time = array();
		$day = floor((strtotime($current_date) - strtotime($date)) / (60 * 60 * 24));

		if ($day == 0) {
			$hour = floor((strtotime($current_date) - strtotime($date)) / (60 * 60));

			if ($hour == 0) {
				$minute = floor((strtotime($current_date) - strtotime($date)) / (60));
				$time = $minute . __d('course', " min(s) ago");

			} else {
				$time = $hour . __d('course', " hour(s) ago");
			}

		} else {
			$time = $day . __d('course', " day(s) ago");
		}

		return $time;
	}
}
