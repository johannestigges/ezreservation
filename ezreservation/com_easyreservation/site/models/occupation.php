<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla modelitem library
jimport ( 'joomla.application.component.modelitem' );

/**
 * Reservations model
 */
class EasyReservationModelOccupation extends JModelItem {
	/**
	 * select all occupation of the selected day
	 */
	public function getOccupations() {
		$occupation_date = $this->getOccupationDate();
		$occupations =  $this->table()->getOccupations($occupation_date, strtotime('+7 day', $occupation_date));
		return $occupations;
	}

	
	/**
	 * get all reservables
	 */
	public function getReservables() {
		$table = JTable::getInstance ( 'Reservable', 'EasyReservationTable' );
		return $table->getAll ();
	}
	
	/**
	 * get the occupation day
	 * @return number
	 */
	public function getOccupationDate() {
		$jinput = JFactory::getApplication ()->input;

		$occupation_date = $jinput->get ( 'occupation_date', strtotime(date('d.m.Y')) );
		
		if ($jinput->get ( 'increment_date', null ) == '1') {
			$occupation_date = strtotime ( '+1 day', $occupation_date );
		}
		if ($jinput->get ( 'decrement_date', null ) == '1') {
			$occupation_date = strtotime ( '-1 day', $occupation_date );
		}
		return $occupation_date;
	}
	
	private function table() {
		return JTable::getInstance ( 'Occupation', 'EasyReservationTable' );
	}
}