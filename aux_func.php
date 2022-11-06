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

/**
 * @param $cur
 * @return void
 */
function buySection($cur): void
{
    echo '<form method="POST" action="add_to_basket.php">';
    echo '<input type="hidden" name="item_id" value="' . $cur['id'] . '"/>';
    echo '</br>';
    echo 'Ilość:<input type="number" name="quantity" value="1"/>';
    echo '</br>';
    echo '<button type="submit" class="btn btn-primary btn-sm">Dodaj do koszyka</button>';
    echo '</form>';
}
/**
 * @return void
 */
function productInfoSection($product): void
{
    echo '<b>' .$product['name'].'</b>';
    echo '<br/>';
    echo 'Cena: ' . $product['price'] .' zł';
    echo '<br/>';
    echo 'Dostępność: ' . availabitiy($product['quantity']);
}
?>