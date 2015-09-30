<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
 
class EasyReservationTableOccupation extends JTable
{                      
  /**
  * Constructor
  *
  * @param object Database connector object
  */
  function __construct( &$db ) {
    parent::__construct('#__ezr_occupation', ['id_reservable','start_time'], $db);
  }
}