<?php
$this->startSetup();
$this->run("
    DROP TABLE IF EXISTS {$this->getTable('swatches/input')};
    CREATE TABLE {$this->getTable('swatches/input')} (
      `swatch_id` int(11) NOT NULL auto_increment,
      `swatch_name` varchar(255) NULL,
      PRIMARY KEY  (`swatch_id`)
    ) ENGINE=INNODB;
");
$this->run("
    DROP TABLE IF EXISTS {$this->getTable('swatches/input_option')};
    CREATE TABLE {$this->getTable('swatches/input_option')} (
      `option_id` int(11) NOT NULL auto_increment,
      `swatch_id` int(11) NOT NULL,
      `option_name` varchar(255) NULL,
      `option_price` decimal(12,2) NOT NULL DEFAULT '0.00',
      `option_image` varchar(255) NULL,
      `option_sku` varchar(255) NULL,
      `option_position` int(11) NOT NULL DEFAULT 0,
      PRIMARY KEY  (`option_id`),
    FOREIGN KEY (swatch_id)
        REFERENCES {$this->getTable('swatches/input')}(swatch_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
    ) ENGINE=INNODB;
");
$this->getConnection()->addColumn(
    $this->getTable('catalog/product_option_type_value'),
    'swatch_option',
    'int(10)'
);
$this->getConnection()->addConstraint(
    'FK_PRODUCT_SWATCH_OPTION',
    $this->getTable('catalog/product_option_type_value'),
    'swatch_option',
    $this->getTable('swatches/input_option'),
    'option_id',
    'restrict',
    'cascade'
);
$this->endSetup();
