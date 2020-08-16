<?php

namespace Project\Task;

class View
{

    
    public function __construct($var = null)
    {
        $this->var = $var;
    }

    public function Table_Start($page, $order_by, $order_type)
    {

        return '
            <table class="table">
            <thead class="thead-light">
                <tr>
                <th scope="col"><a href="'.DOMAIN.'/?mode=tasks&order_by=id&order_type='.($order_by=='id'?($order_type=='asc'?'desc':'asc'):'asc').'&page='.$page.'" class="header-link '.($order_by=='id'?"link-red":"").'">#</a></th>
                <th scope="col"><a href="'.DOMAIN.'/?mode=tasks&order_by=user_name&order_type='.($order_by=='user_name'?($order_type=='asc'?'desc':'asc'):'asc').'&page='.$page.'" class="header-link '.($order_by=='user_name'?"link-red":"").'">Имя пользователя</a></th>
                <th scope="col"><a href="'.DOMAIN.'/?mode=tasks&order_by=user_email&order_type='.($order_by=='user_email'?($order_type=='asc'?'desc':'asc'):'asc').'&page='.$page.'" class="header-link '.($order_by=='user_email'?"link-red":"").'">E-mail</a></th>
                <th scope="col">Текст задачи</th>
                <th scope="col"><a href="'.DOMAIN.'/?mode=tasks&order_by=status&order_type='.($order_by=='status'?($order_type=='asc'?'desc':'asc'):'asc').'&page='.$page.'" class="header-link '.($order_by=='status'?"link-red":"").'">Статус</a></th>
                <th scope="col">Действие</th>
                </tr>
            </thead>
            <tbody>
        ';
    }

    public function Table_End()
    {
        return '</tbody></table>';
    }

    public function Row($id, $user_name, $user_email, $description, $status, $changed_status)
    {
        $count = strlen($description);
        return '
        <tr>
            <th scope="row">' . $id . '</th>
            <td>' . $user_name . '</td>
            <td>' . $user_email . '</td>
            <td>' . (substr($description, 0, ($count > 100 ? 100 : $count))) . ($count <= 100 ? '' : '...') . '</td>
            <td>' . ($status ? '<span class="badge badge-success">Выполнено</span>' : '') . ($changed_status ? ' <span class="badge badge-primary"> Отредактировано администратором</span>' : '') . '</td>
            <td><a href="' . DOMAIN . '/?mode=tasks&action=detail&id=' . $id . '" class="btn btn-primary">Детально</a></td>
        </tr>
        ';
    }



    public function Detail($id, $user_name, $user_email, $description, $status, $changed_status)
    {
        $admin_permission = '';
        if (isset($_SESSION['user_status']) && $_SESSION['user_status']=='1') {
            $admin_permission = '<dt class="col-sm-3">Действие</dt>
                                <dd class="col-sm-9"><a href="' . DOMAIN . '/?mode=tasks&action=edit&id=' . $id . '" class="btn btn-primary">Изменить</a></dd>';
        }

        return '
        <dl class="row">
            <dt class="col-sm-3">#</dt>
            <dd class="col-sm-9">' . $id . '</dd>

            <dt class="col-sm-3">Имя пользователя</dt>
            <dd class="col-sm-9">' . $user_name . '</dd>

            <dt class="col-sm-3">E-mail</dt>
            <dd class="col-sm-9">' . $user_email . '</dd>

            <dt class="col-sm-3">Текст задачи</dt>
            <dd class="col-sm-9">' . $description . '</dd>

            <dt class="col-sm-3">Статус</dt>
            <dd class="col-sm-9">' . ($status ? '<span class="badge badge-success">Выполнено</span>' : '') . ($changed_status ? ' <span class="badge badge-primary"> Отредактировано администратором</span>' : '') . '</dd>


            ' . $admin_permission . '
            
        </dl>

        
        <a href="' . DOMAIN . '">На главную</a>
        
        ';
    }


    public function Add_Form()
    {
        return '
        <h1>Новая задача:</h1>
        <form action="' . DOMAIN . '/?mode=tasks&action=add" method="POST">
            <div class="form-group row">
            <label for="user_name" class="col-sm-2 col-form-label">Имя пользователя</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="user_name" name="user_name">
            </div>
            </div>
            <div class="form-group row">
                <label for="user_email" class="col-sm-2 col-form-label">E-mail</label>
                <div class="col-sm-10">
                <input type="email" class="form-control" id="user_email" name="user_email">
                </div>
            </div>
            <div class="form-group row">
                <label for="description" class="col-sm-2 col-form-label">Текст задачи</label>
                <div class="col-sm-10">
                <textarea class="form-control" id="description" name="description"></textarea>
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Добавить</button>
                </div>
            </div>
        </form>
        
        ';
    }

    public function Edit_Form($id, $user_name, $user_email, $description, $status)
    {
        return '
        <h1>Редактирование задачи:</h1>
        <form action="' . DOMAIN . '/?mode=tasks&action=update&id=' . $id . '" method="POST">
            <div class="form-group row">
                <label for="user_name" class="col-sm-2 col-form-label">Имя пользователя</label>
                <div class="col-sm-10">
                    ' . $user_name . '
                </div>
            </div>
            <div class="form-group row">
                <label for="user_email" class="col-sm-2 col-form-label">E-mail</label>
                <div class="col-sm-10">
                    ' . $user_email . '
                </div>
            </div>
            <div class="form-group row">
                <label for="description" class="col-sm-2 col-form-label">Текст задачи</label>
                <div class="col-sm-10">
                <textarea class="form-control" id="description" name="description">' . $description . '</textarea>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-2">Статус</div>
                <div class="col-sm-10">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="status" name="status" ' . (empty($status) ? '' : 'checked="checked"') . '>
                    <label class="form-check-label" for="status">
                    Выполнено
                    </label>
                </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Изменить</button>
                </div>
            </div>
        </form>
        
        
        ';
    }

    public function Pagination($pages)
    {
        return '
            <nav aria-label="...">
                <ul class="pagination pagination-md">
                    ' . $pages . '
                </ul>
            </nav>
            ';
    }

    public function Pages($url, $page_number, $is_active = false)
    {
        return '<li class="page-item ' . ($is_active ? 'active' : '') . '"><a class="page-link" href="' . DOMAIN . $url . '">' . $page_number . '</a></li>';
    }
}
