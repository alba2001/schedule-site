<?php	                                       			 
/**
 * Schedule View for Schedule Component
 * 
 * @package    Training schedule
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Schedule Component
 *
 * @package	Training schedule
 * @subpackage	Components
 */
class ScheduleViewZapis extends JView
{
	function display($tpl = null)
	{
//		$items = $this->get( 'Data' );
//		$weeks = $this->get( 'Weeks' );
//		$this->assignRef( 'items',	$items );
//		$this->assignRef( 'weeks',	$weeks );

		parent::display($tpl);
	}
}
?>
