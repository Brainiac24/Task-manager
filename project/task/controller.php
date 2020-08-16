<?php

namespace Project\Task;

use PDO;
use Project;
use Project\Auth;

class Controller
{
    private $view = null;
    private $project_view = null;
    private $auth_model = null;

    public function __construct()
    {
        $this->view = new View();
        $this->project_view = new Project\View();
        $this->auth_model = new Auth\Model();
    }

    public function Init()
    {
        $result = '';
        // Роутинг модуля "Задачи"
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) ?? "list";
        switch ($action) {
            case 'list':
                $result = $this->Get_List(filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT), filter_input(INPUT_GET, 'order_by', FILTER_SANITIZE_STRING), filter_input(INPUT_GET, 'order_type', FILTER_SANITIZE_STRING));
                break;
            case 'detail':
                $result = $this->Get_Detail_By_Id(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
                break;
            case 'new':
                $result = $this->view->Add_Form();
                break;
            case 'add':
                $result = $this->Add_New(filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING));
                break;
            case 'edit':
                $result = $this->Edit_Form(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
                break;
            case 'update':
                // Проверка сессии и доступа администратора
                if (isset($_SESSION['user_id']) ) {
                    $user = $this->auth_model->Get_By_Id($_SESSION['user_id']);
                    if ($user !== null && $user['status'] == 1) {
                        // Вызов процесса изменения задачи
                        $result = $this->Edit_By_Id(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT), filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'status', FILTER_DEFAULT, FILTER_VALIDATE_BOOLEAN));
                    }
                }else{
                    $result = $this->project_view->Error_Message('Ошибка! Вы не авторизованы!');
                }
                break;
            default:
                break;
        }

        return $result;
    }

    public function Get_List($page = 1, $order_by = 'id', $order_type = 'asc')
    {
        // Инициализирование дефолтных данных 
        $page = $page != null ? $page : 1;
        $order_by = $order_by != null ? $order_by : 'id';
        $order_type = $order_type != null ? $order_type : 'asc';
        $result = '';

        // Вывод списка задач
        $stmt = (new Model())->Get_List($page, $order_by, $order_type);
        $result .= $this->view->Table_Start($page, $order_by, $order_type);
        for ($i = 0; $i < $stmt->rowCount(); $i++) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $result .= $this->view->Row($row['id'], $row['user_name'], $row['user_email'], $row['description'], $row['status'], $row['changed_status']);
        }
        $result .= $this->view->Table_End();

        // Блок пагинации
        $row_count = (new Model())->Get_Count();
        $pages = '';
        for ($i = 0, $c = 1; $i < $row_count; $i += 3, $c++) {
            $pages .= $this->view->Pages('/?mode=tasks&order_by=' . $order_by . '&page=' . $c, $c, ($page == $c ? true : false));
        }
        $result .= $this->view->Pagination($pages);

        return $result;
    }

    public function Get_Detail_By_Id($id)
    {
        // Вывод детальных данных задачи
        $result = '';
        $stmt = (new Model())->Get_Detail_By_Id($id);
        for ($i = 0; $i < $stmt->rowCount(); $i++) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $result .= $this->view->Detail($row['id'], $row['user_name'], $row['user_email'], $row['description'], $row['status'], $row['changed_status']);
        }
        return $result;
    }

    public function Add_New($user_name, $user_email, $description)
    {

        // Блок валидации
        $validation_array = [];
        if (!preg_match("/^[A-Za-z А-Яа-яЁё]{2,255}$/u", $user_name)) {
            $validation_array[] = 'Поле "Имя пользователя" имеет не корректный формат';
        }
        if (!preg_match("/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/u", $user_email)) {
            $validation_array[] = 'Поле "E-mail" имеет не корректный формат';
        }
        if (!preg_match("/^[-.,\"'_0-9A-Za-z А-Яа-яЁё@?]+$/u", $description)) {
            $validation_array[] = 'Поле "Текст задачи" имеет не корректный формат';
        }

        if (empty($validation_array)) {
            // Добавление задачи
            $result = (new Model())->Add_New($user_name, $user_email, $description);
            if ($result) {
                return $this->project_view->Success_Message('Задача успешно изменена!');
            } else {
                return $this->project_view->Error_Message('Ошибка при добавлении задачи!');
            }
        } else {
            return $this->project_view->Error_Message('Ошибка при добавлении задачи!', $validation_array);
        }
    }

    public function Edit_By_Id($id, $description, $status)
    {
        // Валидация поля текста задачи
        $validation_array = [];
        if (!preg_match("/^[-.,\"'_0-9A-Za-z А-Яа-яЁё@?]+$/u", $description)) {
            $validation_array[] = 'Поле "Текст задачи" имеет не корректный формат';
        }

        if (empty($validation_array)) {
            // Изменение задачи
            $result = (new Model())->Edit_By_Id($id, $description, ($status == "on" ? 1 : 0));
            if ($result) {
                return $this->project_view->Success_Message('Задача успешно изменена!');
            } else {
                return $this->project_view->Error_Message('Ошибка при изменении задачи!');
            }
        } else {
            return $this->project_view->Error_Message('Ошибка при изменении задачи!', $validation_array);
        }
    }

    public function Edit_Form($id)
    {
        // Вывод формы для изменения задачи 
        $result = '';
        $user = $this->auth_model->Get_By_Id($id);
        if (isset($_SESSION['user_id']) && $user !== null && $user['status'] == 1) {
            $stmt = (new Model())->Get_Detail_By_Id($id);
            for ($i = 0; $i < $stmt->rowCount(); $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $result .= $this->view->Edit_Form($row['id'], $row['user_name'], $row['user_email'], $row['description'], $row['status']);
            }
        }

        return $result;
    }
}
