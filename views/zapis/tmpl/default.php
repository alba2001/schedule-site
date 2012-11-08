<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$document =& JFactory::getDocument();
$uri_base = JURI::base().'components/com_schedule/assets/';

// Add style sheet
$document->addStyleSheet($uri_base.'modal_st.css');

// Add scripts
$document->addScript($uri_base.'jquery.placeholder.min.js');



?>
<script type="text/javascript">
    jQuery(document).ready(function(){
	//Placeholder
	jQuery('input[placeholder], textarea[placeholder]').placeholder();
	jQuery(".close_mod").live("click", function(){
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

    });
</script>
<div class="mod_option_visit">
    <div class="wrap_m_cont">			
        <p class="mod_title">Запись на занятие</p>		

        <div class="m_cont">
            <div class="b_lf">
                <div class="min_h_246">
                    <p class="glad_to_see">Мы рады снова Вас видеть!</p>
                    <div class="mod_form">
                        <form action="">
                            <div class="form_unit">
                                <strong>Вариант посещения:</strong>
                                <div class="formline">
                                    <select id="sel_visit" name="sel_visit">
                                        <option selected="selected" value="1">Абонемент или клубная карта</option>
                                        <option value="2">Разовое посещение по купону</option>
                                        <option value="3">Разовое посещение</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form_unit unit_subscription">
                                <strong>Укажите ваши данные:</strong>
                                <div class="formline"><input type="text" class="f_txt" placeholder="номер абонемента или клубной карты" /></div>
                                <div class="formline"><input type="text" class="f_txt" placeholder="конт. тел. в формате +79121234567" /></div>
                            </div>

                            <div class="form_unit unit_once_coupon">
                                <strong>Укажите ваши данные:</strong>
                                <div class="formline"><input type="text" class="f_txt" placeholder="конт. тел. в формате +79121234567" /></div>
                                <div class="formline"><input type="text" class="f_txt" placeholder="Фамилия" /></div>									
                                <div class="formline">
                                    <input type="text" class="f_txt w_116" placeholder="Имя" />
                                    <input type="text" class="f_txt w_116" placeholder="Отчество" />
                                </div>
                            </div>

                            <div class="form_unit unit_once">
                                <strong>Укажите ваши данные:</strong>
                                <div class="formline"><input type="text" class="f_txt f_txt_error" placeholder="конт. тел. в формате +79121234567" /></div>
                                <div class="formline"><input type="text" class="f_txt" placeholder="Фамилия" /></div>									
                                <div class="formline">
                                    <input type="text" class="f_txt w_116" placeholder="Имя" />
                                    <input type="text" class="f_txt w_116 f_txt_error" placeholder="Отчество" />
                                </div>
                            </div>
                            <div class="clearbar"></div>
                        </form>
                    </div>

                </div>

                <a href="javascript:void()" class="bt_green"><span>записаться</span></a>

            </div>
            <div class="b_rt">
                <h4>Выбранное занятие</h4>
                <ul class="schedule_l">
                    <li>24.07.2012 (вторник)<span>дата</span></li>
                    <li>20:20 - 21:30<span>время</span></li>
                    <li>Хатха йога для начинающих<span>название</span></li>
                    <li>Марина Курдюкова<span>преподаватель</span></li>					
                </ul>
                <p class="err_txt">Пожалуйста, проверьте подсвеченные данные.</p>
            </div>
        </div>
    </div>
</div>
