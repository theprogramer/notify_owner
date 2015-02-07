<?php

if (!defined('_PS_VERSION_'))
  exit;
 
class OwnerNotifyModule extends Module
{
  public function __construct()
  {
    $this->name = 'ownernotify';
    $this->tab = 'front_office_features';
    $this->version = '1.0';
    $this->author = 'Thiago Miranda';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7');
    $this->dependencies = array('blockcart');
 
    parent::__construct();
 
    $this->displayName = $this->l('Owner Notify');
    $this->description = $this->l('Notify product owner after payment confirmation.');
 
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
 
    if (!Configuration::get('MYMODULE_NAME'))      
      $this->warning = $this->l('No name provided');
  }
}
?>