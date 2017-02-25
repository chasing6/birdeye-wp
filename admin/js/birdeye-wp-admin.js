(function( $ ) {
	'use strict';

	/**

	 */

    function get_json(data) {
        try {

            return JSON.parse(data);;
        } catch (e) {
            return false;
        }
    }

    var settingsForm = document.querySelector( 'form[name="birdeye-settings-form"]' );

	settingsForm.addEventListener( 'submit', function( event ) {
	    // scope is event trigger key work this will work
	    event.preventDefault();

	    this.querySelector('input[type="submit"]').setAttribute('disabled', '');

	    var spinner = document.createElement('img');

	    spinner.setAttribute( 'src', '/wp-includes/images/spinner.gif');

	    this.querySelector('p.submit').appendChild(spinner);

	    var settings = $(this).serializeArray();

	    $.post(ajaxurl, {'action' : 'save_birdeye_settings', 'settings' : JSON.stringify(settings) }, function (data) {
            data = get_json(data);

	        if (data) {
				console.log(data);

				if (typeof data !== 'object') {
				    console.warn('Ajax return type error, settings my still have been updated. ' + typeof data);
				    return false;
                }



                for (var i = 0; i < data.length; i++) {
				    var div = document.createElement('DIV'),
                        message = document.createTextNode( data[i].message );
				    if (data[i].success) {
                        div.setAttribute( 'class', 'success-message');
                    } else {
                        div.setAttribute( 'class', 'error-message');
                    }
                    div.appendChild( message );
				    document.querySelector('.birdeye-settings-box')
                        .insertBefore( div , document.querySelector('.settings-box-header') );
                }

            }
	       $(spinner).remove();
        });
        this.querySelector('input[type="submit"]').removeAttribute('disabled');
    });

})( jQuery );
