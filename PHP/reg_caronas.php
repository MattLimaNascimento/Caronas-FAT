<?php
session_start();
include_once('config.php');

$usuario = $_SESSION['usuario'];
$tipo = $_POST['tipo'];

if ($tipo == 'reserva') {
    $acento = $_POST['acento'];
    $motorista = $_POST['motorista'];

    // Verificar se o motorista já está na tabela reservas
    $sqlVerifica = "SELECT * FROM reservas WHERE Motorista = '$motorista'";
    $resultVerifica = $conexao->query($sqlVerifica);

    if ($resultVerifica->num_rows > 0) {
        // O motorista já está na tabela   
        $sql = "UPDATE `reservas` SET `$acento` = '$usuario' WHERE `reservas`.`Motorista` = '$motorista';";
        $result = $conexao->query($sql);
        if ($result == 1) {
            echo 'Reserva feita com sucesso!';
        } else {
            echo 'Erro ao fazer reserva.';
        }
    } else {
        // O motorista não está na tabela, realiza a inserção na tabela   
        $sql = "INSERT INTO reservas(`Motorista`, `$acento`) VALUES ('$motorista' , '$usuario')";
        $result = $conexao->query($sql);
        if ($result == 1) {
            echo 'Reserva feita com sucesso!';
        } else {
            echo 'Erro ao fazer reserva.';
        }
    }
} else if ($tipo == 'conferir') {
    $sql = "SELECT * FROM reservas WHERE '$usuario' IN (frente, atras1, atras2, atras3, atras4, atras5,atras6)";
    $result = $conexao->query($sql);

    if ($result && $result->num_rows > 0) {
        echo 'Você tem uma reserva.';
    } else {
        echo 'Você não tem uma reserva.';
    }
} else if ($tipo == 'conferir2') {
    $Data = $_POST['data_hoje'];
    $sql = "SELECT * FROM anuncios_caronas JOIN usuarios ON anuncios_caronas.Usuario = usuarios.Usuario JOIN reservas ON usuarios.Usuario = reservas.Motorista WHERE '$usuario' IN (reservas.frente, reservas.atras1, reservas.atras2, reservas.atras3, reservas.atras4, reservas.atras5, reservas.atras6, reservas.garupa);";
    $result = $conexao->query($sql);
    if ($result && $result->num_rows > 0) {
        $user_data = mysqli_fetch_assoc($result);
        $newpath = '/PHP' . '/' . $user_data['path'];
    
        $cards = array();
    
        $card = '<span class="icon_close" id="icon-close-3"><ion-icon name="close"></ion-icon></span>
                 <header class="rodape_reservas">
                    <img src="' . $newpath . '" alt="motorita imagem" class="motorista_img">
                 </header>
                 <span class="overlay2"></span>
                 <main class="main_reservas" >
                     <div class="infos">
                     <h3 class="name_reserva">' . $user_data['Usuario'] . '</h3>
                     <h2 class="infos_reservas">
                         Origem: ' . $user_data['Origem'] . '<br>
                         Destino: ' . $user_data['Destino'] . '<br>
                         Preço: R$ ' . $user_data['Preco'] . '<br>
                         Veículo: ' . $user_data['Veiculo'] . '<br>
                         Horário: ' . substr($user_data[$Data], 0, 5) . '<br>
                     </h2>
                     </div>
                 </main>
                 <footer class="footer-reservas">
                   <button class="desmarcar" id="desmarcar">Cancelar Carona</button>
                 </footer>
                 ';
        $cards[] = $card;
    
        echo json_encode($cards);
    } else {
        echo "Nenhum resultado encontrado.";
    }
} else if ($tipo == 'Confirmados') {
    $tipo2 = $_POST['tipo2'];
    if ($tipo2 == 1) {
        $sql = "SELECT
        CASE WHEN frente IS NOT NULL THEN frente ELSE NULL END as frente,
        CASE WHEN atras1 IS NOT NULL THEN atras1 ELSE NULL END as atras1,
        CASE WHEN atras2 IS NOT NULL THEN atras2 ELSE NULL END as atras2,
        CASE WHEN atras3 IS NOT NULL THEN atras3 END as atras3,
        CASE WHEN atras4 IS NOT NULL THEN atras4 END as atras4,
        CASE WHEN atras5 IS NOT NULL THEN atras5 END as atras5,
        CASE WHEN atras6 IS NOT NULL THEN atras6 END as atras6,
        CASE WHEN garupa IS NOT NULL THEN garupa END as garupa
    FROM reservas
    WHERE Motorista = '$usuario';";
    $result = $conexao->query($sql);
    if ($result && $result->num_rows > 0) {
        $user_data = mysqli_fetch_assoc($result);
        
        // Construir a cláusula IN com os nomes não nulos
        $names = array();
        if ($user_data['frente'] !== null) {
            $names[] = "'" . $user_data['frente'] . "'";
        }
        if ($user_data['atras1'] !== null) {
            $names[] = "'" . $user_data['atras1'] . "'";
        }
        if ($user_data['atras2'] !== null) {
            $names[] = "'" . $user_data['atras2'] . "'";
        }
        if ($user_data['atras3'] !== null) {
            $names[] = "'" . $user_data['atras3'] . "'";
        }
        if ($user_data['atras4'] !== null) {
            $names[] = "'" . $user_data['atras4'] . "'";
        }
        if ($user_data['atras5'] !== null) {
            $names[] = "'" . $user_data['atras5'] . "'";
        }
        if ($user_data['atras6'] !== null) {
            $names[] = "'" . $user_data['atras6'] . "'";
        }
        if ($user_data['garupa'] !== null) {
            $names[] = "'" . $user_data['garupa'] . "'";
        }
        
        // Verificar se existem nomes não nulos para realizar a consulta
        if (!empty($names)) {
            $namesString = implode(', ', $names);
        
            // Construir a consulta SQL
            $sql2 = "SELECT * FROM usuarios WHERE Usuario IN ($namesString)";
        
            // Executar a consulta
            $result2 = $conexao->query($sql2);
            
            $header = '<header class="rodape_confirmados">
                    <h2 class="confirmados_title">Caronas Confirmadas</h2>
                    <span class="icon_close2" id="icon-close-4"><ion-icon name="close"></ion-icon></span>
               </header>';
    
            $cards = array(); // Array para armazenar os cards
    
            while ($user_data2 = mysqli_fetch_assoc($result2)) {
                $newpath = '/PHP' . '/' . $user_data2['path'];
    
                $card = '<li class="confirmados">
                            <img src="'.$newpath.'" alt="Confirmado" class="confirmado">
                            <h2>'.$user_data2['Usuario'].'</h2>
                            <i class="fa-solid fa-user-check"></i>
                         </li>';
        
              $cards[] = $card; // Adiciona o card ao array
            }
    
            // Adicionar o header ao início do array de cards
            array_unshift($cards, $header);
            
            echo json_encode($cards);
        } else {
            // Caso não haja nomes não nulos, definir $user_data2 como vazio ou null
            $user_data2 = null; // ou array() se desejar retornar um array vazio
            echo 'Não há nenhuma carona';
        }
    } else {
        echo 'Não há nenhuma carona';
    }

    } else if ($tipo2 == 2) {
        $sql = "SELECT
        CASE WHEN frente IS NOT NULL THEN frente ELSE NULL END as frente,
        CASE WHEN atras1 IS NOT NULL THEN atras1 ELSE NULL END as atras1,
        CASE WHEN atras2 IS NOT NULL THEN atras2 ELSE NULL END as atras2,
        CASE WHEN atras3 IS NOT NULL THEN atras3 ELSE NULL END as atras3,
        CASE WHEN atras4 IS NOT NULL THEN atras4 ELSE NULL END as atras4,
        CASE WHEN atras5 IS NOT NULL THEN atras5 ELSE NULL END as atras5,
        CASE WHEN atras6 IS NOT NULL THEN atras6 ELSE NULL END as atras6,
        CASE WHEN garupa IS NOT NULL THEN garupa ELSE NULL END as garupa
    FROM reservas
    WHERE Motorista = '$usuario';";
    $result = $conexao->query($sql);
    $user_data = mysqli_fetch_assoc($result);

    // Construir a cláusula IN com os nomes não nulos
    $names = array();
    
    if ($user_data['frente'] !== null) {
        $names[] = "'" . $user_data['frente'] . "'";
    }
    if ($user_data['atras1'] !== null) {
        $names[] = "'" . $user_data['atras1'] . "'";
    }
    if ($user_data['atras2'] !== null) {
        $names[] = "'" . $user_data['atras2'] . "'";
    }
    if ($user_data['atras3'] !== null) {
        $names[] = "'" . $user_data['atras3'] . "'";
    }
    if ($user_data['atras4'] !== null) {
        $names[] = "'" . $user_data['atras4'] . "'";
    }
    if ($user_data['atras5'] !== null) {
        $names[] = "'" . $user_data['atras5'] . "'";
    }
    if ($user_data['atras6'] !== null) {
        $names[] = "'" . $user_data['atras6'] . "'";
    }
    if ($user_data['garupa'] !== null) {
        $names[] = "'" . $user_data['garupa'] . "'";
    }

    // Verificar se existem nomes não nulos para realizar a consulta
    if (!empty($names)) {
        

        $namesString = implode(', ', $names);
    
        // Construir a consulta SQL
        $sql2 = "SELECT * FROM usuarios WHERE Usuario IN ($namesString)";
    
        // Executar a consulta
        $result2 = $conexao->query($sql2);

        $num_people_found = mysqli_num_rows($result2);

        echo $num_people_found;
    } else {
        // Caso não haja nomes não nulos, definir $user_data2 como vazio ou null
        $user_data2 = null; // ou array() se desejar retornar um array vazio
        echo 0;
    }
    }

} else if ($tipo == 'Cancelar') {
    // Verifica em qual coluna $_SESSION['usuario'] está presente
    $sql_find_column = "SELECT * FROM (
        SELECT
            CASE
                WHEN frente = '".$_SESSION['usuario']."' THEN 'frente'
                WHEN atras1 = '".$_SESSION['usuario']."' THEN 'atras1'
                WHEN atras2 = '".$_SESSION['usuario']."' THEN 'atras2'
                WHEN atras3 = '".$_SESSION['usuario']."' THEN 'atras3'
                WHEN atras4 = '".$_SESSION['usuario']."' THEN 'atras4'
                WHEN atras5 = '".$_SESSION['usuario']."' THEN 'atras5'
                WHEN atras6 = '".$_SESSION['usuario']."' THEN 'atras6'
                ELSE NULL
            END AS column_name
        FROM reservas
    ) AS subquery
    WHERE column_name IS NOT NULL;";
    $result_find_column = $conexao->query($sql_find_column);
    $row_find_column = mysqli_fetch_assoc($result_find_column);
    $column_name = $row_find_column['column_name'];
    

    if ($column_name) {
    // Remove $_SESSION['usuario'] da coluna correspondente
    $sql_update = "UPDATE reservas SET $column_name = '' WHERE $column_name = '".$_SESSION['usuario']."'";
    $conexao->query($sql_update);

    echo "Carona desmarcada com sucesso!";
    } else {
    echo "Você não possui carona marcada.";
    }
    // UPDATE `reservas` SET `frente` = '' WHERE `reservas`.`Motorista` = 'Pedro';
}
?>