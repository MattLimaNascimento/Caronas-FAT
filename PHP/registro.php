<?php
session_start();
include_once('config.php');
//1024 bytes = 1kb
// 1024 kb = 1mb
$tipo = $_POST['tipo'];

if ($tipo === 'usuario') {
  $usuario = $_POST['usuario_registro'];
  $email = $_POST['email_registro'];
  $senha = $_POST['senha_registro'];
  $arquivo = $_FILES['file'];

  if ($arquivo['error'] >= 1) {
    die('Falha ao Enviar o Arquivo');
  }

  if ($arquivo['size'] > 2097152) {
    die('Arquivo muito grande! Max: 2MB');
  }

  $pasta = "media_usuarios/";
  $nomeArquivo = $arquivo['name'];
  $novoNomeArquivo = uniqid();
  $extensao = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));

  if ($extensao != 'jpg' && $extensao != 'png') {
    die('O Tipo do arquivo deve ser uma imagem!');
  }

  $path = $pasta . $novoNomeArquivo . "." . $extensao;
  $right = move_uploaded_file($arquivo['tmp_name'], $path);
  if ($right) {
    $conexao->query("INSERT INTO usuarios (Usuario,Email,Senha,path,data_upload) VALUE ('$usuario','$email','$senha','$path', NOW())");
    echo "Arquivo movido com sucesso!";
  } else {
    echo "Erro ao tentar transferir o arquivo";
  }
} else if ($tipo === 'motorista') {
  $n_registro = mysqli_real_escape_string($conexao, $_POST['n_registro']);
  $placa_carro = mysqli_real_escape_string($conexao, $_POST['placa_carro']);
  $usuario = $_SESSION['usuario'];

  // verifica se as variáveis já existem no banco de dados
  $query = "SELECT * FROM motoristas WHERE n_registros = '{$n_registro}' OR placa_carro = '{$placa_carro}'";
  $result = mysqli_query($conexao, $query);

  if (mysqli_num_rows($result) > 0) {
    // exibe uma mensagem de erro e redireciona para a página anterior
    echo "Usuario existente";
    exit();
  }

  // insere as variáveis no banco de dados
  $stmt = mysqli_prepare($conexao, "INSERT INTO motoristas(n_registros,placa_carro,motorista) VALUES (?,?,?)");
  mysqli_stmt_bind_param($stmt, "sss", $n_registro, $placa_carro, $usuario);
  $success = mysqli_stmt_execute($stmt);

  // exibe uma mensagem de sucesso
  if ($success) {
    echo "Sucesso!";
    exit();
  }
} else if ($tipo == 'usuario_test') {
  $usuario = $_POST['usuario'];
  $sql = "SELECT * from usuarios where Usuario = '$usuario';";
  $result = $conexao -> query($sql);

  if ($result->num_rows == 0) {
    echo 'usuário aprovado';
  } else if($result->num_rows == 1) {
    echo 'usuário já existente';
  }
}
?>