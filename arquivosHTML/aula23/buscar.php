<?php
$termo = $_GET["termo"] ?? "";
echo "Busca por: " . htmlspecialchars($termo);