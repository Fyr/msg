<?php
Router::parseExtensions('html', 'json');
Router::connect('/', array('controller' => 'chat', 'action' => 'index'));

CakePlugin::routes();

require CAKE.'Config'.DS.'routes.php';
