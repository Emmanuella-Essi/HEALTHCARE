<?php

$conn = mysqli_connect("localhost", "root", "", "healthcare");

if ($conn) {
    echo "Base connectée ✅";
} else {
    echo "Erreur de connexion ❌";
}

?>