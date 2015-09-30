<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
 
class EasyReservationTableProtocol extends JTable
{                      
  /**
  * Constructor
  *
  * @param object Database connector object
  */
  function __construct( &$db ) {
    parent::__construct('#__ezr_protocol', ['id_reservation','created'], $db);
  }
}