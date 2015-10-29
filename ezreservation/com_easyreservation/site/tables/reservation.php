<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );
class EasyReservationTableReservation extends JTable {
	/**
	 * Constructor
	 *
	 * @param
	 *        	object Database connector object
	 */
	function __construct(&$db) {
		parent::__construct ( '#__ezr_reservation', 'id', $db );
	}
	
	/**
	 * select all reservations of the current user
	 */
	public function getMyReservations() {
		$user = JFactory::getUser ();
		if (isset ( $user )) {
			$userid = $user->id;
			return $this->_db->setQuery ( 
					"select * from #__ezr_reservation where user_id = $userid order by start_time" )
			->loadObjectList ();
		}
	}

	/**
	 * cancel a reservation
	 * <p>
	 * the reservation gets status 1 and all occupations are deleted
	 * 
	 * @param unknown $id
	 */
	public function cancelReservation ($id) {
		$db = JFactory::getDbo();
		try {
			$db->transactionStart();
			$db->setQuery ("delete from #__ezr_occupation where id_reservation = $id");
			$db->execute();
			$db->setQuery ("update #__ezr_reservation set status = 1 where id = $id");
			$db->execute();
			$db->transactionCommit();
		} catch (Exception $e) {
			$db->transactionRollback();
			JErrorPage::render($e);
		}
	}
	
	/**
	 * insert a reservation
	 *
	 * @param array $data        	
	 */
	public function insertReservation($data) {
		$db = JFactory::getDbo();
		try {
		$columns = array (
				'name',
				'reservation_type',
				'user_id',
				'id_reservable',
				'start_time',
				'end_time',
				'created' 
		);
		$values = array (
				$db->quote ( $data ['name'] ),
				$db->quote ( $data ['reservation_type'] ),
				$db->quote ( $data ['user_id'] ),
				$db->quote ( $data ['id_reservable'] ),
				$this->quoteDate ( $data ['start_time'] ),
				$this->quoteDate ( $data ['end_time'] ),
				$db->quote ( JFactory::getDate ()->toSql () ) 
		);
		$query = $db->getQuery ( true );
		$query->insert ( $db->quoteName ( '#__ezr_reservation' ) );
		$query->columns ( $db->quoteName ( $columns ) );
		$query->values ( implode ( ',', $values ) );
		$db->setQuery ( $query );
		$db->execute ();
		return $db->insertid ();
		} catch (Exception $e) {
			JErrorPage::render($e);
		}
	}
	
	/**
	 * convert a unix timestamp into a database datetime format
	 *
	 * @param int $date        	
	 */
	private function quoteDate($date) {
		return $this->_db->quote ( date ( 'Y-m-d H:i:s', $date ) );
	}
}