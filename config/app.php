<?php

require_once DOC_ROOT. '/php-activerecord/ActiveRecord.php';

ActiveRecord\Config::initialize(function($cfg)
{
    $cfg->set_model_directory(DOC_ROOT . '/models');
    $cfg->set_connections(array(
         'development' => 'mysql://tuntutan:tuntutan@127.0.0.1/tuntutan'
    ));
});