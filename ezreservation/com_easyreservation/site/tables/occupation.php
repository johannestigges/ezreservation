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
		 * select all occupations in a given time interval
		 *
		 * @param unknown $start_time        	
		 * @param unknown $end_time        	
		 * @param number $id_reservable        	
		 */
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
		
		/**
		 * insert an occupation
		 *
		 * @param unknown $data        	
		 */
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
					$this->quoteDate ( $data ['start_time'] ),
					$this->quoteDate ( $data ['end_time'] ),
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
			return $this->_db->quote ( date ( 'Y-m-d H:i:s', $date ) );
		}
	}
	?>