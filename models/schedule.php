<?php
/**
 *
 * Schedule Model for Schedule Component
 * 
 * @package    Training schedule
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * Schedule Model
 *
 * @package    Training schedule
 * @subpackage Components
 */
class ScheduleModelSchedule extends JModel
{
	/**
	 * Schedule data array
	 *
	 * @var array
	 */
	var $_data;

        /**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery()
	{
            $query_visits = ' SELECT COUNT(*) FROM #__schedule_visits AS v WHERE v.calendar_id=c.id';
            $query = ' SELECT c.*,  t.fam, t.im, t.ot, t.trainer_link, v.name, v.training_link,'.
                        '('.$query_visits.') AS visits' 
                    .' FROM #__schedule_calendar AS c'
                    .' LEFT JOIN #__schedule_trainers AS t ON t.id = c.trainer_id'
                    .' LEFT JOIN #__schedule_vids AS v ON v.id = c.vid_id'
                    .$this->_buildQueryWhere()
                    .$this->_buildQueryOrderBy()
            ;
//                var_dump($query);exit;
		return $query;
	}

	/**
	 * Retrieves the schedule data
	 * @return array Array of objects containing the data from the database
	 */
	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
                    $query = $this->_buildQuery();
                    $this->_data = $this->_getList($query);
		}

		return $this->_data;
	}
        /**
        * Build the ORDER part of a query
        *
        * @return string part of an SQL query
        */        
        function _buildQueryOrderBy()
        {
            $orderby = ' ORDER BY date, time_start';
            // Return the ORDER BY clause
            return $orderby;
        }
    /**
    * Builds the WHERE part of a query
    *
    * @return string Part of an SQL query
    */
    function _buildQueryWhere()
    {
        // Prepare the WHERE clause
        $where = array();
        // Where date and time more then now
        $where[] = '(c.date > "'.date('Y-m-d').'" OR (c.date = "'.date('Y-m-d').'" AND c.time_start > "'.date('H:i').'"))';
        // return the WHERE clause
        return ($where) ? ' WHERE '.implode(' AND', $where) : '';
    }        
	/**
	 * Retrieves the week days name
	 * @return array Array of streeng
	 */
	function getWeeks()
	{
            return array(
                    'Воскресенье',
                    'Понедельник',
                    'Вторник',
                    'Среда',
                    'Черверг',
                    'Пятница',
                    'Суббота'
                    );
        }
        /**
         * Сохраняем запись на занятие
         * @return array 
         */
        function save()
        {
            $calendar_id = JRequest::getInt('id');
            // Проверяем окончена ли запись на это занятие
            $calendar =& $this->getTable('calendar');
            if($calendar->out_of_visits($calendar_id))
            {
                return array('status'=>0,'error'=>1,'text'=>JText::_('OUT_OF_VISITS'));
            }
            $sel_visit = jRequest::getInt('sel_visit',NULL);
            // Проверяем какой установлен тип визита
            if(!isset($sel_visit))
            {
                return array('status'=>0,'error'=>2,'text'=>JText::_('VISIT_TYPE_WRONG'));
            }
            switch ($sel_visit)
            {
                case 1:
                    list($result,$data) = $this->_get_data_card();
                    $phone = JRequest::getVar('phone_1');
                    if(!$result)
                    {
                        return array('status'=>0,'error'=>3,'text'=>$data);
                    }
                break;
                case 2:
                    $phone = JRequest::getVar('phone_2');
                    list($result,$data)  = $this->_get_data(2);
                    $data['training_type_id'] = 3;
                    if(!$result)
                    {
                        return array('status'=>0,'error'=>4,'text'=>$data);
                    }
                break;
                case 3:
                    $phone = JRequest::getVar('phone_3');
                    list($result,$data)  = $this->_get_data(3);
                    $data['training_type_id'] = 4;
                    if(!$result)
                    {
                        return array('status'=>0,'error'=>5,'text'=>$data);
                    }
                break;
            }
            
            $data['calendar_id'] = $calendar_id;
            $data['phone'] = $phone;
            $data['registered'] = 1;
            $data['visited'] = 0;
            $table = $this->getTable('visits');
            $visit = $table->get_row(array('client_id'=>$data['client_id'], 'calendar_id'=>$data['calendar_id']));
            if($visit['id']>0)
            {
                return array('status'=>0,'error'=>6,'text'=>JText::_('YUOR_ARE_WRITTEN_ALREDY'));
            }
            if($table->store_data($data))
            {
                return array('status'=>1,'text'=>JText::_('STORE_DATA_OK'));
            }
            else
            {
                return array('status'=>0,'error'=>7,'text'=>JText::_('ERROR_STORE_DATA'));
            }
        }
        /**
         * Возвращаем данные для записи на занятие по абонементу или клубной карте
         * @return array 
         */
        private function _get_data_card()
        {
            $data = array();
            $num_card = JRequest::getInt('num_card_1');
            // Объект таблицы абонементов
            $abonement =& $this->getTable('abonements');
            $abonement->bind_row(array('num'=>$num_card));
            // Если абоненмент не найден - возвращаем ошибку
            if(!$abonement->id)
            {
                return array(0,JTEXT::_('NUM_CARD_NOT_FIND'));
            }
            // Если карта не активирована - активируем ее
            if(!$abonement->is_active())
            {
                // Проверяем не прошел ли срок активации
                if($abonement->is_out_of_activate())
                {
                    return array(0,JTEXT::_('ACTIVATE_PERIOD_OF_YOUR_CARD_IS_OUT_OF_DATE'));
                }
                else 
                {
                    // Активация карты 
                    $abonement->activate_date = date('Y-m-d');
                    $abonement->store();
                }
            }
            else
            {
                // Проверяем не просрочен ли абонемент
                if($abonement->is_out_of_date())
                {
                    return array(0,JTEXT::_('YOUR_CARD_IS_OUT_OF_DATE'));
                }
            }
            // Проверка заморозки карты
            if($abonement->is_freezing())
            {
                return array(0,JTEXT::_('YOUR_CARD_IS_FREEZING'));
            }
            $data['training_type_id'] = $abonement->abonement_type_id;
            $data['client_id'] = $abonement->client_id;
            return array(1,$data);
        }
        /**
         * Возвращаем данные для записи на занятие по купону
         * @return array 
         */
        private function _get_data($type = 2)
        {
            $data = array();
            $fam = htmlspecialchars(JRequest::getVar('fam_'.$type));
            $im = htmlspecialchars(JRequest::getVar('im_'.$type));
            $ot = htmlspecialchars(JRequest::getVar('ot_'.$type));
            // Таблица клиентов
            $t_clients = $this->getTable('clients');
            $client = array('fam'=>$fam,'im'=>$im,'ot'=>$ot);
            // Если клиент не найден, то заносим его в таблицу
            if(!$row = $t_clients->get_row($client))
            {
                $phone = JRequest::getVar('phone_'.$type);
                $pattern = "/\+(\d{1})\((\d{3})\) (\d{3})-(\d{2})-(\d{2})/";
                $replace = "\\1\\2\\3\\4\\5";
                $client['phone'] = preg_replace($pattern,$replace,$phone);
                $client_id = $t_clients->store_data($client);
            }
            else
            {
                $client_id = $row['id'];
            }
            $data['client_id'] = $client_id;
            return array(1,$data);
        }
    
}
