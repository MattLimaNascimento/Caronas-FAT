<?php
session_start();

include_once('config.php');
$email_log = $_SESSION['email_login'];
$senha_log = $_SESSION['senha_login'];
$sql = "SELECT * FROM usuarios WHERE Email = '$email_log' and Senha = '$senha_log'";
$result = $conexao->query($sql);
$user_data = mysqli_fetch_assoc($result);
$usuario = $user_data['Usuario'];
$newpath = '/PHP' . '/' . $user_data['path'];

$cards = array();

$card = '<div class="profile_user" id="profile">
            <img src="'.$newpath.'" alt="profile-img">
        </div>
        <div class="menu_user">
          <h3>'.$usuario.'<br/><span>'.$user_data['Email'].'</span></h3>
          <ul>
            <li id="Caronas-confirmadas">
              <i class="fa-solid fa-user-check"></i>
              <a href="#">Caronas Confirmadas</a>
              <div class="sinal2" id = "sinal2"></div>
            </li>
            <li id="Carona_ok">
              <i class="fa fa-car-side"></i>
              <a href="#" id="Vaga_registrada">Carona Marcada</a>
              <div class="sinal" id="sinal"></div>
            </li>
            <li id="logout-btn">
              <i class="fa-solid fa-right-from-bracket"></i>
              <a href="#">Sair</a>
            </li>
          </ul>
        </div>';
$cards[] = $card;

echo json_encode($cards);

?>