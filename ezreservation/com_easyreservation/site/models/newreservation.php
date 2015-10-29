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
	 * @var array containing all input data
	 */
	private $input_data; 

	/**
	 * @var array containing reservation data
	 */
	private $reservation;
	
	/**
	 * get all reservables
	 */
	public function getReservables() {
		return $this->table ( 'Reservable' )->getAll ();
	}

	public function newReservation() {
		
		if ($this->getData() == false) {
			return false;
		}

		if ($this->checkData() == false) {
			return false;
		}
		
		$table_reservation = $this->table ( 'Reservation' );
		$table_occupation = $this->table ( 'Occupation' );
		$table_protocol = $this->table ('Protocol');
		
		
		
		$this->reservation ['id_reservation'] = $table_reservation->insertReservation ( $this->reservation );
		$table_occupation->insert ( $this->reservation );
		
		$protocol = array();
		$protocol['id_reservation'] = $this->reservation['id_reservation'];
		$protocol['user_id'] = JFactory::getUser()->id;
		$protocol['description'] = 'created reservation';
		$this->table('Protocol')->insert ($protocol);
		
		$this->redirectWithMessage ( COM_EASYRESERVATION_RESERVATION_CREATED, 
				'index.php/component/easyreservation?view=occupation' );
		return true;
	}
	
	public function checkData() {
		// check date not too far in future
		if (strtotime('+ 14 days') < $this->reservation['start_time']) {
			JFactory::getApplication ()->enqueueMessage ( JText::_ ( COM_EASYRESERVATION_OCCUPATION_TOO_FAR_IN_FUTURE ), 'warning' );
			return false;
		}
		
		// check availability
		$table_occupation = $this->table ( 'Occupation' );
		$occupations = $table_occupation->getOccupations( $this->reservation ['start_time'], $this->reservation ['end_time'], $this->reservation ['id_reservable']  );
		if (count($occupations) > 0) {
			JFactory::getApplication ()->enqueueMessage ( JText::_ ( COM_EASYRESERVATION_OCCUPATION_NOT_AVAILABLE ), 'warning' );
			return false;
		}
		return true;
	}
	
	public function getData() {
		$jinput = JFactory::getApplication()->input;
		$user = JFactory::getUser ();
		// check is user logged in?
		if ($user->id == 0) {
			$login = new JUri(JRoute::_('index.php?option=com_users&view=login'));
			$login->setVar('return', base64_encode(JUri::current()));
			$app->redirect ( JRoute::_ ( $login ) );
			return false;				
		}
		
		$this->input_data = array();
		$this->input_data ['id_reservable'] = $jinput->get ( 'id_reservable','1','INT' );
		$this->input_data ['start_date'] = $jinput->get('start_date', date('d.m.Y',strtotime('+ 1 day')));
		$this->input_data ['start_time'] = $jinput->get('start_time', '7', 'INT');
		$this->input_data ['duration'] = $jinput->get( 'duration', '1', 'INT');
		$this->input_data ['reservation_type'] = $jinput->get ('reservation_type', '1' , 'INT');
		$this->input_data ['start_day'] = $jinput->get('start_day');
		$this->input_data ['end_day'] = $jinput->get('end_day');
		
		$this->reservation = array();
		switch ($this->input_data['reservation_type']) {
			case 1:
				$this->reservation['name'] = $user->name;
				break;
			case 2:
				$this->reservation['name'] = JText::_('COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TYPE2');
				break;
			case 3:
				$this->reservation['name'] = JText::_('COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TYPE3');
				break;
			case 4:
				$this->reservation['name'] = JText::_('COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TYPE4');
				break;
			default:
				$this->reservation['name'] = $user->name;
		}
		$this->reservation ['reservation_type'] = $this->input_data [ 'reservation_type' ]; 
		$this->reservation['user_id'] = $user->id;
		$this->reservation ['id_reservable'] = $this->input_data['id_reservable'];
		$this->reservation ['start_time'] = $this->addHours($this->input_data['start_date'],$this->input_data['start_time'] );
		$this->reservation ['end_time'] = $this->addHours( $this->reservation['start_time'], $this->input_data['duration'] );
		$this->reservation ['start_day'] = $this->input_data['start_day'];
		$this->reservation ['end_day'] = $this->input_data['end_day'];

		return $this->input_data;
	}
	
	private function addHours ($day, $hours) {
		if (is_int($day)) {
			return strtotime(" + $hours hours",$day);
		} else {
			return strtotime ("$day + $hours hours");
		}
	}
	
	private function table($table) {
		return JTable::getInstance ( $table, 'EasyReservationTable' );
	}
	
	private function redirectWithMessage($message, $url, $type = 'message') {
		$app = JFactory::getApplication ();
		$app->redirect ( JRoute::_ ( $url ) );
		$app->enqueueMessage ( JText::_ ( $msg ), $type );
	}
}