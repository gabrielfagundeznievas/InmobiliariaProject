<?php

function conectarDB() : mysqli {
    $db = new mysqli('localhost', 'id19296669_root', 'Q[_x4Z~URbc<a-]|', 'id19296669_bienesraices');

    if(!$db) {
        echo "Error no se pudo conectar";
        exit;
    }

    return $db;
}
