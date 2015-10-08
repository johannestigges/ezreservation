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
			return $this->_db->setQuery ( "select * from #__ezr_reservation where user_id = $userid order by created" )->loadObjectList ();
		}
	}
	
	/**
	 * insert a reservation
	 *
	 * @param array $data        	
	 */
	public function insertReservation($data) {
		$db = $this->_db;
		$columns = array (
				'name',
				'reservation_type',
				'user_id',
				'id_reservable',
				'start_time',
				'end_time' 
		);
		$values = array ();
		$values [] = $db->quote ( $data ['name'] );
		$values [] = $db->quote ( $data ['reservation_type'] );
		$values [] = $db->quote ( $data ['user_id'] );
		$values [] = $db->quote ( $data ['id_reservable'] );
		$values [] = $db->quote ( $data ['start_time'] );
		$values [] = $db->quote ( $data ['end_time'] );
		$query = $db->getQuery ( true );
		$query->insert ( $db->quoteName ( '#__ezr_reservation' ) )->columns ( $db->quoteName ( $columns ) )->values ( implode ( ',', $values ) );
		$db->setQuery ( $query );
		$db->execute ();
		echo "inserted";
		return $db->insertid();
	}
}