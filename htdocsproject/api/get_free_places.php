<?php
require_once '../database/db.php';

$from = htmlspecialchars($_GET['from']);
$to = htmlspecialchars($_GET['to']);

header('content-type: application/json');
if(preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $from) == false || preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $to) == false) {
  echo '[]';
  exit;
}

$stmt = $db->prepare('SELECT P.* from plaats P LEFT JOIN reservering R ON R.plaatsnummer = P.plaatsnummer
  WHERE (R.datum_aankomst IS NULL OR R.datum_aankomst NOT BETWEEN :from AND :to) AND (R.datum_vertrek IS NULL OR R.datum_vertrek NOT BETWEEN :from and :to)');

$stmt->execute([
  ':from' => $from,
  ':to' => $to
]);

$freePlaces = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($freePlaces);