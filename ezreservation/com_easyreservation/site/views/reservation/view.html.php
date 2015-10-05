<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla view library
jimport ( 'joomla.application.component.view' );

require_once JPATH_COMPONENT . '/views/reservables/view.html.php';

/**
 * HTML View class for the Easy Reservation Component
 */
class EasyReservationViewReservation extends JViewLegacy {
	
	// Overwriting JView display method
	function display($tpl = null) {
		$this->msg = '<h1>'.JText::_('COM_EASYRESERVATION_MY_RESERVATIONS').'</h1>';
		
		foreach ( $this->get ( 'MyReservations' ) as $reservation ) {
			$this->msg .= ('<br />' . $reservation->id . ' ' . $reservation->name);
// 			$reserable = $this->reservable($reservation->id_reservable);
// 			$this->mg .= $reservable->name;
		}
		
		
		// check for errors
		if (count ( $errors = $this->get ( 'Errors' ) )) {
			JLog::dd ( implode ( '<br />', $errors ), JLog::WARNING, 'jerror' );
			return false;
		}
		
		// Display the view
		parent::display ( $tpl );
	}
}
