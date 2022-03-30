<?php
require_once '../database/db.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if($id === false && $_POST['id'] !== 'new') {
  header('Location: /reserveringen/');
  exit;
}

$isNewReservation = $_POST['id'] === 'new';

$clientId = filter_input(INPUT_POST, 'clientId', FILTER_VALIDATE_INT);
$placeNumber = filter_input(INPUT_POST, 'placeNumber', FILTER_VALIDATE_INT);
$from = htmlspecialchars($_POST["from"]);
$to = htmlspecialchars($_POST["to"]);

if($clientId == false || $placeNumber == false || preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $from) == false || preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $to) == false) {
  var_dump($placeNumber);
  header('Location: /reserveringen/bewerken.php?id=' . $id ? $id : 'new');
  exit;
}

var_dump($isNewReservation);
if($isNewReservation) {
  var_dump('test');
  $stmt = $db->prepare('INSERT INTO reservering (klantnummer, plaatsnummer, datum_aankomst, datum_vertrek) VALUES (:clientId, :placeNumber, :from, :to)');
  $stmt->execute([
    ':clientId' => $clientId,
    ':placeNumber' => $placeNumber,
    ':from' => $from,
    ':to' => $to
  ]);
  
  header('Location: /reserveringen/');
  exit;
} else {
  $stmt = $db->prepare("UPDATE reservering SET klantnummer = :clientId, plaatsnummer = :placeNumber, datum_aankomst = :from, datum_vertrek = :to WHERE reserveringsnummer = :id");
  $stmt->execute([
    ':clientId' => $clientId,
    ':placeNumber' => $placeNumber,
    ':from' => $from,
    ':to' => $to,
    ':id' => $id
  ]);

  header('Location: /reserveringen/');
  exit;
}