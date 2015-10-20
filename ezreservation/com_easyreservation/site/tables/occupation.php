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

		public function getOccupations($start_time, $end_time, $id_reservable = 0) {
			$db = $this->_db;
			$query = $db->getQuery ( true );
			$query->select ( array (
					'id_reservable',
					'start_time',
					'end_time',
					'id_reservation',
					'name',
					'reservation_type' 
			) );
			$query->from ( $db->quoteName ( '#__ezr_occupation' ) );
			$query->where ( "start_time < " . $this->quoteDate ( $end_time ) );
			$query->where ( 'end_time > ' . $this->quoteDate ( $start_time ) );
			if ($id_reservable > 0) {
				$query->where ( "id_reservable = $id_reservable" );
			}
			$db->setQuery ( $query );
			return $db->loadObjectList ();
		}
		
		// public function getOccupations($date) {
		// $d = date ( 'Y-m-d', $date );
		// return $this->_db->setQuery ( "select * from #__ezr_occupation where date(start_time) = '$d' order by start_time" )->loadObjectList ();
		// }
		public function insert($data) {
			$db = $this->_db;
			$columns = array (
					'id_reservable',
					'start_time',
					'end_time',
					'id_reservation',
					'name',
					'reservation_type' 
			);
			$values = array (
					$db->quote ( $data ['id_reservable'] ),
					$db->quote ( $data ['start_time'] ),
					$db->quote ( $data ['end_time'] ),
					$db->quote ( $data ['id_reservation'] ),
					$db->quote ( $data ['name'] ),
					$db->quote ( $data ['reservation_type'] ) 
			);
			$query = $db->getQuery ( true );
			$query->insert ( $db->quoteName ( '#__ezr_occupation' ) )->columns ( $db->quoteName ( $columns ) )->values ( implode ( ',', $values ) );
			$db->setQuery ( $query );
			$db->execute ();
		}
		
		private function quoteDate($date) {
			return $this->_db->quote ( JFactory::getDate ( $date )->toSql () );
		}
	}	
