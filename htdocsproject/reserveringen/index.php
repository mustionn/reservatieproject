<?php
require_once '../base/master.php';
require_once '../database/db.php';

$stmt = $db->query('SELECT reservering.*, klant.voornaam, klant.achternaam, plaats.omschrijving FROM reservering
INNER JOIN klant ON reservering.klantnummer = klant.klantnummer
INNER JOIN plaats ON plaats.plaatsnummer = reservering.plaatsnummer
');

$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-container d-flex align-items-stretch">
  <?php require_once '../base/menu.php'; ?>
  <div class="page-content flex-grow-1 d-flex justify-content-center p-5">

    <div class="card d-inline-block align-top">
      <div class="card-body">
        <h5 class="card-title text-center">Reserveringen</h5>
        <a href="/reserveringen/bewerken.php?id=new" class="btn btn-primary">Voeg reservering toe</a>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Reserveringsnummer</th>
              <th scope="col">Plaatsnummer</th>
              <th scope="col">Omschrijving</th>
              <th scope="col">Van</th>
              <th scope="col">Tot</th>
              <th scope="col">Voornaam</th>
              <th scope="col">Achternaam</th>
              <th scope="col">Bewerken</th>
              <th scope="col">Verwijderen</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($reservations as $reservation) { ?>
              <tr>
                <th scope="row"><?= $reservation['reserveringsnummer'] ?></th>
                <td><?= $reservation['plaatsnummer'] ?></td>
                <td><?= $reservation['omschrijving'] ?></td>
                <td><?= $reservation['datum_aankomst'] ?></td>
                <td><?= $reservation['datum_vertrek'] ?></td>
                <td><?= $reservation['voornaam'] ?></td>
                <td><?= $reservation['achternaam'] ?></td>
                <td><a href="/reserveringen/bewerken.php?id=<?=$reservation['reserveringsnummer']?>">Bewerken</a></td>
                <td><a href="/api/delete_reservation.php?id=<?=$reservation['reserveringsnummer']?>">Verwijderen</a></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>