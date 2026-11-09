<?php

function consultar($con, $sql) {
    $stmt = $con->prepare($sql);
    $stmt->execute();

    return $stmt->get_result();
}
?>