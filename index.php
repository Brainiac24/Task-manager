<?php
// Вставка кода конфигурации и автозагрузки 
require_once('./configs.php');
require_once('./autoloader.php');

// Инициализация главного модуля проекта
echo (new Project\Controller())->Init();