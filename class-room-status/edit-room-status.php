
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
                <input type="hidden" id="hidden-original-class-id" name="original-class-id">
                <input type="hidden" id="hidden-original-subtype" name="original-subtype">
                
                <input type="hidden" id="hidden-original-start-time" name="original-start-time">
                <input type="hidden" id="hidden-original-end-time" name="original-end-time">
                
                <input type="hidden" id="hidden-original-day-id" name="original-day-id">
                <input type="hidden" id="hidden-original-room" name="original-room">

                <P class="form-label h1-label">Class Details:</P>
                <div style="padding-left: 8px;">
                    <div class="mb-2">
                        <div class="alert alert-danger d-none" id="general-error"></div>
                    </div>
                    
                    <div class="mb-2">
                        <P class="form-label">Class ID</P>
                        <div class="dropdown">
                            <input type="text" class="form-control dropdown-input" placeholder="Select or search..." id="dropdown-class-id" name="class">
                            <input type="hidden" id="hidden-class-id" name="class-id"/>
                            <div class="dropdown-list" id="dropdown-list-class-id">
                                <!-- Options will be populated here by JavaScript -->
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <br>

                <div class="ms-5 mb-2">
                    <div class="mb-0 me-2 d-flex gap-2 subject-type">
                        <input type="hidden" id="determiner-type" name="determiner-type" value="false">
                        <P class="form-label mb-0 me-2">Subject Type:</P><br>
                        <div class="d-flex justify-content-center align-items-center">
                            <label for="LEC" class="form-label mb-0 me-2">LEC</label>
                            <input type="radio" id="LEC" value="LEC" name="subject-type">
                        </div>
                        
                        <div class="d-flex justify-content-center align-items-center">
                            <label for="LAB" class="form-label mb-0 me-2">LAB</label>
                            <input type="radio" id="LAB" value="LAB" name="subject-type">
                        </div>
                    </div>

                    <div class="invalid-feedback"></div>
                </div>

                <P class="form-label h1-label">Class Schedule:</P>
                <div style="padding-left: 8px;">
                    <div class="mb-2">
                        <div class="alert alert-danger d-none" id="general-error-1"></div>
                    </div>
                    
                    <div class="mb-2 d-flex gap-3">
                        <P class="form-label h1-label div-time" style="display: none;">LEC:</P>
                        <div class="mb-2 flex-fill">
                            <label for="start-time" class="form-label">Start-Time</label>
                            <input type="time" class="form-control" id="start-time" name="start-time" >
                            <div class="invalid-feedback"></div>
                        </div>
                        <br>
        
                        <div class="mb-2 flex-fill">
                            <label for="end-time" class="form-label">End-Time</label>
                            <input type="time" class="form-control" id="end-time" name="end-time">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                
                    <div class="mb-2 d-flex gap-3">
                        <div class="d-flex gap-2">
                            <P class="form-label">Day</P>
                        </div>

                        <div class="mb-2 d-flex flex-column gap-1">
                            <div class="mb-2 d-flex gap-4 day-id-1" style="padding-left: 8px;"> 
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <label for="1" class="form-label">M</label>
                                    <input type="radio" id="1" value="Monday" name="day-id">
                                </div>
                                
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <label for="2" class="form-label">T</label>
                                    <input type="radio" id="2" value="Tuesday" name="day-id">
                                </div>
                                
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <label for="3" class="form-label">W</label>
                                    <input type="radio" id="3" value="Wednesday" name="day-id">
                                </div>
        
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <label for="4" class="form-label">Th</label>
                                    <input type="radio" id="4" value="Thursday" name="day-id">
                                </div>
        
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <label for="5" class="form-label">F</label>
                                    <input type="radio" id="5" value="Friday" name="day-id">
                                </div>
        
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <label for="6" class="form-label">Sa</label>
                                    <input type="radio" id="6" value="Saturday" name="day-id">
                                </div>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
            
                    <div class="mb-2">
                        <div class="d-flex gap-2">
                            <P class="form-label">Room </P>
                        </div>

                        <div class="dropdown">
                            <input type="text" class="form-control dropdown-input" placeholder="Select or search..." id="dropdown-room" name="room-input-1" >
                            <input type="hidden" id="hidden-room-id" name="room-id-1"/>
                            <div class="dropdown-list" id="dropdown-list-name">
                                <!-- Options will be populated here by JavaScript -->
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
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
