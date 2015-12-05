<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla view library
jimport ( 'joomla.application.component.view' );

// set locale
setlocale ( LC_ALL, JFactory::getLanguage ()->getTag () );

/**
 * HTML View class for the Easy Reservation Component
 * <p>
 * this class is responsible for displaying an occupation
 * table.
 */
class EasyReservationViewOccupation extends JViewLegacy {
	private $user;
	private $occupations;
	private $reservables;
	public $start_date;
	
	// Overwriting JView display method
	function display($tpl = null) {
		$this->user = JFactory::getUser ();
		$this->occupations = $this->get ( 'Occupations' );
		$this->reservables = $this->get ( 'Reservables' );
		$this->start_date = $this->get ( 'StartDate' );
		$days = $this->get ( 'Days' ) - 1;
		$startHour = $this->get ( 'StartHour' );
		$endHour = $this->get ( 'EndHour' );
		
		$this->msg ( '<table class="occupation_table">' )->nl ();
		$this->msg ( '<thead><tr>' );
		$this->tag ( '&nbsp;', 'th' );
		foreach ( range ( 0, $days ) as $i ) {
			$this->tag ( $this->convertDate ( $this->addDays ( $this->start_date, $i ) ), 'th', 'colspan="' . count ( $this->reservables ) . '"' . $this->css_class ( $this->days ( $i ) ) );
		}
		$this->msg ( '</tr>' )->nl ();
		$this->msg ( '<tr>' );
		$this->tag ( JText::_ ( 'COM_EASYRESERVATION_OCCUPATION_TIME' ), 'th' );
		foreach ( range ( 0, $days ) as $i ) {
			foreach ( $this->reservables as $reservable ) {
				$this->tag ( $reservable->name, 'th', $this->css_class ( $this->days ( $i ) ) );
			}
		}
		$this->msg ( '</tr></thead><tbody>' )->nl ();
		
		foreach ( range ( $startHour, $endHour ) as $time ) {
			$this->msg ( '<tr>' );
			$this->tag ( "$time:00 - " . ($time + 1) . ':00', 'td', 'align="center"' );
			foreach ( range ( 0, $days ) as $i ) {
				foreach ( $this->reservables as $reservable ) {
					$this->printOccupation ( $i, $time, $reservable->id );
				}
			}
			$this->msg ( '</tr>' )->nl ();
		}
		$this->msg ( '</tbody></table>' )->nl ();
		
		if ($this->user->id > 0) {
			$this->link ( 'COM_EASYRESERVATION_OCCUPATION_NEW_RESERVATION', 'index.php?option=com_easyreservation&view=newreservation' );
			$this->msg ( '&nbsp; ' );
			$this->link ( 'COM_EASYRESERVATION_OCCUPATION_MY_RESERVATIONS', 'index.php?option=com_easyreservation&view=reservations' );
		} else {
			$this->link ( 'COM_EASYRESERVATION_OCCUPATION_SUBMIT_LOGIN', 'index.php?option=com_users&view=login' );
		}
		
		// check for errors
		if (count ( $errors = $this->get ( 'Errors' ) )) {
			JLog::dd ( implode ( '<br />', $errors ), JLog::WARNING, 'jerror' );
			return false;
		}
		
		// Display the view
		parent::display ( $tpl );
	}
	/**
	 * create a html link
	 *
	 * @param unknown $msg        	
	 * @param unknown $url        	
	 */
	private function link($msg, $url) {
		$link = new JUri ( JRoute::_ ( $url ) );
		$this->tag ( JText::_ ( $msg ), 'a', "href='$link'" );
	}
	/**
	 *
	 * @param unknown $content        	
	 * @param unknown $tag        	
	 * @param string $attr        	
	 * @return EasyReservationViewOccupation
	 */
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
		return date ( 'H:i', $datetime );
	}
	private function addDays($datetime, $i = 0) {
		return strtotime ( "+ $i days", $datetime );
	}
	
	/**
	 * return formatted date
	 *
	 * @param unknown $datetime        	
	 * @return string
	 */
	private function convertDate($datetime) {
		return strftime ( ' %a, %d.%m.%Y', $datetime );
	}
	private function isInTimeRange($start_time, $end_time, $occupation) {
		return (strtotime ( $occupation->start_time ) < $end_time and strtotime ( $occupation->end_time ) > $start_time);
	}
	
	/**
	 * creates a HTML-Element for one occupation
	 *
	 * @param integer $day        	
	 * @param integer $time        	
	 * @param integer $id_reservable        	
	 */
	private function printOccupation($day, $time, $id_reservable) {
		$start_time = strtotime ( " + $day days + $time hours", $this->start_date );
		
		$occupation = $this->getOccupation ( $start_time, $id_reservable );
		if (isset ( $occupation )) {
			$this->showOccupation ( $day, $start_time, $occupation );
		} else {
			$this->showAvailable ( $day, $start_time, $id_reservable );
		}
	}
	
	/**
	 * Creates a HTML-Element showing an occupation.
	 *
	 * @param integer $start_time        	
	 * @param object $occupation        	
	 */
	private function showOccupation($day, $start_time, $occupation) {
		// if the duration of the occupation is more than one timeslot,
		// use 'rowspan' to show the occupation block.
		$duration = $this->getDuration ( $occupation );
		if ($duration > 1) {
			if ($start_time == strtotime ( $occupation->start_time )) {
				$this->tag ( $this->showOccupationName ( $occupation ), 'td', " rowspan='$duration'" . $this->css_class ( $this->reservationClass ( $occupation->reservation_type, $start_time ) . ' ' . $this->days ( $day ) ) )->nl ();
			} else {
				// show nothing!
			}
		} else {
			$this->tag ( $this->showOccupationName ( $occupation ), 'td', $this->css_class ( $this->reservationClass ( $occupation->reservation_type, $start_time ) . ' ' . $this->days ( $day ) ) )->nl ();
		}
	}
	private function showOccupationName($occupation) {
		if ($this->user->id > 0) {
			return JText::_ ( "COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TYPE" . $occupation->reservation_type ) . ' ' . $occupation->name;
		} else {
			return JText::_ ( "COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TYPE" . $occupation->reservation_type );
		}
	}
	
	/**
	 * Creates a html-element showing a table cell for a reservable time slot.
	 *
	 * @param integer $datetime        	
	 * @param object $id_reservable        	
	 * @return
	 *
	 */
	private function showAvailable($day, $datetime, $id_reservable) {
		// user not logged in? -> no link available
		if ($this->user->id == 0) {
			return $this->tag ( '&nbsp', 'td', $this->css_class ( $this->reservationClass ( 0, $datetime ) . ' ' . $this->days ( $day ) ) );
		}
		// create link to new reservation
		$link = new JUri ( JRoute::_ ( 'index.php?option=com_easyreservation&view=newReservation' ) );
		$link->setVar ( 'id_reservable', $id_reservable );
		$link->setVar ( 'start_date', date ( 'd.m.Y', $datetime ) );
		$link->setVar ( 'start_time', date ( 'H', $datetime ) );
		
		$this->msg ( '<td ' . $this->css_class ( $this->reservationClass ( 0, $datetime ) . ' ' . $this->days ( $day ) ) . '>' );
		// use inner <div> tag to show the link in all available space
		$this->tag ( '<div style="height:100%;width:100%">&nbsp;</div>', 'a', "href='$link'" );
		$this->msg ( '</td>' )->nl ();
	}
	
	/**
	 * calculates the duration of one occupation
	 *
	 * @param object $occupation        	
	 * @return number duration in hours
	 */
	private function getDuration($occupation) {
		return (strtotime ( $occupation->end_time ) - strtotime ( $occupation->start_time )) / 3600;
	}
	
	/**
	 * returns an occupation or NULL
	 *
	 * @param integer $start_time        	
	 * @param integer $id_reservable        	
	 * @return object occupation or NULL
	 */
	private function getOccupation($start_time, $id_reservable) {
		foreach ( $this->occupations as $occupation ) {
			if ($occupation->id_reservable == $id_reservable and $this->isInTimeRange ( $start_time, $start_time + 3600, $occupation )) {
				return $occupation;
			}
		}
	}
	
	/**
	 * returns a class attribute
	 *
	 * @param unknown $value        	
	 * @return string
	 */
	private function css_class($value) {
		if (is_array ( $value )) {
			return " class='" . join ( ' ', $value ) . "' ";
		} else {
			return " class='$value' ";
		}
	}
	
	/**
	 * returns the css days class
	 *
	 * @param unknown $days        	
	 * @return string
	 */
	private function days($days) {
		return " days$days ";
	}
	/**
	 * returns the css reservation class
	 *
	 * @param unknown $type        	
	 * @param unknown $datetime        	
	 * @return string
	 */
	private function reservationClass($type, $datetime) {
		return " type$type" . $this->weekend ( $datetime ) . ' ';
	}
	
	/**
	 * check if the datetime is a weekend (saturday or sunday)
	 *
	 * @param unknown $datetime        	
	 * @return string '_weekend' or ''
	 */
	private function weekend($datetime) {
		$day = date ( 'N', $datetime );
		if (6 == $day or 7 == $day) {
			return '_weekend';
		} else {
			return '';
		}
	}
}
