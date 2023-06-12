<?php
session_start();
include_once('config.php');
$tipo = $_POST['tipo'];

if ($tipo == 1) {
    $Usuario = $_SESSION['usuario'];
    $origem = mysqli_real_escape_string($conexao, $_POST['origem']);
    $destino = mysqli_real_escape_string($conexao, $_POST['destino']);
    $preco = mysqli_real_escape_string($conexao, $_POST['Preco']);
    $Tipo_de_Veiculo = mysqli_real_escape_string($conexao, $_POST['Tipo_de_Veiculo']);
    $Vagas_Totais = mysqli_real_escape_string($conexao, $_POST['Vagas_Totais']);
    $Segunda = mysqli_real_escape_string($conexao, $_POST['Segunda']);
    $Terca = mysqli_real_escape_string($conexao, $_POST['Terca']);
    $Quarta = mysqli_real_escape_string($conexao, $_POST['Quarta']);
    $Quinta = mysqli_real_escape_string($conexao, $_POST['Quinta']);
    $Sexta = mysqli_real_escape_string($conexao, $_POST['Sexta']);
    $Fixo = mysqli_real_escape_string($conexao, $_POST['Fixo']);
    if($Fixo == 'true') {
        // Verifica se as variáveis já existem no banco de dados
        $query = "SELECT * FROM anuncios_caronas WHERE Usuario = '{$Usuario}' AND Destino = '{$destino}' AND Veiculo = '{$Tipo_de_Veiculo}' AND Vagas = '{$Vagas_Totais}'AND Preco = '{$preco}'AND Segunda = '{$Segunda}'AND Terca = '{$Terca}'AND Quarta = '{$Quarta}'AND Quinta = '{$Quinta}'AND Sexta = '{$Sexta}' AND Origem = '{$origem}'";
        $result = mysqli_query($conexao, $query);
        
        if(mysqli_num_rows($result) > 0) {
            // Exibe uma mensagem de erro e redireciona para a página anterior
            echo "Já existe um registro!";
            exit();
        }
        
        // Insere as variáveis no banco de dados
        $stmt = mysqli_prepare($conexao, "INSERT INTO anuncios_caronas(Usuario,Destino,Origem,Preco,Veiculo,Vagas,Segunda,Terca,Quarta,Quinta,Sexta) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, "sssssssssss", $Usuario,$destino,$origem,$preco, $Tipo_de_Veiculo,$Vagas_Totais, $Segunda, $Terca, $Quarta, $Quinta, $Sexta);
        $success = mysqli_stmt_execute($stmt);
        
        // Exibe uma mensagem de sucesso
        if ($success) {
            echo "Carona cadastrada com sucesso!";
            exit();
        }
    } else {
        // Verifica se as variáveis já existem no banco de dados
        $query = "SELECT * FROM `anuncios_caronas temp` WHERE Usuario = '{$Usuario}' AND Destino = '{$destino}' AND Veiculo = '{$Tipo_de_Veiculo}' AND Vagas = '{$Vagas_Totais}'AND Preco = '{$preco}'AND Segunda = '{$Segunda}'AND Terca = '{$Terca}'AND Quarta = '{$Quarta}'AND Quinta = '{$Quinta}'AND Sexta = '{$Sexta}' AND Origem = '{$origem}'";
        $result = mysqli_query($conexao, $query);
        
        if(mysqli_num_rows($result) > 0) {
            // Exibe uma mensagem de erro e redireciona para a página anterior
            echo "Já existe um registro!";
            exit();
        }
        
        // Insere as variáveis no banco de dados
        $stmt = mysqli_prepare($conexao, "INSERT INTO `anuncios_caronas temp`(Usuario,Destino,Origem,Preco,Veiculo,Vagas,Segunda,Terca,Quarta,Quinta,Sexta) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, "sssssssssss", $Usuario,$destino,$origem,$preco, $Tipo_de_Veiculo,$Vagas_Totais, $Segunda, $Terca, $Quarta, $Quinta, $Sexta);
        $success = mysqli_stmt_execute($stmt);
        
        // Exibe uma mensagem de sucesso
        if ($success) {
            echo "Carona cadastrada com sucesso!";
            exit();
        }
    }
} elseif ($tipo == 2) { 
    print_r($_SESSION['check']);
}
?>
