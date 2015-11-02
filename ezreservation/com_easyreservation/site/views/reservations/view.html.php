<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla view library
jimport ( 'joomla.application.component.view' );
JHtml::_('behavior.modal');
require_once JPATH_COMPONENT . '/views/reservables/view.html.php';

/**
 * HTML View class for the Easy Reservation Component
 */
class EasyReservationViewReservations extends JViewLegacy {
	
	// Overwriting JView display method
	function display($tpl = null) {
		$user = JFactory::getUser();
		if ($user->id == 0) {
			$JFactory::getApplication()->redirect(JUri::current());
			return;
		}
		
		$this->cancel(JFactory::getApplication()->input->get('cancel'));
		
		$this->msg = '<h1>' . JText::_ ( COM_EASYRESERVATION_RESERVATIONS ) . "</h1>\n";
		
		$this->msg .= '<table style="border-collapse: separate; border-spacing:20px;"><tr>';
		$this->msg .= ('<th>' . JText::_ ( COM_EASYRESERVATION_RESERVATIONS_RESERVABLE ) . '</th>');
		$this->msg .= ('<th>' . JText::_ ( COM_EASYRESERVATION_RESERVATIONS_TYPE ) . '</th>');
		$this->msg .= ('<th>' . JText::_ ( COM_EASYRESERVATION_RESERVATIONS_DATE ) . '</th>');
		$this->msg .= ('<th>' . JText::_ ( COM_EASYRESERVATION_RESERVATIONS_TIME ) . '</th>');
		$this->msg .= ('<th>' . JText::_ ( COM_EASYRESERVATION_RESERVATIONS_CREATED ) . '</th>');
		$this->msg .= ('<th>' . JText::_ ( COM_EASYRESERVATION_RESERVATIONS_STATUS ) . '</th>' );
		if ($user->authorise('core.admin')) {
			$this->msg .= ('<th>' . JText::_ ( COM_EASYRESERVATION_RESERVATIONS_USER ) . '</th>' );
		}
		
		$this->msg .= ('<th>&nbsp;</th>' );
		$this->msg .= "</tr>\n";
		foreach ( $this->get ( 'MyReservations' ) as $reservation ) {
			$this->msg .= '<tr>';
			$this->msg .= ('<td>' . $reservation->id_reservable . '</td>');
			$this->msg .= ('<td>' . JText::_('COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TYPE'.$reservation->reservation_type). '</td>');
			$this->msg .= ('<td>' . $this->date($reservation->start_time) . '</td>');
			$this->msg .= ('<td>' . $this->time($reservation->start_time) . ' - ' . $this->time($reservation->end_time) . '</td>');
			$this->msg .= ('<td>' . $reservation->created . '</td>');
			if ($reservation->status == 1) {
				$this->msg .= ('<td>' . JText::_ (COM_EASYRESERVATION_RESERVATIONS_CANCELLED) . '</td>' );
			} else {
				$this->msg .= '<td>'. JText::_(COM_EASYRESERVATION_RESERVATIONS_RESERVED) .'</td>';
			}
			if ($user->authorise('core.admin')) {
				$this->msg .= '<td>'. JFactory::getUser($reservation->user_id)->name . '</td>';
			}
			
			if ($this->canCancel($reservation)) {
				$this->msg .= '<td>';
				$this->showLinkCancel($reservation->id);
				$this->msg .= '</td>';
			} else {
				$this->msg .= '<td>&nbsp;</td>';
			}
			$this->msg .= "</tr>\n";
			// $reserable = $this->reservable($reservation->id_reservable);
			// $this->mg .= $reservable->name;
		}
		$this->msg .= "</table>\n";
		$this->msg .= '<a href="' . JRoute::_('index.php') . '">';
		$this->msg .= JText::_ ( COM_EASYRESERVATION_BACK );
		$this->msg .= "</a>\n";
		
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
	private function canCancel($reservation) {
		return $reservation->status == 0 and 
		strtotime($reservation->start_time) > strtotime ('+8 hours');
	}
	private function cancel ($id) {
		if ($id > 0) {
			$model = $this->getModel();
			return $model->cancelReservation($id);
		}
		return false;
	}
	
	private function showLinkCancel($id) {
		$link = JFactory::getURI();
		$link->setVar('cancel',$id);
		$this->msg .= '<td><a href="' . $link->toString() . '"';
		$this->msg .= (' onclick="return confirm(\''. JText::_ (COM_EASYRESERVATION_CONFIRM_CANCEL) . '\');"');
		$this->msg .= '>';
		$this->msg .= JText::_ (COM_EASYRESERVATION_RESERVATIONS_CANCEL);
		$this->msg .= '</a>';
	}
}
