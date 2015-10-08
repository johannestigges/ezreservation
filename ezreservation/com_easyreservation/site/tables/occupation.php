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
		 * select an occupation
		 * <p>
		 * use this functionto check if a period is available
		 *
		 * @param unknown $start_time        	
		 * @param unknown $end_time        	
		 */
		public function select($id_reservable, $start_time, $end_time) {
			$db = $this->_db;
			$query = $db->getQuery ( true );
			$query->select ( array (
					'id_reservable',
					'start_time',
					'end_time',
					'id_reservation',
					'name',
					'reservation_type' 
			) )->from ( $db->quoteName ( '#__ezr_occupation' ) )->where ( "id_reservable = $id_reservable and (start_time <= '$start_time' and end_time > '$start_time') or ((start_time <= '$end_time' and end_time > '$end_time'))" );
			$db->setQuery ( $query );
			return $db->loadObjectList ();
		}
		
		/**
		 * select all occuptions
		 */
		public function getOccupations($date) {
			$d = date ( 'Y-m-d', $date );
			return $this->_db->setQuery ( "select * from #__ezr_occupation where date(start_time) = '$d' order by start_time" )->loadObjectList ();
		}
		
		public function insert($data) {
			echo 'insert <pre>';print_r($data);echo '</pre>';
			$db = $this->_db;
			$columns = array (
					'id_reservable',
					'start_time',
					'end_time',
					'id_reservation',
					'name',
					'reservation_type' 
			);
			$values = array ();
			$values [] = $db->quote ( $data ['id_reservable'] );
			$values [] = $db->quote ( $data ['start_time'] );
			$values [] = $db->quote ( $data ['end_time'] );
			$values [] = $db->quote ( $data ['id_reservation'] );
			$values [] = $db->quote ( $data ['name'] );
			$values [] = $db->quote ( $data ['reservation_type'] );
			$query = $db->getQuery ( true );
			print_r ( $query );
			$query->insert ( $db->quoteName ( '#__ezr_occupation' ) )->columns ( $db->quoteName ( $columns ) )->values ( implode ( ',', $values ) );
			$db->setQuery ( $query );
			$db->execute ();
		}
	}	
