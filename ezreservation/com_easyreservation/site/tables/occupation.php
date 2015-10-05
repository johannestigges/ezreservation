<?php

defined ( '_JEXEC' ) or die ( 'Restricted access' );
class EasyReservationTableOccupation extends JTable {
	/**
	 * Constructor
	 *
	 * @param
	 *        	object Database connector object
	 */
	function __construct(&$db) {
		parent::__construct ( '#__ezr_occupation', [ 
				'id_reservable',
				'start_time' 
		], $db );
	}
	
	/**
	 * select all occupation where start_time is between to values
	 *
	 * @param unknown $start_time_from        	
	 * @param unknown $start_time_until        	
	 */
	public function select($start_time_from, $start_time_until) {
		return $this->_db->setQuery ( "select * from #__ezr_occupation where start_time between \'$start_time_from\' and \'$start_time_until\' order by start_time" )->loadObjectList ();
	}
	
	/**
	 * select all occuptions
	 */
	public function getOccupations() {
		return $this->_db->setQuery ( "select * from #__ezr_occupation order by start_time" )->loadObjectList ();
	}
}