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
            JRequest::checkToken() or jexit( 'Invalid Token' );
            $model = $this->getModel();
            echo json_encode($model->save());
            exit;
	}
	/**
	 * cancel editing a record
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

//		$model = $this->getModel('training');
            $model = new SchedulesModelTraining;

		if ($model->auto_store_calendar(TRUE)) {
			$msg = JText::_( 'Calendar filled!' );
		} else {
			$msg = JText::_( 'Error Filling Calendar' );
		}
                echo $msg;exit;
	}

}
?>
