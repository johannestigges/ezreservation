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
	 * select all occupations of the selected days
	 */
	public function getOccupations() {
		$start_date = $this->getStartDate();
		$end_date = $this->addDays($start_date, $this->getDays());
		return $this->table()->getOccupations($start_date, $end_date);
	}
	
	/**
	 * get all reservables
	 */
	public function getReservables() {
		$table = JTable::getInstance ( 'Reservable', 'EasyReservationTable' );
		return $table->getAll ();
	}
	
	/**
	 * get the starting occupation day
	 * @return number
	 */
	public function getStartDate() {
		$jinput = JFactory::getApplication ()->input;
		$start_date = $jinput->get ( 'start_date', strtotime(date('d.m.Y')) );
		if ($jinput->get ( 'increment_date', null ) == '1') {
			$start_date = $this->addDays($start_date, $this->getDays());
		}
		if ($jinput->get ( 'decrement_date', null ) == '1') {
			$start_date = $this->subtractDays($start_date, $this->getDays());
		}
		return $start_date;
	}
	
	public function getDays() {
		$jinput = JFactory::getApplication()->input;
		return $jinput->get ('days', 1, 'INT');
	}
	
	public function getStartHour() {
		$jinput = JFactory::getApplication()->input;
		return $jinput->get ('start_hour', 7, 'INT');
	}
	
	public function getEndHour() {
		$jinput = JFactory::getApplication()->input;
		return $jinput->get ('end_hour', 21, 'INT');
	}
	
	private function table() {
		return JTable::getInstance ( 'Occupation', 'EasyReservationTable' );
	}

	private function addDays ($datetime, $days) {
		return strtotime("+ $days days" , $datetime);
	}
	
	private function subtractDays($datetime, $days) {
		return strtotime("- $days days" , $datetime);
	}
}