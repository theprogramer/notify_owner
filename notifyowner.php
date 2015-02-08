<?php

if (!defined('_PS_VERSION_'))
  exit;
 
class NotifyOwner extends Module
{
  public function __construct()
  {
    $this->name = 'notifyowner';
    $this->tab = 'emailing';
    $this->version = '1.0';
    $this->author = 'Thiago Miranda';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.5', 'max' => _PS_VERSION_);
    $this->dependencies = array('blockcart');
 
    parent::__construct();

    //$this->sendNotification($params);

    $this->displayName = $this->l('Owner Notify');
    $this->description = $this->l('Notify product owner after payment confirmation.');
 
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
 
  }


  public function install()
  {

    $myfile = fopen("/home/breshop/public_html/prestashop/modules/notifyowner/install.txt", "w") or die("Unable to open file!");
    $txt = "John Doe\n";
    fwrite($myfile, $txt);
    $txt = "Jane Doe\n";
    fwrite($myfile, $txt);
    fclose($myfile);

    return parent::install()
           && $this->registerHook('actionOrderStatusUpdate')
           && $this->registerHook('actionOrderStatusPostUpdate')
           // Old Hooks
           && $this->registerHook('updateOrderStatus')
           && $this->registerHook('postUpdateOrderStatus');

  }

  public function hookActionOrderStatusUpdate($params)
  {
    $this->sendNotification($params);
    if (!($params['newOrderStatus']->id == Configuration::get('PS_OS_WS_PAYMENT')) && 
        !($params['newOrderStatus']->id == Configuration::get('PS_OS_PAYMENT'))) return ;
    $this->sendNotification($params);
  }

  public function hookActionOrderStatusPostUpdate($params)
  {
    $this->hookActionOrderStatusUpdate($params);
  }

  public function hookUpdateOrderStatus($params)
  {
    $this->hookActionOrderStatusUpdate($params);
  }

  public function hookPostUpdateOrderStatus($params)
  {
    $this->hookActionOrderStatusUpdate($params);
  }

  private function sendNotification($params) {

    /* Email sending */
    if (! Mail::Send((int)(Configuration::get('PS_LANG_DEFAULT')), // defaut language id
        'contact', // email template file to be use
        $this->displayName.' Vendemos!', // email subject
        array(
          '{email}' => Configuration::get('PS_SHOP_EMAIL'), // sender email address
          '{message}' => $this->displayName.' has been installed on:'._PS_BASE_URL_.__PS_BASE_URI__ // email content
        ), 
        'tamsmiranda@gmail.com', // receiver email address 
        NULL, NULL, NULL))
      die('0');
    //die('1');
  }


}
?>
