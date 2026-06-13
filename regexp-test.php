<?php

//$email = "test@gmail.com";

/*if (preg_match('/@gmail\.com$', $email)) {
    echo "Gmail topildi";
}*/

$phone = "+998901234567";
if (preg_match('/^\+998{9}$/', $phone)) {
    echo "Telefon to'g'ri";
} else {
    echo "Telefon noto'g'ri";
}