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
    return parent::install()
           && $this->registerHook('actionOrderStatusUpdate')
           && $this->registerHook('actionOrderStatusPostUpdate');
  }

  public function hookActionOrderStatusUpdate($params)
  {
    if (!($params['newOrderStatus']->id == Configuration::get('PS_OS_WS_PAYMENT')) && 
        !($params['newOrderStatus']->id == Configuration::get('PS_OS_WS_PAYMENT')) &&
        !($params['newOrderStatus']->id == Configuration::get('MoIP_STATUS_0')) &&
        !($params['newOrderStatus']->id == Configuration::get('MoIP_STATUS_3'))) return ;
    $order = new Order($params['id_order']);
    $products = $params['cart']->getProducts(true);
    foreach ($products as $cartProduct) {
      $this->sendNotification($cartProduct['id_product']);
    }
  }

  public function hookActionOrderStatusPostUpdate($params)
  {
    $this->hookActionOrderStatusUpdate($params);
  }

  private function sendNotification($productId) {
    $product = new Product($productId);
    $link = new Link();
    $url = $link->getProductLink($product);
    // Send email
    if (Validate::isLoadedObject($product)) {
      if (! Mail::Send((int)(Configuration::get('PS_LANG_DEFAULT')), // defaut language id
          'notifyowner', // email template file to be use
          'Vendemos seu enjoo!', // email subject
          array(
            '{name}' => $product->owner_name, // Owner name
            '{product}' => $product->name[1], // Owner name
            '{product_url}' => $url,
            '{product_price}' => sprintf("%01.2f", $product->price)
          ), 
          $product->owner_email, // receiver email address 
          $product->owner_name, // receiver name
          NULL, // from
          NULL, // from name
          NULL, // attachment
          NULL, // SMTP
          dirname(__FILE__).'/mails/'
          )
        )
        die('0');
    }
  }


}
?>
