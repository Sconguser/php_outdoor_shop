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
    echo '<br/>';
    echo '<b>Dodaj do koszyka:</b>';
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
function showProductDescription($product):void
{
    echo '<b>Opis produktu:</b>';
    echo '<br/>';
    echo $product['description'];
}
function ratingToString($rating){
    switch($rating){
        case 1:
            return "1 gwiazdka";
        case 2:
            return "2 gwiazdki";
        case 3:
            return "3 gwiazdki";
        case 4:
            return "4 gwiazdki";
        case 5:
            return "5 gwiazdek";
    }
    return "Nie wiadomo :/";
}
function showProductImage($product_id){
    return '<img src="images/'.$product_id.'.jpg" alt="Product image" width="500" height="400"/>';
}

function showProductThumb($product_id){
    return '<img src="images/'.$product_id.'.jpg" alt="Product image" width="250" height="200"/>';
}
?>