
<!-- Modal -->
<div
  class="modal fade"
  id="staticBackdrop"
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
          class="btn-close modal-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <form action="" method="post" id="form-edit">
        <div class="modal-body">
          
          <div class="mb-2">
            <div class="alert alert-danger d-none" id="general-error"></div>
          </div>
          
          <div class="mb-2">
            <label for="room-name" class="form-label">Room name:</label>
            <input type="text" class="form-control" id="room-name" name="room-name" />
            <div class="invalid-feedback"></div>
          </div>

          <div class="mb-2">
            <P class="form-label">Room Type</P>
            <div class="dropdown">
                <input type="text" class="form-control dropdown-input" placeholder="Search and Select..." id="dropdown-room-type" name="room-type-desc" >
                <input type="hidden" id="hidden-room-type-id" name="room-type"/>
                <div class="dropdown-list" id="dropdown-list-room-type">
                    <!-- Options will be populated here by JavaScript -->
                </div>
                <div class="invalid-feedback"></div>
            </div>
          </div>

          <!-- <div class="mb-2">
            <label for="room-type" class="form-label">Room type:</label>
            <select class="form-select" id="room-type" name="room-type">
              <option value="">--Select--</option>
            </select>
            <div class="invalid-feedback"></div>
          </div> -->

        </div>

        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary modal-close"
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
