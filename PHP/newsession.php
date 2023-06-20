<?php
    include_once('config.php'); 
    session_start();
    $email_log = $_POST['email_login'];
    $senha_log = $_POST['senha_login'];
    $check = $_POST['check'];
    $sql = "SELECT * FROM usuarios WHERE Email = '$email_log' and Senha = '$senha_log'";
    $result = $conexao->query($sql);
    $user_data = mysqli_fetch_assoc($result);   
    $usuario = $user_data['Usuario'];
    $newpath = '/PHP'.'/'. $user_data['path'];
    $check_bool = filter_var($check, FILTER_VALIDATE_BOOLEAN);

    print_r($result);
    
    // if ($check_bool === true) {
    //     // Verificar se há uma sessão anteriormente criada
    //     if(isset($_SESSION['email_login']) && isset($_SESSION['senha_login'])) {
    //         // Verificar se a sessão existente é diferente da anterior
    //         if($_SESSION['email_login'] != $email_log || $_SESSION['senha_login'] != $senha_log) {
    //             // Apagar todas as informações da sessão anterior
    //             session_unset();
    //             session_destroy();
    //             session_start();
    //         }
    //     }
    
    //     // Criar uma nova sessão com as informações fornecidas
    //     $_SESSION['check'] = 1;
    //     $_SESSION['email_login'] = $email_log;
    //     $_SESSION['senha_login'] = $senha_log;
    //     $_SESSION['usuario'] = $usuario;
    //     $_SESSION['path'] = $newpath;

    //     echo "sessão criada com sucesso";
    // } else if ($check_bool === false) {
    //     // Verificar se há uma sessão anteriormente criada
    //     if(isset($_SESSION['email_login']) && isset($_SESSION['senha_login'])) {
    //         // Verificar se a sessão existente é diferente da anterior
    //         if($_SESSION['email_login'] != $email_log || $_SESSION['senha_login'] != $senha_log) {
    //             // Apagar todas as informações da sessão anterior
    //             session_unset();
    //             session_destroy();
    //             session_start();
    //         }
    //     }
        
    //     // Criar uma nova sessão com as informações fornecidas
    //     $_SESSION['check'] = 0;
    //     $_SESSION['email_login'] = $email_log;
    //     $_SESSION['senha_login'] = $senha_log;
    //     $_SESSION['usuario'] = $usuario;
    //     $_SESSION['path'] = $newpath;
    //     echo "sessão criada com sucesso";
    // } else {
    //     echo 'Error';
    // }
?>
