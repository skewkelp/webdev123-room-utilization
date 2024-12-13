
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-id="">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Edit Status Room</h5>
            <button type="button" class="btn-close modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div> 
          <form action="" method="post" id="form-edit">
            <div class="modal-body">
                <input type="hidden" id="hidden-class-status-id" name="class-status-id">
                <input type="hidden" id="hidden-class-day-id" name="class-day-id">
                <input type="hidden" id="hidden-original-class-id" name="original-class-id">
                <input type="hidden" id="hidden-original-class-time-id" name="original-class-time-id">
                <input type="hidden" id="hidden-original-start-time" name="original-start-time">
                <input type="hidden" id="hidden-original-end-time" name="original-end-time">
                <input type="hidden" id="hidden-original-day-id" name="original-day-id">

                <P class="form-label h1-label">Class Details:</P>
                <div style="padding-left: 8px;">
                    <div class="mb-2">
                        <P class="form-label">Class ID</P>
                        <div class="dropdown">
                            <input type="text" class="form-control dropdown-input" placeholder="Select or search..." id="dropdown-class-id" name="class-id" >
                            <input type="hidden" id="hidden-class-id" name="class-id"/>
                            <div class="dropdown-list" id="dropdown-list-class-id">
                                <!-- Options will be populated here by JavaScript -->
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <br>

                <P class="form-label h1-label">Class Schedule:</P>
                <div style="padding-left: 8px;">
                    <div class="mb-2">
                        <div class="alert alert-danger d-none" id="existing-class-error"></div>
                        <input type="hidden" id="existing-class-id"/>
                    </div>
                    
                    <div class="mb-2">
                        <label for="start-time" class="form-label">Start-Time</label>
                        <input type="time" class="form-control" id="start-time" name="start-time" >
                        <div class="invalid-feedback"></div>
                    </div>
                    <br>

                    <div class="mb-2">
                        <label for="end-time" class="form-label">End-Time</label>
                        <input type="time" class="form-control" id="end-time" name="end-time">
                        <div class="invalid-feedback"></div>
                    </div>
                    <br>
                    
                    <P class="form-label">Day</P>
                    <div class="mb-2 d-flex flex-column gap-1">
                        <div class="mb-2 d-flex gap-4 day-id" style="padding-left: 8px;"> 
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <label for="1" class="form-label">M</label>
                                <input type="radio" id="1" value="1" name="day-id">
                            </div>
                            
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <label for="2" class="form-label">T</label>
                                <input type="radio" id="2" value="2" name="day-id">
                            </div>
                            
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <label for="3" class="form-label">W</label>
                                <input type="radio" id="3" value="3" name="day-id">
                            </div>

                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <label for="4" class="form-label">Th</label>
                                <input type="radio" id="4" value="4" name="day-id">
                            </div>

                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <label for="5" class="form-label">F</label>
                                <input type="radio" id="5" value="5" name="day-id">
                            </div>

                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <label for="6" class="form-label">Sa</label>
                                <input type="radio" id="6" value="6" name="day-id">
                            </div>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal-close" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary brand-bg-color">Edit Room</button>
            </div>
          </form>
    </div>
  </div>
</div>
