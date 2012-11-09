<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$action = JROUTE::_(JURI::base()."index.php?option=com_schedule&task=save"); 
$ajax_img_src = JRoute::_(JURI::base()).'components/com_schedule/assets/img/ajax-loader.gif';
?>
<script type="text/javascript">
jQuery(document).ready(function($){
   $.mask.definitions['#']='[9]';  
   $(".sh_phone").mask("+7(#99) 999-99-99");
});
</script>
<div class="wrap_m_cont" id="div_option_visit">			
    <p class="mod_title">Запись на занятие</p>		
    <style type="text/css">
        #m_content p, li{
            text-align: left;
/*            line-height: normal;*/

        }
        #m_content ul, ul li {
            margin: 0;
        }
    </style>    

    <div class="m_cont" id="m_content">
        <div class="b_lf">
            <div class="min_h_246">
                <p class="glad_to_see">Мы рады снова Вас видеть!</p>
                <div class="mod_form">
                    <form id="option_visit" action="<?=$action?>" method="post" >
                        <input type="hidden" id="shedule_id" name="id" value="">
                        <?php echo JHTML::_( 'form.token' ); // устанавливаем токен?>  
                        <div class="form_unit">
                            <strong>Вариант посещения:</strong>
                            <div class="formline">
                                <select id="sel_visit" name="sel_visit">
                                    <option selected="selected" value="1">Абонемент или клубная карта</option>
<!--                                    <option value="2">Разовое посещение по купону</option>-->
                                    <option value="3">Разовое посещение</option>
                                </select>
                            </div>
                        </div>

                        <div class="form_unit unit_subscription">
                            <strong>Укажите ваши данные:</strong>
                            <div class="formline"><input type="text" name="num_card_1" id="num_card_1" class="f_txt" placeholder="номер абонемента или клубной карты" /></div>
                            <div class="formline"><input type="text" name="phone_1" class="f_txt sh_phone" placeholder="конт. тел. в формате +79121234567" /></div>
                        </div>

<!--                        <div class="form_unit unit_once_coupon">
                            <strong>Укажите ваши данные:</strong>
                            <div class="formline"><input type="text" name="phone_2" class="f_txt sh_phone" placeholder="конт. тел. в формате +79121234567" /></div>
                            <div class="formline"><input type="text"  name="fam_2" class="f_txt" placeholder="Фамилия" /></div>									
                            <div class="formline">
                                <input type="text" class="f_txt w_116" name="im_2" placeholder="Имя" />
                                <input type="text" class="f_txt w_116" name="ot_2" placeholder="Отчество" />
                            </div>
                        </div>-->

                        <div class="form_unit unit_once">
                            <strong>Укажите ваши данные:</strong>
                            <div class="formline"><input type="text" name="phone_3" class="f_txt sh_phone" placeholder="конт. тел. в формате +79121234567" /></div>
                            <div class="formline"><input type="text" name="fam_3" class="f_txt" placeholder="Фамилия" /></div>									
                            <div class="formline">
                                <input type="text" class="f_txt w_116" name="im_3" placeholder="Имя" />
                                <input type="text" class="f_txt w_116" name="ot_3" placeholder="Отчество" />
                            </div>
                        </div>
                        <div class="clearbar"></div>
                    </form>
                </div>

            </div>

            <a id="submit_zapis" href="javascript:void()" class="bt_green"><span>записаться</span></a>

        </div>
        <div class="b_rt">
            <h4>Выбранное занятие</h4>
            <ul class="schedule_l">
                <li class="shedule_date"></li>
                <li class="shedule_time"></li>
                <li class="shedule_name"></li>
                <li class="shedule_fio"></li>
            </ul>
            <p class="err_txt" id="err_text">Пожалуйста, проверьте подсвеченные данные.</p>
            <p class="err_txt" id="err_result"></p>
            <img class="err_txt" id="ajax_loader" src="<?=$ajax_img_src?>" alt="ajax_loader"/>
        </div>
    </div>
</div>
