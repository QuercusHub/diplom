<?php 

function getConnection(){

    $host = 'localhost';
    $dbname = 'dp.loc';
    $charset = 'utf8';
    $user = 'root';
    $password = '';
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $db = new PDO($dsn, $user, $password, $opt);
    return $db;
}

function redirect_to($path)
{
    header('Location: ' . $path);
    exit();
}

function get_all_users()
{
    $db = getConnection();
    $users = $db->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);;
    return $users;
}

function create_user($login, $email, $password, $role, $avatar)
{
    $db = getConnection();

        $sql = "INSERT INTO `users` SET login = :login, email = :email, password = :password, role = :role, avatar = :avatar";
        $result2 = $db->prepare($sql);
        $result2->bindParam(':login', $login);
        $result2->bindParam(':email', $email);
        $result2->bindParam(':password', $password);
        $result2->bindParam(':role', $role);
        $result2->bindParam(':avatar', $avatar);
        $result2->execute();
        redirect_to("index.php");
    }
    

function get_user_by_id($id)
{

    if ($id) {
        $db = getConnection();
        $sql = 'SELECT * FROM users WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }
}

function delete_user($id)
{
    $db = getConnection();
    $avatar = get_user_avatar($id);
    unlink('uploads/' . $avatar);
    $sql = "DELETE FROM users WHERE id = :id";
    $result = $db->prepare($sql);
    $result->bindParam(':id', $id, PDO::PARAM_INT);
    $result->execute();

    redirect_to("index.php");
}

function update_user($id, $login, $email, $role, $status, $avatar)
{

    $db = getConnection();
    $sql = "UPDATE users SET login = :login, email = :email, role = :role, status = :status, avatar = :avatar WHERE id = :id";
    $result = $db->prepare($sql);
    $result->bindParam(':id', $id, PDO::PARAM_INT);
    $result->bindParam(':login', $login, PDO::PARAM_STR);
    $result->bindParam(':email', $email, PDO::PARAM_STR);
    $result->bindParam(':role', $role, PDO::PARAM_STR);
    $result->bindParam(':status', $status, PDO::PARAM_STR);
    $result->bindParam(':avatar', $avatar, PDO::PARAM_STR);
    $result->execute();
    
    //redirect_to("index.php");
}


function get_user_avatar($id)
{
    if ($id) {

        $db = getConnection();
        $sql = 'SELECT avatar FROM users WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        $avatar = $result->fetch();
        return $avatar['avatar'];
    }
}


function check_avatar_file_on_disk($filename)
{
    
    if (file_exists('uploads/' . $filename)) {
        $avatar = 'uploads/' . $filename;
    } else {
        $avatar = 'uploads/avatar-m.png';
    }
    return $avatar;
}

function update_user_password($password, $id)
{
    //var_dump($password, $id); die;
    $password = password_hash($password, PASSWORD_BCRYPT);
    $db = getConnection();
    $sql = "UPDATE users SET password = :password WHERE id = :id";
    $result = $db->prepare($sql);
    $result->bindParam(':id', $id, PDO::PARAM_INT);
    $result->bindParam(':password', $password, PDO::PARAM_STR);
    $result->execute();
    
    redirect_to("index.php");
}