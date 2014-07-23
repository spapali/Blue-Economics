<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('blueecon_faq', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration(array (
  'dsn' => 'mysql:host=localhost;dbname=blueecon_faq',
  'user' => 'root',
  'password' => '',
));
$manager->setName('blueecon_faq');
$serviceContainer->setConnectionManager('blueecon_faq', $manager);
$serviceContainer->setAdapterClass('blueeconomics', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration(array (
  'dsn' => 'mysql:host=localhost;dbname=blueeconomics',
  'user' => 'root',
  'password' => '',
));
$manager->setName('blueeconomics');
$serviceContainer->setConnectionManager('blueeconomics', $manager);
$serviceContainer->setDefaultDatasource('blueecon_faq');