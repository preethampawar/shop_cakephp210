<?php
App::uses('AppModel', 'Model');

class PromoCode extends AppModel
{
	var $name = 'PromoCode';
	var $belongsTo = ['Site'];
	var $useTable = 'promo_codes';
	const PROMO_CODE_TYPE_TOTAL_PURCHASE_AMOUNT = 'TOTAL_PURCHASE_AMOUNT';
	const PROMO_CODE_REDEEM_TYPE_SINGLE = 'SINGLE_TIME';
	const PROMO_CODE_REDEEM_TYPE_MULTIPLE = 'MULTIPLE_TIMES';

	const PROMO_CODE_TYPES = [
		self::PROMO_CODE_TYPE_TOTAL_PURCHASE_AMOUNT => self::PROMO_CODE_TYPE_TOTAL_PURCHASE_AMOUNT
	];
	const PROMO_CODE_REDEEM_TYPES = [
		self::PROMO_CODE_REDEEM_TYPE_SINGLE => self::PROMO_CODE_REDEEM_TYPE_SINGLE,
		self::PROMO_CODE_REDEEM_TYPE_MULTIPLE => self::PROMO_CODE_REDEEM_TYPE_MULTIPLE,
	];

	public function getActivePromoCodes($siteId)
	{
		$todaysDate = date('Y-m-d');
		$conditions = [
			'PromoCode.active' => 1,
			'PromoCode.deleted' => 0,
			'PromoCode.site_id' => $siteId,
			'PromoCode.start_date <= ' => $todaysDate,
			'PromoCode.end_date >= ' => $todaysDate,
		];

		$this->unbindModel(['belongsTo' => 'Site']);

		$promoCodes = $this->find('all', ['conditions' => $conditions, 'order' => ['PromoCode.discount_value' => 'DESC']]);		

		return $promoCodes;
	}

}
