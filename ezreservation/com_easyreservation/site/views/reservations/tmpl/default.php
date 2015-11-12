<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$csspath = JUri::base () . 'components/com_easyreservation/css/reservations.css';
$document = JFactory::getDocument ();
$document->addStyleSheet ( $csspath );

echo $this->msg; 
?>
