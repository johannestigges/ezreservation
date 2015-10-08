<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla view library
jimport ( 'joomla.application.component.view' );

/**
 * HTML View class for the Easy Reservation Component
 */
class EasyReservationViewOccupation extends JViewLegacy {
	private $occupations;
	private $reservables;
	public $occupation_date;
	
	// Overwriting JView display method
	function display($tpl = null) {

		$this->occupations = $this->get ( 'Occupations' );
		$this->reservables = $this->get ( 'Reservables' );
		$this->occupation_date = $this->get('OccupationDate');
		
		$this->msg ( '<table class="occupation_table">' )->nl ()->msg ( '<tr>' );
		$this->tag ( JText::_ ( 'COM_EASYRESERVATION_OCCUPATION_TIME' ), 'th', 'colspan="3"' );
		foreach ( $this->reservables as $reservable ) {
			$this->tag ( $reservable->name, 'th' );
		}
		$this->msg ( '</tr>' )->nl ();
		
		foreach ( range ( 7, 23 ) as $time ) {
			$this->msg ( '<tr>' );
			$this->tag ( "$time:00", 'td', 'align="right"' );
			$this->tag ( '-', 'td' );
			$this->tag ( ($time + 1) . ':00', 'td', 'align="right"' );
			foreach ( $this->reservables as $reservable ) {
				$this->printOccupation ( $time, $reservable->id );
			}
			$this->msg ( '</tr>' );
		}
		$this->msg ( '</table>' )->nl ();
		
		// check for errors
		if (count ( $errors = $this->get ( 'Errors' ) )) {
			JLog::dd ( implode ( '<br />', $errors ), JLog::WARNING, 'jerror' );
			return false;
		}
		
		// Display the view
		parent::display ( $tpl );
	}
	
	private function tag($content, $tag, $attr = null) {
		$this->msg ( "<$tag" );
		if (isset ( $attr )) {
			$this->msg ( ' ' . $attr );
		}
		$this->msg ( '>' )->msg ( $content )->msg ( "</$tag>" );
		return $this;
	}
	
	private function msg($content) {
		$this->msg .= $content;
		return $this;
	}
	
	private function nl() {
		$this->msg .= "\n";
		return $this;
	}
	private function time($datetime) {
		$date = new JDate ( $datetime );
		return $date->format ( 'H:i' );
	}
	private function isStart($occupation, $time) {
		$date = new JDate ( $occupation->start_time );
		return strcmp ( $date->format ( 'H' ), $time ) == 0;
	}
	private function day($datetime) {
		$date = new JDate ( $datetime );
		return $date->format ( 'd.m.Y' );
	}
	private function printOccupation($time, $id_reservable) {
		foreach ( $this->occupations as $occupation ) {
			if ($occupation->id_reservable == $id_reservable and $this->isStart ( $occupation, $time )) {
				$this->tag ( $occupation->name, 'th', "class='type$occupation->reservation_type'" );
				return;
			}
		}
		$this->tag ( '&nbsp', 'th', 'class="type0"' );
	}
}
