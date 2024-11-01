/**
 * Frontend Javascript For The Totally Booked Plugin.
 *
 * @version 0.1
 * @package totally-booked
 * @since 0.1
 */

jQuery( document).ready( function($){

    //Input Clearing On Click
    $( 'body' ).on( 'focus', 'input.clear_on_focus', function(){
        if( $( this).val() == $( this).data( 'original_value' ) ){
            $( this ).val( '' );
        }
    });

    $( 'body' ).on( 'blur', 'input.clear_on_focus', function(){
        if( $( this).val() == '' ){
            $( this ).val( $( this).data( 'original_value' ) );
        }
    });

    //Find Bookstore Form
    $( 'body' ).on( 'submit', 'form.tb_find_bookstore_form', function(){

        //Gather The Vars
        var city = $( this ).find( '.city' ).val();
        var state = $( this ).find( '.state' ).val();
        var zip = $( this ).find( '.zip' ).val();

        if ( city == 'City' ) { city = '' }
        if ( state == 'State' ) { state = '' }
        if ( zip == 'Zip' ) { zip = '' }

        var url = $( this ).attr( 'action' ) + "?q=bookstore&near=" + city + "+" + state + "+" + zip;

        window.open(url, "", "");

        return false;
    })

    //Ensure We have A popup Wrapper
    if( $( '#tb_popup_wrapper').length < 1 ){
        $( 'body').append( $( '<div id="tb_popup_overlay"></div><div id="tb_popup_wrapper"></div>'))
    }


    $( '.tb_buynow_button' ).on( 'click', function(){

        var content = $( this ).next( '.tb_buynow_content' ).html();

        var width = $( window).width() / 2;

        var offsetLeft = ( $( window).width() - width ) / 2;

        $( '#tb_popup_wrapper' ).append( content );

        setBuyNowPopupDimensions();

        openBuynowPopup();
    });

    var PopupWrapper = $( '#tb_popup_wrapper');

    function openBuynowPopup(){
        PopupWrapper.show();
        $( '#tb_popup_overlay' ).show();

    }

    function closeBuyNowPopup(){
        PopupWrapper.hide().empty();
        $( '#tb_popup_overlay' ).hide().empty();
    }

    function setBuyNowPopupDimensions(){

        var WindowWidth = $( window).width();

        var ScrollTop = $( window ).scrollTop();

        width = WindowWidth / 2;

        if( WindowWidth < 650 ){

            var width = parseInt( WindowWidth * 0.95 );
            var offsetLeft = 0;
            var top = ScrollTop + 100;

        } else {

            var width = WindowWidth / 2;
            if( width > 650 ) width = 650;
            var offsetLeft = ( WindowWidth - width ) / 2;
            var top = ScrollTop + 100;

        }

        PopupWrapper.css({
            'width' : width + 'px',
            'left'  : offsetLeft + 'px',
            'top'   : top + 'px'
        });

    }

    $( window ).resize(function () { setBuyNowPopupDimensions(); });


    //Closed When Close Button Clicked
    $( 'body').on( 'click', '.close_buynow_popup, #tb_popup_overlay', function(){
    //$( '.close_buynow_popup, #tb_popup_overlay' ).live( 'click', function () {
        closeBuyNowPopup();
    });

})
