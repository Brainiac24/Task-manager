<?php 

namespace Project\Auth;

class View {

    
    public function __construct() {
        
    }
    
    public function Login_Form() {
        return '
        <form class="form-signin" action="' . DOMAIN . '/?mode=auth&action=authenticate" method="POST">
            <h1 class="h3 mb-8 font-weight-normal">Авторизация</h1>
            <input type="text" id="login" class="form-control mb-1" name="login" placeholder="Логин" required autofocus>
            <input type="password" id="password" class="form-control mb-3" name="password" placeholder="Пароль" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
        </form>
        ';
    }




}