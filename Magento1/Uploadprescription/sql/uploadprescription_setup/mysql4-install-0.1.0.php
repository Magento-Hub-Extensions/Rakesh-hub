<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table prescription_details(id int not null auto_increment, customer_name varchar(100),customer_mail varchar(100),customer_phonenumber varchar(100),prescription_images varchar(100),clinical_history varchar(100),diagnosys varchar(100), primary key(id));

		
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 