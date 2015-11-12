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
		$model = $this->getModel();
		
		$this->cancel(JFactory::getApplication()->input->get('cancel'));
		$this->delete(JFactory::getApplication()->input->get('delete'));
	
		$this->msg = '<h1>' . JText::_ ( COM_EASYRESERVATION_RESERVATIONS ) . "</h1>\n";
		
		$this->msg .= "<table class='reservations'>\n<thead><tr>";
		if ($model->isAdmin()) {
			$this->msg .= ('<th class="optional">' . JText::_ ( COM_EASYRESERVATION_RESERVATIONS_ID ) . '</th>');
		}
		$this->msg .= ('<th>' . JText::_ ( COM_EASYRESERVATION_RESERVATIONS_RESERVABLE ) . '</th>');
		$this->msg .= ('<th class="optional">' . JText::_ ( COM_EASYRESERVATION_RESERVATIONS_TYPE ) . '</th>');
		$this->msg .= ('<th>' . JText::_ ( COM_EASYRESERVATION_RESERVATIONS_DATE ) . '</th>');
		$this->msg .= ('<th>' . JText::_ ( COM_EASYRESERVATION_RESERVATIONS_TIME ) . '</th>');
		$this->msg .= ('<th class="optional">' . JText::_ ( COM_EASYRESERVATION_RESERVATIONS_CREATED ) . '</th>');
		if ($user->authorise('core.admin')) {
			$this->msg .= ('<th class="optional">' . JText::_ ( COM_EASYRESERVATION_RESERVATIONS_USER ) . '</th>' );
		}
		$this->msg .= ('<th>' . JText::_ ( COM_EASYRESERVATION_RESERVATIONS_STATUS ) . '</th>' );
		$this->msg .= ('<th>&nbsp;</th>' );
		$this->msg .= ('<th>&nbsp;</th>' );
		$this->msg .= "</tr></thead><tbody>\n";
		foreach ( $this->get ( 'MyReservations' ) as $reservation ) {
			$this->msg .= '<tr>';
			if ($model->isAdmin()) {
				$this->msg .= ('<td class="optional">' . $reservation->id . '</td>');
			}
			$this->msg .= ('<td>' . $reservation->id_reservable . '</td>');
			$this->msg .= ('<td class="optional">' . JText::_('COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TYPE'.$reservation->reservation_type). '</td>');
			$this->msg .= ('<td>' . $this->date($reservation->start_time) . '</td>');
			$this->msg .= ('<td>' . $this->time($reservation->start_time) . ' - ' . $this->time($reservation->end_time) . '</td>');
			$this->msg .= ('<td class="optional">' . $reservation->created . '</td>');
			if ($model->isAdmin()) {
				$this->msg .= '<td class="optional">'. JFactory::getUser($reservation->user_id)->name . '</td>';
			}
			if ($reservation->status == 1) {
				$this->msg .= ('<td>' . JText::_ (COM_EASYRESERVATION_RESERVATIONS_CANCELLED) . '</td>' );
			} else {
				$this->msg .= '<td>'. JText::_(COM_EASYRESERVATION_RESERVATIONS_RESERVED) .'</td>';
			}
			if ($model->canCancel($reservation->id)) {
				$this->msg .= '<td>';
				$this->showLinkCancel($reservation->id);
				$this->msg .= '</td>';
			} else {
				$this->msg .= '<td>&nbsp;</td>';
			}
			if ($model->isAdmin()) {
				$this->msg .= '<td>';
				$this->showLinkDelete($reservation->id);
				$this->msg .= '</td>';
			} else {
				$this->msg .= '<td>&nbsp;</td>';
			}
			
			$this->msg .= "</tr>\n";
		}
		$this->msg .= "</tbody></table>\n";
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
			return $this->getModel()->cancelReservation($id);
		}
		return false;
	}
	private function delete ($id) {
		if ($id > 0) {
			return $this->getModel()->deleteReservation($id);
		}
		return false;
	}
	
	private function showLinkCancel($id) {
		$this->msg .= '<a href="' . JFactory::getURI()->toString() . "&cancel=$id" . '"';
		$this->msg .= (' onclick="return confirm(\''. JText::_ (COM_EASYRESERVATION_CONFIRM_CANCEL) . '\');"');
		$this->msg .= '>';
		$this->msg .= JText::_ (COM_EASYRESERVATION_RESERVATIONS_CANCEL);
		$this->msg .= '</a>';
	}
	
	private function showLinkDelete($id) {
		$this->msg .= '<a href="' . JFactory::getURI()->toString() . "&delete=$id" . '"';
		$this->msg .= (' onclick="return confirm(\''. JText::_ (COM_EASYRESERVATION_CONFIRM_DELETE) . '\');"');
		$this->msg .= '>';
		$this->msg .= JText::_ (COM_EASYRESERVATION_RESERVATIONS_DELETE);
		$this->msg .= '</a>';
	}
}
