<?php

/**
 * Created by PhpStorm.
 * User: casey
 * Date: 12/4/16
 * Time: 1:04 PM
 */
class Birdeye_Shortcodes
{

    public static function init() {
        add_shortcode( 'birdeye_scrolling_widget', array( __CLASS__ , 'scrolling_widget' ) );
        add_shortcode( 'birdeye_reviews_summary' , array( __CLASS__ , 'get_summary') );
    }

    public static function get_reviews($type, $atts = array()) {

        $index = (isset( $atts['index'] ) ) ? $atts['index'] : 0;
        $count = (isset( $atts['count'] ) ) ? $atts['count'] : 10;

        $api_key = get_option( 'birdeye-api-key' );
        $business_id = get_option( 'birdeye-business-id' );

        if ($type == 'listing') {
            $url = "https://api.birdeye.com/resources/v1/review/businessId/$business_id?sindex=$index&count=$count&api_key=$api_key";
        } elseif ($type == 'summary') {
            $url = "https://api.birdeye.com/resources/v1/review/businessid/$business_id/summary?api_key=$api_key";
        } else {
            return false;
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

    public static function count_reviews($reviews = []) {
        $review_count = 0;
        foreach ($reviews as $review) {
            $review_count += $review->reviewCount;
        }

        return $review_count;
    }

    public static function get_review_average($reviews) {
        $total_reviews = self::count_reviews($reviews);

        $added = 0;

        foreach ($reviews as $review) {
            $added += $review->reviewCount * $review->rating;
        }

        return array('average' => round($added / $total_reviews), 'total-reviews' => $total_reviews);
    }

    public static function scrolling_widget($atts) {



        $reviews = self::get_reviews('listing', $atts);

        $reviews = json_decode($reviews);

        if (!$reviews) {
            echo 'There as an error getting the reviews';
            return '';
        } else {
            echo '<div class="birdeye-reviews-widget">';
            foreach ($reviews as $review) {
                ?>
                <div class="review-container">
                    <a href="<?php echo $review->uniqueReviewUrl; ?>">
                        <div class="review-header grid-container">
                            <div class="avatar" class="grid-25"><img src="<?php echo $review->reviewer->thumbnailUrl; ?>" alt="Review Avatar"></div>
                            <div class="reviewer" class="grid-75">
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
                        <div class="review-body">
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
            }
            echo '</div>';
        }

    }

    public static function get_summary($atts) {

        $reviews = self::get_reviews('summary', $atts );

        if (!$reviews && wp_doing_ajax()) {
		    echo json_encode(['success' => 'false']);
        } else if (!$reviews)  {
            return false;
        } else {

            $reviews = json_decode($reviews);


            $average_rating = self::get_review_average($reviews->ratings);

            ob_start();
            ?>
            <div class="birdeye-review-summary">
            <div class="review-summary" style="display:none;">

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

            $reviews = self::get_reviews('listing');

            $reviews = json_decode($reviews);

            foreach ($reviews as $review) {
                ?>
                <article class="review-listing-container">
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
                                <?php printf('<span class="review-date small">%s</span>', $review->reviewDate); ?>
                            </div>
                        </div>
                        <div class="review-listing-body">
                            <div class="rating" itemtype="http://schema.org/AggregateRating" itemscope itemprop="aggregateRating">
                                <span content="<?php echo $review->rating; ?>" itemprop="ratingValue"><?php echo $review->rating; ?></span>
                                <span class="rating-stars">
									<?php

                                    foreach (range(1, 5) as $star) {
                                      if ( $star <= $review->rating ) {
                                        echo '<i class="fa fa-star"></i>';
                                      } else {
                                        echo '<i class="fa fa-star-o"></i>';
                                      }

                                    }


                                    ?>
						                            </span>

                                <span class="review-source small"><?php echo $source = $review->sourceType == 'Our Website' ? 'Birdeye' : $review->sourceType; ?></span>

                            </div>
                            <div class="review-comments">
                                <p>
                                    <?php echo $review->comments; ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </article>
                <?php
            } // end of foreach
            echo '</div>';

            return ob_get_clean();
        } // end else statement

    }

}
