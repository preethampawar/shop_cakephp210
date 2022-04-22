<?php
App::uses('AppModel', 'Model');

class ProductReview extends AppModel
{
	public $name = 'ProductReview';

	var $belongsTo = ['User', 'Product'];

	public function getProductRating($productId)
	{
		App::uses('CakeSession', 'Model/Datasource');
		$userId = CakeSession::check('User.id') ? CakeSession::read('User.id') : 0;
		$userProductReviewId = CakeSession::check('ProductReview.'.$productId) ? CakeSession::read('ProductReview.'.$productId) : null;

		$currentUserRating = 0;

		// get current user review for the selected product
		$productReviewInfo = [];
		if ($userProductReviewId) {
			$productReviewInfo = $this->findById($userProductReviewId);
		} elseif($userId) {
			$productReviewInfo = $this->findByProductIdAndUserId($productId, $userId);
		}

		if($productReviewInfo) {
			$currentUserRating = (int)$productReviewInfo['ProductReview']['rating'];
		}

		// get avg product ratings
		$sql = 'SELECT product_id, sum(rating) total_ratings_value, count(rating) total_ratings_count, round(avg(rating), 1) avg_rating FROM product_reviews where product_id = "'.$productId.'" group by product_id';
		$result = $this->query($sql);

		$avg_rating = 0;
		$ratings_count = 0;
		if ($result) {
			$avg_rating = $result[0][0]['avg_rating'];
			$ratings_count = $result[0][0]['total_ratings_count'];
		}

		return [
			'productId' => $productId,
			'userRating' => $currentUserRating,
			'avgRating' => $avg_rating,
			'ratingsCount' => $ratings_count,
		];
	}

}
