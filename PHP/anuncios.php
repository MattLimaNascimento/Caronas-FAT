<?php
session_start();
include_once('config.php');
$usuario = $_SESSION['usuario'];
$tipo = $_POST['tipo'];

if ($tipo == 1){
  $sql = "SELECT * FROM reservas WHERE '$usuario' IN (frente, atras1, atras2, atras3, atras4, atras5,atras6,garupa)";
  $result = $conexao->query($sql);

  if (isset($_POST['dia_semana']) && $_POST['dia_semana'] == $_POST['dia_SemanaAtual'] && $result && $result->num_rows == 0) {
    $Data = $_POST['dia_semana'];
    
    $hora_atual  = $_POST['horario_atual'];

    $sql = "SELECT * FROM anuncios_caronas JOIN usuarios ON anuncios_caronas" . '.' . "Usuario = usuarios" . '.' . "Usuario WHERE anuncios_caronas" . '.' . "$Data <> '00:00';";
    $result = $conexao->query($sql);

    $cards = array(); // Array para armazenar os cards

    while ($user_data = mysqli_fetch_assoc($result)) {
      $horario_card = substr($user_data[$Data], 0, 5);
      
      if (strtotime($horario_card) < strtotime($hora_atual)) {
        $newpath = '/PHP' . '/' . $user_data['path'];
        $card = '<div class="card swiper-slide">
          <div class="image-content">
            <span class="overlay"></span>
            <div class="card-image">
              <img src="' . $newpath . '" alt="" class="card-img">
            </div>
          </div>
          <div class="card-content">
            <h2 class="name">' . $user_data['Usuario'] . '</h2>
            <h2 class="description">
                Veículo: <span class="veiculo">' . $user_data['Veiculo'] . '</span><br>
                Origem: <span class="origem">' . $user_data['Origem'] . '</span><br>
                Destino: <span class="destino">' . $user_data['Destino'] . '</span><br>
                Horário: <span class="horario">' . $horario_card . '</span><br>
                Preço: R$ <span class="preco">' . $user_data['Preco'] . '</span><br>          
            </h2>
          </div>
        </div>';

        $cards[] = $card; // Adiciona o card ao array

        continue; // Pula para a próxima iteração do loop
      }
      if ($user_data['Usuario'] == $usuario || $user_data['Email'] == $_SESSION['email_login']) {
        $newpath = '/PHP' . '/' . $user_data['path'];
        $card = '<div class="card swiper-slide">
          <div class="image-content">
            <span class="overlay"></span>
            <div class="card-image">
              <img src="' . $newpath . '" alt="" class="card-img">
            </div>
          </div>
          <div class="card-content">
            <h2 class="name">' . $user_data['Usuario'] . '</h2>
            <h2 class="description">
                Veículo: <span class="veiculo">' . $user_data['Veiculo'] . '</span><br>
                Origem: <span class="origem">' . $user_data['Origem'] . '</span><br>
                Destino: <span class="destino">' . $user_data['Destino'] . '</span><br>
                Horário: <span class="horario">' . $horario_card . '</span><br>
                Preço: R$ <span class="preco">' . $user_data['Preco'] . '</span><br>          
            </h2>
          </div>
        </div>';

        $cards[] = $card; // Adiciona o card ao array

        continue; // Pula para a próxima iteração do loop
      }
      $motorista = $user_data['Usuario'];
      $reservasSql = "SELECT * FROM reservas WHERE Motorista = '$motorista'";
      $reservasResult = $conexao->query($reservasSql);
      $reservasData = mysqli_fetch_assoc($reservasResult);

      $newpath = '/PHP' . '/' . $user_data['path'];
      $card = '<div class="card swiper-slide">
          <div class="image-content">
            <span class="overlay"></span>
            <div class="card-image">
              <img src="' . $newpath . '" alt="" class="card-img">
            </div>
          </div>
          <div class="card-content">
            <h2 class="name">' . $user_data['Usuario'] . '</h2>
            <h2 class="description">
                Veículo: <span class="veiculo">' . $user_data['Veiculo'] . '</span><br>
                Origem: <span class="origem">' . $user_data['Origem'] . '</span><br>
                Destino: <span class="destino">' . $user_data['Destino'] . '</span><br>
                Horário: <span class="horario">' . substr($user_data[$Data], 0, 5) . '</span><br>
                Preço: R$ <span class="preco">' . $user_data['Preco'] . '</span><br>          
            </h2>';

      if ($user_data['Veiculo'] == "moto") {
        $card .= '<div class="buttons container">
                  <button class="button garupa" data-button-id="garupa">Garupa</button>                      
                </div>';
      } else {
        $card .= '<div class="buttons container">';
        $contador = 0; // Contador para controlar a quantidade de lugares vazios
        $maxButtons = min($user_data['Vagas'], 7); // Número máximo de botões a serem exibidos

        if (empty($reservasData['frente']) && $contador < $maxButtons) {
          $card .= '<button class="button" data-button-id="frente">Frente</button>';
          $contador++;
        }

        for ($i = 1; $i <= 7; $i++) {
          $coluna = 'atras' . $i;
          if ($i > 1 && $i <= $user_data['Vagas'] && empty($reservasData[$coluna]) && $contador < $maxButtons) {
            $card .= '<button class="button" data-button-id="' . $coluna . '">Atrás</button>';
            $contador++;
          }
        }

        $card .= '</div>';
      }

      $card .= '</div>
        </div>';

      $cards[] = $card; // Adiciona o card ao array
    }

    echo json_encode($cards);
  } else if ($_POST['dia_semana'] != $_POST['dia_SemanaAtual']) {
    $Data = $_POST['dia_semana'];

    $sql = "SELECT * FROM anuncios_caronas JOIN usuarios ON anuncios_caronas" . '.' . "Usuario = usuarios" . '.' . "Usuario WHERE anuncios_caronas" . '.' . "$Data <> '00:00';";
    $result = $conexao->query($sql);

    $cards = array(); // Array para armazenar os cards

    while ($user_data = mysqli_fetch_assoc($result)) {
      $newpath = '/PHP' . '/' . $user_data['path'];
      $card = '<div class="card swiper-slide">
              <div class="image-content">
                <span class="overlay"></span>
                <div class="card-image">
                <img src="' . $newpath . '" alt="" class="card-img">
                </div>
              </div>
              <div class="card-content">
                <h2 class="name">' . $user_data['Usuario'] . '</h2>
                <h2 class="description">
                  Veículo: ' . $user_data['Veiculo'] . '<br>
                  Origem: ' . $user_data['Origem'] . '<br>
                  Destino: ' . $user_data['Destino'] . '<br>
                  Horário: ' . substr($user_data[$Data], 0, 5) . '<br>
                  Preço: R$ ' . $user_data['Preco'] . '<br>          

                </h2>
              </div>
            </div>';
      $cards[] = $card; // Adiciona o card ao array
    }

    echo json_encode($cards);
  } else {
    $Data = $_POST['dia_semana'];

    $sql = "SELECT * FROM anuncios_caronas JOIN usuarios ON anuncios_caronas" . '.' . "Usuario = usuarios" . '.' . "Usuario WHERE anuncios_caronas" . '.' . "$Data <> '00:00';";
    $result = $conexao->query($sql);

    $cards = array(); // Array para armazenar os cards

    while ($user_data = mysqli_fetch_assoc($result)) {
      $newpath = '/PHP' . '/' . $user_data['path'];
      $card = '<div class="card swiper-slide">
              <div class="image-content">
                <span class="overlay"></span>
                <div class="card-image">
                <img src="' . $newpath . '" alt="" class="card-img">
                </div>
              </div>
              <div class="card-content">
                <h2 class="name">' . $user_data['Usuario'] . '</h2>
                <h2 class="description">
                  Veículo: ' . $user_data['Veiculo'] . '<br>
                  Origem: ' . $user_data['Origem'] . '<br>
                  Destino: ' . $user_data['Destino'] . '<br>
                  Horário: ' . substr($user_data[$Data], 0, 5) . '<br>
                  Preço: R$ ' . $user_data['Preco'] . '<br>          

                </h2>
              </div>
            </div>';
      $cards[] = $card; // Adiciona o card ao array
    }

    echo json_encode($cards);
  }
} else if ($tipo == 2) {
  $sql = "SELECT * FROM reservas_temp WHERE '$usuario' IN (frente, atras1, atras2, atras3, atras4, atras5,atras6,garupa)";
  $result = $conexao->query($sql);

  if ($result && $result->num_rows == 0) {
    $hora_atual  = $_POST['horario_atual'];
    $Data = $_POST['dia_SemanaAtual'];

    $sql = "SELECT * FROM `anuncios_caronas temp` JOIN usuarios ON `anuncios_caronas temp`" . '.' . "Usuario = usuarios" . '.' . "Usuario WHERE `anuncios_caronas temp`" . '.' . "$Data <> '00:00';";
    $result = $conexao->query($sql);

    $cards = array(); // Array para armazenar os cards

    while ($user_data = mysqli_fetch_assoc($result)) {
      $horario_card = substr($user_data[$Data], 0, 5);
      if (strtotime($horario_card) < strtotime($hora_atual)) {
        $newpath = '/PHP' . '/' . $user_data['path'];
        $card = '<div class="card swiper-slide" id="cards_temp">
          <div class="image-content">
            <span class="overlay"></span>
            <div class="card-image">
              <img src="' . $newpath . '" alt="" class="card-img">
            </div>
          </div>
          <div class="card-content">
            <h2 class="name">' . $user_data['Usuario'] . '</h2>
            <h2 class="description">
                Veículo: <span class="veiculo">' . $user_data['Veiculo'] . '</span><br>
                Origem: <span class="origem">' . $user_data['Origem'] . '</span><br>
                Destino: <span class="destino">' . $user_data['Destino'] . '</span><br>
                Horário: <span class="horario" id="horario_temp">' . $horario_card . '</span><br>
                Preço: R$ <span class="preco">' . $user_data['Preco'] . '</span><br>          
            </h2>
          </div>
        </div>';

        $cards[] = $card; // Adiciona o card ao array

        continue; // Pula para a próxima iteração do loop
      }
      if ($user_data['Usuario'] == $usuario || $user_data['Email'] == $_SESSION['email_login']) {
        $newpath = '/PHP' . '/' . $user_data['path'];
        $card = '<div class="card swiper-slide" id="cards_temp">
          <div class="image-content">
            <span class="overlay"></span>
            <div class="card-image">
              <img src="' . $newpath . '" alt="" class="card-img">
            </div>
          </div>
          <div class="card-content">
            <h2 class="name">' . $user_data['Usuario'] . '</h2>
            <h2 class="description">
                Veículo: <span class="veiculo">' . $user_data['Veiculo'] . '</span><br>
                Origem: <span class="origem">' . $user_data['Origem'] . '</span><br>
                Destino: <span class="destino">' . $user_data['Destino'] . '</span><br>
                Horário: <span class="horario" id="horario_temp">' . $horario_card . '</span><br>
                Preço: R$ <span class="preco">' . $user_data['Preco'] . '</span><br>          
            </h2>
          </div>
        </div>';

        $cards[] = $card; // Adiciona o card ao array

        continue; // Pula para a próxima iteração do loop
      }
      $motorista = $user_data['Usuario'];
      $reservasSql = "SELECT * FROM reservas WHERE Motorista = '$motorista'";
      $reservasResult = $conexao->query($reservasSql);
      $reservasData = mysqli_fetch_assoc($reservasResult);

      $newpath = '/PHP' . '/' . $user_data['path'];
      $card = '<div class="card swiper-slide" id="cards_temp">
          <div class="image-content">
            <span class="overlay"></span>
            <div class="card-image">
              <img src="' . $newpath . '" alt="" class="card-img">
            </div>
          </div>
          <div class="card-content">
            <h2 class="name">' . $user_data['Usuario'] . '</h2>
            <h2 class="description">
                Veículo: <span class="veiculo">' . $user_data['Veiculo'] . '</span><br>
                Origem: <span class="origem">' . $user_data['Origem'] . '</span><br>
                Destino: <span class="destino">' . $user_data['Destino'] . '</span><br>
                Horário: <span class="horario" id="horario_temp">' . substr($user_data[$Data], 0, 5) . '</span><br>
                Preço: R$ <span class="preco">' . $user_data['Preco'] . '</span><br>          
            </h2>';

      if ($user_data['Veiculo'] == "moto") {
        $card .= '<div class="buttons container">
                  <button class="button garupa" id="temp" data-button-id="garupa">Garupa</button>                      
                </div>';
      } else {
        $card .= '<div class="buttons container">';
        $contador = 0; // Contador para controlar a quantidade de lugares vazios
        $maxButtons = min($user_data['Vagas'], 7); // Número máximo de botões a serem exibidos

        if (empty($reservasData['frente']) && $contador < $maxButtons) {
          $card .= '<button class="button" id="temp" data-button-id="frente">Frente</button>';
          $contador++;
        }

        for ($i = 1; $i <= 7; $i++) {
          $coluna = 'atras' . $i;
          if ($i > 1 && $i <= $user_data['Vagas'] && empty($reservasData[$coluna]) && $contador < $maxButtons) {
            $card .= '<button class="button" id="temp" data-button-id="' . $coluna . '">Atrás</button>';
            $contador++;
          }
        }

        $card .= '</div>';
      }

      $card .= '</div>
        </div>';

      $cards[] = $card; // Adiciona o card ao array
    }

    echo json_encode($cards);
  } else {
    $hora_atual  = $_POST['horario_atual'];
    $Data = $_POST['dia_SemanaAtual'];

    $sql = "SELECT * FROM `anuncios_caronas temp` JOIN usuarios ON `anuncios_caronas temp`" . '.' . "Usuario = usuarios" . '.' . "Usuario WHERE `anuncios_caronas temp`" . '.' . "$Data <> '00:00';";
    $result = $conexao->query($sql);

    $cards = array(); // Array para armazenar os cards

    while ($user_data = mysqli_fetch_assoc($result)) {
      $newpath = '/PHP' . '/' . $user_data['path'];
      $card = '<div class="card swiper-slide">
              <div class="image-content">
                <span class="overlay"></span>
                <div class="card-image">
                <img src="' . $newpath . '" alt="" class="card-img">
                </div>
              </div>
              <div class="card-content">
                <h2 class="name">' . $user_data['Usuario'] . '</h2>
                <h2 class="description">
                  Veículo: ' . $user_data['Veiculo'] . '<br>
                  Origem: ' . $user_data['Origem'] . '<br>
                  Destino: ' . $user_data['Destino'] . '<br>
                  Horário: ' . substr($user_data[$Data], 0, 5) . '<br>
                  Preço: R$ ' . $user_data['Preco'] . '<br>          

                </h2>
              </div>
            </div>';
      $cards[] = $card; // Adiciona o card ao array
    }

    echo json_encode($cards);
  }
} else if ($tipo == 3) {
  $motorista = $_POST['nome_motor'];
  $sql = "DELETE FROM `anuncios_caronas temp` WHERE `anuncios_caronas temp`.Usuario = '$motorista';";
  $sql2 = "DELETE FROM reservas_temp WHERE `reservas_temp`.Motorista = '$motorista';";
  $result = $conexao->query($sql);
  $result = $conexao->query($sql2);
}
?>