<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla view library
jimport ( 'joomla.application.component.view' );

require_once JPATH_COMPONENT . '/views/reservables/view.html.php';

/**
 * HTML View class for the Easy Reservation Component
 */
class EasyReservationViewReservations extends JViewLegacy {
	
	// Overwriting JView display method
	function display($tpl = null) {
		$this->msg = '<h1>' . JText::_ ( 'COM_EASYRESERVATION_RESERVATIONS' ) . '</h1>';
		
		$this->msg .= '<table style="border-collapse: separate; border-spacing:20px;"><tr>';
		$this->msg .= ('<th>' . JText::_ ( 'COM_EASYRESERVATION_RESERVATIONS_RESERVABLE' ) . '</th>');
		$this->msg .= ('<th>' . JText::_ ( 'COM_EASYRESERVATION_RESERVATIONS_DATE' ) . '</th>');
		$this->msg .= ('<th>' . JText::_ ( 'COM_EASYRESERVATION_RESERVATIONS_TIME' ) . '</th>');
		$this->msg .= ('<th>' . JText::_ ( 'COM_EASYRESERVATION_RESERVATIONS_CREATED' ) . '</th>');
		$this->msg .= '</tr>';
		foreach ( $this->get ( 'MyReservations' ) as $reservation ) {
			$this->msg .= '<tr>';
			$this->msg .= ('<td>' . $reservation->id_reservable . '</td>');
			$this->msg .= ('<td>' . $this->date($reservation->start_time) . '</td>');
			$this->msg .= ('<td>' . $this->time($reservation->start_time) . ' - ' . $this->time($reservation->end_time) . '</td>');
			$this->msg .= ('<td>' . $reservation->created . '</td>');
			$this->msg .= '</tr>';
			// $reserable = $this->reservable($reservation->id_reservable);
			// $this->mg .= $reservable->name;
		}
		$this->msg .= '</table>';
		$this->msg .= '<a href="';
		$this->msg .= $this->baseurl;
		$this->msg .= '/index.php?option=com_easyreservation&view=occupation">';
		$this->msg .= JText::_ ( 'COM_EASYRESERVATION_BACK' );
		$this->msg .= '</a>';
		
		// check for errors
		if (count ( $errors = $this->get ( 'Errors' ) )) {
			JLog::dd ( implode ( '<br />', $errors ), JLog::WARNING, 'jerror' );
			return false;
		}
		
		// Display the view
		parent::display ( $tpl );
	}
	private function date($d) {
		return JFactory::getDate($d)->format ('d.m.Y');
	}
	private function time($d) {
		return JFactory::getDate($d)->format ('H:i');
	}
}
