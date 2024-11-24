<?php 
// require_once('../classes/room.class.php');

// $roomObj = new Room();
// if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//     if (isset($_GET['id'])) {
//         $id = $_GET['id'];
//         $record = $roomObj->fetchRoomName($id);
//         if (!empty($record)) {
//             $roomname = $record['room_name'];
//         } else {
//             echo 'No product found (GET)';
//             exit;
//         }
//     } else {
//         echo 'No product found(Noset GET)';
//         exit;
//     }
// } <?= $roomname >
?>

<!-- Modal -->
<div
  class="modal fade"
  id="staticBackdropedit"
  data-bs-backdrop="static"
  data-bs-keyboard="false"
  tabindex="-1"
  aria-labelledby="staticBackdropLabel"
  aria-hidden="true"
  data-id=""
>
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Edit Room: </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <form action="" method="post" id="form-edit-room">
        <div class="modal-body">

          <div class="mb-2">
            <label for="room-name" class="form-label">Room name:</label>
            <input type="text" class="form-control" id="room-name" name="room-name" />
            <div class="invalid-feedback"></div>
          </div>

          <div class="mb-2">
            <label for="room-type" class="form-label">Room type:</label>
            <select class="form-select" id="room-type" name="room-type">
              <option value="">--Select--</option>
            </select>
            <div class="invalid-feedback"></div>
          </div>

        </div>

        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            Close
          </button>
          <button type="submit" class="btn btn-primary brand-bg-color">
            Update Room
          </button>
        </div>
      </form>
    </div>
  </div>
</div>