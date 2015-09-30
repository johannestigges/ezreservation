<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla view library
jimport ( 'joomla.application.component.view' );

/**
 * HTML View class for the Easy Reservation Component
 */
class EasyReservationViewReservation extends JViewLegacy {
	
	// Overwriting JView display method
	function display($tpl = null) {
		$this->msg = "<h1>Reservations</h1>";
		
		foreach ( $this->get ( 'MyReservations' ) as $reservation ) {
			$this->msg .= ('<br />' . $reservation->id . ' ' . $reservation->name);
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
