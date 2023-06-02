<?php
   session_start();
   include_once('config.php');
   
   $n_registro_login = $_POST['n_registro'];
   $placa_carro_login = $_POST['placa_carro'];
   $usuario = $_SESSION['usuario'];
   
   $sql = "SELECT * FROM motoristas WHERE n_registros = ? AND placa_carro = ?";
   $stmt = $conexao->prepare($sql);
   $stmt->bind_param("ss", $n_registro_login, $placa_carro_login);
   $stmt->execute();
   $result = $stmt->get_result();
   
   if ($result->num_rows < 1) {
       // motorista não encontrado
       echo "Não existe";
       unset($_SESSION['n_registro']);
       unset($_SESSION['placa_carro']);
   } else {
        $row = $result->fetch_assoc();
        if ($row['motorista'] == $usuario) {
            // motorista encontrado e corresponde ao usuário atual
            $_SESSION['n_registro'] = $row['n_registros'];
            $_SESSION['placa_carro'] = $row['placa_carro'];
            echo "Existe";
        } else {
            // motorista encontrado, mas não corresponde ao usuário atual
            echo "Motorista inválido";
            unset($_SESSION['n_registro']);
            unset($_SESSION['placa_carro']);
        }
   }
   $stmt->close();   
?>
