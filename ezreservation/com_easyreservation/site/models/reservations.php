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
		return $this->table()->getMyReservations ();
	}
	
	public function cancelReservation($id) {
		if ($this->canCancel($id)) { 
			$this->table()->cancelReservation($id);
			return true;
		}
		return false;
	}
	
	public function deleteReservation ($id) {
		if ($this->isAdmin()) { 
			$this->table()->deleteReservation($id);
			return true;
		}
		return false;
	}
	
	public function canCancel($id) {
		if (empty($id) or $id == 0) {
			return false;
		}

		$table = $this->table();
		if ($table->load($id)) {
			if ($table->id == $id and $table->status == 0) {
				if ($this->isAdmin()) {
					return true;
				}
				if (strtotime ($table->start_time) > strtotime ('+8 hours')) {
					return true;
				}
			}
		}
		return false;
	}
	
	private function table($table='Reservation') {
		return JTable::getInstance($table, 'EasyReservationTable');
	}
	
	public function isAdmin() {
		return JFactory::getUser()->authorise('core.admin');
	}
}