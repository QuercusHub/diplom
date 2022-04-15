<?php
require_once 'function.php';

$id = $_GET['id'];



$id = $_POST['id'];
$login = $_POST["login"];
$email = $_POST["email"];
$role = $_POST["role"];
$status = $_POST['status'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

/**
 * создание пользователя
 */
if (isset($_POST['create_user'])) {

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

    if (empty($_FILES["avatar"]["name"])) {
        $avatar = get_user_avatar($id);
    } else {
        $avatar = uniqid() . $_FILES["avatar"]["name"];
        move_uploaded_file($_FILES["avatar"]["tmp_name"], 'uploads/' . $avatar);
    }
update_user($id, $login, $email, $role, $status, $avatar);
}

/**
 * удаление пользователя
 */
if (isset($id)) {
    delete_user($id);
}









