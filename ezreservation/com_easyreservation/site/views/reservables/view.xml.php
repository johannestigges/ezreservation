<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla view library
jimport ( 'joomla.application.component.view' );

/**
 * XML View class for the HelloWorld Component
 */
class EasyReservationViewReservables extends JViewLegacy {
	// Overwriting JView display method
	function display($tpl = null) {
		echo "<?xml version='1.0' encoding='UTF-8'?> <reservables>";
		foreach ( $this->get ( 'All' ) as $reservable ) {
			echo "<reservable>";
			echo "<id>" . $reservable->id . "</id>";
			echo "<name>" . $reservable->name . "</name>";
			echo "</reservable>";
		}
		echo "</reservables>";
	}
}