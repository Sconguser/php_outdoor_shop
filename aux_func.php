<?php
function showAlert($message): void
{
    echo '<div class="alert alert-primary" role="alert">';
    echo $message;
    echo '</div>';
}


function availabitiy($quantity)
{
    if ($quantity == 0) {
        return 'Produkt niedostępny';
    } else if ($quantity > 0 && $quantity < 10) {
        return 'Mała ilość produktu w magazynie';
    } else if ($quantity >= 10 && $quantity < 50) {
        return 'Średnia ilość produktu w magazynie';
    } else {
        return 'Duża ilość produktu w magazynie';
    }
}
?>