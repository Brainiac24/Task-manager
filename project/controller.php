<?php

namespace Project;

class Controller
{

    public function __construct()
    {
        // Инициализация модуля сессии
        ob_start();
        session_start();
    }

    public function Init()
    {
        $res = '';
        $data = '';
        $view = new View();
        
        $mode = filter_input(INPUT_GET, 'mode', FILTER_SANITIZE_STRING) ?? "tasks";
        // Роутинг базового модуля проекта
        switch ($mode) {
            case 'tasks':
                $data = (new Task\Controller())->Init();
                break;
            case 'auth':
                $data = (new Auth\Controller())->Init();
                break;
            default:
                break;
        }
        $res .= $view->Header();
        $res .= $data;
        $res .= $view->Footer();

        return $res;
    }
}
