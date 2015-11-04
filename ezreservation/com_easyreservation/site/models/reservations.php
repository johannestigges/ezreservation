<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla modelitem library
jimport ( 'joomla.application.component.modelitem' );

/**
 * Reservations model
 */
class EasyReservationModelReservations extends JModelItem {

	/**
	 * get all reservables
	 */
	public function getMyReservations() {
		$table = JTable::getInstance ( 'Reservation', 'EasyReservationTable' );
		return $table->getMyReservations ();
	}
	
	public function cancelReservation($id) {
		
		$table = JTable::getInstance ( 'Reservation', 'EasyReservationTable' );
		
		if ($table->load($id) and $table->id == $id and 
			$table->status == 0 and	strtotime ($table->start_time) > strtotime ('+8 hours')) {
				$table->cancelReservation($id);
				return true;
		}
		return false;
	}
}