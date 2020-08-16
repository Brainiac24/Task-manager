<?php

namespace Project\Auth;

use PDO;
use Project;

class Controller
{

    private $view = null;
    private $project_view = null;
    public function __construct()
    {
        $this->view = new View();
        $this->project_view = new Project\View();
    }

    public function Init()
    {
        $result = '';
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) ?? "login";

        // Роутинг модуля "Авторизация"
        switch ($action) {
            case 'login':
                $result = $this->view->Login_Form();
                break;
            case 'authenticate':
                $result = $this->Login(filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
                break;
            case 'exit':
                $result = $this->Exit();
                break;
            default:
                break;
        }

        return $result;
    }

    public function Login($login, $password)
    {
        // Авторизация пользователя и создание сессии
        $stmt = (new Model())->Login($login, $password);
        for ($i = 0; $i < $stmt->rowCount(); $i++) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['login'];
            $_SESSION['user_status'] = $row['status'];
        }

        if (isset($_SESSION['user_id'])) {
            return $this->project_view->Success_Message('Вы успешно авторизованы как ' . $_SESSION['user_name']);
        } else {
            return $this->project_view->Error_Message('Ошибка при авторизации! Пользователь с таким логином или паролем не найдёны');
        }
    }

    public function Exit()
    {
        // Удалении авторизационных данных из сессии
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_status']);
        if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_name'])) {
            return $this->project_view->Success_Message('Вы успешно вышли из своего аккаунта!');
        } else {
            return $this->project_view->Error_Message('Ошибка при выходе. Вы авторизованы как ' . $_SESSION['user_name']);
        }
    }
}
