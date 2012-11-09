jQuery(document).ready(function($){
	//Placeholder
	jQuery('input[placeholder], textarea[placeholder]').placeholder();

	// Call modals
	jQuery("a.fancy_modal").fancybox({
		padding: 0,
		autoSize: false,
		fitToView	: false,
		width		: 617,
		height		: 412,
		maxWidth	: 617,
		maxHeight	: 600,
		type: 'inline',
		wrapCSS: 'modal_st',
		closeBtn : true,
		closeClick: false,
		nextClick : false,
		arrows : false,
		mouseWheel : false,
		keys : null,
		helpers: {
			overlay : {
				css: {
					cursor : 'auto'
				},
				closeClick : false
			}
		}
	});
	jQuery(".close_mod, .fancybox-close").live("click", function(){
                jQuery('#succesful_entry').hide();
                jQuery('#err_result').hide();
                jQuery('#div_option_visit').show();
		jQuery.fancybox.close( );
	})
	
	//Selecting visit	 
   jQuery("#sel_visit").change(function () {
	  jQuery("#sel_visit option:selected").each(function () {
			
			if( jQuery('#sel_visit option:selected').val()==1){
				jQuery('.unit_subscription').show("slow"),
				jQuery('.unit_once_coupon').css('display','none'),
				jQuery('.unit_once').css('display','none')
			}
			if( jQuery('#sel_visit option:selected').val()==2){
				jQuery('.unit_subscription').css('display','none'),
				jQuery('.unit_once_coupon').show("slow"),
				jQuery('.unit_once').css('display','none')
			}
			if( jQuery('#sel_visit option:selected').val()==3){
				jQuery('.unit_subscription').css('display','none'),
				jQuery('.unit_once_coupon').css('display','none'),
				jQuery('.unit_once').show("slow")
			}
			
		  });         
	})
	jQuery("#sel_visit").change()
        /**
         * Валидация данных
         * при успшной - вызов события сабмит
         */
	jQuery('#submit_zapis').click(function(e){
            e.preventDefault();
            var _class = get_class();
            var empty_inputs = jQuery(_class+' input:text[value=""]');
            if(empty_inputs.length > 0)
            {
                empty_inputs.addClass('f_txt_error');
                jQuery('#err_text').show("slow");
                return
            }
            else
            {
                var _inputs = jQuery(_class+' input:text');
                jQuery('#err_text').hide("slow");
                _inputs.removeClass('f_txt_error');

            }
            jQuery('#option_visit').submit();
        });
        /**
         *  Обработка сбмита формы и отправка данных
         */
        jQuery('#option_visit').submit(function(e){
            e.preventDefault();
            jQuery.ajaxSetup({
                beforeSend: function (){
                    jQuery('.err_txt').hide("slow");
                    jQuery('#ajax_loader').show();
                },
                complete: function (){jQuery('#ajax_loader').hide();}
            });
            jQuery.ajax({
                type: 'POST',
                url: jQuery('#option_visit').attr('action'),
                data: jQuery('#option_visit').serialize(),
                success: function(data){
                    data = jQuery.parseJSON(data);
                    if(data.status == 0)
                    {
                        jQuery('#err_result').html(data.text);
                        jQuery('#err_result').show("slow");
                        if(data.error == 3)
                        {
                            jQuery('#num_card_1').addClass('f_txt_error');
                        }
                    }
                    else
                    {
                        jQuery('#div_option_visit').hide('slow');
                        jQuery('#succesful_entry').show('slow');
                    }
                }
            });            
        });

});
function get_class()
{
    var classes = Array(
        '.unit_subscription',
        '.unit_once_coupon',
        '.unit_once'
    );
    var selected = jQuery('#sel_visit option:selected').val()-1;
    return classes[selected];
};

