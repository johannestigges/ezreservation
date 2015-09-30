<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the Easy Reservation Component
 */
class EasyReservationViewEasyReservation extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
		// Assign data to the view
		$this->msg = $this->get('Msg');
		
		// check for errors
		if (count($errors = $this->get('Errors')))
		{
			JLog::dd(implode('<br />', $errors), JLog::WARNING, 'jerror');
			return false;
		}
 
		// Display the view
		parent::display($tpl);
	}
}
?>