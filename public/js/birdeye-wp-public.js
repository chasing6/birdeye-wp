// JavaScript Document

(function ( $ ) {

   	


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
                totalReviews = 0;
            
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