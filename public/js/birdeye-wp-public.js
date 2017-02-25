// JavaScript Document

(function ( $ ) {

   	function get_styles(){
        var styles = '<link rel="stylesheet" href="https://opensource.keycdn.com/fontawesome/4.6.3/font-awesome.min.css" integrity="sha384-Wrgq82RsEean5tP3NK3zWAemiNEXofJsTwTyHmNb/iL3dP/sZJ4+7sOld1uqYJtE" crossorigin="anonymous">';

        return styles;
    }


    if ($('.birdeye-reviews-widget')) {

            var $reviews = $('.review-container'),
                pos = 0;

            $($reviews[pos]).css('display', 'block');

            $('.birdeye-reviews-widget').append('<div class="widget-arrow right"></div>').append('<div class="widget-arrow left"></div>');

            $(document).on('click', '.widget-arrow', function () {
                var _this = $(this),
                    curPos = pos,
                    next;

                if (_this.is('.right')){

                    if (curPos === ($reviews.length -1)) {
                        next = 0;
                    } else {
                        next = curPos + 1;
                    }

                    $($reviews[curPos]).fadeOut('200', function () {
                        $($reviews[next]).fadeIn('200');
                        pos = next;
                    });


                } else if (_this.is('.left')) {


                    if (curPos === (0)) {
                        next = $reviews.length -1;
                    } else {
                        next = curPos -1;
                    }

                    $($reviews[curPos]).fadeOut('200', function () {
                        $($reviews[next]).fadeIn('200');
                        pos = next;
                    });

                }



            });


    }

    if ($('.birdeye-review-summary')) {

            var summary = $('.birdeye-review-summary'),
                title = "<h3>Reviews Summary</h3>",
                newReview = '<div class="new-review-links"><a href="'+$gReviewLink+'" class="button">Post a Review on Google</a> <a href="'+$yelpReviewLink+'" class="button">Post a Review on Yelp</a></div>',
                totalReviews = 0;
            summary.before(get_styles());
            var averageRating = parseFloat($('.average-rating h4').text());
            $('.average-rating h4').text(averageRating.toFixed(1) );
            totalReviews = parseFloat($('[data-id="total-reviews"]').text());

            $('.rating-chart li').each(function () {
                var count = parseFloat( $(this).find('.review-chart-count').text() );
                $(this).find('.chart-bar').css({

                    'margin-right' : '10px',
                    'width'        :  ( ( count / totalReviews) * 435 ) + 'px'

                });
            });





    }


})(jQuery);