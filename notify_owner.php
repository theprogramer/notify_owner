<?php

if (!defined('_PS_VERSION_'))
  exit;
 
class NotifyOwnerModule extends Module
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
 
  }

  public function install()
  {
    if (Shop::isFeatureActive())
      Shop::setContext(Shop::CONTEXT_ALL);
 
    return parent::install() &&
      $this->registerHook('actionPaymentConfirmation')
  }

  public function hookActionPaymentConfirmation($params)
  {
    /* Email sending */
    if (!Mail::Send((int)$module->context->cookie->id_lang,
        'notify_owner',
        sprintf(Mail::l('Vendemos o seu produto', (int)$module->context->cookie->id_lang)),
        null,// Templatevrs
        null, //$friendMail,
        null,
        'tamsmiranda@gmail.com',
        'Thiago Miranda',
        null,
        null,
        dirname(__FILE__).'/mails/'))
      die('0');
    die('1');
  }
}
?>