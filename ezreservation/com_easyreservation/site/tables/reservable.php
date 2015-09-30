<?php

defined ( '_JEXEC' ) or die ( 'Restricted access' );
class EasyReservationTableReservable extends JTable {
	/**
	 * Constructor
	 *
	 * @param
	 *        	object Database connector object
	 */
	function __construct(&$db) {
		parent::__construct ( '#__ezr_reservable', 'id', $db );
	}
	
	/**
	 * select all reservables
	 */
	public function getAll() {
		return $this->_db->setQuery('select * from #__ezr_reservable order by id')->loadObjectList();
	}
}