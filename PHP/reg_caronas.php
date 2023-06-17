<?php
session_start();
include_once('config.php');

$usuario = $_SESSION['usuario'];
$tipo = $_POST['tipo'];

if ($tipo == 'reserva') {
    if (!isset($_POST['Reserva'])) {
        $horario = $_POST['horario'];
        $acento = $_POST['acento'];
        $motorista = $_POST['motorista'];
    
        // Verificar se o motorista já está na tabela reservas
        $sqlVerifica = "SELECT * FROM reservas WHERE Motorista = '$motorista'";
        $resultVerifica = $conexao->query($sqlVerifica);
    
        if ($resultVerifica->num_rows > 0) {
            // O motorista já está na tabela   
            $sql = "UPDATE `reservas` SET `$acento` = '$usuario', `Horario` = '$horario' WHERE `reservas`.`Motorista` = '$motorista';";
            $result = $conexao->query($sql);
            if ($result == 1) {
                echo 'Reserva feita com sucesso!';
            } else {
                echo 'Erro ao fazer reserva.';
            }
        } else {
            // O motorista não está na tabela, realiza a inserção na tabela   
            $sql = "INSERT INTO reservas(`Motorista`, `$acento`, `Horario`) VALUES ('$motorista' , '$usuario', '$horario');";
            $result = $conexao->query($sql);
            if ($result == 1) {
                echo 'Reserva feita com sucesso!';
            } else {
                echo 'Erro ao fazer reserva.';
            }
        }
    } else {
        $horario = $_POST['horario'];
        $acento = $_POST['acento'];
        $motorista = $_POST['motorista'];
        $_SESSION['carona-temporária'] = 1;

        // Verificar se o motorista já está na tabela reservas
        $sqlVerifica = "SELECT * FROM reservas_temp WHERE Motorista = '$motorista'";
        $resultVerifica = $conexao->query($sqlVerifica);
    
        if ($resultVerifica->num_rows > 0) {
            // O motorista já está na tabela   
            $sql = "UPDATE `reservas_temp` SET `$acento` = '$usuario', `Horario` = '$horario' WHERE `reservas_temp`.`Motorista` = '$motorista';";
            $result = $conexao->query($sql);
            if ($result == 1) {
                echo 'Reserva feita com sucesso!';
            } else {
                echo 'Erro ao fazer reserva.';
            }
        } else {
            // O motorista não está na tabela, realiza a inserção na tabela   
            $sql = "INSERT INTO reservas_temp(`Motorista`, `$acento`, `Horario`) VALUES ('$motorista' , '$usuario', '$horario');";
            $result = $conexao->query($sql);
            if ($result == 1) {
                $_SESSION['carona-temporária'] = 1;
                echo 'Reserva feita com sucesso!';
            } else {
                echo 'Erro ao fazer reserva.';
            }
        }
    }
} else if ($tipo == 'conferir') {

    if (!empty($_SESSION['carona-temporária'])) {
        $sql = "SELECT * FROM reservas_temp WHERE '$usuario' IN (frente, atras1, atras2, atras3, atras4, atras5,atras6,garupa)";
        $result = $conexao->query($sql);
    
        if ($result && $result->num_rows > 0) {
            echo 'Você tem uma reserva.';
        } else {
            echo 'Você não tem uma reserva.';
        }
    } else {
        $sql = "SELECT * FROM reservas WHERE '$usuario' IN (frente, atras1, atras2, atras3, atras4, atras5,atras6,garupa)";
        $result = $conexao->query($sql);
    
        if ($result && $result->num_rows > 0) {
            echo 'Você tem uma reserva.';
        } else {
            echo 'Você não tem uma reserva.';
        }
    }
} else if ($tipo == 'conferir2') {
    // $Data = $_POST['data_hoje'];
    $Data = 'Segunda';
    
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
                         Horário: <span class="horario_reserva">' . substr($user_data[$Data], 0, 5) . '</span><br>
                     </h2>
                     </div>
                 </main>
                 <footer class="footer-reservas">
                   <button class="desmarcar" id="desmarcar">Cancelar Carona</button>
                 </footer>
                 ';
        $cards[] = $card;
    
        echo json_encode($cards);
    } else if (isset($_SESSION['carona-temporária']) && $_SESSION['carona-temporária'] == '1') {
        $sql = "SELECT * FROM `anuncios_caronas temp` JOIN usuarios ON `anuncios_caronas temp`.Usuario = usuarios.Usuario JOIN reservas_temp ON usuarios.Usuario = reservas_temp.Motorista WHERE '$usuario' IN (reservas_temp.frente, reservas_temp.atras1, reservas_temp.atras2, reservas_temp.atras3, reservas_temp.atras4, reservas_temp.atras5, reservas_temp.atras6, reservas_temp.garupa);";
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
        }
    } else {
        echo "Nenhum resultado encontrado.";
    }
} else if ($tipo == 'Confirmados') {
    $tipo2 = $_POST['tipo2'];
    if ($tipo2 == 1) {
        // Verificação caronas temporárias
            $sql = "SELECT
            COALESCE(frente, '') AS frente,
            COALESCE(atras1, '') AS atras1,
            COALESCE(atras2, '') AS atras2,
            COALESCE(atras3, '') AS atras3,
            COALESCE(atras4, '') AS atras4,
            COALESCE(atras5, '') AS atras5,
            COALESCE(atras6, '') AS atras6,
            COALESCE(garupa, '') AS garupa
            FROM reservas_temp
            WHERE Motorista = '$usuario';";
            $result = $conexao->query($sql);
            
            // Verificação caronas Fixas
            $query = "SELECT
            COALESCE(frente, '') AS frente,
            COALESCE(atras1, '') AS atras1,
            COALESCE(atras2, '') AS atras2,
            COALESCE(atras3, '') AS atras3,
            COALESCE(atras4, '') AS atras4,
            COALESCE(atras5, '') AS atras5,
            COALESCE(atras6, '') AS atras6,
            COALESCE(garupa, '') AS garupa
            FROM reservas
            WHERE Motorista = '$usuario';";
            $result2 = $conexao->query($query);
            print_r($result2);
            print_r($result);

            if ($result && $result->num_rows > 0) {
                $user_data = mysqli_fetch_assoc($result);
                
                // Construir a cláusula IN com os nomes não nulos
                $names = array();
                if ($user_data['frente'] !== '') {
                    $names[] = "'" . $user_data['frente'] . "'";
                }
                if ($user_data['atras1'] !== '') {
                    $names[] = "'" . $user_data['atras1'] . "'";
                }
                if ($user_data['atras2'] !== '') {
                    $names[] = "'" . $user_data['atras2'] . "'";
                }
                if ($user_data['atras3'] !== '') {
                    $names[] = "'" . $user_data['atras3'] . "'";
                }
                if ($user_data['atras4'] !== '') {
                    $names[] = "'" . $user_data['atras4'] . "'";
                }
                if ($user_data['atras5'] !== '') {
                    $names[] = "'" . $user_data['atras5'] . "'";
                }
                if ($user_data['atras6'] !== '') {
                    $names[] = "'" . $user_data['atras6'] . "'";
                }
                if ($user_data['garupa'] !== '') {
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
            } else if($result2 && $result2->num_rows > 0)
            {
                $user_data = mysqli_fetch_assoc($result2);
                    
                
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
        $sql2 = "SELECT
        CASE WHEN frente IS NOT NULL THEN frente ELSE NULL END as frente,
        CASE WHEN atras1 IS NOT NULL THEN atras1 ELSE NULL END as atras1,
        CASE WHEN atras2 IS NOT NULL THEN atras2 ELSE NULL END as atras2,
        CASE WHEN atras3 IS NOT NULL THEN atras3 ELSE NULL END as atras3,
        CASE WHEN atras4 IS NOT NULL THEN atras4 ELSE NULL END as atras4,
        CASE WHEN atras5 IS NOT NULL THEN atras5 ELSE NULL END as atras5,
        CASE WHEN atras6 IS NOT NULL THEN atras6 ELSE NULL END as atras6,
        CASE WHEN garupa IS NOT NULL THEN garupa ELSE NULL END as garupa
        FROM reservas_temp
        WHERE Motorista = '$usuario';";
        $result2 = $conexao->query($sql2);

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
    
                $num_people_found = mysqli_num_rows($result2);
    
                echo $num_people_found;
            } else {
                // Caso não haja nomes não nulos, definir $user_data2 como vazio ou null
                $user_data2 = null; // ou array() se desejar retornar um array vazio
                echo 0;
            }
        } else if ($result2 && $result2->num_rows > 0){
            $user_data = mysqli_fetch_assoc($result2);
    
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
        } else {
            echo 0;
        }
        }

} else if ($tipo == 'Cancelar') {
    if (!empty($_SESSION['carona-temporária'])) {
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
                    WHEN garupa = '".$_SESSION['usuario']."' THEN 'garupa'
                    ELSE NULL
                END AS column_name
            FROM reservas_temp
        ) AS subquery
        WHERE column_name IS NOT NULL;";
        $result_find_column = $conexao->query($sql_find_column);
        $row_find_column = mysqli_fetch_assoc($result_find_column);
        $column_name = $row_find_column['column_name'];
        
    
        if ($column_name) {
        // Remove $_SESSION['usuario'] da coluna correspondente
        $sql_update = "UPDATE reservas_temp SET $column_name = '' WHERE $column_name = '".$_SESSION['usuario']."'";
        $conexao->query($sql_update);
    
        echo "Carona desmarcada com sucesso!";
        $_SESSION['carona-temporária'] = 0;
        } else {
        echo "Você não possui carona marcada.";
        }
    } else {
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
                    WHEN garupa = '".$_SESSION['usuario']."' THEN 'garupa'
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
    }
}
?>