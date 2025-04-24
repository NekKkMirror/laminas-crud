<?php

$config = include __DIR__ . '/module/Db/config/module.config.php';

return $config['doctrine']['connection']['orm_default'];