<?php 

namespace Project;

class View {

    
    public function __construct() {
    }


    public function Header()
    {

        
        if (isset($_SESSION['user_id'])) {
            $login = '<a class="nav-link " href="'.DOMAIN.'/?mode=auth&action=exit">Выйти</a>';
        }else{
            $login = '<a class="nav-link " href="'.DOMAIN.'/?mode=auth&action=login">Войти</a>';
        }

        return '
        <!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Задачник</title>

    <!-- Bootstrap core CSS -->
    <link href="'.DOMAIN.'/public/css/bootstrap.min.css" rel="stylesheet">
    <link href="'.DOMAIN.'/public/css/style.css" rel="stylesheet">


</head>

<body>
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="'.DOMAIN.'">Задачи</a>

            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link btn btn-primary" href="'.DOMAIN.'/?mode=tasks&action=new"  > + Новая задача</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                     '.$login.'
                    
                </form>
            </div>
        </div>
    </nav>

    <main role="main" class="container">
        ';
    }

    public function Footer()
    {
        return '
        
    </main><!-- /.container -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    </html>

        ';
    }

    public function Success_Message($message)
    {
        return '
        <div class="alert alert-success" role="alert">
            '.$message.'
        </div>

        <a href="' . DOMAIN . '">На главную</a>
        ';
    }


    public function Error_Message($message, $validation_array=null)
    {
        return '
            <div class="alert alert-danger" role="alert">
                '.$message.'

                '.(!empty($validation_array)?('<br><br> * '.implode('<br> * ',$validation_array)):'').'
            </div>
            
            
        <a href="' . DOMAIN . '">На главную</a>
            ';
    }
}