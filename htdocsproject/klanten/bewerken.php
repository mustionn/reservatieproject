<?php
require_once '../base/master.php';

$clientId = $_GET['id'] === 'new' ? 'new' : filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($clientId === 'new') {
  $client = [
    'klantnummer' => 'new',
    'voornaam' => '',
    'achternaam' => '',
    'postcode' => '',
    'woonplaats' => '',
    'email' => ''
  ];
} else {
  require_once '../database/db.php';
  $stmt = $db->prepare('SELECT * FROM klant WHERE klantnummer = :klantnummer');
  $stmt->execute([':klantnummer' => $clientId]);
  if($stmt->rowCount() === 0) {
    header('Location: /klanten/');
    exit;
  }
  $client = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="page-container d-flex align-items-stretch">
  <?php require_once '../base/menu.php'; ?>
  <div class="page-content flex-grow-1 d-flex justify-content-center align-items-start p-5">
    <div class="card d-inline-block align-top">
      <div class="card-body">
        <h5 class="card-title text-center">Klant <?= $clientId === 'new' ? 'aanmaken' : 'bewerken' ?></h5>
        <form method="post" action="/api/edit_client.php">
          <input type="hidden" name="id" value="<?= $client['klantnummer'] ?>" required>
          <div class="mb-3">
            <label for="firstName" class="form-label">Voornaam</label>
            <input type="text" class="form-control" name="firstName" value="<?= $client['voornaam'] ?>" required>
          </div>
          <div class="mb-3">
            <label for="lastName" class="form-label">Achternaam</label>
            <input type="text" class="form-control" name="lastName" value="<?= $client['achternaam'] ?>" required>
          </div>
          <div class="mb-3">
            <label for="postalCode" class="form-label">Postcode</label>
            <input type="text" class="form-control" name="postalCode" value="<?= $client['postcode']?>" required>
          </div>
          <div class="mb-3">
            <label for="place" class="form-label">Woonplaats</label>
            <input type="text" class="form-control" name="place" value="<?= $client['woonplaats']?>" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= $client['email']?>" required>
          </div>
          <div class="mb-3">
            <button type="submit" class="btn btn-primary">Opslaan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>