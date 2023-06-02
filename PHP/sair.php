<?php
session_start();
session_unset();
session_destroy();
echo "A sessão foi terminada com sucesso!";
?>