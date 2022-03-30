<?php
require_once '../database/db.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if($id === false && $_POST['id'] !== 'new') {
  header('Location: /klanten/');
  exit;
}

$isNewClient = $_POST['id'] === 'new';
$firstName = htmlspecialchars($_POST["firstName"]);
$lastName = htmlspecialchars($_POST["lastName"]);
$postalCode = htmlspecialchars($_POST["postalCode"]);
$place = htmlspecialchars($_POST["place"]);
$email = htmlspecialchars($_POST["email"]);

if(strlen($firstName) === 0 || strlen($lastName) === 0 || strlen($postalCode) === 0 || strlen($place) === 0 || strlen($email) === 0) {
  header('Location: /klanten/bewerken.php?id=' . $id ? $id : 'new');
  exit;
}

if($isNewClient) {
  $stmt = $db->prepare('INSERT INTO klant (voornaam, achternaam, postcode, woonplaats, email) VALUES (:firstName, :lastName, :postalCode, :place, :email)');
  $stmt->execute([
    ':firstName' => $firstName,
    ':lastName' => $lastName,
    ':postalCode' => $postalCode,
    ':place' => $place,
    ':email' => $email
  ]);
  
  header('Location: /klanten/');
  exit;
} else {
  $stmt = $db->prepare("UPDATE klant SET voornaam = :firstName, achternaam = :lastName, postcode = :postalCode, woonplaats = :place, email = :email WHERE klantnummer = :id");
  $stmt->execute([
    ':firstName' => $firstName,
    ':lastName' => $lastName,
    ':postalCode' => $postalCode,
    ':place' => $place,
    ':email' => $email,
    ':id' => $id
  ]);

  header('Location: /klanten/');
  exit;
}