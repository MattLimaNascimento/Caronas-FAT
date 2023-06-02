<?php
session_start();
if ($_POST['tipo'] == 'login') {
    if (isset($_SESSION['email_login']) || isset($_SESSION['senha_login'])) {
        if ($_SESSION['check'] == 1) {
            echo 'Há sessão';
        } else if ($_SESSION['check'] == 0) {
            echo 'sessao temporária';
        }
    } else {
        echo 'Não há sessão';
    }
} else if ($_POST['tipo'] == 'motorista') {
    if (isset($_SESSION['n_registro']) || isset($_SESSION['placa_carro'])) {
        echo 'Há sessão';
    } else {
        echo 'Não há sessão';
    }
}
?>