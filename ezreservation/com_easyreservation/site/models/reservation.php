<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla modelitem library
jimport ( 'joomla.application.component.modelitem' );

/**
 * Reservations model
 */
class EasyReservationModelReservation extends JModelItem {
	/**
	 * get all reservables
	 */
	public function getMyReservations() {
		$table = JTable::getInstance ( 'Reservation', 'EasyReservationTable' );
		return $table->getMyReservations ();
	}
}