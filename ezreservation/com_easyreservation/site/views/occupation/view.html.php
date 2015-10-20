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
		$this->occupation_date = $this->get ( 'OccupationDate' );
		
		$this->msg ( '<table class="occupation_table">' )->nl ();
		$this->msg ( '<tr>' );
		$this->tag ( '&nbsp;', 'th', 'colspan="3"' );
		foreach ( range ( 0, 4 ) as $i ) {
			$this->tag ( $this->day ( $this->occupation_date, $i ), 'th', 'colspan="' . count ( $this->reservables ) . '"' );
		}
		$this->msg ( '</tr>' )->nl ();
		$this->msg ( '<tr>' );
		$this->tag ( JText::_ ( 'COM_EASYRESERVATION_OCCUPATION_TIME' ), 'th', 'colspan="3"' );
		foreach ( range ( 0, 4 ) as $i ) {
			foreach ( $this->reservables as $reservable ) {
				$this->tag ( $reservable->name, 'th' );
			}
		}
		$this->msg ( '</tr>' )->nl ();
		
		foreach ( range ( 7, 23 ) as $time ) {
			$this->msg ( '<tr>' );
			$this->tag ( "$time:00", 'td', 'align="right"' );
			$this->tag ( '-', 'td' );
			$this->tag ( ($time + 1) . ':00', 'td', 'align="right"' );
			foreach ( range ( 0, 4 ) as $i ) {
				foreach ( $this->reservables as $reservable ) {
					$this->printOccupation ( $i, $time, $reservable->id );
				}
			}
			$this->msg ( '</tr>' );
		}
		$this->msg ( '</table>' )->nl ();
		
		if (JFactory::getUser ()->id > 0) {
			$this->tag ( JText::_ ( 'COM_EASYRESERVATION_OCCUPATION_NEW_RESERVATION' ), 'a', 'href=' . $this->baseurl . '/index.php?option=com_easyreservation&view=newreservation' );
			$this->msg ( ' ' );
			$this->tag ( JText::_ ( 'COM_EASYRESERVATION_OCCUPATION_MY_RESERVATIONS' ), 'a', 'href=' . $this->baseurl . '/index.php?option=com_easyreservation&view=reservations' );
		} else {
			$this->tag ( JText::_ ( 'COM_EASYRESERVATION_OCCUPATION_SUBMIT_LOGIN' ), 'a', 'href=' . $this->baseurl . '/index.php/anmelden' );
		}
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
		$this->msg ( '>' )->msg ( $content )->msg ( '</$tag>' );
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
	private function day($datetime, $i = 0) {
		return date ( 'd.m.Y', $datetime + ( int ) $i * 86400 );
	}
	private function isInTimeRange($start_time, $end_time, $occupation) {
		return (strtotime ( $occupation->start_time ) < $end_time and strtotime ( $occupation->end_time ) > $start_time);
	}
	private function printOccupation($day, $time, $id_reservable) {
		$start_time = $this->occupation_date + ( int ) $day * 86400 + ( int ) $time * 3600;
		$end_time = $start_time + 3600;
		
		foreach ( $this->occupations as $occupation ) {
			if ($occupation->id_reservable == $id_reservable and $this->isInTimeRange ( $start_time, $end_time, $occupation )) {
				$this->tag ( $occupation->name, 'th', "class='type$occupation->reservation_type'" );
				return;
			}
		}
		$this->tag ( '&nbsp', 'th', 'class="type0"' );
	}
}
