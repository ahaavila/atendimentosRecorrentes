<?php
    define('HOST', '10.0.50.205');
    define('USUARIO','relatorios_adapter');
    define('SENHA', 'Rel@TomaEssaCaiCai2');
    define('BD', 'clmaster_adapter_comercial');
    
    $conexao = mysqli_connect(HOST, USUARIO, SENHA, BD) or die('Não foi possível conectar');
    $conexao->set_charset('utf8');
?>