<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$document =& JFactory::getDocument();
$uri_base = JURI::base().'components/com_schedule/assets/';

// Add style sheet
$document->addStyleSheet($uri_base.'style.css');
//$document->addStyleSheet($uri_base.'modal_st.css');

$document->addStyleSheet($uri_base.'fancybox/jquery.fancybox.css?v=2.0.6');
$document->addStyleSheet($uri_base.'fancybox/helpers/jquery.fancybox-buttons.css?v=1.0.2');

// Add scripts
$document->addScript('/anahata/media/plg_fancybox/js/jquery-1.6.4.min.js');
$document->addScript($uri_base.'fancybox/jquery.fancybox.js?v=2.0.6');
$document->addScript($uri_base.'fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.2');
$document->addScript($uri_base.'jquery.placeholder.min.js');
$document->addScript($uri_base.'jquery.maskedinput-1.3.min.js');
$document->addScript($uri_base.'function.js');

// Source images
$btn_off_src = JURI::base().'components/com_schedule/assets/img/knopka-OFF.png';
$scale_src = JURI::base().'components/com_schedule/assets/img/shkala-%num%.png';
//$modal_link = JURI::base().'index.php?option=com_schedule&view=zapis&tmpl=component';
$modal_link = '#mod_option_visit';
?>

<div id="com_schedule">
<!--Модальные окна-->
<div id="mod_successful_entry" class="none">
</div>
<div id="mod_option_visit" class="none">
    <?php echo $this->loadTemplate('successful_entry');?>
    <?php echo $this->loadTemplate('option_visit');?>
</div>
<!--/Модальные окна-->
<div id="jbArticle">
    <div class="jbMeta">
        <div class="itemTitle">
            <div class="titleWrapper full ">
                <!--<h2 class="contentheading">-->
                <h2 class="componentheading">Расписание</h2>
            </div>
        </div>
    </div>
    <table border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td style="width: 105px;" valign="bottom">
                    <p><span style="font-size: small;">время</span></p>
                </td>
                <td valign="bottom">
                    <p><span style="font-size: small;">название</span></p>
                </td>
                <td style="width: 9px;" valign="bottom"></td>
                <td style="width: 150px;" valign="bottom">
                    <p><span style="font-size: small;">инструктор</span></p>
                </td>
                <td style="width: 57px;"></td>
                <td style="width: 30px;"></td>
            </tr>
<?php $temp_date = ''?>
<?php foreach($this->items as $item) :?>            
    <?php $start_row = $temp_date==$item->date?FALSE:TRUE?>            
            <?php if($start_row):?>
            <tr>
                <td colspan="6" valign="bottom">
                    <?php
                        $date = mktime(0, 0, 0, substr($item->date,5,2), substr($item->date,8,2), substr($item->date,0,4));
                        $week = (int)date('w', $date);
                        $date = substr($item->date,8,2).'.'.substr($item->date,5,2).'.'.substr($item->date,0,4);
                        $week = $this->weeks[$week];
                    ?>
                    <p><strong><span style="font-size: small;"><br />
                        <span  class="schedule_week"><?php echo $week?></span>
                        <span style="float:right" class="schedule_date"><?=$date?>
                        </span></span></strong>
                    </p>
                    <hr />
                </td>
            </tr>
                <?php $temp_date = $item->date?>
            <?php endif?>
            <tr>
                <?php $time = substr($item->time_start,0,3).substr($item->time_start,3,2)
                            .' - '.substr($item->time_stop,0,3).substr($item->time_stop,3,2)?>
                <td valign="bottom"><span id="schedule_time_<?=$item->id?>" style="line-height: normal; font-size: small;">
                            <?=$time?>
                    </span></td>
                <td valign="bottom">
                    <span style="line-height: normal; font-size: small;">
                        <?php if($item->training_link):?>
                            <a id="schedule_name_<?=$item->id?>" href="<?=$item->training_link?>">
                                <?=$item->name?>
                            </a>
                            <?php else :?>
                                <span id="schedule_name_<?=$item->id?>"><?=$item->name?></span>
                            <?php endif?>
                    </span></td>
                <td valign="bottom"><span style="font-size: small;"> </span><br /></td>
                <td valign="bottom">
                    <p><span style="font-size: small; line-height: normal;">
                            <?php
                                $fio = $item->im?' '.$item->im.' ':'';
                                $fio .= $item->fam;
//                                $fio .= $item->im?' '.mb_substr($item->im,0,1).'.':'';
//                                $fio .= $item->ot?' '.mb_substr($item->ot,0,1).'.':'';
                            ?>
                            <?php if($item->trainer_link):?>
                            <a id="schedule_fio_<?=$item->id?>" href="<?=$item->trainer_link?>">
                                <?=$fio?>
                            </a>
                            <?php else :?>
                                <span id="schedule_fio_<?=$item->id?>"><?=$fio?></span>
                            <?php endif?>
                        </span></p>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <a class="fancy_modal" href="<?=$modal_link?>" title="" rel="<?=$item->id.';'.$week.';'.$date?>">
                        <img src="<?=$btn_off_src?>" border="0" alt=""/>
                    </a>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <?php $num=ceil($item->visits*7/$item->max_clients) ?>
                    <?//=$item->visits.'/'.$num?>
                    <img src="<?=  str_replace('%num%', $num, $scale_src)?>" border="0" alt=""/>
                </td>
            </tr>
<?php endforeach?>            
        </tbody>
    </table>
</div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('.fancy_modal').click(function(){
            var data = $(this).attr('rel').split(';');
            var _id = data[0];
            var _week = data[1];
            var _date = data[2];
            var _time = $('#schedule_time_'+_id).text();
            var _name = $('#schedule_name_'+_id).text();
            var _fio = $('#schedule_fio_'+_id).text();
            $('.shedule_date').html(_date+' ('+_week+')<span>дата</span>');
            $('.shedule_time').html(_time+'<span>время</span>');
            $('.shedule_name').html(_name+'<span>название</span>');
            $('.shedule_fio').html(_fio+'<span>преподаватель</span>');
            $('#shedule_id').val(_id);
        });
    });
</script>
