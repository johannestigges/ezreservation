<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla view library
jimport ( 'joomla.application.component.view' );

/**
 * HTML View class for the Easy Reservation Component
 */
class EasyReservationViewReservables extends JViewLegacy {
	
	// Overwriting JView display method
	function display($tpl = null) {
		$this->msg = "<h1>Reservables</h1>";
		
		foreach ( $this->get ( 'All' ) as $reservable ) {
			$this->msg .= ('<br />' . $reservable->id . ' ' . $reservable->name);
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
