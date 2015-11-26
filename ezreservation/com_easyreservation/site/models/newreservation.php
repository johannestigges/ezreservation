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
	 * @var array containing all occupations
	 */
	private $occupations;

	/**
	 * get all reservables
	 */
	public function getReservables() {
		return $this->table ( 'Reservable' )->getAll ();
	}
	
	/**
	 * return all occupations
	 * @return multitype:
	 */
	public function getOccupations() {
		return $this->occupations;
	}
	
	/**
	 * return input data
	 * @return multitype:
	 */
	public function getInputData() {
		$jinput = JFactory::getApplication ()->input;
		$user = JFactory::getUser ();
		// check is user logged in?
		if ($user->id == 0) {
			$login = new JUri ( JRoute::_ ( 'index.php?option=com_users&view=login' ) );
			$login->setVar ( 'return', base64_encode ( JFactory::getURI () ) );
			$app->redirect ( JRoute::_ ( $login ) );
			return false;
		}
		
		$this->input_data = array ();
		$this->input_data ['id_reservable'] = $jinput->get ( 'id_reservable', '1', 'INT' );
		$this->input_data ['start_date'] = $jinput->get ( 'start_date', date ( 'd.m.Y', strtotime ( '+ 1 day' ) ) );
		$this->input_data ['start_time'] = $jinput->get ( 'start_time', '7', 'INT' );
		$this->input_data ['duration'] = $jinput->get ( 'duration', '1', 'INT' );
		$this->input_data ['reservation_type'] = $jinput->get ( 'reservation_type', '1', 'INT' );
		$this->input_data ['reservation_name'] = $jinput->get ( 'reservation_name', NULL, 'RAW');
		$this->input_data ['start_day'] = $jinput->get ( 'start_day' );
		$this->input_data ['end_day'] = $jinput->get ( 'end_day' );
		
		return $this->input_data;
	}
		
	/**
	 * create a new Reservation
	 * 
	 * @return boolean
	 */
	public function newReservation() {
		if ($this->getData () == false) {
			return false;
		}
		
		if ($this->checkData () == false) {
			return false;
		}
		
		$db = JFactory::getDbo ();
		try {
			$table_occupation = $this->table('Occupation');

			$db->transactionStart ();
			
			$this->reservation ['id_reservation'] = $this->table ( 'Reservation' )->insertReservation ( $this->reservation );
			
			foreach ($this->occupations as $occupation) {
				$occupation ['id_reservation'] = $this->reservation['id_reservation'];
				$table_occupation->insert($occupation);
			}
			
			$protocol = array ();
			$protocol ['id_reservation'] = $this->reservation ['id_reservation'];
			$protocol ['user_id'] = JFactory::getUser ()->id;
			$protocol ['description'] = 'created reservation '. $this->reservation['id_reservation'] . ' with ' . count($this->occupations) . ' occupations';
			$this->table ( 'Protocol' )->insert ( $protocol );
			
			$db->transactionCommit ();
		} catch ( Exception $e ) {
			$db->transactionRollback ();
			JErrorPage::render ( $e );
			return false;
		}
		
		return true;
	}
	
	/**
	 * check the reservation data
	 * @return boolean
	 */
	private function checkData() {
		$user = JFactory::getUser ();
		
		// check date not too far in future
		if (strtotime ( '+ 14 days' ) < $this->reservation ['start_time'] and 
				$user->authorise('core.admin') == false) {
			JFactory::getApplication ()->enqueueMessage ( 
					JText::_ ( COM_EASYRESERVATION_OCCUPATION_TOO_FAR_IN_FUTURE ), 'warning' );
			return false;
		}
		
		// check availability
		$table_occupation = $this->table ( 'Occupation' );
		for ($i = 0, $size = count($this->occupations); $i < $size; $i++) {
			$occupation = $this->occupations[$i];
			$o = $table_occupation->getOccupations ( 
					$occupation ['start_time'], 
					$occupation ['end_time'], 
					$this->reservation ['id_reservable'] );
			if (count($o) > 0) {
				$this->occupations[$i]['occupied'] = 1;
				JFactory::getApplication ()->enqueueMessage ( 
						JText::_ ( COM_EASYRESERVATION_OCCUPATION_NOT_AVAILABLE ), 'warning' );
				return false;
			}
		}
		return true;
	}
	
	/**
	 * transfer all data from $jinput to $input_data
	 * and $reservation
	 * 
	 * @return boolean|multitype:
	 */
	private function getData() {
		// check is user logged in?
		$user = JFactory::getUser ();
		if ($user->id == 0) {
			$login = new JUri ( JRoute::_ ( 'index.php?option=com_users&view=login' ) );
			$login->setVar ( 'return', base64_encode ( JFactory::getURI () ) );
			$app->redirect ( JRoute::_ ( $login ) );
			return false;
		}
		
		// get input data
		$this->getInputData();
		
		// create reservation data
		$this->reservation = array ();

		if (empty ( $this->input_data ['reservation_name'] )) {
			$this->reservation ['name'] = $user->name;
		} else {
			$this->reservation ['name'] = $this->input_data ['reservation_name'];
		}
		$this->reservation['name'] = str_replace ('--', '&shy;', $this->reservation['name']);

		$this->reservation ['reservation_type'] = $this->input_data ['reservation_type'];
		$this->reservation ['user_id'] = $user->id;
		$this->reservation ['id_reservable'] = $this->input_data ['id_reservable'];
		$this->reservation ['start_time'] = $this->addHours ( $this->input_data ['start_date'], $this->input_data ['start_time'] );
		$this->reservation ['end_time'] = $this->addHours ( $this->reservation ['start_time'], $this->input_data ['duration'] );
		$this->reservation ['start_day'] = $this->input_data ['start_day'];
		$this->reservation ['end_day'] = $this->input_data ['end_day'];
		
		// create occupations data
		$this->createOccupations();
		
		return true;
	}
	
	private function createOccupations() {
		$this->occupations = array();
		if (empty($this->reservation ['end_day'])) {
			$this->occupations[] = $this->createOccupation($this->reservation['start_time']);
		} else {
			$start_time = $this->reservation ['start_time'];
			$end_day = strtotime ($this->reservation ['end_day'] . ' + 1 day');
			while ($start_time < $end_day) {
				$this->occupations[] = $this->createOccupation($start_time);
				$start_time = strtotime("+ 1 week", $start_time);
			}
		}
	}
	
	/**
	 * create a new occupation object
	 * 
	 * @param unknown $id
	 * @param unknown $start_time
	 * @return multitype:
	 */
	private function createOccupation ($start_time) {
		$occupation['id_reservable'] = $this->reservation ['id_reservable'];
		$occupation['start_time'] = $start_time;
		$occupation['end_time'] = $this->addHours($occupation ['start_time'], $this->input_data['duration']);
		$occupation['id_reservation'] = ''; // is added later
		$occupation['name'] = $this->reservation ['name'];
		$occupation['reservation_type'] = $this->reservation ['reservation_type'];
		
		return $occupation;
	}
	
	/**
	 * add hours to a date
	 * @param unknown $day
	 * @param unknown $hours
	 * @return number
	 */
	private function addHours($day, $hours) {
		if (is_int ( $day )) {
			return strtotime ( " + $hours hours", $day );
		} else {
			return strtotime ( "$day + $hours hours" );
		}
	}
	
	/**
	 * returns a table object for database operations
	 * @param unknown $table
	 */
	private function table($table) {
		return JTable::getInstance ( $table, 'EasyReservationTable' );
	}
}