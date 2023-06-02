<?php
session_start();
include_once('config.php');
$usuario = $_SESSION['usuario'];

$sql = "SELECT * FROM reservas WHERE '$usuario' IN (frente, atras1, atras2, atras3, atras4, atras5,atras6,garupa)";
$result = $conexao->query($sql);

if (isset($_POST['dia_semana']) && $_POST['dia_semana'] == $_POST['dia_SemanaAtual'] && $result && $result->num_rows == 0) {
  $Data = $_POST['dia_semana'];

  $sql = "SELECT * FROM anuncios_caronas JOIN usuarios ON anuncios_caronas" . '.' . "Usuario = usuarios" . '.' . "Usuario WHERE anuncios_caronas" . '.' . "$Data <> '00:00';";
  $result = $conexao->query($sql);

  $cards = array(); // Array para armazenar os cards

  while ($user_data = mysqli_fetch_assoc($result)) {
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
              Horário: <span class="horario">' . substr($user_data[$Data], 0, 5) . '</span><br>
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
?>