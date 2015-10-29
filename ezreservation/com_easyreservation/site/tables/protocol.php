<?php

defined ( '_JEXEC' ) or die ( 'Restricted access' );
class EasyReservationTableProtocol extends JTable {
	/**
	 * Constructor
	 *
	 * @param
	 *        	object Database connector object
	 */
	function __construct(&$db) {
		parent::__construct ( '#__ezr_protocol', [ 
				'id_reservation',
				'created' 
		], $db );
	}
	
	/**
	 * insert a reservation
	 *
	 * @param array $data        	
	 */
	public function insert($data) {
		$db = JFactory::getDbo ();
		try {
			$columns = array (
					'id_reservation',
					'created',
					'user_id',
					'description' 
			);
			$values = array (
					$db->quote ( $data ['id_reservation'] ),
					$db->quote ( date ('Y-m-d H:i:s') ),
					$db->quote ( $data ['user_id'] ),
					$db->quote ( $data ['description'] ) 
			);
			$query = $db->getQuery ( true );
			$query->insert ( $db->quoteName ( '#__ezr_protocol' ) );
			$query->columns ( $db->quoteName ( $columns ) );
			$query->values ( implode ( ',', $values ) );
			$db->setQuery ( $query );
			$db->execute ();
		} catch ( Exception $e ) {
			JErrorPage::render ( $e );
		}
	}
}