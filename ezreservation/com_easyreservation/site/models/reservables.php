<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla modelitem library
jimport ( 'joomla.application.component.modelitem' );

/**
 * Reservations model
 */
class EasyReservationModelReservables extends JModelItem {
	/**
	 * get all reservables
	 */
	public function getReservables() {
		$table = JTable::getInstance ( 'Reservable', 'EasyReservationTable' );
		return $table->getAll ();
	}
}