<?php
$total_segundos = 1000;
$horas = floor($total_segundos / 3600);
$minutos = floor(($total_segundos % 3600) / 60);
$segundos = $total_segundos % 60;

echo sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);