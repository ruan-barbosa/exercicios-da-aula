<?php

$palavra1 = trim(fgets(STDIN));
$palavra2 = trim(fgets(STDIN));
$palavra3 = trim(fgets(STDIN));

if ($palavra1 == "vertebrado") {
    
    if ($palavra2 == "ave") {
        if ($palavra3 == "carnivoro") {
            echo "aguia\n";
        } elseif ($palavra3 == "onivoro") {
            echo "pomba\n";
        }
    } elseif ($palavra2 == "mamifero") {
        if ($palavra3 == "onivoro") {
            echo "homem\n";
        } elseif ($palavra3 == "herbivoro") {
            echo "vaca\n";
        }
    }
    
} elseif ($palavra1 == "invertebrado") {
    
    if ($palavra2 == "inseto") {
        if ($palavra3 == "hematofago") {
            echo "pulga\n";
        } elseif ($palavra3 == "herbivoro") {
            echo "lagarta\n";
        }
    } elseif ($palavra2 == "anelideo") {
        if ($palavra3 == "hematofago") {
            echo "sanguessuga\n";
        } elseif ($palavra3 == "onivoro") {
            echo "minhoca\n";
        }
    }
    
}
