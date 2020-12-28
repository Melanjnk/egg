<?php

$mysqli = new mysqli("db", "root", "example", "egg");

$id = $mysqli->real_escape_string($_GET['id']);

$sql = 'SELECT u.id, u.name, u.phone, u.email 
            FROM users as u 
            WHERE u.id=?';

//$user = [];
if ($stmt = $mysqli->prepare($sql)) {
    //can be TYPE = s, if id is string;
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmtResult = $stmt->get_result();
    if ($stmtResult->num_rows > 0) {
        //get last with current id (if id is not AI)
        while ($row = $stmtResult->fetch_assoc()) {
            $user = $row;
        }
    }
    $stmt->close();
}

if ($user) {
    echo 'User: ';
    echo '<pre>';
    print_r($user);
    echo '</pre>';
} else {
    echo 'No user with id: "' . $id . '"';
}

//Task 2
$catalogId = $mysqli->real_escape_string($_GET['catalog_id']);

$sql2 = 'SELECT q.* , u.name, u.phone
            FROM `questions` as q
        INNER JOIN users as u
            ON u.id = q.user_id
        WHERE q.`catalog_id`=?';

//$user = [];
if ($stmt = $mysqli->prepare($sql2)) {
    //can be TYPE = s, if id is string;
    $stmt->bind_param('i', $catalogId);
    $stmt->execute();
    $stmtResult = $stmt->get_result();
    if ($stmtResult->num_rows > 0) {
        while ($row = $stmtResult->fetch_assoc()) {
            $usersQuestions[] = $row;
        }
    }
    $stmt->close();
}

if ($usersQuestions) {
    echo 'User Questions: ';
    echo '<pre>';
    print_r($usersQuestions);
    echo '</pre>';
} else {
    echo 'No User Questions with catalog_id: "' . $catalogId . '"';
}

//task 3

$sql3 = 'SELECT u.name, u.phone, SUM(o.subtotal), AVG(o.subtotal), MAX(o.created)
FROM `orders` as o
RIGHT JOIN users as u
 ON u.id = o.user_id
GROUP BY o.user_id';

if ($stmt = $mysqli->prepare($sql3)) {
    $stmt->execute();
    $stmtResult = $stmt->get_result();
    if ($stmtResult->num_rows > 0) {
        while ($row = $stmtResult->fetch_assoc()) {
            $usersOrders[] = $row;
        }
    }
    $stmt->close();
}

if ($usersOrders) {
    echo 'User Orders: ';
    echo '<pre>';
    print_r($usersOrders);
    echo '</pre>';
} else {
    echo 'No User Orders';
}

//task 4
?>
<script>
    function printOrderTotal(responseString) {
        let responseJSON = JSON.parse(responseString);
        let orderSubtotal;
        const digitsAfterFloat = 2;
        responseJSON.forEach(function (item) {
            if (!item.price) {
                item.price = 0;
            }
            orderSubtotal = item.price.toFixed(digitsAfterFloat);
        });
        let result = 'Стоимость заказа: ';
        let price = orderSubtotal > 0 ? orderSubtotal + ' руб.' : 'Бесплатно';
        result = result + price;
        console.log(result);
    }
</script>
