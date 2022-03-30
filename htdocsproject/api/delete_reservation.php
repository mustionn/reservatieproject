<?php
require_once '../database/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if($id === false) {
  header('Location: /reserveringen/');
  exit;
}

$stmt = $db->prepare('DELETE FROM reservering WHERE reserveringsnummer = :id');
$stmt->execute([':id' => $id]);

header('Location: /reserveringen/');