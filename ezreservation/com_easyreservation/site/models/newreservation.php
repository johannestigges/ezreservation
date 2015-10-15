<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla modelitem library
jimport ( 'joomla.application.component.modelitem' );

/**
 * Reservations model
 */
class EasyReservationModelNewReservation extends JModelItem {
	/**
	 * get all reservables
	 */
	public function getReservables() {
		return $this->table('Reservable')->getAll ();
	}
	
	
	public function newReservation() {
		$table_reservation = $this->table('Reservation');
		$table_occupation = $this->table('Occupation');
		
		$jinput = JFactory::getApplication ()->input;
		$user = JFactory::getUser();
		if ($user->id == 0) {
			return $this->redirectWithMessage(COM_EASYRESERVATION_NOT_LOGGED_IN,'warning','index.php/anmelden');
		}
		
		$data = array();
		$data['name'] = $user->name;
		$data['user_id'] = $user->id;
		$data['reservation_type'] = 1; // quick reservation
		$data['id_reservable'] = $jinput->get('id_reservable');
		$data['start_time'] = $this->calcStartTime($jinput);
		$data['end_time'] = $this->calcEndTime($jinput, $data['start_time']);
		
		// check availability
		$occupations = $table_occupation->select($data['id_reservable'],$data['start_time'],$data['end_time']);
		if (count($occupations) == 0) {
			$data['id_reservation'] = $table_reservation->insertReservation($data);
			$table_occupation->insert($data);
			return $this->redirectWithMessage(COM_EASYRESERVATION_RESERVATION_CREATED, 'index.php/component/easyreservation?view=occupation');
		} else {
			return $this->redirectWithMessage(COM_EASYRESERVATION_OCCUPATION_NOT_AVAILABLE,'warning','index.php/component/easyreservation?view=newReservation');
		}
	}
	
	private function calcStartTime($jinput) {
		$date = new JDate($jinput->get('start_date'));
		$datetime = $date->toUnix() + (int)$jinput->get('start_time') * 36;
		return new JDate($datetime);
	}
	
	private function calcEndTime($jinput, $start_time) {
		return new JDate($start_time->toUnix() + (int) $jinput->get('duration') * 3600);
	}
	
	
	private function table($table) {
		return JTable::getInstance ( $table, 'EasyReservationTable' );
	}
	
	private function redirectWithMessage($message, $type='message', $url) {
		$app = &JFactory::getApplication();
		$app->redirect($url);
		$app->enqueueMessage(JText::_($msg),$type);
	}
	
}