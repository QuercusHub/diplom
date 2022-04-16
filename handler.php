<?php
require_once 'function.php';

/**
 * удаление пользователя
 */
if (isset($_GET['id'])) {
    delete_user($_GET['id']);
}

/**
 * создание пользователя
 */
if (isset($_POST['create_user'])) {

    $id = $_POST['id'];
    $login = $_POST["login"];
    $email = $_POST["email"];
    $role = $_POST["role"];
    $status = $_POST['status'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    if (!empty($_FILES["avatar"]["name"])) {
        $avatar = uniqid() . $_FILES["avatar"]["name"];
        move_uploaded_file($_FILES["avatar"]["tmp_name"], 'uploads/' . $avatar);
    } else {
        $avatar = 'avatar-m.png';
    }
create_user($login, $email, $password, $role, $avatar);
}

/**
 * редактирование пользователя
 */
if (isset($_POST['edit_user'])) {

    $id = $_POST['id'];
    $login = $_POST["login"];
    $email = $_POST["email"];
    $role = $_POST["role"];
    $status = $_POST['status'];
    $password = $_POST['password'];

    if (empty($_FILES["avatar"]["name"])) {
        $avatar = get_user_avatar($id);
    } else {
        $avatar = uniqid() . $_FILES["avatar"]["name"];
        move_uploaded_file($_FILES["avatar"]["tmp_name"], 'uploads/' . $avatar);
    }

update_user($id, $login, $email, $role, $status, $avatar);

    if (!empty($password)) {
        update_user_password($password, $id);
    } else {
        redirect_to('index.php');
    }
}











