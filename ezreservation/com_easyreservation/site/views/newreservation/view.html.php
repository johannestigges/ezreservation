<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla view library
jimport ( 'joomla.application.component.view' );

require_once JPATH_COMPONENT . '/views/reservables/view.html.php';

/**
 * HTML View class for the Easy Reservation Component
 */
class EasyReservationViewNewReservation extends JViewLegacy {

	public $reservables;
	public $date;
	
	// Overwriting JView display method
	function display($tpl = null) {

		$this->reservables = $this->get('Reservables');
		$this->date = new JDate('now + 1 day');
		$jinput = JFactory::getApplication()->input;


			// check for errors
		if (count ( $errors = $this->get ( 'Errors' ) )) {
			JLog::dd ( implode ( '<br />', $errors ), JLog::WARNING, 'jerror' );
			return false;
		}
		
		if ($jinput->get('submit',null ) == '1') {
			$model = new EasyReservationModelNewReservation();
			$model->newReservation();
			return true;
		}
		if ($jinput->get('cancel',null) == '1' || !JFactory::getUser()->id) {
			JFactory::getApplication()->redirect(JRoute::_("index.php?option=com_easyreservation&view=occupation"));
			return true;
		} else {
			// Display the view
			parent::display ( $tpl );
		}
	}
}
