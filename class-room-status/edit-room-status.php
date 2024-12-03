
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
              <P class="form-label h1-label">Class Details:</P><input type="hidden" id="hidden-class-status-id" name="class-status-id">
              <div style="padding-left: 8px;">
                  <div class="mb-2"><input type="hidden" id="hidden-class-id" name="class-id">
                      <P class="form-label">Room</P>
                      <div class="dropdown"><input type="hidden" id="hidden-room-id" name="room-id"/>
                          <input type="text" class="form-control dropdown-input" placeholder="Select or search..." id="dropdown-room" name="room" >
                          <div class="invalid-feedback"></div>
                          <div class="dropdown-list" id="dropdown-list-name">
                              <!-- Options will be populated here by JavaScript -->
                          </div>
                          <input type="hidden" id="hidden-original-room-id" name="original-room-id">
                      </div>
                  </div>
                  <br>
                  <div class="mb-2">
                      <P class="form-label">Subject</P><input type="hidden" id="hidden-subject-id" name="subject-id"/>
                      <div class="dropdown">
                          <input type="text" class="form-control dropdown-input" placeholder="Select or search..." id="dropdown-subject" name="subject">
                          <div class="invalid-feedback"></div>
                          <div class="dropdown-list" id="dropdown-list-subject">
                              <!-- Options will be populated here by JavaScript -->
                          </div>
                          <input type="hidden" id="hidden-original-subject-id" name="original-subject-id">
                      </div>
                  </div>
                  <br>
                  <div class="mb-2">
                      <P class="form-label">Section</P><input type="hidden" id="hidden-section-id" name="section-id"/>
                      <div class="dropdown">
                          <input type="text" class="form-control dropdown-input" placeholder="Select or search..." id="dropdown-section" name="section">
                          <div class="invalid-feedback"></div>
                          <div class="dropdown-list" id="dropdown-list-section">
                              <!-- Options will be populated here by JavaScript -->
                          </div>
                          <input type="hidden" id="hidden-original-section-id" name="original-section-id">
                      </div>
                  </div>
                  <br>
                  <div class="mb-2">
                      <P class="form-label">Teacher Assigned</P><input type="hidden" id="hidden-teacher-assigned" name="teacher-assigned"/>
                      <div class="dropdown">
                          <input type="text" class="form-control dropdown-input" placeholder="Select or search..." id="dropdown-teacher" name="teacher" >
                          <div class="invalid-feedback"></div>
                          
                          <div class="dropdown-list" id="dropdown-list-teacher">
                              <!-- Options will be populated here by JavaScript -->
                          </div>
                          <input type="hidden" id="hidden-original-teacher-assigned" name="original-teacher-assigned">
                      </div>
                  </div>
              </div>
  
              <br><br>
              <P class="form-label h1-label">Class Schedule:</P><input type="hidden" id="hidden-class-time-id" name="class-time-id">
              <div style="padding-left: 8px;">
                  <div class="mb-2"><input type="hidden" id="hidden-original-start-time" name="original-start-time">
                      <label for="start-time" class="form-label">Start-Time</label>
                      <input type="time" class="form-control" id="start-time" name="start-time">
                      <div class="invalid-feedback"></div>
                  </div>
                  <br>
  
                  <div class="mb-2"><input type="hidden" id="hidden-original-end-time" name="original-end-time">
                      <label for="end-time" class="form-label">End-Time</label>
                      <input type="time" class="form-control" id="end-time" name="end-time">
                      <div class="invalid-feedback"></div>
                  </div>
                  <br>
                  
                  

                  <P class="form-label">Day</P><input type="hidden" id="hidden-class-day-id" name="class-day-id">
                  <div class="mb-2 d-flex flex-column gap-1"><input type="hidden" id="hidden-original-day-id" name="original-day-id">
                      <div class="mb-2 d-flex gap-3 day-id"> 
                     
                          <label for="1" class="form-label">M</label>
                          <input type="checkbox" id="1" value="1" name="day-id[]">
  
                          <label for="2" class="form-label">T</label>
                          <input type="checkbox" id="2" value="2" name="day-id[]">
  
                          <label for="3" class="form-label">W</label>
                          <input type="checkbox" id="3" value="3" name="day-id[]">
  
                          <label for="4" class="form-label">Th</label>
                          <input type="checkbox" id="4" value="4" name="day-id[]">
  
                          <label for="5" class="form-label">F</label>
                          <input type="checkbox" id="5" value="5" name="day-id[]">
  
                          <label for="6" class="form-label">Sa</label>
                          <input type="checkbox" id="6" value="6" name="day-id[]">
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
