<?php

namespace Project\Task;

use PDO;
use Project\Database;

class Model
{
    private $db = null;

    public function __construct()
    {
        $this->db = (new Database\Controller())->db;
    }

    public function Get_List($page = 1, $order_by = 'id', $order_type = 'asc')
    {
        // Выборка списка задач
        try {
            $stmt = $this->db->prepare('SELECT * FROM TASKS ORDER BY ' . $order_by . ' ' . $order_type . ' LIMIT 3 OFFSET ' . (($page - 1) * 3));
            $stmt->execute();
            return $stmt;
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function Get_Count()
    {
        // Выборка количества задач
        $row_count = 0;
        try {
            $stmt = $this->db->prepare('SELECT Count(id) as row_count FROM TASKS');
            $stmt->execute();

            for ($i = 0; $i < $stmt->rowCount(); $i++) {
                $row = $stmt->fetch();
                $row_count = $row['row_count'];
                break;
            }
            return $row_count;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function Get_Detail_By_Id($id)
    {
        // Выборка детальных данных задачи 
        try {
            $stmt = $this->db->prepare("SELECT * FROM tasks WHERE `id` = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            return $stmt;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function Add_New($user_name, $user_email, $description)
    {
        // Добавление в базы данных новой задачи
        try {
            $stmt = $this->db->prepare("INSERT INTO tasks SET `user_name` = :user_name, `user_email` = :user_email, `description` = :description");
            $stmt->execute([
                ':user_name' => $user_name,
                ':user_email' => $user_email,
                ':description' => $description,
            ]);
            return $stmt;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function Edit_By_Id($id, $description, $status)
    {
        // Изменение данных задачи
        try {
            $old_description = '';
            $old_changed_status = '';
            // Выборка задачи для сопоставления изменений с новым текстом задачи
            $stmt = $this->Get_Detail_By_Id($id);
            for ($i = 0; $i < $stmt->rowCount(); $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $old_description = $row['description'];
                $old_changed_status = $row['changed_status'];
            }

            $stmt = $this->db->prepare("UPDATE tasks SET `status` = :status, `changed_status` = :changed_status, `description` = :description WHERE `id` = :id");
            $stmt->execute([
                ':id' => $id,
                ':status' => $status,
                ':description' => $description,
                ':changed_status' => ($old_description != $description ? 1 : $old_changed_status), // Проверка изменения администратором текста задачи 
            ]);
            return $stmt;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
