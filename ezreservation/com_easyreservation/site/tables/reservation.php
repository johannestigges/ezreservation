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
}