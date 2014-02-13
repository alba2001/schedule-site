<?php	                                       			 
/**
 * Schedule default controller
 * 
 * @package    Training schedule
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license    GNU/GPL
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Schedule World Component Controller
 *
 * @package		ScheduleWorld
 */
class ScheduleController extends JController
{
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{
		parent::display();
	}
	/**
	 * Сохраняем запись на занятие.
	 *
	 * @access	public
	 */
	function save()
	{
            // Check for request forgeries
            if(!JRequest::checkToken() OR !JRequest::checkToken('GET'))
            {
//                jexit( 'Invalid Token' );
            }
            
            $model = $this->getModel();
            echo json_encode($model->save());
            exit;
	}
	/**
	 * Автоматическое заполнение календаря занятий и
         * отправка писем о предстоящем дне роджения преподавателя
	 * @return void
	 */
	function fill_calendar()
	{
            require_once(JPATH_ROOT
                    . DS . 'administrator'
                    . DS . 'components'
                    . DS . 'com_schedule'
                    . DS . 'models'
                    . DS . 'training.php');
            require_once(JPATH_ROOT
                    . DS . 'administrator'
                    . DS . 'components'
                    . DS . 'com_schedule'
                    . DS . 'models'
                    . DS . 'trainers.php');

            $mod_trainers = new SchedulesModelTrainers;
            $trainers = $mod_trainers->soon_birth_day('10', TRUE);
            
            
            // Отправка письма со списком преподавателей
            if($trainers)
            {
                echo $this->_send_email($trainers)->message;
                echo '<br/>';
            }
            
            // у которых день рожденья ч-з 10 дней.
            $model = new SchedulesModelTraining;
            $msg = '';

            if ($model->auto_store_calendar(TRUE)) {
                    $msg .= '<br/>'.JText::_( 'Calendar filled!' );
            } else {
                    $msg .= '<br/>'.JText::_( 'Error Filling Calendar' );
            }
            echo $msg;
            exit;
	}
        /**
         * Отправка писм преподаватлям
         * @param array $trainers - список преподавателей.
         * @return bolean
         */
        private function _send_email($body)
        {
            $config =& JFactory::getConfig();
//            global $mainframe;
            $email['from'] = array( 
                $config->getValue( 'config.mailfrom' ),
                $config->getValue( 'config.fromname' ) );
            $params = &JComponentHelper::getParams('com_schedule');
            $email['to'] = $params->get('email');
//            $email['to'] = 'vasiliy.nalivayko@gmail.com';
            $email['replay'] = $config->getValue( 'config.mailfrom' );
            $subject = 'Празднует день рождения через 10 дней';
            $email['subject'] = $subject.' - '.$body[0];
            $email['body'] = $subject.': '.implode('</br>', $body);
            $mailer = & JFactory::getMailer();
            $mailer->setSender($email['from']);
            $mailer->addRecipient($email['to']);
            $mailer->addReplyTo($email['replay']);
            $mailer->setSubject($email['subject']);
            $mailer->setBody($email['body']);
            $mailer->IsHTML(true);
            return $mailer->Send();
        }

}
?>
