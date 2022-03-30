<?php
require_once '../base/master.php';
require_once '../database/db.php';

$stmt = $db->query('SELECT * FROM klant');
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-container d-flex align-items-stretch">
  <?php require_once '../base/menu.php'; ?>
  <div class="page-content flex-grow-1 d-flex justify-content-center p-5">

    <div class="card d-inline-block align-top">
      <div class="card-body">
        <h5 class="card-title text-center">Klantenoverzicht</h5>
        <a href="/klanten/bewerken.php?id=new" class="btn btn-primary">Voeg klant toe</a>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Voornaam</th>
              <th scope="col">Achternaam</th>
              <th scope="col">Postcode</th>
              <th scope="col">Woonplaats</th>
              <th scope="col">Email</th>
              <th scope="col">Bewerken</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($clients as $client) { ?>
              <tr>
                <th scope="row"><?= $client['klantnummer'] ?></th>
                <td><?= $client['voornaam'] ?></td>
                <td><?= $client['achternaam'] ?></td>
                <td><?= $client['postcode'] ?></td>
                <td><?= $client['woonplaats'] ?></td>
                <td><?= $client['email'] ?></td>
                <td><a href="/klanten/bewerken.php?id=<?=$client['klantnummer']?>">Bewerken</a></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>