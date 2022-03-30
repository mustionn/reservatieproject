<?php
require_once '../base/master.php';
require_once '../database/db.php';

$reservationId = $_GET['id'] === 'new' ? 'new' : filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($reservationId === 'new') {
  $reservation = [
    'reserveringsnummer' => 'new',
    'klantnummer' => '',
    'plaatsnummer' => '',
    'datum_aankomst' => '',
    'datum_vertrek' => '',
  ];
  $reservationOptions = [];
} else {
  $stmt = $db->prepare('SELECT * FROM reservering WHERE reserveringsnummer = :reserveringsnummer');
  $stmt->execute([':reserveringsnummer' => $reservationId]);
  if($stmt->rowCount() === 0) {
    header('Location: /klanten/');
    exit;
  }
  $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

  $stmt = $db->prepare('SELECT * FROM reservering_reserveringsoptie WHERE reserveringsnummer = :reserveringsnummer');
  $reservationOptions = $stmt->execute([':reserveringsnummer' => $reservationId]) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
}

$stmt = $db->query('SELECT * FROM klant');
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $db->query('SELECT * FROM reserveringsoptie');
$options = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-container d-flex align-items-stretch">
  <?php require_once '../base/menu.php'; ?>
  <div class="page-content flex-grow-1 d-flex justify-content-center align-items-start p-5">
    <div class="card d-inline-block align-top">
      <div class="card-body">
        <h5 class="card-title text-center">Reservering <?= $reservationId === 'new' ? 'aanmaken' : 'bewerken' ?></h5>
        <form method="post" action="/api/edit_reservation.php">
          <input type="hidden" name="id" value="<?= $reservation['reserveringsnummer'] ?>" required>
          <div class="mb-3">
            <label for="clientId" class="form-label">Klant</label>
            <div class="d-block">
              <select name="clientId">
                <?php foreach ($clients as $client) { ?>
                  <option value="<?= $client['klantnummer'] ?>" <?= $client['klantnummer'] === $reservation['klantnummer'] ? 'selected' : '' ?>>
                    <?= $client['voornaam'] . ' ' . $client['achternaam'] . ' ' . $client['postcode'] . ' ' . $client['woonplaats'] ?>
                  </option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label for="lastName" class="form-label">Van</label>
            <input type="date" class="form-control" name="from" value="<?= $reservation['datum_aankomst'] ?>" required>
          </div>
          <div class="mb-3">
            <label for="postalCode" class="form-label">Tot</label>
            <input type="date" class="form-control" name="to" value="<?= $reservation['datum_vertrek'] ?>" required>
          </div>
          <div class="mb-3">
            <label for="place" class="form-label">Plaats</label>
            <?php if($reservation['datum_aankomst'] === '' || $reservation['datum_vertrek'] === '') { ?>
              <div class="form-text place-number-info">Voer Van en Tot in om beschikbare plaatsen te zien</div>
              <select name="placeNumber" class="invisible" required></select>
            <?php } else { ?>
              <div class="form-text place-number-info invisible">Voer Van en Tot in om beschikbare plaatsen te zien</div>
              <select name="placeNumber" class="visible" data-init-on-load required></select>
            <?php } ?>
          </div>
          <div class="mb-3">
            <h4>Reserveringsopties</h3>
          </div>
          <?php foreach($options as $option) { ?>
            <div class="mb-3">
              <label for="option<?= $option['optienummer'] ?>" class="form-label">
                <?= $option['naam'] . ' (€ ' . number_format($option['prijs'] / 100, 2, ',', '.') . ')' ?>
              </label>
              <input type="number" class="form-control" name="option:<?= $option['optienummer'] ?>" min="0" step="1" value="0">
            </div>
          <?php } ?>
          <button type="submit" class="btn btn-primary">Opslaan</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script defer>
  const fromInput = document.querySelector('input[name="from"]');
  const toInput = document.querySelector('input[name="to"]');
  const placesInput = document.querySelector('select[name="placeNumber"]');

  function loadPlaces() {
    fetch(`/api/get_free_places.php?from=${fromInput.value}&to=${toInput.value}`).then((response) => {
      response.json().then((jsonData) => {
        placesInput.innerHTML = '';
        jsonData.forEach((place) => {
          const option = document.createElement('option');
          option.value = place.plaatsnummer;
          option.innerText = `${place.omschrijving} (€ ${(place.prijs / 100).toLocaleString('nl-NL', { minimumFractionDigits: 2 })})`;
          placesInput.appendChild(option);
        });
        if(placesInput.classList.contains('invisible')) {
          placesInput.classList.remove('invisible');
          document.querySelector('place-number-info').classList.add('invisible');
        }
      })
    })
  }

  if(placesInput.hasAttribute('data-init-on-load')) {
    loadPlaces();
  }

  [fromInput, toInput].forEach((input) => input.addEventListener('change', function() {
    console.log('test');
    if(/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/.test(fromInput.value) && /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/.test(toInput.value)) {
      loadPlaces();
    }
  }));
</script>