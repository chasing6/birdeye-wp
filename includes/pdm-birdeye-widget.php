<?php

function enqueue_font_awesome() {
	?><link rel="stylesheet" href="https://opensource.keycdn.com/fontawesome/4.6.3/font-awesome.min.css" integrity="sha384-Wrgq82RsEean5tP3NK3zWAemiNEXofJsTwTyHmNb/iL3dP/sZJ4+7sOld1uqYJtE" crossorigin="anonymous">
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/pmd-birdeye-css.css" media="screen" title="no title">

	<?php
}

add_action('wp_head', 'enqueue_font_awesome');

function get_reviews($type) {
	$api_key = 'The Api Key Goes Here';
	$business_id = 'The Business Id Goes Here';

	if ($type == 'listing') {
		$url = "https://api.birdeye.com/resources/v1/review/businessId/$business_id?sindex=0&count=10&api_key=$api_key";
	} elseif ($type == 'summary') {
		$url = "https://api.birdeye.com/resources/v1/review/businessid/$business_id/summary?api_key=$api_key";
	}

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, 0);

	if ($type == 'listing') {
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, '{}');

	}
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  "Content-Type: application/json",
	  "Accept: application/json"
	));

	$response = curl_exec($ch);
	curl_close($ch);

	return $response;

}



function count_reviews($reviews = []) {
	$review_count = 0;
	foreach ($reviews as $review) {
		$review_count += $review->reviewCount;
	}

	return $review_count;
}

function get_review_average($reviews) {
	$total_reviews = count_reviews($reviews);

	$added = 0;

	foreach ($reviews as $review) {
		$added += $review->reviewCount * $review->rating;
	}

	return array('average' => round($added / $total_reviews), 'total-reviews' => $total_reviews);
}

function get_summary() {
	$reviews = get_reviews('summary');

	if (!$reviews) {
		echo json_encode(['success' => 'false']);
	} else {

		$reviews = json_decode($reviews);


		$average_rating = get_review_average($reviews->ratings);
		?>
		<div class="review-summary">

			<div class="average-rating">
				<h4>
					<?php echo $average_rating['average'];?>
				</h4>
				<p> Out of <span data-id="total-reviews"><?php echo $average_rating['total-reviews'];?></span> reviews</p>
			</div>
			<div class="rating-chart">
				<ul>
					<?php
						foreach ($reviews->ratings as $review) {

							$rating = ($review->rating == 0) ? 'nr' : $review->rating;

							printf('<li><span class="review-chart-stars">%s</span><span class="chart-bar"></span><span class="review-chart-count">%s</span></li>', $rating, $review->reviewCount);
						}
					?>
				</ul>
			</div>

		</div>

		<?php

		$reviews = get_reviews('listing');

		$reviews = json_decode($reviews);

		foreach ($reviews as $review) {
			?>
				<div class="review-listing-container">
					<a href="<?php echo $review->uniqueReviewUrl; ?>">
						<div class="review-listing-header">
							<div class="avatar" class="alignright"><img src="<?php echo $review->reviewer->thumbnailUrl; ?>" alt="Review Avatar"></div>
							<div class="reviewer">
								<h4>
									<?php

										if (!empty($review->reviewer->nickName)) {
											echo $review->reviewer->nickName;
										} elseif (!empty($review->reviewer->firstname)) {
											echo $review->reviewer->firstname . ' ' . $review->reviewer->lastname;
										} else {
											echo 'Anonymous';
										}

									?>
								</h4>
								<?php printf('<span class="review-date">%s</span>', $review->reviewDate); ?>
							</div>
						</div>
						<div class="review-listing-body">
							<div class="rating" itemtype="http://schema.org/AggregateRating" itemscope itemprop="aggregateRating">
								<span content="<?php echo $review->rating; ?>" itemprop="ratingValue"><?php echo $review->rating; ?></span>
								<span class="rating-stars">
									<?php

									foreach (range(1, $review->rating) as $star) {
										echo '<span class="star"></span>';
									}

									?>
								</span>

								<span class="review-source"><?php echo $review->sourceType; ?></span>

							</div>
							<div class="review-comments">
								<p>
									<?php echo $review->comments; ?>
								</p>
							</div>
						</div>
					</a>
				</div>
			<?php
		} // end of foreach



	} // end else statement

	wp_die();
}

add_action('wp_ajax_display_reviews','scolling_widget');
add_action('wp_ajax_nopriv_display_reviews','scolling_widget');

add_action('wp_ajax_get_summary', 'get_summary');
add_action('wp_ajax_nopriv_get_summary', 'get_summary');
