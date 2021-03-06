<?php 

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

//load tables
JTable::addIncludePath(JPATH_COMPONENT.'/tables');

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by EasyReservation
$controller = JControllerLegacy::getInstance('EasyReservation');

// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
