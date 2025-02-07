$(document).ready(function () { 
    //hide restricted elements
  function hideRestrictedElements() {
    try {
      const userPermissions = window.userPermissions || {};

      // Log permissions only if they have changed
      if (!this.loggedPermissions || 
        this.loggedPermissions.isAdmin !== userPermissions.isAdmin || 
        this.loggedPermissions.isStaff !== userPermissions.isStaff) {
        console.debug('User Permissions:', userPermissions);
        this.loggedPermissions = userPermissions; // Store current permissions
      }

      // Use a flag to check if elements have already been hidden
      let elementsHidden = false;

      // Hide admin elements
      if (!userPermissions.isAdmin) {
        $('.admin').addClass('d-none');
        elementsHidden = true;
      } else {
        $('.admin').removeClass('d-none'); // Show if admin
      }

      // Hide staff elements
      if (!userPermissions.isStaff) {
        $('.staff').addClass('d-none');
        elementsHidden = true;
      } else {
        $('.staff').removeClass('d-none'); // Show if staff
      }

      // Hide elements requiring either permission
      if (!userPermissions.isAdmin && !userPermissions.isStaff) {
        $('.restricted').addClass('d-none');
        elementsHidden = true;
      } else {
        $('.restricted').removeClass('d-none'); // Show if either permission
      }

      // Log if any elements were hidden or shown
      if (elementsHidden) {
        // console.debug('Restricted elements updated based on permissions.');
      }
    } catch (error) {
      console.error('Error in hideRestrictedElements function in admin.js:', error);
    }
  }

  // Debounce function to limit execution frequency
  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  const debouncedHideRestrictedElements = debounce(hideRestrictedElements, 100);

  // Initial hide
  hideRestrictedElements(); // Call once on page load
  // MutationObserver to watch for added nodes
  const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.addedNodes.length) {
        debouncedHideRestrictedElements();
      }
    });
  });
  observer.observe(document.body, { childList: true, subtree: true });
  
  // Function to close the modal
  function closeModal(modal) {
    const modalElement = modal[0]; // Get the first DOM element from the jQuery object
    modalElement.classList.remove('active'); // Remove active class
    modalElement.style.display = 'none'; // Set display to none
  }

  // Function to serializeForm
  function serializeForm(formId) {
    return $(formId).serialize(); // Serialize the form and return the result
  }

  // Event listener for navigation links
  $(".nav-link").on("click", function (e) {
    e.preventDefault(); // Prevent default anchor click behavior
    $(".nav-link").removeClass("link-active"); // Remove active class from all links
    $(this).addClass("link-active"); // Add active class to the clicked link

    let url = $(this).attr("href"); // Get the URL from the href attribute
    window.history.pushState({ path: url }, "", url); // Update the browser's URL without reloading
  });

  $(".user-profile").on("click", function (e) {
    e.preventDefault(); // Prevent default anchor click behavior
    $(".user-profile").removeClass("link-active"); // Remove active class from all links
    $(".nav-link").removeClass("link-active");
    $(this).addClass("link-active"); // Add active class to the clicked link

    let url = $(this).attr("href"); // Get the URL from the href attribute
    window.history.pushState({ path: url }, "", url); // Update the browser's URL without reloading
  });


  //burger-sidebar Event Listener
  $("#burger").on("click", function (e) {
    e.preventDefault(); // Prevent default behavior
    
    // Disable the button to prevent multiple clicks
    $(this).prop('disabled', true);

    // delay before logic
    setTimeout(function(){
      var sidebar = $("#sidebar");
      var navLabel = $(".sidebar-button-text.ms-2");
      var content = $(".content-page.px-3");
      var sidebartitle = $(".side-nav-title");

      if (sidebar.width() === 260) {
        sidebartitle.addClass("collapsed flex-nowrap overflow-hidden");
        sidebar.addClass("collapsed");
        navLabel.toggle();
        content.css("margin-left", "70px");
      } else {
        sidebartitle.removeClass("collapsed");
        sidebar.removeClass("collapsed");
        navLabel.toggle();
        content.css("margin-left", "262px");
      }

      // Re-enable the button after the action
      $("#burger").prop('disabled', false);
    }, 300); //0.3 secs

  });

  // Function to set the selected room (and store in localStorage)
  function setSelectedRoom(roomName) {
    localStorage.setItem("selectedRoom", roomName);
    $("#roomschedule-link").data("room", roomName); // Update the data attribute as well
  }

  // Function to get the selected room from localStorage
  function getSelectedRoom() {
    return localStorage.getItem("selectedRoom");
  }


  //SIDE BAR NAVIGATION LINK BUTTON
  // Event listener for the roomlist-link
  $("#roomlist-link").on("click", function (e) {
    e.preventDefault(); // Prevent default behavior
    viewroomList(); // Call the function to load analytics
  });

  

  function handleClassStatusClick(e) {
    e.preventDefault();
    let roomName= $(this).data('room'); 

    console.log("room:", roomName);
    let functionRef = viewroomStatus;
    // Check if semester is picked for this session
    checkSemester(functionRef, roomName);
  }

  $("#classlist-link").on("click", handleClassStatusClick);




  // Event listener for the claslist-link
  $("#classlist-link").on("click", function (e) {
    e.preventDefault();
    let functionRef = viewroomStatus;

    // Check if semester is picked for this session
    checkSemester(functionRef);
  });


  

  function handleRoomScheduleClick(e) {
    e.preventDefault();
    let roomName= $(this).data('room'); 

    console.log("room:", roomName);
    let functionRef = viewroomSchedule;
    // Check if semester is picked for this session
    checkSemester(functionRef, roomName);
  }

  //Event listener for the roomschedule-link
  $("#roomschedule-link").on("click", handleRoomScheduleClick);


  $("#profile-link").on("click", function (e) {
    e.preventDefault(); // Prevent default behavior
    viewProfile(); // Call the function to load products
  });

  $("#userlist-link").on("click", function (e) {
    e.preventDefault(); // Prevent default behavior
    viewuserList(); // Call the function to load products
  });


  $("#faculty-classlist-link").on("click", function (e){
    e.preventDefault(); // Prevent default behavior
    let functionRef = viewmyclassSchedule;

    // Check if semester is picked for this session
    checkSemester(functionRef);
  });


  // href="room-schedule" id="roomschedule-link" 
  // Determine which page to load based on the current URL
  let url = window.location.href;

  if (url.endsWith("room-list")) {
    $("#roomlist-link").trigger("click"); // Trigger the dashboard click event
  } else if (url.endsWith("class-status")) {
    $("#classlist-link").trigger("click"); // Trigger the roomstatus click 
  
  } else if (url.endsWith("room-schedule")){
    let storedRoom = getSelectedRoom();
    if(storedRoom && storedRoom !== ""){
      $("#roomschedule-link").data("room", storedRoom);
      $("#roomschedule-link").trigger("click"); // Trigger the roomschedule click event
      
    }
    $("#roomschedule-link").trigger("click"); // Trigger the roomschedule click event
  } else if (url.endsWith("profile-page")) {
    $("#profile-link").trigger("click"); // Trigger the profile link click event
  } else if (url.endsWith("user-list")){
    $("#userlist-link").trigger("click");
  } else if (url.endsWith("faculty-class-list")){
    $("#faculty-classlist-link").trigger("click");
  }else {
    $("#roomlist-link").trigger("click"); // Default to dashboard if no specific page
  }
  // else if(url.endsWith("room-schedule?")){
  //   // window.location.assign("room-schedule");
  // }


  //Check if semester is selected
  function checkSemester(pageFunctionRef, roomName){
    $.ajax({
      url: '../fetch-data/check-semester-picked.php',
      method: 'GET',
      dataType: 'json',
      success: function(response) { 
        if (response.semesterPicked){
          if(pageFunctionRef == viewroomStatus){
            viewroomStatus();
          }else if(pageFunctionRef == viewroomSchedule){
            viewroomSchedule(roomName);
          }else if(pageFunctionRef == viewmyclassSchedule){
            viewmyclassSchedule();
          }

        }else{
          chooseSemester(pageFunctionRef);
        }
      },
      error: function() {
        // Fallback to semester picker on error
        chooseSemester();
      }
    });
  }

  //Function to load choose semester, load content
  function chooseSemester(pageFunctionRef) {
    $.ajax({
      type: "GET",
      url: "../main-page/choose-semester.php",
      dataType: "html",
      success: function (response) {
        $(".content-page").html(response);
        
        const semesterText= $('#dropdown-semester');
        const semesterList = $('#dropdown-list-semester');
        const semesterId = $('#hidden-semester-id');
        customDropdown(semesterText, semesterList, semesterId, "../fetch-data/fetch-semesterList.php", function(data, dropdownList) {
          $.each(data, function (index, semester) {
            const dataValue = cleanInput(`${semester.semester_id}|${semester.school_year}`);
            dropdownList.append(
              $("<div>", {
                text: semester.semester_desc, // Displayed text
                'data-value': dataValue // Value attribute
              })
            );
          });
        });
        // Event handler for semester form submission
        $("#form-semester").on("submit", function (e) {
          e.preventDefault();
          // Save semester choice
          selectedSemester($(this), pageFunctionRef); // Pass the form element to the function
        });
      },
      error: function(xhr, status, error) {
        alert("Failed to load select semester modal!");
        console.error("Error loading modal on choose-semester.php:", status, error);
      }
    });
  }

  //chosen semester
  function selectedSemester(form, pageFunctionRef){
    $.ajax({
      url: '../main-page/save-semester.php',
      method: 'POST',
      data: form.serialize(), // Now properly serializes the form data
      dataType: 'json',
      success: function(response) {
        console.log("Response received:", response);
        if (response.status === "error") {
          if (response.semester_idErr){
            $("#dropdown-semester").addClass("is-invalid");
            $("#dropdown-semester").siblings(".invalid-feedback").text(response.semester_idErr).show();
          } else {
            $("#dropdown-semester").removeClass("is-invalid");
          }
        } else if (response.status === 'success') {
          if(pageFunctionRef == viewroomStatus){
            viewroomStatus();
          }else if(pageFunctionRef == viewroomSchedule){
            viewroomSchedule();
          }else if(pageFunctionRef == viewmyclassSchedule){
            viewmyclassSchedule();
          }
        }
      },
      error: function(xhr, status, error) {
        alert("Failed to select semester!");
        console.error("Error saving data on save-semester.php:", status, error);
      }
      
    });
  }


  function viewmyclassSchedule(){
    $.ajax({
      type: "GET", // Use GET request
      url: "../faculty-class-list/my-class-schedule.php?v=" + new Date().getTime(), // URL for the analytics view
      dataType: "html", // Expect HTML response
      success: function (response) {
        $(".content-page").html(response); // Load the response into the content area
          // Call function to load the chart

        $("#table-class").DataTable({
          dom: "rtp",
          pageLength: 10,
          ordering: false,
          drawCallback: function(){
            
            $(".room-schedule").on("click", function(e){
              // e.preventDefault(); // Prevent default behavior
              const newUrl = "room-schedule"; // Assuming href is "room-schedule"
              let roomName= $(this).data('room'); 
              setSelectedRoom(roomName);
              // console.log("room:", roomName);
              // window.location.href = newUrl;
              window.location.assign(newUrl);
              // window.history.pushState({}, '', newUrl);
            }); // Trigger the roomschedule click event
      

            $("#class-occupy").on("click", function (e) {
              e.preventDefault(); // Prevent default behavior
          
              const button = $(this); // Reference to the clicked button
              button.prop("disabled", true); // Disable the button
    
              const classID = $(this).data('classid');
              const subType = $(this).data('subjecttype');
              const classDay = $(this).data('classday');
              const roomStatus = $(this).data('status');
              console.log("Data:", classID, subType, classDay, roomStatus);

              // Call the AJAX function
              changingclassStatus(classID, subType, classDay, roomStatus);
              
            });
          }
        });

        
        
      },
      error: function(xhr, status, error) {
        alert("Failed to load view profile content!");
        console.error("Error loading content on viewProfile.php:", status, error);
      }

    });

  }

  function changingclassStatus(classID, subType, classDay, roomStatus, faculty = null, userID, semesterID, schoolYear){
    if(faculty !== null){
      var classToggleUrl = "../faculty-occupy-class/class-toggle.html?v=";
    }else{
      var classToggleUrl = "../faculty-class-list/class-toggle.html?v=";
    }

    $.ajax({
      type: "GET", // Use GET request
      url: classToggleUrl + new Date().getTime(), 
      dataType: "html", // Expect HTML response
      success: function (view) {
        $(".modal-container").html(view); // Load the modal view
        $("#staticBackdrop").modal("show");
        
        const modal = $('#staticBackdrop');
        console.log("Modal content loaded successfully.");

        if(faculty !== null){
          // Then fetch and populate the data
          $.ajax({
            url: `../fetch-data/fetch-room-status.php?classID=${classID}&subType=${subType}&classDay=${classDay}`,
            dataType: "json",
            success: function(data){
              console.log('remarks:', data.remarks);
              // Populate remark
              $('#remark').val(data.remarks);
              $('#og-remark').val(data.remarks);
              $('#remark').prop('disabled', true);
              $('#temporary').prop('disabled', true);
          
            },
            error: function(xhr, status, error) {
              alert("Failed to fetch remark record!");
              console.error("Error fetching data on fetch-room-status.php:", status, error);
            }
          });

          const classText = $('#dropdown-class');
          const classId = $('#class-list');
          const classList = $('#dropdown-list-class');
          const classUrl = `../fetch-data/fetch-facultyclasses.php?semesterID=${semesterID}&schoolYear=${schoolYear}&userID=${userID}`;
          customDropdown(classText, classList, classId, classUrl, function(data, dropdownList) {
            //clear previous option
            dropdownList.empty();
            // Check if data is empty
            if (data.length === 0) {
              $("#general-error").removeClass("d-none").html(cleanInput("<strong>NO HANDLED CLASS!</strong><br>Please contact an admin to add a class to your class list."));
              $('#dropdown-class').addClass("text-danger");
              $('#dropdown-class').addClass("is-invalid");
              $('#dropdown-class').val("Pls close the form");
              $('#submit').hide(); // Hide submit button
              return; // Exit if no data
            }
            // Remove error class if there are classes
            $('#submit').show(); // Show submit button
            $("#general-error").addClass("d-none");
            $('#dropdown-class').removeClass("text-danger");
            $('#dropdown-class').removeClass("is-invalid");
            
            $.each(data, function(index, cList) {
              dropdownList.append(
                $('<div>', {
                  text: `${cList.class_id}|${cList.subject_code}(${cList.subject_type})|${cList.section_name}|${cList.faculty_name}`, // Displayed text
                  'data-value': `${cList.class_id}|${cList.subject_type}` // Value attribute
                })
              );
            });
          });

          // Event listener for when a class is selected
          $('#class-list').on('change', function (e) {
            e.preventDefault();
            const classValue = $('#dropdown-class').val();
            // Assuming you have a temporary input field or variable
            $("#temporary").val("Temporary Occupied: " + classValue); 
            $("#appended-remark").val("Temporary Occupied: " + classValue); 
            // Call any function you want to execute after selection, e.g., closeModal()
          });

        }


        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal); // Pass modal to closeModal function
        }); 

        // Event listener for the add product form submission
        $("#form-edit").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          updateClassStatus(classID, subType, classDay, roomStatus, faculty); // Call function to save product
        });
        
      },
      error: function (xhr, status, error) {
        alert("Failed to load class class statys modal!");
        console.error("Error loading modal on class-toggle.html:", status, error);
      }
    });
  }

  function updateClassStatus(classID, subType, classDay, roomStatus, faculty = null, varState = null, remark){
    if(faculty !== null){
      var updateUrl = `../faculty-occupy-class/update-class-status.php?classID=${classID}&subType=${subType}&classDay=${classDay}&roomStatus=${roomStatus}`;
    }else if(varState !== null && varState == 'Temporary Occupying'){//AUTO UPDATE
      // console.log("ERROR FREE !");
      // return;
      if(!classID && !subType && !classDay && !roomStatus && !remark){
        console.error("Error, data empty:", classID, subType, classDay, roomStatus);
        return;
      }
      var updateUrl = `../faculty-occupy-class/auto-reupdate.php?classID=${classID}&subType=${subType}&classDay=${classDay}&roomStatus=${roomStatus}&remark=${remark}`;
    }else{
      var updateUrl = `../faculty-class-list/update-class-status.php?classID=${classID}&subType=${subType}&classDay=${classDay}&roomStatus=${roomStatus}`;
    }

    // Debug what's being sent
    const formEdit = serializeForm("#form-edit");
    console.log("Sending data:", formEdit);
    
    $.ajax({
      type: "POST", // Use POST request
      url: updateUrl, // URL for saving room
      data: formEdit, // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        console.log("Response received:", response);
        if (response.status === "success") {
          alert('User list updated successfully.');
          // On success, hide modal and reset form
          if(faculty !== null){
            $("#staticBackdrop").modal("hide");
            $("#form-edit")[0].reset(); // Reset the form
            // Optionally, reload page to show new entry
            viewroomStatus();
          }else if(varState !== null && varState == 'Temporary Occupying'){
            viewroomStatus();
          }else{
            $("#staticBackdrop").modal("hide");
            $("#form-edit")[0].reset(); // Reset the form
            // Optionally, reload page to show new entry
            viewmyclassSchedule();
          }

          
        }else if (response.status === "error") {
          if (response.generalErr){
            $("#general-error").removeClass("d-none").html(cleanInput(response.generalErr));
          } else {
            $("#general-error").addClass("d-none");
          }

          if (response.class_listErr){
            $("#class-list").addClass("is-invalid");
            $("#class-list").siblings(".invalid-feedback").text(response.class_listErr).show();
          } else {
            $("#class-list").removeClass("is-invalid");
          }

          if(response.occupying_remarksErr){
            $("#appended-remark").addClass("is-invalid");
            $("#appended-remark").siblings(".invalid-feedback").html(cleanInput(response.occupying_remarksErr)).show();
          } else {
            $("#appended-remark").removeClass("is-invalid");
          }

        }else if (response.status === "ERROR") {
          alert("Failed to auto update class status!");
          console.error("Error Auto updating data on auto-reupdate.php:", status, error);
        }
      },
      error: function (xhr, status, error) {
        alert("Failed to update class status!");
        console.error("Error updating data on update-class-status.php:", status, error);
      }
    });

  }

  // Function to load analytics view
  function viewProfile() {
    $.ajax({
      type: "GET", // Use GET request
      url: "../profile/viewProfile.php", // URL for the analytics view
      dataType: "html", // Expect HTML response
      success: function (response) {
        $(".content-page").html(response); // Load the response into the content area
          // Call function to load the chart

        $("#table-profile").DataTable({
          dom: "rtp",
          pageLength: 10,
          ordering: false
        });

        $(".edit-user").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
      
          const button = $(this); // Reference to the clicked button
          button.prop("disabled", true); // Disable the button

          const accountID = $(this).data('id');
          // Call the AJAX function
          editProfile(accountID).always(function() {
            button.prop("disabled", false); // Re-enable the button after AJAX completes
          });
          
        });
        
      },
      error: function(xhr, status, error) {
        alert("Failed to load view profile content!");
        console.error("Error loading content on viewProfile.php:", status, error);
      }

    });
  }
  
  //load modal edit user list
  function editProfile(accountID) {
    // Split the composite ID into its parts
    return $.ajax({
      type: "GET", // Use GET request
      url: "../profile/edit-profile.html?v=" + new Date().getTime(), // URL 
      dataType: "html", // Expect JSON response
      success: function (view) {
        // Assuming 'view' contains the new content you want to display
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal
        const modal =  $('#staticBackdrop');

        // Then fetch and populate the data
        $.ajax({
          url: `../fetch-data/fetch-profile-record.php?accountID=${accountID}`, 
          dataType: "json",
          success: function(data) {
            console.log('Fetched data:', data);
            // Determine user role based on admin and staff status
            $('#account-id').val(data.account_id);
            $('#original-first-name').val(data.first_name);
            $('#original-last-name').val(data.last_name);
            $('#first-name').val(data.first_name);
            $('#last-name').val(data.last_name);
            console.log('Fetched data:', data);

          },
          error: function(xhr, status, error) {
            alert("Failed to fetch profile record!");
            console.error("Error fetching data on fetch-profile-record.php:", status, error);
          }
        });
      
        $('#show-password').on("change", function(){
          const passwordField = $('#password');
          if (this.checked) {
            passwordField.attr('type', 'text'); // Change to text to show password
          } else {
            passwordField.attr('type', 'password'); // Change back to password
          }
        });

        $('#show-confirm').on("change", function(){
          const passwordField = $('#confirm-password');
          if (this.checked) {
            passwordField.attr('type', 'text'); // Change to text to show password
          } else {
            passwordField.attr('type', 'password'); // Change back to password
          }
        });

        $('#change-password').on("change", function(){
          if (this.checked) {
            $('.div-password').show(); // Show all divs with class 'div-account'
            $('.password-input').prop('disabled', false); // Enable inputs
            $('#change-passkey').val('true');
          } else {
            $('.div-password').hide(); 
            $('.password-input').prop('disabled', true); // Enable inputs
            $('#change-passkey').val('false');
          }
        });

        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal);
        }); 

        $("#form-edit").on("submit", function (e) {
          e.preventDefault();
          updateProfile();
        });
      },
      error: function (xhr, status, error) {
        alert("Failed to load edit user list modal!");
        console.error("Error loading modal on edit-user.html:", status, error);
      }
    });
  }

  //update user from list
  function updateProfile(){
    // Debug what's being sent
    const formEdit = serializeForm("#form-edit");
    console.log("Sending data:", formEdit);
    
    $.ajax({
      type: "POST", // Use POST request
      url: "../profile/update-profile.php", // URL for saving room
      data: formEdit, // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        console.log("Response received:", response);
        if (response.status === "error") {
          // Handle validation errors
          if (response.generalErr){
            $("#general-error").removeClass("d-none").html(cleanInput(response.generalErr));
          } else {
            $("#general-error").addClass("d-none");
          }
        
          if(response.first_nameErr){
            $("#first-name").addClass("is-invalid");
            $("#first-name").siblings(".invalid-feedback").text(response.first_nameErr).show();
          } else {
            $("#first-name").removeClass("is-invalid");
          }

          if(response.last_nameErr){
            $("#last-name").addClass("is-invalid");
            $("#last-name").siblings(".invalid-feedback").text(response.last_nameErr).show();
          } else {
            $("#last-name").removeClass("is-invalid");
          }

          if(response.passwordErr){
            $("#password").addClass("is-invalid");
            $("#password").siblings(".invalid-feedback").text(response.passwordErr).show();
          } else {
            $("#password").removeClass("is-invalid");
          }

          if(response.confirm_passwordErr){
            $("#confirm-password").addClass("is-invalid");
            $("#confirm-password").siblings(".invalid-feedback").text(response.confirm_passwordErr).show();
          } else {
            $("#confirm-password").removeClass("is-invalid");
          }

        } else if (response.status === "success") {
          alert('User list updated successfully.');
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-edit")[0].reset(); // Reset the form
          // Optionally, reload page to show new entry
          viewProfile();
        }
      },
      error: function (xhr, status, error) {
        alert("Failed to update user profile!");
        console.error("Error updating data on update-profile.php:", status, error);
      }
    });
  }


  //Function to load room schedule view, load content
  function viewroomSchedule(roomName = null) {
    $.ajax({
      type: "GET", // Use GET request
      url: "../room-schedule/viewroom-schedule.php?v=" + new Date().getTime(), // URL for the analytics view
      dataType: "html", // Expect HTML response
      success: function (response) {
        $(".content-page").html(response); // Load the response into the content are
        
        
        console.log("roomName :", roomName);


        $.fn.dataTable.ext.errMode = 'none'; // Suppress DataTables error messages

        const semesterPK = $("#filter-room").data('semester');
        const semesterArr = semesterPK.split('|');
        var semester = semesterArr[0];
        let schoolYear = semesterArr[1];
        let semesterText = '';

        if(semester == '1'){
          semesterText = '1st Sem|';
        }else if(semester == '2'){
          semesterText = '2nd Sem|';
        }else{
          console.error("Error, semesterText is empty on function viewroomSchedule():", error);
        }

        $('#chosen-semester').text(semesterText + schoolYear);

        function getAllSchedules(roomID, semesterPK){
          $.ajax({
            url: `../fetch-schedule/fetch-class-schedule.php?selectedRoom=${roomID}&semesterPK=${semesterPK}`, 
            dataType: "json",
            success: function(data) {
              console.log('Fetched data:', data);
              if (data.error) { // Check for an error property in the JSON response
                alert(data.message || "An error occurred fetching the schedule."); // Display specific error message or a generic one
                console.error("Server-side error:", data.message);
                return; // Exit the success handler
              }
              populateTable(data);

            },
            error: function(xhr, status, error) {
              alert("Failed to fetch class schedule record!");
              console.error("Error fetching data on fetch-class-schedule.php:", status, error);
            }
            
          });
        // Example usage
        }

        function populateTable(schedules) {
          var timeSlots = [
            '06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
            '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
            '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30',
            '19:00', '19:30', '20:00'
          ];
      
          var daySlots = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
          
          let tableHead = $('#schedule-header');
          let tableBody = $('#schedule-data');
          tableBody.empty(); // Clear existing content
          tableHead.empty();

          for (let i = 0; i < 1; i++) {
            let time = 'Time';
            let tableRow = $('<tr></tr>');
            tableRow.append('<th class="border-start border-end dt-column-title">' + time + '</th>');

            for (let j = 0; j < daySlots.length; j++) {
              let day = daySlots[j];
              tableRow.append('<th class="border-start border-end dt-column-title" data-day="' + day + '">' + day + '</th>');

            }
            tableHead.append(tableRow);
          }

          if(!schedules){
            for (let i = 0; i < timeSlots.length; i++) {
              let time = timeSlots[i];
              let tableRow = $('<tr></tr>');
              tableRow.attr('data-time', time);
              tableRow.append('<td class="border-start border-end">' + time + '</td>');

              for (let j = 0; j < daySlots.length; j++) {
                let day = daySlots[j];
                let tableCell = $('<td></td>');
                tableCell.addClass('border-start border-end');
                tableCell.attr('data-day', day);

                tableRow.append(tableCell);
              }
              tableBody.append(tableRow);
            }
            $('#room-name-title').text('SELECT A ROOM TO FILTER');

            return;
          }

          tableBody.empty(); // Clear existing content

          let filledCells = {};
          let classCount = 0;

          for (var i = 0; i < timeSlots.length; i++) {
            let time = timeSlots[i];
            let tableRow = $('<tr></tr>');
            tableRow.attr('data-time', time);
            tableRow.append('<td class="border-start border-end">' + time + '</td>');

            for (var j = 0; j < daySlots.length; j++) {
              let day = daySlots[j];
              
              // Skip if this cell is already filled
              if (filledCells[day + '-' + i]) {
                continue; // Skip this day if the time slot is filled
              }

              let tableCell = $('<td></td>');
              tableCell.addClass('border-start border-end');
              tableCell.attr('data-day', day);

              let foundClass = false;
              for (var k = 0; k < schedules.length; k++) {
                let schedule = schedules[k];
                if (schedule.class_day.toLowerCase() === day.toLowerCase() &&
                  time >= schedule.start_time && time < schedule.end_time) {
                  
                  // Calculate rowspan
                  let rowSpan = 1;
                  for (var l = i + 1; l < timeSlots.length; l++) {
                    if (timeSlots[l] >= schedule.start_time && timeSlots[l] < schedule.end_time) {
                      rowSpan++;
                      filledCells[day + '-' + l] = true; // Mark this cell as filled
                    } else {
                      break; // Stop if we reach a time not in the range
                    }
                  }

                  // Render the cell with rowspan
                  tableCell.attr('rowspan', rowSpan);
                  tableCell.html(schedule.subject_code + '<br>' + schedule.section_name + '<br>' + schedule.instructor_name);
                  tableCell.addClass('class-scheduled');
                  foundClass = true;

                  classCount++;
                  // Mark all subsequent rows as filled
                  for (var m = 1; m < rowSpan; m++) {
                    filledCells[day + '-' + (i + m)] = true; // Mark subsequent rows
                  }
                  break; // Exit the loop after placing the class
                }
              }

              if (!foundClass) {
                tableCell.html(''); // Empty cell if no class is scheduled
              }

              if(classCount == 0){//schedule feed, empty
                $("#schedule-count").show();
              }else{
                $("#schedule-count").hide();
              }

              tableRow.append(tableCell);
            }
            tableBody.append(tableRow);
          }
        } 
        
        $("#filter-room").on("submit", function (e) {
          e.preventDefault(); // Prevent default behavior
          const roomID = $('#room').val();
        
          if (roomID) {
            $('#room-name-title').text($('#room option:selected').text());
            // Update room name title
            getAllSchedules(roomID, semesterPK);
          }
           // Call function to add product
        });

        if(roomName !== undefined){
          $('#room').val(roomName);
          $('#filter-room').trigger("submit");
        }

        populateTable();//call to load time

        let table = $("#table-room-schedule").DataTable({
          dom: "rtp", // Set DataTable options
          pageLength: 29, // Default page length
          ordering: false,
          scrollY: "calc(100vh - 100px)",
          scrollCollapse: true,
          language: {
              "emptyTable": "",
              "zeroRecords": "" 
          }
        });
  
      },
      error: function(xhr, status, error) {
        alert("Failed to load room schedule content!");
        console.error("Error loading content on viewroom-schedule.php:", status, error);
      }
    });
  }

  //FUNCTION USER LIST
  //Function to load user list view, load content
  function viewuserList() {
    $.ajax({
      type: "GET", // Use GET request
      url: "../admin/user-list/viewuser-list.php", // URL for the analytics view
      dataType: "html", // Expect HTML response
      success: function (response) {
        $(".content-page").html(response); // Load the response into the content area
         // Call function to load the chart

        let table = $("#table-user-list").DataTable({
          dom: "rtp",
          pageLength: 10,
          ordering: false,
          drawCallback: function(){
            
            $(".edit-user").on("click", function (e) {
              e.preventDefault(); // Prevent default behavior
              const button = $(this); // Reference to the clicked button
              button.prop("disabled", true); // Disable the button
              
              const userID = $(this).data('id');
              // Call the AJAX function
              editUserList(userID).always(function() {
                button.prop("disabled", false); // Re-enable the button after AJAX completes
              });

            });

            $(".delete-user").on("click", function (e) {
              e.preventDefault(); // Prevent default behavior
              const button = $(this); // Reference to the clicked button
              button.prop("disabled", true); // Disable the button
              
              const userID = $(this).data('id');
              // Call the AJAX function
              deletingUser(userID).always(function() {
                button.prop("disabled", false); // Re-enable the button after AJAX completes
              });

            });

          }
        });
        
        // Bind custom input to DataTable search
        $("#search-subject").on("keyup", function () {
          table.search(this.value).draw(); // Search room based on input
        });

        $("#user-type").on("change", function () {
          const filterRole = $('#user-type').val();
          
          table.column(4).search(filterRole).draw(); // Added draw() to refresh the table
        });


        $("#add-user").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          addUserList(); // Call function to add 
        });


        
      },
      error: function(xhr, status, error) {
        alert("Failed to load user list content!");
        console.error("Error loading content on viewuser-list.php:", status, error);
      }
    });
  }

  //load modal add user list
  function addUserList() {
    $.ajax({
      type: "GET", // Use GET request
      url: "../admin/user-list/add-user.html?v=" + new Date().getTime(), // URL for add product view
      dataType: "html", // Expect HTML response
      success: function (view) {
        $(".modal-container").html(view); // Load the modal view
        $("#staticBackdrop").modal("show");
        
        const modal = $('#staticBackdrop');
        console.log("Modal content loaded successfully.");

        $('#show-password').on("change", function(){
          const passwordField = $('#password');
          if (this.checked) {
            passwordField.attr('type', 'text'); // Change to text to show password
          } else {
            passwordField.attr('type', 'password'); // Change back to password
          }
        });

        $('#user-role').on("change", function() {
          const selectedOption = $(this).val();
          // Show or hide the divs based on selected option
          let displayedText = '';
          if(selectedOption == "faculty"){
            displayedText = 'Account Details for Faculty';
          }else if(selectedOption =="admin-faculty"){
            displayedText = 'Account Details for Admin-Faculty';
          }else if(selectedOption =="admin"){
            displayedText = 'Account Details for Admin';
          }

          $('#account-for').text(displayedText);

          if (selectedOption === 'faculty' || 
            selectedOption === 'admin-faculty' || 
            selectedOption === 'admin') {
            
            $('.div-account').show(); // Show all divs with class 'div-account'
            $('.account-input').prop('disabled', false); // Enable inputs
            $('#determiner').val('true');
          } else {
            $('.div-account').hide(); // Hide all divs with class 'div-account'
            $('.account-input').prop('disabled', true); // Disable inputs
            $('#determiner').val('false');
          }
        });

        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal); // Pass modal to closeModal function
        }); 

        // Event listener for the add product form submission
        $("#form-add").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission


          saveUser(); // Call function to save product
        });
        
      },
      error: function (xhr, status, error) {
        alert("Failed to load add user modal!");
        console.error("Error loading modal on add-user.html:", status, error);
      }
    });
  }

  //save user from user list
  function saveUser(){
    // Debug what's being sent
    const formAdd = serializeForm("#form-add");
    console.log("Sending data:", formAdd);
    
    $.ajax({
      type: "POST", // Use POST request
      url: "../admin/user-list/save-user.php?", // URL for saving room
      data: formAdd, // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        console.log("Response received:", response);
        if (response.status === "error") {
          // Handle validation errors 
          if (response.generalErr){
            $("#general-error").removeClass("d-none").html(cleanInput(response.generalErr));
          } else {
            $("#general-error").addClass("d-none");
          }
          
          if (response.user_idErr){
            $("#user-id").addClass("is-invalid");
            $("#user-id").siblings(".invalid-feedback").text(response.user_idErr).show();
            $("#example-id").show();
            $("#example-id").addClass("text-danger");
          } else {
            $("#user-id").removeClass("is-invalid");
            $("#example-id").removeClass("text-danger");
            $("#example-id").hide();
          }

          if (response.usernameErr){
            $("#username").addClass("is-invalid");
            $("#username").siblings(".invalid-feedback").text(response.usernameErr).show();
            $("#example-username").show();
            $("#example-username").addClass("text-danger");
          } else {
            $("#username").removeClass("is-invalid");
            $("#example-username").removeClass("text-danger");
            $("#example-username").hide();
          }
          
          if(response.user_roleErr){
            $("#user-role").addClass("is-invalid");
            $("#user-role").siblings(".invalid-feedback").text(response.user_roleErr).show();
          } else {
            $("#user-role").removeClass("is-invalid");
          }

          //account Error validations
          if (response.generalErr1){
            $("#general-error-1").removeClass("d-none").html(cleanInput(response.generalErr1));
          } else {
            $("#general-error-1").addClass("d-none");
          }

          if(response.first_nameErr){
            $("#first-name").addClass("is-invalid");
            $("#first-name").siblings(".invalid-feedback").text(response.first_nameErr).show();
          } else {
            $("#first-name").removeClass("is-invalid");
          }

          if(response.last_nameErr){
            $("#last-name").addClass("is-invalid");
            $("#last-name").siblings(".invalid-feedback").text(response.last_nameErr).show();
          } else {
            $("#last-name").removeClass("is-invalid");
          }

          if(response.passwordErr){
            $("#password").addClass("is-invalid");
            $("#password").siblings(".invalid-feedback").text(response.passwordErr).show();
          } else {
            $("#password").removeClass("is-invalid");
          }

        } else if (response.status === "success") {
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-add")[0].reset(); // Reset the form
          // Optionally, reload page to show new entry
          viewuserList();
        }
      },
      error: function (xhr, status, error) {
        alert("Failed to add user!");
        console.error("Error saving data on save-user.php:", status, error);
       }

    });
  }

  //load modal edit user list
  function editUserList(userID) {
    // Split the composite ID into its parts
    return $.ajax({
      type: "GET", // Use GET request
      url: "../admin/user-list/edit-user.html?v=" + new Date().getTime(), // URL 
      dataType: "html", // Expect JSON response
      success: function (view) {
        // Assuming 'view' contains the new content you want to display
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal
        const modal =  $('#staticBackdrop');

        // Then fetch and populate the data
        $.ajax({
          url: `../fetch-data/fetch-user-record.php?userID=${userID}`, 
          dataType: "json",
          success: function(data) {
            console.log('Fetched data:', data);
            $('#original-user-id').val(userID);
            $('#user-id').val(userID);

            $('#original-username').val(data.username);
            $('#username').val(data.username);
            
            // Determine user role based on admin and staff status
            let is_admin = data.is_admin; // This will be a string
            let is_staff = data.is_staff; // This will be a string
            let user_role = '';

            if (is_admin == '1' || is_staff == '1') {
              if (is_admin == '1' && is_staff == '0') {
                user_role = 'admin';
              } else if (is_admin == '0' && is_staff == '1') {
                user_role = 'faculty';
              } else if (is_admin == '1' && is_staff == '1') {
                user_role = 'admin-faculty';
              }
              $('#original-first-name').val(data.first_name);
              $('#original-last-name').val(data.last_name);
              $('#first-name').val(data.first_name);
              $('#last-name').val(data.last_name);
              
            } else if (is_admin == '0' && is_staff == '0') {
              user_role = 'student';
            } else {
              console.error("Error fetching data: unexpected values for is_admin or is_staff.", {
                is_admin: is_admin,
                is_staff: is_staff
              });
            }
            //select
            $('#user-role').val(user_role).trigger("change");
    
          },
          error: function(xhr, status, error) {
            alert("Failed to fetch user list record!");
            console.error("Error fetching data on fetch-user-record.php:", status, error);
          }
        });
        
        $('#user-role').on("change", function() {
          const selectedOption = $(this).val();
          // Show or hide the divs based on selected option
          let displayedText = '';
          if(selectedOption == "faculty"){
            displayedText = 'Account Details for Faculty';
          }else if(selectedOption =="admin-faculty"){
            displayedText = 'Account Details for Admin-Faculty';
          }else if(selectedOption =="admin"){
            displayedText = 'Account Details for Admin';
          }

          $('#account-for').text(displayedText);

          if (selectedOption === 'faculty' || 
            selectedOption === 'admin-faculty' || 
            selectedOption === 'admin') {
            
            $('.div-account').show(); // Show all divs with class 'div-account'
            $('.account-input').prop('disabled', false); // Enable inputs
            $('#determiner').val('true');
          } else {
            $('.div-account').hide(); // Hide all divs with class 'div-account'
            $('.account-input').prop('disabled', true); // Disable inputs
            $('#determiner').val('false');
          }
        });

        $('#show-password').on("change", function(){
          const passwordField = $('#password');
          if (this.checked) {
            passwordField.attr('type', 'text'); // Change to text to show password
          } else {
            passwordField.attr('type', 'password'); // Change back to password
          }
        });

        $('#change-password').on("change", function(){
          if (this.checked) {
            $('.div-password').show(); // Show all divs with class 'div-account'
            $('#change-passkey').val('true');
          } else {
            $('.div-password').hide(); 
            $('#change-passkey').val('false');
          }
        });

        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal);
        }); 

        $("#form-edit").on("submit", function (e) {
          e.preventDefault();
          updateUser();
        });
      },
      error: function (xhr, status, error) {
        alert("Failed to load edit user list modal!");
        console.error("Error loading modal on edit-user.html:", status, error);
      }
    });
  }

  //update user from list
  function updateUser(){
    // Debug what's being sent
    const formEdit = serializeForm("#form-edit");
    console.log("Sending data:", formEdit);
    
    $.ajax({
      type: "POST", // Use POST request
      url: "../admin/user-list/update-user.php", // URL for saving room
      data: formEdit, // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        console.log("Response received:", response);
        if (response.status === "error") {
          // Handle validation errors
          if (response.generalErr){
            $("#general-error").removeClass("d-none").html(cleanInput(response.generalErr));
          } else {
            $("#general-error").addClass("d-none");
          }
          
          if (response.user_idErr){
            $("#user-id").addClass("is-invalid");
            $("#user-id").siblings(".invalid-feedback").text(response.user_idErr).show();
            $("#example-id").show();
            $("#example-id").addClass("text-danger");
          } else {
            $("#user-id").removeClass("is-invalid");
            $("#example-id").removeClass("text-danger");
            $("#example-id").hide();
          }

          if (response.usernameErr){
            $("#username").addClass("is-invalid");
            $("#username").siblings(".invalid-feedback").text(response.usernameErr).show();
            $("#example-username").show();
            $("#example-username").addClass("text-danger");
          } else {
            $("#username").removeClass("is-invalid");
            $("#example-username").removeClass("text-danger");
            $("#example-username").hide();
          }
          
          if(response.user_roleErr){
            $("#user-role").addClass("is-invalid");
            $("#user-role").siblings(".invalid-feedback").text(response.user_roleErr).show();
          } else {
            $("#user-role").removeClass("is-invalid");
          }

          //account Error validations
          if (response.generalErr1){
            $("#general-error-1").removeClass("d-none").html(cleanInput(response.generalErr1));
          } else {
            $("#general-error-1").addClass("d-none");
          }

          if(response.first_nameErr){
            $("#first-name").addClass("is-invalid");
            $("#first-name").siblings(".invalid-feedback").text(response.first_nameErr).show();
          } else {
            $("#first-name").removeClass("is-invalid");
          }

          if(response.last_nameErr){
            $("#last-name").addClass("is-invalid");
            $("#last-name").siblings(".invalid-feedback").text(response.last_nameErr).show();
          } else {
            $("#last-name").removeClass("is-invalid");
          }

          if(response.passwordErr){
            $("#password").addClass("is-invalid");
            $("#password").siblings(".invalid-feedback").text(response.passwordErr).show();
          } else {
            $("#password").removeClass("is-invalid");
          }

        } else if (response.status === "success") {
          alert('User list updated successfully.');
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-edit")[0].reset(); // Reset the form
          // Optionally, reload page to show new entry
          viewuserList();
        }
      },
      error: function (xhr, status, error) {
        alert("Failed to update user list!");
        console.error("Error updating data on update-user.php:", status, error);
      }
    });
  }

  //load modal delete user list
  function deletingUser(userID){
    return $.ajax({
      type: "GET", // Use GET request
      url: "../admin/user-list/deleting-user.html?v=" + new Date().getTime(), // URL to get product data
      dataType: "html", // Expect JSON response
      success: function (view) {
        // Assuming 'view' contains the new content you want to display
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal
        $("#staticBackdroped").attr("data-id");

        const modal = $('#staticBackdrop');
      
        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal); // Pass modal to closeModal function
        }); 

        // Event listener for the add product form submission
        $("#form-delete").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          deleteUser(userID); // Call function to save product
        });
      },
      error: function(xhr, status, error) {
        alert("Failed loading delete class detail modal!");
        console.error("Error loading modal on deleting-subjects-details.html:", status, error);
      }

    });
  } 

  //delete user from list
  function deleteUser(userID){
    const submitButton = $("#form-delete button[type='submit']");
    submitButton.prop('disabled', true);
    
    const formDelete = serializeForm("#form-delete");
    console.log("Sending data:", formDelete);

    $.ajax({
      type: "POST", // Use POST request
      url: `../admin/user-list/delete-user.php?userID=${userID}`, // URL for saving room
      data: formDelete, // Serialize the form data for submission, Add ID to form data
      dataType: "json", // Expect JSON response
      success: function (response) {
        console.log("Response received:", response);
        if (response.status === "success") {
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-delete")[0].reset(); // Reset the form
          // Optionally, reload page to show new entry
          alert("User deleted successful!");

          viewuserList();
        }
      },
      error: function(xhr, status, error) {
        alert("Failed to delete class detail!");
        console.error("Error deleting data on delete-class-details.php:", status, error);
      }

    });
  }


    // // Event listener for the room-schedule link
    // $(".room-schedule").on("click", function (e) {
    //   e.preventDefault(); // Prevent default behavior if needed
      
    //   let roomName = $(this).data('room'); 
    //   $("#roomschedule-link").data('room', roomName);
    //   // console.log("roomName:", globalRoom);
    //   const newUrl = "room-schedule"; // Assuming href is "room-schedule"
    
    //   window.history.pushState({}, '', newUrl);

    //   let functionRef = viewroomSchedule;
    //   // Check if semester is picked for this session
    //   checkSemester(functionRef, roomName);
    //           // console.log("url:", newUrl);
    // });

  

  //FUNCTIONS FOR PAGE ROOM-LIST
  // Function to load room list
  function viewroomList() {
    $.ajax({
      type: "GET", // Use GET request
      url: "../room-list/viewroomlist.php", // URL for the analytics view
      dataType: "html", // Expect HTML response
      success: function (response) {
        $(".content-page").html(response); // Load the response into the content area
         // Call function to load the chart
        localStorage.setItem("selectedRoom", "");

        var table = $("#table-room-list").DataTable({
          dom: "rtp",
          pageLength: 10,
          ordering: false
        });
        
        // Bind custom input to DataTable search
        $("#custom-search").on("keyup", function () {
          table.search(this.value).draw(); // Search room based on input
        });
        

        $("#add-room").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          addRoom(); // Call function to add product
        });


        $(".room-status").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          // editRoom(); // Call the function to load products
        });

        
        $(".room-schedule").on("click", function(e){
          // e.preventDefault(); // Prevent default behavior
          const newUrl = "room-schedule"; // Assuming href is "room-schedule"
          let roomName= $(this).data('room'); 
          setSelectedRoom(roomName);
          // console.log("room:", roomName);
          // window.location.href = newUrl;
          window.location.assign(newUrl);
          // window.history.pushState({}, '', newUrl);
        }); // Trigger the roomschedule click event
  

        $(".edit-room").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
      
          const button = $(this); // Reference to the clicked button
          button.prop("disabled", true); // Disable the button
          
          const roomCode = $(this).data('roomcode');
          const roomNo = $(this).data('roomno');

           // Call the AJAX function
          // editRoom(this.dataset.id);
          // Call the AJAX function
          editRoom(roomCode, roomNo).always(function() {
            button.prop("disabled", false); // Re-enable the button after AJAX completes
          });

        });
        
      },
      error: function(xhr, status, error) {
        alert("Failed to load view room list content!");
        console.error("Error loading content on viewroomlist.php:", status, error);
      }
    });
  }

  //load modal add room details
  function addRoom() {
    $.ajax({
      type: "GET", // Use GET request
      url: "../admin/room-list-modals/add.html?v=" + new Date().getTime(), // URL for add product view
      dataType: "html", // Expect HTML response
      success: function (view) {
        $(".modal-container").html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal

        const modal = $('#staticBackdrop');

        // fetchroomType(); // Load room type for the select input
        const rtypeText = $('#dropdown-room-type');
        const rtypeId = $('#hidden-room-type-id');
        const rtypeList = $('#dropdown-list-room-type');
        customDropdown(rtypeText, rtypeList, rtypeId, "../fetch-data/fetch-roomtype.php", function(data, dropdownList) {
          $.each(data, function(index, rtype) {
              dropdownList.append(
                  $('<div>', {
                      text: rtype.room_type_desc, // Displayed text
                      'data-value': rtype.type_id // Value attribute
                  })
              );
          });
        });

        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal); // Pass modal to closeModal function
        }); 

        // Event listener for the add  form submission
        $("#form-add").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          saveRoom(); // Call function to save data
        });
      },
      error: function (xhr, status, error) {
        alert("Failed to load add room modal!");
        console.error("Error loading modal on add.html:", status, error);
      }

    });
  }

  //save room details
  function saveRoom(){
    const formAdd = serializeForm("#form-add");
    console.log("Sending data:", formAdd);

    $.ajax({
      type: "POST", // Use POST request
      url: "../admin/room-list-modals/save-room.php", // URL for saving room
      data: formAdd, // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        if (response.status === "error") {
          // Handle validation errors
          if (response.generalErr){
            $("#general-error").removeClass("d-none").html(cleanInput(response.generalErr));
          } else {
            $("#general-error").addClass("d-none");
          }

          if (response.room_nameErr){
            $("#room-name").addClass("is-invalid"); // Mark field as invalid
            $("#room-name").siblings(".invalid-feedback").text(response.room_nameErr).show(); // Show error message
          } else {
            $("#room-name").removeClass("is-invalid"); // Remove invalid class if no error
          }
          
          if (response.room_typeErr){
            $("#dropdown-room-type").addClass("is-invalid"); // Mark field as invalid
            $("#dropdown-room-type").siblings(".invalid-feedback").text(response.room_typeErr).show(); // Show error message
          } else {
            $("#dropdown-room-type").removeClass("is-invalid"); // Remove invalid class if no error
          }
          
        } else if (response.status === "success") {
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("form")[0].reset(); // Reset the form
          // Optionally, reload roomlist to show new entry
          viewroomList();
        }
      },
      error: function (xhr, status, error) {
        alert("Failed to add room details!");
        console.error("Error saving data on save-room.php:", status, error);
      }

    });
  }
  
  //load modal edit room details
  function editRoom(roomCode, roomNo) {
    return $.ajax({
      type: "GET", // Use GET request
      url: "../admin/room-list-modals/edit.php?v=" + new Date().getTime(), // URL to get product data
      dataType: "html", // Expect JSON response
      success: function (view) {
        // Assuming 'view' contains the new content you want to display
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal
        $("#staticBackdroped").attr("data-id", roomCode, roomNo);

        const modal = $('#staticBackdrop');

          //function to fetch record list of room
        $.ajax({
          url: `../fetch-data/fetch-room.php?roomCode=${roomCode}&roomNo=${roomNo}`, //2 parameters separated by &
          dataType: "json",
          success: function(data) {
              console.log('Fetched data:', data);
             
              $('#room-name').val(data.room_name);
              $('#dropdown-room-type').val(data.room_type);
              $('#hidden-room-type-id').val(data.room_code);

          },
          error: function(xhr, status, error) {
            alert("Failed to fetch room details record!");
            console.error("Error fetching data on fetch-room.php:", status, error);
          }
          
        });


        const rtypeText = $('#dropdown-room-type');
        const rtypeId = $('#hidden-room-type-id');
        const rtypeList = $('#dropdown-list-room-type');
        customDropdown(rtypeText, rtypeList, rtypeId, "../fetch-data/fetch-roomtype.php", function(data, dropdownList) {
          $.each(data, function(index, rtype) {
              dropdownList.append(
                  $('<div>', {
                      text: rtype.room_type_desc, // Displayed text
                      'data-value': rtype.room_type_id // Value attribute
                  })
              );
          });
        });

        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal); // Pass modal to closeModal function
        }); 

        // Event listener for the add product form submission
        $("#form-edit").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          updateRoom(roomCode, roomNo); // Call function to save product
        });
      },
      error: function (xhr, status, error) {
        alert("Failed to load edit room details modal!");
        console.error("Error loading modal on edit.php:", status, error);
      }
    });
  }

  //update room details
  function updateRoom(roomCode, roomNo) {
    // Debug what's being sent
    const formEdit = serializeForm("#form-edit");
    console.log("Sending data:", formEdit);

    $.ajax({
      type: "POST", // Use POST request
      url: `../admin/room-list-modals/update-room.php?roomCode=${roomCode}&roomNo=${roomNo}`, // URL for saving room
      data: formEdit, // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        if (response.status === "error") {
          // Handle validation errors
          if (response.generalErr){
            $("#general-error").removeClass("d-none").html(cleanInput(response.generalErr));
          } else {
            $("#general-error").addClass("d-none");
          }

          if (response.room_nameErr) {
            $("#room-name").addClass("is-invalid"); // Mark field as invalid
            $("#room-name").siblings(".invalid-feedback").text(response.room_nameErr).show(); // Show error message
          } else {
            $("#room-name").removeClass("is-invalid"); // Remove invalid class if no error
          }
          
          if (response.room_typeErr) {
            $("#dropdown-room-type").addClass("is-invalid");
            $("#dropdown-room-type")
              .siblings(".invalid-feedback")
              .text(response.room_typeErr)
              .show();
          } else {
            $("#dropdown-room-type").removeClass("is-invalid");
          }
          
        } else if (response.status === "success") {
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("form")[0].reset(); // Reset the form
          // Optionally, reload products to show new entry
          viewroomList();
        }
      },
      error: function (xhr, status, error) {
        alert("Failed to edit room details!");
        console.error("Error updating data on update-room.php:", status, error);
      }
    });
  }


  //FUNCTIONS FOR PAGE CLASS-STATUs || ROOM-STATUS
  //Function to load room status view
  function viewroomStatus(){
    $.ajax({
      type: "GET", // Use GET request
      url: "../room-status/viewclass-status.php", // URL for the analytics view
      dataType: "html", // Expect HTML response
      success: function (response) {
        $(".content-page").html(response); // Load the response into the content area

        //FOR SUBJECT TABLE --start
        const selectProspectus = document.getElementById("dropdown-subject-prospectus");
        var countDefault = false;

        $('#prospectus-text').text(selectProspectus.value);

        function setProspectus() {
          $.ajax({
            type: "POST", // Use POST request
            url: "../fetch-data/fetch-selected-semester.php", // URL to your PHP script that handles the request
            data: { count_default: countDefault},
            dataType: 'json',
            success: function(response) {
              if (response.status === "success"){
                countDefault = true;
                if(response.countDefault){
                  const defaultProspectus = '2023-2024';
                  selectProspectus.value = defaultProspectus;
                  fetchSubjectByProspectus(); // Fetch data for the current day
                }else{
                  selectProspectus.value;
                  fetchSubjectByProspectus();
                }

              }
            },
            error: function(xhr, status, error) {
              alert("Failed to fetch selected semester!");
              console.error("Error fetching data on fetch-selected-semester.php:", status, error);
            }

          });
        }

        function fetchSubjectByProspectus(){
          const selectedProspectus = selectProspectus.value;
          // Make an AJAX call to fetch data based on the selected day
          $.ajax({
            type: "POST", // Use POST request
            url: "../fetch-data/fetch-subject-details.php", // URL to your PHP script that handles the request
            data: { selected_prospectus: selectedProspectus }, // Send selected day as data
            // dataType: 'json',
            success: function(response) {
              console.log("Selected option:", selectedProspectus);
              // Update the table body with the fetched data
              $("#table-subject-details tbody").html(response);
              
            },
            error: function(xhr, status, error) {
              alert("Failed to fetch subject details based on prospectus filter!");
              console.error("Error fetching data on fetch-subject-details.php:", status, error);
            }
          });
        }
        

        $('#table-subject-details').on('click', '.edit-subject-details, .delete-subject-details', function(e) {
          e.preventDefault();
          // const button = $(this);
          // button.prop("disabled", true);  

          $(".edit-subject-details").off('click').on("click", function(e) {
            e.preventDefault();
            const button = $(this);
            button.prop("disabled", true);
            
            const subjectID = $(this).data('subjectid');
            
            editSubjectDetails(subjectID, selectProspectus.value).always(function() {
              button.prop("disabled", false);
            });
          });

          $(".delete-subject-details").off('click').on("click", function(e) {
            e.preventDefault();
            const button = $(this);
            button.prop("disabled", true);
            
            const subjectID = $(this).data('subjectid');
          
            deletingSubjectDetails(subjectID, selectProspectus.value).always(function() {
                button.prop("disabled", false);
            });
          });
          
        });

        function initializeSubjectDetails() {
          if ($.fn.DataTable.isDataTable('#table-subject-details')) {
              $('#table-subject-details').DataTable().destroy();
          }
          selectProspectus.addEventListener("change", fetchSubjectByProspectus);

          var tableSubject = $("#table-subject-details").DataTable({
            dom: "rtp",
            pageLength: 10,
            ordering: false,
          });
          // Then bind the search event
          $("#search-subject").on("keyup", function () {
            console.log("Search triggered", this.value);
            console.log("Table instance:", tableSubject);
            tableSubject.search(this.value).draw();
          });

        }
        
        //ADD SUBJECT DETAILS
        $("#add-subject-details").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          addsubjectDetails(selectProspectus.value);
        });
        
        //ADD PROSPECTUS
        $("#add-subject-prospectus").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          // addsubjectDetails(selectProspectus.value);
        });

        // initialize
        setProspectus();
        
        selectProspectus.addEventListener("change", function() {
          fetchSubjectByProspectus(); // Call the function to fetch subjects
          $('#prospectus-text').text(selectProspectus.value); // Update the text
        });
        initializeSubjectDetails();
        //--end

        //FOR CLASS DETAILS TABLE//---start
        // Initialize DataTable for class details first
        var tableDetails = $("#table-class-details").DataTable({
          dom: "rtp",
          pageLength: 10,
          ordering: false,
          drawCallback: function() {
            const semesterPK = $('#table-room-status').data('semester');
            let splitSemester = semesterPK.split('|');
            let semesterID = splitSemester[0];
            let schoolYear = splitSemester[1];
            // console.error("TEST VAR SEMESTER :", semesterPK);

            // First binding here
            $(".edit-class-details").off('click').on("click", function(e) {
              e.preventDefault();
              const button = $(this);
              button.prop("disabled", true);
              
              const classId = $(this).data('classid');
              const subType = $(this).data('subtype');
              
              editclassDetails(classId, subType, selectProspectus.value).always(function() {
                  button.prop("disabled", false);
              });
            });

            $(".delete-class-details").off('click').on("click", function(e) {
              e.preventDefault();
              const button = $(this);
              button.prop("disabled", true);
              
              const classId = $(this).data('classid');
              const subType = $(this).data('subtype');

              deletingclassDetails(classId, subType, semesterID, schoolYear).always(function() {
                  button.prop("disabled", false);
              });
            });
          }
        });

        // Then bind the search event
        $("#search-class-details").on("keyup", function () {
          console.log("Search triggered", this.value);
          console.log("Table instance:", tableDetails);
          tableDetails.search(this.value).draw();
        });

        // Get the select element
        const selectDay = document.getElementById("day");
        
        // Function to set the current day in the dropdown, local real-time
        // Function to set the current day in the dropdown, local real-time
        function setCurrentDay() {
          const options = ["Sunday","Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
          const currentDayIndex = new Date().getDay();  //
          const currentDay = options[currentDayIndex]; // Get current day name

          // Set the dropdown value to the current day
          selectDay.value = currentDay;
          fetchDayData(); // Fetch data for the current day
        }

        function fetchDayData(){
          const selectedDay = selectDay.value;
          // Make an AJAX call to fetch data based on the selected day
          $.ajax({
            type: "POST", // Use POST request
            url: "../fetch-data/fetch-scheduled-classday.php", // URL to your PHP script that handles the request
            data: { selected_day: selectedDay }, // Send selected day as data
            // dataType: 'json',
            success: function(response) {
              console.log("Selected option:", selectedDay);

              // Update the table body with the fetched data
              $("#table-room-status tbody").html(response);

            },
            error: function(xhr, status, error) {
              alert("Failed to fetch class status based on day filter!");
              console.error("Error fetching data on fetch-scheduled-classday:", status, error);
            }
          });
        }

        //setup to check if table row has the right remark condition
        $('#day').on("change", function(){
          setupInterval();
        });


        //Custom Dropdown ROOM FORM
        const roomText = $('#dropdown-room-name');
        const roomId = $('#hidden-room-id');
        const roomList = $('#dropdown-list-room-name');
        customDropdown(roomText, roomList, roomId, "../fetch-data/fetch-room-name.php", function(data, dropdownList) {
          $.each(data, function(index, room) {
            dropdownList.append(
              $("<div>", {
                text: room.room_name, // Displayed text
                'data-value': `${room.room_code}|${room.room_no}` // Value attribute
              })
            );
          });
        });

  
        const rtypeText = $('#dropdown-room-type');
        const rtypeId = $('#hidden-room-type-id');
        const rtypeList = $('#dropdown-list-room-type');
        customDropdown(rtypeText, rtypeList, rtypeId, "../fetch-data/fetch-roomtype.php", function(data, dropdownList) {
          $.each(data, function(index, rtype) {
            dropdownList.append(
              $("<div>", {
                text: rtype.rtype_desc, // Displayed text
                'data-value': rtype.type_id // Value attribute
              })
            );
          });
        });

        const roomstatusText= $('#dropdown-room-status');
        const roomstatusList = $('#dropdown-list-room-status');
        const roomstatusId = $('#hidden-room-status-id');
        customDropdown(roomstatusText, roomstatusList, roomstatusId, "", function(data, dropdownList) {
          var statusArr = ['OCCUPIED', 'AVAILABLE'];
          
          $.each(statusArr, function (index, roomstatus) {
            dropdownList.append(
              $("<div>", {
                text:roomstatus, // Displayed text
                'data-value': roomstatus // Value attribute
              })
            );
          });
        });

        //Custom Dropdown CLASS FORM
        const subjectText= $('#dropdown-subject');
        const subjectList = $('#dropdown-list-subject');
        const subjectId = $('#hidden-subject-id');
        customDropdown(subjectText, subjectList, subjectId, "../fetch-data/fetch-subject.php", function(data, dropdownList) {
          $.each(data, function (index, subject) {
            dropdownList.append(
              $("<div>", {
                text:subject.subject_id, // Displayed text
                'data-value': subject.subject_id // Value attribute
              })
            );
          });
        });

        const subtypeText= $('#dropdown-subject-type');
        const subtypeList = $('#dropdown-list-subject-type');
        const subtypeId = $('#hidden-subject-type');
        customDropdown(subtypeText, subtypeList, subtypeId, null, function(data, dropdownList) {
          var subtypeArr = ['LEC', 'LAB'];
          
          $.each(subtypeArr, function (index, subtype) {
            dropdownList.append(
              $("<div>", {
                text:subtype, // Displayed text
                'data-value': subtype // Value attribute
              })
            );
          });
        });

        const sectionText= $('#dropdown-section');
        const sectionList = $('#dropdown-list-section');
        const sectionId = $('#hidden-section-id');
        customDropdown(sectionText, sectionList, sectionId, "../fetch-data/fetch-section.php", function(data, dropdownList) {
          $.each(data, function (index, section) {
            const displayContent = cleanInput(`${section.course_abbr}${section.year_level}${section.section}`);
            dropdownList.append(
              $("<div>", {
                text: displayContent, // Displayed text
                'data-value': `${section.course_abbr}|${section.year_level}|${section.section}` // Value attribute
              })
            );
          });
        });

        
        //add class details
        $("#add-class-details").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          addclassDetails(selectProspectus.value);
        });
        //end ---


        //FOR CLASS STATUS SCHEDULE TABLE--start
         // Call function to load modal form class status
        $("#add-room-status").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          const semesterPK = $(this).data('semester');
          let splitSemester = semesterPK.split('|');
          let semesterID = splitSemester[0];
          let schoolYear = splitSemester[1];

          // console.error("TEST VAR SEMESTER :", semesterPK);
          addroomStatus(semesterID, schoolYear); // Call function to add status
        });

        function checkCondition() {
          // Replace this with your actual condition
          const conditionMet = true; // Example condition
      
          if (conditionMet) {
              $('#table-room-status').trigger('condition');
          }
        }

      
        $('#table-room-status').on('condition', function(e) {
          // Your logic here when the condition event is triggered
          console.log('Custom condition event triggered');
        });

        $('#table-room-status').on('click', '.room-schedule, .room-status, .edit-room-status, .display-status, .delete-room-status', function(e) {
          e.preventDefault();
          const button = $(this);
          button.prop("disabled", true);
  
          const semesterPK = $('#table-room-status').data('semester');
          let splitSemester = semesterPK.split('|');
          let semesterID = splitSemester[0];
          let schoolYear = splitSemester[1];
          // console.error("TEST VAR SEMESTER :", semesterPK);
          const userName = $('#table-room-status').data('name');
          const userID = $('#table-room-status').data('userid');


          $(".edit-room-status").off('click').on("click", function(e){
            e.preventDefault();
            const button = $(this);
            button.prop("disabled", true);

            const classId = $(this).data('classid');
            const subType = $(this).data('subjecttype');
            const classDay = $(this).data('classday');

            editroomStatus(classId, subType, classDay, semesterID, schoolYear).always(function(){
              button.prop("disabled", false);
            });
          });

          $(".room-schedule").on("click", function(e){
            e.preventDefault();
            const button = $(this);
            button.prop("disabled", true);

          });

          $(".room-status").on("click", function(e){
            e.preventDefault();
            const button = $(this);
            button.prop("disabled", true);

            const classID = $(this).data('classid');
            const subType = $(this).data('subjecttype');
            const classDay = $(this).data('classday');
            const condition = $(this).data('condition');
            const roomStatus = $(this).data('status');
            const facultyAssigned = $(this).data('faculty');
            

            if(condition == false && facultyAssigned !== userName){
              // alert("This room is Occupied");
              showAlert('This room is Occupied!',2000);

            }else{
              console.log("Faculty:", facultyAssigned, " User:", userName);

              if(facultyAssigned == userName){
                // showAlert('Button Clicked', 2000);
                console.log("Data:", classID, subType, classDay, roomStatus);
                // Call the AJAX function
                changingclassStatus(classID, subType, classDay, roomStatus);
              }else{
                // showAlert('Button Clicked', 2000);
                console.log("Data:", classID, subType, classDay, roomStatus, userName, userID, semesterID, schoolYear);
                // Call the AJAX function
                changingclassStatus(classID, subType, classDay, roomStatus, userName, userID,semesterID, schoolYear);
              }
            }

          });

        
          $(".delete-room-status").on("click", function(e){
            e.preventDefault();
            const button = $(this);
            button.prop("disabled", true);

            const classId = $(this).data('classid');
            const subType = $(this).data('subjecttype');
            const classDay = $(this).data('classday');

            deleteconfirmationStatus(classId, subType, classDay, semesterID, schoolYear).always(function(){
              button.prop("disabled", false);
            });
          });
        });


        function initializeDataTable() {
          if ($.fn.DataTable.isDataTable('#table-room-status')) {
              $('#table-room-status').DataTable().destroy();
          }
          
          selectDay.addEventListener("change", fetchDayData);

          var table = $("#table-room-status").DataTable({
            dom: "rtp",
            pageLength: 10,
            ordering: false,
          });
          
           // Room form filter handler
          $("#room-form").off('submit').on("submit", function (e) {
            e.preventDefault();
  
            const roomName = $('#dropdown-room-name').val();
            const roomType = $('#dropdown-room-type').val();
            const status = $('#dropdown-room-status').val();
            const action = e.originalEvent.submitter.value;
            const currentDay = selectDay.value;
  
            console.log("Room form filtering for day:", currentDay);
            // Clear previous filters
            table.search('').columns().search('').draw();
  
            if (action === "filter") {
              // Apply room filters
              if (roomName && roomName !== "choose") {
                  table.column(1).search(roomName);
              }
              if (roomType && roomType !== "choose") {
                  table.column(2).search(roomType);
              }
              if (status && status !== "choose") {
                  table.column(9).search(status);
              }
              table.draw();
            } else if (action === "all") {
              fetchDayData();
            }
          });
  
          // Class form filter handler
          $("#class-form").off('submit').on("submit", function (e) {
            e.preventDefault();
            // const subjectCode = $('#subject-code-filter').val();
            const subjectCode = $('#dropdown-subject').val();
            const subjectType = $('#dropdown-subject-type').val();
            const section = $('#dropdown-section').val();
            const action = e.originalEvent.submitter.value;
            const currentDay = selectDay.value;
  
            console.log("Class form filtering for day:", currentDay);
  
            // Clear previous filters
            table.search('').columns().search('').draw();
  
            if (action === "filter") {
              // Apply class filters
              if (subjectCode && subjectCode !== "choose") {
                  table.column(3).search(subjectCode);
              }
              if (subjectType && subjectType !== "choose") {
                  table.column(4).search(subjectType);
              }
              if (section && section !== "choose") {
                  table.column(5).search(section);
              }
              table.draw();
            } else if (action === "all") {
              fetchDayData();
            }
          });
        
        }

        var foundTarget = false; // Flag to track if the target is found
        var arrTimer = [];
        var timerIndex = 0;

        function checkCondition(classID, subType, roomStatus, varState, endTime, classDay){
          let checkerTimeInterval = arrTimer[timerIndex];
          checkerTimeInterval = setInterval(() => checkTimeAndRun(classID, subType, roomStatus, varState, endTime, classDay), 3000);
          
          timerIndex++;
        }

        // Function to get the current local time in HH:MM:SS format
        function getCurrentLocalTime() {
          const now = new Date();
          const hours = now.getHours().toString().padStart(2, '0');
          const minutes = now.getMinutes().toString().padStart(2, '0');
          const seconds = now.getSeconds().toString().padStart(2, '0');
          
          return `${hours}:${minutes}:${seconds}`;
        }

        // Function to compare current time with specific end time and day
        function checkTimeAndRun(classID, subType, roomStatus, varState, endTime, targetDay) {
          const fetchedTime = getCurrentLocalTime();
          let checkTime = fetchedTime.split(':');
          let currentTime = `${checkTime[0]}:${checkTime[1]}`;
          
          let checkEndTime = endTime.split(':');
          let targetEndTime = `${checkEndTime[0]}:${checkEndTime[1]}`;

          const now = new Date();   
          // Get the current day and the target day
          const currentDay = now.toISOString().split('T')[0]; // Format YYYY-MM-DD
          const isSameDay = currentDay === targetDay; // Compare with target date
          
          let faculty = null;
          // Compare times and run something if they match
          if (isSameDay && currentTime === targetEndTime) {
            console.log("Running the function because the time and date match!");
            return updateClassStatus(classID, subType, targetDay, roomStatus, faculty, varState);
          
          } else {
            // console.log("No match. Current time:", currentTime);
            // return null;
          }
        }

        // Example usage
        // const endTime = "12:15:30"; // Example end time from your database
        // const targetDate = "2023-01-07"; // Example target date in YYYY-MM-DD format

        

        function conditionRemarks() {
          const targetPhrase = 'Temporary Occupied:';
          let countFoundTarget = 0;
          // Iterate over each .check-remarks element
          $('.check-remarks').each(function() {
            let checkRemarks = $(this).data('remarks'); // Get remarks for the current element
            
            // Check if checkRemarks exists and includes the target phrase
            if (checkRemarks && checkRemarks.includes(targetPhrase)){
              console.log("Current Remarks:", checkRemarks);

              const classID = $('.room-status').data('classid');
              const classDay = $('.room-status').data('classday');
              const subType = $('.room-status').data('subjecttype');
              const roomStatus = $('.room-status').data('status');
              const varState = 'Temporary Occupying';
              const endTime = $('.check-remarks').data('endtime');
              
              console.log("Data:", classID, subType, classDay, roomStatus, varState, endTime);
              // showAlert('TARGET FOUND!', 2000);
              countFoundTarget++;
              // Optionally stop checking if you only want to alert once
              checkCondition(classID, subType, roomStatus, varState, endTime, classDay);
            }
          });
          console.log("count:", countFoundTarget);

          if(countFoundTarget > 0){
            stopInterval();
          }else if(countFoundTarget == 0){
            // showAlert('Target Not Found!', 2000);
            stopInterval(); // Stop the interval if target not found
          }
      
        }
        
        var remarkCheckerInterval;
        //setup interval to check conditionRemarks
        function setupInterval() {
          remarkCheckerInterval = setInterval(conditionRemarks, 3000);
        }

        // Function to stop the interval
        function stopInterval() {
          clearInterval(remarkCheckerInterval); // Clear the specific interval
          console.log("Interval stopped.");
      }

        //Initial 
        setupInterval();

        // initialize
        setCurrentDay();
        selectDay.addEventListener("change", fetchDayData);
        initializeDataTable();




        //--end

      },
      error: function(xhr, status, error) {
        alert("Failed to load class status content!");
        console.error("Error loading content on viewclass-status.php:", status, error);
      }
    });
  }

  //SUBJECT DETAILS FUNCTIONS
  //load modal add subject details
  function addsubjectDetails(prospectus) {
    $.ajax({
      type: "GET", // Use GET request
      url: "../admin/subject-detail-modals/add-subject-details.html?v=" + new Date().getTime(), // URL for add product view
      dataType: "html", // Expect HTML response
      success: function (view) {
        $(".modal-container").html(view); // Load the modal view
        console.log("Modal content loaded successfully.");
        $("#staticBackdrop").modal("show");
        
        const modal = $('#staticBackdrop');

        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal); // Pass modal to closeModal function
        }); 

        // Event listener for the add product form submission
        $("#form-add").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          savesubjectDetails(prospectus); // Call function to save product
        });
        
      },
      error: function (xhr, status, error) {
        alert("Failed to load add subject details modal!");
        console.error("Error loading modal on add-subject-details.html:", status, error);
     
      }
    });
  }
  
  //save subject details      
  function savesubjectDetails(prospectus){
    // Debug what's being sent
    const formAdd = serializeForm("#form-add");
    console.log("Sending data:", formAdd);
    
    $.ajax({
      type: "POST", // Use POST request
      url: `../admin/subject-detail-modals/save-subject-details.php?prospectus=${prospectus}`, // URL for saving room
      data: formAdd, // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        console.log("Response received:", response);
        if (response.status === "error") {
          // Handle validation errors 
          if (response.generalErr){
            $("#general-error").removeClass("d-none").html(cleanInput(response.generalErr));
          } else {
            $("#general-error").addClass("d-none");
          }
          
          if (response.subject_codeErr){
            $("#subject-code").addClass("is-invalid");
            $("#subject-code").siblings(".invalid-feedback").text(response.subject_codeErr).show();
            $("#example-class").show();
            $("#example-class").addClass("text-danger");
          } else {
            $("#subject-code").removeClass("is-invalid");
            $("#example-class").removeClass("text-danger");
            $("#example-class").hide();
          }

          if (response.descriptionErr){
            $("#description").addClass("is-invalid");
            $("#description").siblings(".invalid-feedback").text(response.descriptionErr).show();
          } else {
            $("#description").removeClass("is-invalid");
          }

          if(response.lec_unitsErr){
            $("#lec-units").addClass("is-invalid");
            $("#lec-units").siblings(".invalid-feedback").html(cleanInput(response.lec_unitsErr)).show();
          } else {
            $("#lec-units").removeClass("is-invalid");
          }

          if (response.lab_unitsErr){
            $("#lab-units").addClass("is-invalid");
            $("#lab-units").siblings(".invalid-feedback").text(response.lab_unitsErr).show();
          } else {
            $("#lab-units").removeClass("is-invalid");
          }
        
        } else if (response.status === "success") {
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-add")[0].reset(); // Reset the form
          // Optionally, reload page to show new entry
          viewroomStatus();
        }
      },
      error: function (xhr, status, error) {
        alert("Failed to add subject details!");
        console.error("Error saving data on save-subject-details.php:", status, error);
       }

    });
  }

  //load modal edit subject details      
  function editSubjectDetails(subjectID, prospectus) {
    // Split the composite ID into its parts
    return $.ajax({
      type: "GET", // Use GET request
      url: "../admin/subject-detail-modals/edit-subject-details.html?v=" + new Date().getTime(), // URL 
      dataType: "html", // Expect JSON response
      success: function (view) {
        // Assuming 'view' contains the new content you want to display
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal
        const modal =  $('#staticBackdrop');
          // Then fetch and populate the data
        $.ajax({
          url: `../fetch-data/fetch-subject-details-record.php?subjectID=${subjectID}&prospectusID=${prospectus}`, //2 parameters separated by &
          dataType: "json",
          success: function(data) {
            console.log('Fetched data:', data);
            $('#original-subject-code').val(data.subject_id);
            $('#subject-code').val(data.subject_id);

            $('#original-description').val(data.sub_desc);
            $('#description').val(data.sub_desc);
              
            $('#original-lec-units').val(data.lec_units);
            $('#lec-units').val(data.lec_units);
            
            $('#original-lab-units').val(data.lab_units);
            $('#lab-units').val(data.lab_units);
            
            $('#prospectus').val(data.prospectus_id);
          },
          error: function(xhr, status, error) {
            alert("Failed to fetch subject details record!");
            console.error("Error fetching data on fetch-subject-details-record.php:", status, error);
          }
        });
        
        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal);
        }); 

        $("#form-edit").on("submit", function (e) {
          e.preventDefault();
          updatesubjectDetails();
        });
      },
      error: function (xhr, status, error) {
        alert("Failed to load edit subject details modal!");
        console.error("Error loading modal on edit-subject-details.html:", status, error);
      }
    });
  }

  //update subject details      
  function updatesubjectDetails(){
    // Debug what's being sent
    const formEdit = serializeForm("#form-edit");
    console.log("Sending data:", formEdit);
    
    $.ajax({
      type: "POST", // Use POST request
      url: "../admin/subject-detail-modals/update-subject-details.php", // URL for saving room
      data: formEdit, // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        console.log("Response received:", response);
        if (response.status === "error") {
          // Handle validation errors
          if (response.generalErr){
            $("#general-error").removeClass("d-none").html(cleanInput(response.generalErr));
          } else {
            $("#general-error").addClass("d-none");
          }
          
          if (response.subject_codeErr){
            $("#subject-code").addClass("is-invalid");
            $("#subject-code").siblings(".invalid-feedback").text(response.subject_codeErr).show();
            $("#example-class").show();
            $("#example-class").addClass("text-danger");
          } else {
            $("#subject-code").removeClass("is-invalid");
            $("#example-class").removeClass("text-danger");
            $("#example-class").hide();
          }

          if (response.descriptionErr){
            $("#description").addClass("is-invalid");
            $("#description").siblings(".invalid-feedback").text(response.descriptionErr).show();
          } else {
            $("#description").removeClass("is-invalid");
          }

          if(response.lec_unitsErr){
            $("#lec-units").addClass("is-invalid");
            $("#lec-units").siblings(".invalid-feedback").html(cleanInput(response.lec_unitsErr)).show();
          } else {
            $("#lec-units").removeClass("is-invalid");
          }

          if (response.lab_unitsErr){
            $("#lab-units").addClass("is-invalid");
            $("#lab-units").siblings(".invalid-feedback").text(response.lab_unitsErr).show();
          } else {
            $("#lab-units").removeClass("is-invalid");
          }

        } else if (response.status === "success") {
          alert('Class details updated successfully.');
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-edit")[0].reset(); // Reset the form
          // Optionally, reload page to show new entry
          viewroomStatus();
        }
      },
      error: function (xhr, status, error) {
        alert("Failed to update subject details!");
        console.error("Error updating data on update-subject-details.php:", status, error);
      }
    });
  }

  //load modal delete subject details    
  function deletingSubjectDetails(subjectID, prospectus){
    return $.ajax({
      type: "GET", // Use GET request
      url: "../admin/subject-detail-modals/deleting-subject-details.html?v=" + new Date().getTime(), // URL to get product data
      dataType: "html", // Expect JSON response
      success: function (view) {
        // Assuming 'view' contains the new content you want to display
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal
        $("#staticBackdroped").attr("data-id");

        const modal = $('#staticBackdrop');
      
        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal); // Pass modal to closeModal function
        }); 

        // Event listener for the add product form submission
        $("#form-delete").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          deleteSubjectDetails(subjectID, prospectus); // Call function to save product
        });
      },
      error: function(xhr, status, error) {
        alert("Failed loading delete class detail modal!");
        console.error("Error loading modal on deleting-subjects-details.html:", status, error);
      }

    });
  } 

  //delete subject details  
  function deleteSubjectDetails(subjectID, prospectusID){
    const submitButton = $("#form-delete button[type='submit']");
    submitButton.prop('disabled', true);
    
    const formDelete = serializeForm("#form-delete");
    console.log("Sending data:", formDelete);

    $.ajax({
      type: "POST", // Use POST request
      url: `../admin/subject-detail-modals/delete-subject-details.php?subjectID=${subjectID}&prospectusID=${prospectusID}`, // URL for saving room
      data: formDelete, // Serialize the form data for submission, Add ID to form data
      dataType: "json", // Expect JSON response
      success: function (response) {
        console.log("Response received:", response);
        if (response.status === "success") {
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-delete")[0].reset(); // Reset the form
          // Optionally, reload page to show new entry
          viewroomStatus();

        }else{
          console.error("Error deleting data on delete-subject-details.php:", response.message);
          alert("Failed to delete subject details!");
          $("#staticBackdrop").modal("hide");
          $("#form-delete")[0].reset(); // Reset the form
          viewroomStatus();
        }
      },
      error: function(xhr, status, error) {
        alert("Failed to delete subject details!");
        console.error("Error deleting data on delete-subject-details.php:", status, error);
        $("#staticBackdrop").modal("hide");
        $("#form-delete")[0].reset(); // Reset the form
        viewroomStatus();
      }

    });
  }


  //CLASS DETAILS FUNCTION
  //load modal add class details
  function addclassDetails(prospectus = null) {
    if(prospectus !== null){
      var subjectUrl = `../fetch-data/fetch-subject.php?prospectusID=${prospectus}`;
    }else{
      var subjectUrl = `../fetch-data/fetch-subject.php`;
    }
    
    $.ajax({
      type: "GET", // Use GET request
      url: "../admin/class-detail-modals/add-class-details.html?v=" + new Date().getTime(), // URL for add product view
      dataType: "html", // Expect HTML response
      success: function (view) {
        $(".modal-container").html(view); // Load the modal view
        console.log("Modal content loaded successfully.");
        $("#staticBackdrop").modal("show");
        const modal = $('#staticBackdrop');
        
        const subjectText= $('#dropdown-subject');
        const subjectList = $('#dropdown-list-subject');
        const subjectId = $('#hidden-subject-id');
        customDropdown(subjectText, subjectList, subjectId, subjectUrl, function(data, dropdownList) {
          dropdownList.empty(); // Clear existing options
          const errorMessage = 'List Empty, Please add more subjects for this prospectus.';
      
          if (data.length === 0) {
              subjectText.val(errorMessage); // Display message in the input
              saveclassDetails();
              return; // Exit if no data
          }
          
          $.each(data, function (index, subject) {
            const displayContent = cleanInput(`${subject.subject_id}---LC|LAB---${subject.subject_units}`);
            dropdownList.append(
              $("<div>", {
                text:displayContent, // Displayed text
                  'data-value': subject.subject_id // Value attribute
              })
            );
          });
        });

        const sectionText= $('#dropdown-section');
        const sectionList = $('#dropdown-list-section');
        const sectionId = $('#hidden-section-id');
        // fetchSection();//fetchsection
        customDropdown(sectionText, sectionList, sectionId, "../fetch-data/fetch-section.php", function(data, dropdownList) {
          $.each(data, function (index, section) {
            const displayContent = cleanInput(`${section.course_abbr}${section.year_level}${section.section}`);
            dropdownList.append(
              $("<div>", {
                text: displayContent, // Displayed text
                'data-value': `${section.course_abbr}|${section.year_level}|${section.section}` // Value attribute
              })
            );
          });
        });

        const teacherText= $('#dropdown-teacher');
        const teacherList = $('#dropdown-list-teacher');
        const teacherId = $('#hidden-teacher-assigned');
        customDropdown(teacherText, teacherList, teacherId, "../fetch-data/fetch-teacher.php", function(data, dropdownList) {
          $.each(data, function (index, teacher) {
            dropdownList.append(
              $("<div>", {
                text: teacher.teacher_name, // Displayed text
                'data-value': teacher.faculty_id // Value attribute
              })
            );
          });
        });

        const teacherTextLab= $('#dropdown-teacher-lab');
        const teacherListLab = $('#dropdown-list-teacher-lab');
        const teacherIdLab = $('#hidden-teacher-assigned-lab');
        customDropdown(teacherTextLab, teacherListLab, teacherIdLab, "../fetch-data/fetch-teacher.php", function(data, dropdownList) {
          $.each(data, function (index, teacher) {
            dropdownList.append(
              $("<div>", {
                text: teacher.teacher_name, // Displayed text
                'data-value': teacher.faculty_id // Value attribute
              })
            );
          });
        });

        // Fix: Update checkbox event handler
        $('input[name="subject-type[]"]').on("change", function() {
          // Count checked checkboxes
          const checkedCount = $('input[name="subject-type[]"]:checked').length;
          // Show div-teacher if both checkboxes are checked
          if (checkedCount === 2) {
            $('#div-teacher').show();
            $('#hidden-teacher-assigned-lab').prop('disabled', false);
            $('#determiner').val('true');
          } else {
            $('#div-teacher').hide();
            $('#hidden-teacher-assigned-lab').prop('disabled', true);
            $('#determiner').val('false');
          }
      
        });

        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal); // Pass modal to closeModal function
        }); 

        // Event listener for the add product form submission
        $("#form-add").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          saveclassDetails(); // Call function to save product
        });
        
      },
      error: function (xhr, status, error) {
        alert("Failed to load add class details modal!");
        console.error("Error loading modal on add-class-details.html:", status, error);
      }
    });
  }

  //save class details
  function saveclassDetails(){
    // Debug what's being sent
    const formAdd = serializeForm("#form-add");
    console.log("Sending data:", formAdd);
    
    $.ajax({
      type: "POST", // Use POST request
      url: "../admin/class-detail-modals/save-class-details.php", // URL for saving room
      data: formAdd, // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        console.log("Response received:", response);
        if (response.status === "error") {
          // Handle validation errors 
          if (response.generalErr){
            $("#general-error").removeClass("d-none").html(cleanInput(response.generalErr));
          } else {
            $("#general-error").addClass("d-none");
          }
          
          if (response.class_idErr){
            $("#class-id").addClass("is-invalid");
            $("#class-id").siblings(".invalid-feedback").text(response.class_idErr).show();
            $("#example-class").show();
            $("#example-class").addClass("text-danger");
          } else {
            $("#class-id").removeClass("is-invalid");
            $("#example-class").removeClass("text-danger");
            $("#example-class").hide();
          }

          if (response.subject_idErr){
            $("#dropdown-subject").addClass("is-invalid");
            $("#dropdown-subject").siblings(".invalid-feedback").text(response.subject_idErr).show();
          } else {
            $("#dropdown-subject").removeClass("is-invalid");
          }

          if(response.subject_typeErr){
            $(".subject-type").addClass("is-invalid");
            $(".subject-type").siblings(".invalid-feedback").html(cleanInput(response.subject_typeErr)).show();
          } else {
            $(".subject-type").removeClass("is-invalid");
          }

          if (response.section_idErr){
            $("#dropdown-section").addClass("is-invalid");
            $("#dropdown-section").siblings(".invalid-feedback").text(response.section_idErr).show();
          } else {
            $("#dropdown-section").removeClass("is-invalid");
          }

          if (response.teacher_assignedErr){
            $("#dropdown-teacher").addClass("is-invalid");
            $("#dropdown-teacher").siblings(".invalid-feedback").text(response.teacher_assignedErr).show();
          } else {
            $("#dropdown-teacher").removeClass("is-invalid");
          }

          if (response.teacher_assigned_labErr){
            $("#dropdown-teacher-lab").addClass("is-invalid");
            $("#dropdown-teacher-lab").siblings(".invalid-feedback").text(response.teacher_assigned_labErr).show();
          } else {
            $("#dropdown-teacher-lab").removeClass("is-invalid");
          }
        
        } else if (response.status === "success") {
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-add")[0].reset(); // Reset the form
          // Optionally, reload page to show new entry
          viewroomStatus();
        }
      },
      error: function(xhr, status, error) {
        alert("Failed to add class details!");
        console.error("Error saving data on save-class-details.php:", status, error);
      }

    });
  }
  
  //load modal edit class details
  function editclassDetails(classId, subType, prospectus = null) {
    // Split the composite ID into its parts
    return $.ajax({
      type: "GET", // Use GET request
      url: "../admin/class-detail-modals/edit-class-details.html?v=" + new Date().getTime(), // URL 
      dataType: "html", // Expect JSON response
      success: function (view) {
        // Assuming 'view' contains the new content you want to display
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal
        $("#staticBackdroped").attr("data-id", classId, subType);
        const modal =  $('#staticBackdrop');
        
        if(prospectus !== null){
          var subjectUrl = `../fetch-data/fetch-subject.php?prospectusID=${prospectus}`;
        
        }else{
          var subjectUrl = `../fetch-data/fetch-subject.php`;
        }
        
        // Then fetch and populate the data
        $.ajax({
          url: `../fetch-data/fetch-class-detail.php?classId=${classId}&subType=${subType}`, //2 parameters separated by &
          dataType: "json",
          success: function(data) {
            console.log('Fetched data:', data);
            $('#original-class-id').val(data.class_id);
            $('#class-id').val(data.class_id);

            $('#original-subject-id').val(data.subject_id);
            $('#dropdown-subject').val(`${data.subject_id}---LC|LAB---${data.subject_units}`);
            $('#hidden-subject-id').val(data.subject_id);
            
            $('#original-subtype-id').val(data.subtype_id);
            $('input[name="subject-type"][value="' + data.subtype_id + '"]').prop('checked', true);
            
            $('#original-section-id').val(data.section_id);
            $('#dropdown-section').val(data.section_name);
            $('#hidden-section-id').val(data.section_id);

            $('#dropdown-teacher').val(data.teacher_name);
            $('#hidden-teacher-assigned').val(data.teacher_id);

          },
          error: function(xhr, status, error) {
            alert("Failed to fetch class detail record!");
            console.error("Error fetching data on fetch-class-detail.php:", status, error);
          }

        });
        
        const subjectText= $('#dropdown-subject');
        const subjectList = $('#dropdown-list-subject');
        const subjectId = $('#hidden-subject-id');
        // fetchSubject();//fetchsubject
        customDropdown(subjectText, subjectList, subjectId, subjectUrl, function(data, dropdownList) {
          dropdownList.empty(); // Clear existing options
          const errorMessage = 'List Empty, Please add more subjects for this prospectus.';

          if (data.length === 0) {
            subjectText.val(errorMessage); // Display message in the input
            updateclassDetails();
            return; // Exit if no data
          }

          $.each(data, function (index, subject) {
            const displayContent = cleanInput(`${subject.subject_id}---LC|LAB---${subject.subject_units}`);
            dropdownList.append(
              $("<div>", {
                text:displayContent, // Displayed text
                  'data-value': subject.subject_id // Value attribute
              })
            );
          });
        });

        const sectionText= $('#dropdown-section');
        const sectionList = $('#dropdown-list-section');
        const sectionId = $('#hidden-section-id');
        // fetchSection();//fetchsection
        customDropdown(sectionText, sectionList, sectionId, "../fetch-data/fetch-section.php", function(data, dropdownList) {
          $.each(data, function (index, section) {
            const displayContent = cleanInput(`${section.course_abbr}${section.year_level}${section.section}`);
            dropdownList.append(
              $("<div>", {
                text: displayContent, // Displayed text
                'data-value': `${section.course_abbr}|${section.year_level}|${section.section}` // Value attribute
              })
            );
          });
        });

        const teacherText= $('#dropdown-teacher');
        const teacherList = $('#dropdown-list-teacher');
        const teacherId = $('#hidden-teacher-assigned');
        customDropdown(teacherText, teacherList, teacherId, "../fetch-data/fetch-teacher.php", function(data, dropdownList) {
          $.each(data, function (index, teacher) {
            dropdownList.append(
              $("<div>", {
                text: teacher.teacher_name, // Displayed text
                'data-value': teacher.faculty_id // Value attribute
              })
            );
          });
        });
        
        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal);
        }); 

        $("#form-edit").on("submit", function (e) {
          e.preventDefault();
          updateclassDetails();
        });
      },
      error: function(xhr, status, error) {
        alert('Failed to load edit class details modal!');
        console.error("Error loading modal on edit-class-details.html:", status, error);
      }
    });
  }

  //update class details
  function updateclassDetails(){
    // Debug what's being sent
    const formEdit = serializeForm("#form-edit");
    console.log("Sending data:", formEdit);
    
    $.ajax({
      type: "POST", // Use POST request
      url: "../admin/class-detail-modals/update-class-details.php", // URL for saving room
      data: formEdit, // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        console.log("Response received:", response);
        if (response.status === "error") {
          // Handle validation errors
          //Check if class id is already existing
          if (response.generalErr){
            $("#general-error").removeClass("d-none").html(cleanInput(response.generalErr));
          } else {
            $("#general-error").addClass("d-none");
          }

          if (response.class_idErr){
            $("#class-id").addClass("is-invalid");
            $("#class-id").siblings(".invalid-feedback").text(response.class_idErr).show();
          } else {
            $("#class-id").removeClass("is-invalid");
          }

          if (response.subject_idErr){
            $("#dropdown-subject").addClass("is-invalid");
            $("#dropdown-subject").siblings(".invalid-feedback").text(response.subject_idErr).show();
          } else {
            $("#dropdown-subject").removeClass("is-invalid");
          }
          
          if(response.subject_typeErr){
            $(".subject-type").addClass("is-invalid");
            $(".subject-type").siblings(".invalid-feedback").html(cleanInput(response.subject_typeErr)).show();
          } else {
            $(".subject-type").removeClass("is-invalid");
          }

          if (response.section_idErr){
            $("#dropdown-section").addClass("is-invalid");
            $("#dropdown-section").siblings(".invalid-feedback").text(response.section_idErr).show();
          } else {
            $("#dropdown-section").removeClass("is-invalid");
          }

          if (response.teacher_assignedErr){
            $("#dropdown-teacher").addClass("is-invalid");
            $("#dropdown-teacher").siblings(".invalid-feedback").text(response.teacher_assignedErr).show();
          } else {
            $("#dropdown-teacher").removeClass("is-invalid");
          }

        } else if (response.status === "success") {
          alert('Class details updated successfully.');
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-edit")[0].reset(); // Reset the form
          // Optionally, reload page to show new entry
          viewroomStatus();
        }
      },
      error: function(xhr, status, error) {
        alert('Failed to update class details!');
        console.error("Error on updating data on update-class-details.php.:", status, error);
      }

    });
  }

  //load modal delete class details
  function deletingclassDetails(classId, subType, semesterID, schoolYear){
    return $.ajax({
      type: "GET", // Use GET request
      url: "../admin/class-detail-modals/deleting-class-details.html?v=" + new Date().getTime(), // URL to get product data
      dataType: "html", // Expect JSON response
      success: function (view) {
        // Assuming 'view' contains the new content you want to display
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal
        $("#staticBackdroped").attr("data-id", classId, subType);

        const modal = $('#staticBackdrop');
        
        $.ajax({
          url: `../fetch-data/fetch-class-detail.php?classId=${classId}&subType=${subType}&semesterID=${semesterID}&schoolYear=${schoolYear}`,
          dataType: "json",
          success: function(data) {
            console.log('Fetched data:', data.class_id, data.subtype_id); // For debugging
            //Fetch class id from query
            $('#hidden-class-id').val(data.class_id);
            //Fetch class subject id
            $('#hidden-subtype-id').val(data.subtype_id);
            //Fetch class id from query
            $('#semester-id').val(data.semester_id);
            //Fetch class subject id
            $('#school-year').val(data.school_year);
          },
          error: function(xhr, status, error) {
            alert("Error fetching status record!");
            console.error("Error fetching data on fetch-class-detail.php:", error);
          }
        });

        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal); // Pass modal to closeModal function
        }); 

        // Event listener for the add product form submission
        $("#form-delete").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          deleteclassDetails(); // Call function to save product
        });
      },
      error: function(xhr, status, error) {
        alert("Failed loading delete class detail modal!");
        console.error("Error loading modal on deleting-class-details.html:", status, error);
      }

    });
  } 

  //delete class details
  function deleteclassDetails(){
    const submitButton = $("#form-delete button[type='submit']");
    submitButton.prop('disabled', true);
    
    const formDelete = serializeForm("#form-delete");
    console.log("Sending data:", formDelete);

    $.ajax({
      type: "POST", // Use POST request
      url: "../admin/class-detail-modals/delete-class-details.php", // URL for saving room
      data: formDelete, // Serialize the form data for submission, Add ID to form data
      dataType: "json", // Expect JSON response
      success: function (response) {
        console.log("Response received:", response);
        if (response.status === "success") {
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-delete")[0].reset(); // Reset the form
          // Optionally, reload page to show new entry
          viewroomStatus();
        }else{
          console.error("Error deleting data on delete-class=details.php:", response.message);
          alert("Failed to delete class details!");
          $("#staticBackdrop").modal("hide");
          $("#form-delete")[0].reset(); // Reset the form
          viewroomStatus();
        }
      },
      error: function(xhr, status, error) {
        alert("Failed to delete class detail!");
        console.error("Error deleting data on delete-class-details.php:", status, error);
      }

    });
  }


  //CLASS STATUS || ROOM STATUS FUNCTION
  //load modal add room status
  function addroomStatus(semesterID = null, schoolYear = null) {
    if(semesterID !== null && schoolYear !==null){
      var classUrl = `../fetch-data/fetch-classes.php?semesterID=${semesterID}&schoolYear=${schoolYear}`;
    }else{
      var classUrl = `../fetch-data/fetch-classes.php`;
    }
    $.ajax({
      type: "GET", // Use GET request
      url: "../admin/room-status-modals/add-room-status.html?v=" + new Date().getTime(), // URL for add product view
      dataType: "html", // Expect HTML response
      success: function (view) {
        $(".modal-container").html(view); // Load the modal view
        console.log("Modal content loaded successfully.");
        $("#staticBackdrop").modal("show");
        
        const modal = $('#staticBackdrop');

        //DROP DOWN FOR CLASS ID
        const classText = $('#dropdown-class-id');
        const classId = $('#hidden-class-id');
        const classList = $('#dropdown-list-class-id');
        customDropdown(classText, classList, classId, classUrl, function(data, dropdownList) {
          $.each(data, function (index, classes) {
            dropdownList.append(
              $("<div>", {
                text: `${classes.class_sub}---LC|LB---${classes.subject_units}`,
                'data-value': classes.class_id
              })
            );
          });
        });


        //DROP DOWN FOR FIRST ROOM
        const roomText = $('#dropdown-room');
        const roomId = $('#hidden-room-id');
        const roomList = $('#dropdown-list-name');
        customDropdown(roomText, roomList, roomId, "../fetch-data/fetch-room-name.php", function(data, dropdownList) {
          $.each(data, function(index, room) {
            dropdownList.append(
              $("<div>", {
                text: room.room_name, // Displayed text
                'data-value': `${room.room_code}|${room.room_no}` // Value attribute
              })
            );
          });
        });


        //DROP DOWN FOR SECOND ROOM
        const roomTextLab = $('#dropdown-room-2');
        const roomIdLab = $('#hidden-room-id-2');
        const roomListLab = $('#dropdown-list-name-2');
        customDropdown(roomTextLab, roomListLab, roomIdLab, "../fetch-data/fetch-room-name.php", function(data, dropdownList) {
          $.each(data, function(index, room) {
            dropdownList.append(
              $("<div>", {
                text: room.room_name, // Displayed text
                'data-value': `${room.room_code}|${room.room_no}` // Value attribute
              })
            );
          });
        });

        // Fix: Update checkbox event handler
        $('input[name="day-id[]"]').on("change", function() {
          const checkedCheckboxes = $('input[name="day-id[]"]:checked');
          const checkedCount = checkedCheckboxes.length;
      
          if (checkedCount > 2) {
            $(this).prop('checked', false);
            return; // Exit if limit exceeded
          }
        });

        // Fix: Update checkbox event handler
        $('input[name="subject-type[]"]').on("change", function() {
          const checkedCheckboxes = $('input[name="subject-type[]"]:checked');
          const checkedCount = checkedCheckboxes.length;
          // Show div-room if both checkboxes are checked
          if (checkedCount === 2) {
            $('.div-time').show();
            $('.div-day').css('display', 'flex');
            $('#determiner-type').val('true');

            $('#hidden-room-id-2').prop('disabled', false);
            // $('#determiner-room').val('true');
          } else {
            $('.div-time').hide();
            $('.div-day').hide();
            $('#determiner-type').val('false');

            $('#hidden-room-id-2').prop('disabled', true);
            // $('#determiner-room').val('false');
          }
        });


        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal); // Pass modal to closeModal function
        }); 

        // Event listener for the add room status form submission
        $("#form-add").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          const button = $(this); // Reference to the clicked button
          saveroomStatus(); // Call function to save room status
          button.prop("disabled", true); // Disable the button
      
        });
        
      },
      error: function(xhr, status, error) {
        alert("Failed to load add class status schedule modal!");
        console.error("Error loading modal on add-room-status.html:", status, error);
      }

    });
  }

  //save room status
  function saveroomStatus(){
    // Debug what's being sent  
    const formAdd = serializeForm("#form-add");
    console.log("Sending data:", formAdd);
    
    $.ajax({
      type: "POST", // Use POST request
      url: "../admin/room-status-modals/save-room-status.php", // URL for saving room
      data: formAdd, // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        if (response.status === "error") {
          // Handle validation errors
          if (response.generalErr){
            $("#general-error").removeClass("d-none").html(cleanInput(response.generalErr));
          } else {
            $("#general-error").addClass("d-none");
          }

          if (response.class_idErr){
            $("#dropdown-class-id").addClass("is-invalid"); // Mark field as invalid
            $("#dropdown-class-id").siblings(".invalid-feedback").text(response.class_idErr).show(); // Show error message
          } else {
            $("#dropdown-class-id").removeClass("is-invalid"); // Remove invalid class if no error
          }

          if (response.subject_typeErr){
            $(".subject-type").addClass("is-invalid"); // Mark field as invalid
            $(".subject-type").siblings(".invalid-feedback").text(response.subject_typeErr).show(); // Show error message
          } else {
            $(".subject-type").removeClass("is-invalid"); // Remove invalid class if no error
          }

          if (response.generalErr1){
            $("#general-error-1").removeClass("d-none").html(cleanInput(response.generalErr1));
          } else {
            $("#general-error-1").addClass("d-none");
          }

          if (response.start_time_1Err){
            $("#start-time-1").addClass("is-invalid"); // Mark field as invalid
            $("#start-time-1").siblings(".invalid-feedback").text(response.start_time_1Err).show(); // Show error message
          } else {
            $("#start-time-1").removeClass("is-invalid"); // Remove invalid class if no error
          }

          if (response.end_time_1Err){
            $("#end-time-1").addClass("is-invalid"); // Mark field as invalid
            $("#end-time-1").siblings(".invalid-feedback").text(response.end_time_1Err).show(); // Show error message
          } else {
            $("#end-time-1").removeClass("is-invalid"); // Remove invalid class if no error
          }

          if (response.day_id_1Err){
            $(".day-id-1").addClass("is-invalid"); // Mark field as invalid
            $(".day-id-1").siblings(".invalid-feedback").text(response.day_id_1Err).show(); // Show error message
          } else {
            $(".day-id-1").removeClass("is-invalid"); // Remove invalid class if no error
          }

          if (response.room_id_1Err){
            $("#dropdown-room").addClass("is-invalid"); // Mark field as invalid
            $("#dropdown-room").siblings(".invalid-feedback").text(response.room_id_1Err).show(); // Show error message
          } else {
            $("#dropdown-room").removeClass("is-invalid"); // Remove invalid class if no error
          }
          
          
          //error for inputs 2
          if (response.generalErr2){
            $("#general-error-2").removeClass("d-none").html(cleanInput(response.generalErr2));
          } else {
            $("#general-error-2").addClass("d-none");
          }

          if (response.start_time_2Err){
            $("#start-time-2").addClass("is-invalid"); // Mark field as invalid
            $("#start-time-2").siblings(".invalid-feedback").text(response.start_time_2Err).show(); // Show error message
          } else {
            $("#start-time-2").removeClass("is-invalid"); // Remove invalid class if no error
          }

          if (response.end_time_2Err){
            $("#end-time-2").addClass("is-invalid"); // Mark field as invalid
            $("#end-time-2").siblings(".invalid-feedback").text(response.end_time_2Err).show(); // Show error message
          } else {
            $("#end-time-2").removeClass("is-invalid"); // Remove invalid class if no error
          }

          if (response.day_id_2Err){
            $(".day-id-2").addClass("is-invalid"); // Mark field as invalid
            $(".day-id-2").siblings(".invalid-feedback").text(response.day_id_2Err).show(); // Show error message
          } else {
            $(".day-id-2").removeClass("is-invalid"); // Remove invalid class if no error
          }
          
          if (response.room_id_2Err){
            $("#dropdown-room-2").addClass("is-invalid"); // Mark field as invalid
            $("#dropdown-room-2").siblings(".invalid-feedback").text(response.room_id_2Err).show(); // Show error message
          } else {
            $("#dropdown-room-2").removeClass("is-invalid"); // Remove invalid class if no error
          }
          
        } else if (response.status === "success") {
          const submitButton = $("#form-add button[type='submit']");
          submitButton.prop('disabled', true);
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-add")[0].reset(); // Reset the form
          // Optionally, reload roomlist to show new entry
          viewroomStatus();
        }
      },
      error: function(xhr, status, error) {
        alert("Failed to add class Status Schedule!");
        console.error("Error saving data on save-room-status.php:", status, error);
      }

    });
  }

  //load modal edit class status
  function editroomStatus(classID, subType, classDay, semesterID = null, schoolYear = null){
    
    return $.ajax({
      type: "GET", // Use GET request
      url: "../admin/room-status-modals/edit-room-status.php?v=" + new Date().getTime(), // URL to get product data
      dataType: "html", // Expect JSON response
      success: function (view) {
        // Assuming 'view' contains the new content you want to display
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal
        $("#staticBackdroped").attr("data-id");

        const modal = $('#staticBackdrop');
        
        // Then fetch and populate the data
        $.ajax({
          url: `../fetch-data/fetch-room-status.php?classID=${classID}&subType=${subType}&classDay=${classDay}`,
          dataType: "json",
          success: function(data){
            console.log('Fetched data:', data); // For debugging
            console.log('Original Day ID:', data.class_day);
            const displayContent = cleanInput(`${data.class_id}---LC|LAB---${data.subject_units}`);

            $('#hidden-original-class-id').val(data.class_id);
            $('#hidden-original-subtype').val(data.subject_type);
            $('#hidden-original-start-time').val(data.start_time);//what start time
            $('#hidden-original-end-time').val(data.end_time);//what end time
            $('#hidden-original-day-id').val(data.class_day);//what day
            $('#hidden-original-room').val(data.room_name);//what day

            //Fetch class id
            $('#dropdown-class-id').val(displayContent);
            $('#hidden-class-id').val(data.class_id);

            $(`input[name="subject-type"][value="${data.subject_type}"]`).prop('checked', true);

            // Check the appropriate day radio
            $(`input[name="day-id"][value="${data.class_day}"]`).prop('checked', true);

            // Populate time fields
            $('#start-time').val(data.start_time);
            $('#end-time').val(data.end_time);

            $('#dropdown-room').val(data.room_name);
            $('#hidden-room-id').val(data.room_id);

          },
          error: function(xhr, status, error) {
            alert("Failed to fetch class status schedule record!");
            console.error("Error fetching data on fetch-room-status.php:", status, error);
          }

        });
        
        if(semesterID !== null && schoolYear !==null){
          var classUrl = `../fetch-data/fetch-classes.php?semesterID=${semesterID}&schoolYear=${schoolYear}`;
        }else{
          var classUrl = `../fetch-data/fetch-classes.php`;
        }

        //DROP DOWN FOR CLASS ID
        const classText = $('#dropdown-class-id');
        const classId = $('#hidden-class-id');
        const classList = $('#dropdown-list-class-id');

        customDropdown(classText, classList, classId, classUrl, function(data, dropdownList) {
          $.each(data, function (index, classes) {
            dropdownList.append(
              $("<div>", {
                text: `${classes.class_sub}---LC|LB---${classes.subject_units}`,
                'data-value': classes.class_id
              })
            );
          });
        });

        const roomText = $('#dropdown-room');
        const roomId = $('#hidden-room-id');
        const roomList = $('#dropdown-list-name');
        // fetchroomName(roomText, roomList, roomId);
        customDropdown(roomText, roomList, roomId, "../fetch-data/fetch-room-name.php", function(data, dropdownList) {
          $.each(data, function(index, room) {
            dropdownList.append(
              $("<div>", {
                text: room.room_name, // Displayed text
                'data-value': `${room.room_code}|${room.room_no}` // Value attribute
              })
            );
          });
        });

        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal); // Pass modal to closeModal function
        }); 

        // Event listener for the add product form submission
        $("#form-edit").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          updateroomStatus(); // Call function to save product
        });
      },
      error: function(xhr, status, error) {
        alert("Failed to load edit class status schedule modal!");
        console.error("Error loading modal on edit-room-status.php:", status, error);
      }

    });
  }

  //update class status  
  function updateroomStatus(){
    const submitButton = $("#form-edit button[type='submit']");
    submitButton.prop('disabled', true);
    // Debug what's being sent
    const formEdit = serializeForm("#form-edit");
    console.log("Sending data:", formEdit);
    
    $.ajax({
      type: "POST", // Use POST request
      url: "../admin/room-status-modals/update-room-status.php?v=" + new Date().getTime(), // URL for saving room
      data: formEdit, // Serialize the form data for submission, Add ID to form data
      dataType: "json", // Expect JSON response
      success: function (response) {
        if (response.status === "error") {
          // Handle validation errors
          if (response.generalErr){
            $("#general-error").removeClass("d-none").html(cleanInput(response.generalErr));
          } else {
            $("#general-error").addClass("d-none");
          }

          if (response.class_idErr){
            $("#dropdown-class-id").addClass("is-invalid"); // Mark field as invalid
            $("#dropdown-class-id").siblings(".invalid-feedback").text(response.class_idErr).show(); // Show error message
          } else {
            $("#dropdown-class-id").removeClass("is-invalid"); // Remove invalid class if no error
          }

          if (response.subject_typeErr){
            $(".subject-type").addClass("is-invalid"); // Mark field as invalid
            $(".subject-type").siblings(".invalid-feedback").text(response.subject_typeErr).show(); // Show error message
          } else {
            $(".subject-type").removeClass("is-invalid"); // Remove invalid class if no error
          }

          if (response.generalErr1){
            $("#general-error-1").removeClass("d-none").html(cleanInput(response.generalErr1));
          } else {
            $("#general-error-1").addClass("d-none");
          }

          if (response.start_time_1Err){
            $("#start-time").addClass("is-invalid"); // Mark field as invalid
            $("#start-time").siblings(".invalid-feedback").text(response.start_time_1Err).show(); // Show error message
          } else {
            $("#start-time").removeClass("is-invalid"); // Remove invalid class if no error
          }

          if (response.end_time_1Err){
            $("#end-time").addClass("is-invalid"); // Mark field as invalid
            $("#end-time").siblings(".invalid-feedback").text(response.end_time_1Err).show(); // Show error message
          } else {
            $("#end-time").removeClass("is-invalid"); // Remove invalid class if no error
          }

          if (response.day_id_1Err){
            $(".day-id-1").addClass("is-invalid"); // Mark field as invalid
            $(".day-id-1").siblings(".invalid-feedback").text(response.day_id_1Err).show(); // Show error message
          } else {
            $(".day-id-1").removeClass("is-invalid"); // Remove invalid class if no error
          }

          if (response.room_id_1Err){
            $("#dropdown-room").addClass("is-invalid"); // Mark field as invalid
            $("#dropdown-room").siblings(".invalid-feedback").text(response.room_id_1Err).show(); // Show error message
          } else {
            $("#dropdown-room").removeClass("is-invalid"); // Remove invalid class if no error
          }
          
        } else if (response.status === "success") {
          alert('Class Schedule updated successfully.');
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-edit")[0].reset(); // Reset the form
          // Optionally, reload roomlist to show new entry
          viewroomStatus();
        }
      },
      error: function(xhr, status, error) {
        alert("Failed to edit class Status Schedule!");
        console.error("Error updating data on update-room-status.php:", status, error);
      }

    });

  }

  //Load modal delete class status
  function deleteconfirmationStatus(classID, subType, classDay, semesterID, schoolYear){
    return $.ajax({
      type: "GET", // Use GET request
      url: "../admin/room-status-modals/delete-confirmation-status.html?v=" + new Date().getTime(), // URL to get product data
      dataType: "html", // Expect JSON response
      success: function (view) {
        // Assuming 'view' contains the new content you want to display
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal
        $("#staticBackdroped").attr("data-id");

        const modal = $('#staticBackdrop');
        
        $.ajax({
          url: `../fetch-data/fetch-room-status.php?classID=${classID}&subType=${subType}&classDay=${classDay}&semesterID=${semesterID}&schoolYear=${schoolYear}`,
          dataType: "json",
          success: function(data) {
            console.log('Fetched data:', data); // For debugging
            //Fetch class id
            $("#class-id").val(data.class_id);
            //Fetch subject type
            $("#subject-type").val(data.subject_type);
            //Fetch-class-day
            $("#class-day").val(data.class_day);
            //Fetch-semester-id
            $("#semester-id").val(data.semester);
            //Fetch-school-year
            $("#school-year").val(data.school_year);
            
          },
          error: function(xhr, status, error) {
            alert('Error fetching data class status!');
            console.error("Error fetching data on fetch-room-status.php:", error);
          }
        });

        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal); // Pass modal to closeModal function
        }); 

        // Event listener for the add product form submission
        $("#form-delete").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          deleteroomStatus(); // Call function to save product
        });
      },
      error: function(xhr, status, error) {
        alert("Failed to load delete class status schedule modal!");
        console.error("Error loading modal on delete-confirmation-status.html:", status, error);
      }

    });
  }

  //delete class status
  function deleteroomStatus(){
    const submitButton = $("#form-delete button[type='submit']");
    submitButton.prop('disabled', true);
    // Debug what's being sent
    const formDelete = serializeForm("#form-delete");
    console.log("Sending data:", formDelete);

    $.ajax({
      type: "POST", // Use POST request
      url: "../admin/room-status-modals/delete-room-status.php", // URL for saving room
      data: formDelete, // Serialize the form data for submission, Add ID to form data
      dataType: "json", // Expect JSON response
      success: function (response) {
        if (response.status === "success") {
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-delete")[0].reset(); // Reset the form
          // Optionally, reload roomlist to show new entry
          viewroomStatus();
        }else{
          console.error("Error deleting data on delete-room-status.php:", response.message);
          alert("Failed to delete class details!");
          $("#staticBackdrop").modal("hide");
          $("#form-delete")[0].reset(); // Reset the form
          viewroomStatus();
        }

        
      },
      error: function(xhr, status, error) {
        alert("Failed to delete class status schedule!");
        console.error("Error deleting data on delete-room-status.php:", status, error);
      }

    });
  }

  //function to fetch room name, goes to roomlist folder, fetch-room-name
  function customDropdown(optionText, dropdownId, optionId, fetchUrl, appendOptionsCallback) {
    const dropdownList = dropdownId;
    if (!fetchUrl) {
      return; // Exit the function early
    }
    // Fetch data for the dropdown
    $.ajax({
      url: fetchUrl,
      type: "GET",
      dataType: "json",
      success: function(data) {
        dropdownList.empty(); // Clear existing options
        
        // Use the provided callback to append options
        appendOptionsCallback(data, dropdownList);

        // Open dropdown on input click
        optionText.on('click', function(event) {
          event.stopPropagation();
          $('.dropdown-list').not(dropdownList).hide();
          dropdownList.toggle();
          filterItems();
        });

        // Filter items based on input
        optionText.on('input', function() {
          filterItems();
        });

        // Select an item and update the input value
        dropdownList.on('click', 'div', function(event) {
          event.stopPropagation();
          const selectedText = $(this).text();
          const selectedValue = $(this).data('value');
          
          optionText.val(selectedText);
          optionId.val(selectedValue);
          dropdownList.hide();

          // Trigger change event on class-id when an option is selected
          optionId.trigger('change'); // This will call the change event listener
        });

        // Function to filter items
        function filterItems() {
          const filter = optionText.val().toLowerCase();
          let hasVisibleItems = false;

          dropdownList.children('div').each(function() {
            const item = $(this);
            if (item.text().toLowerCase().includes(filter)) {
                item.show();
                hasVisibleItems = true;
            } else {
                item.hide();
            }
          });

          dropdownList.toggle(hasVisibleItems);
        }

        // Close dropdown when clicking outside
        $(document).on('click', function(event) {
          if (!$(event.target).closest('.dropdown').length) {
              dropdownList.hide();
          }
        });
      },
      error: function(xhr, status, error) {
        console.error("Error fetching data:", error);
        alert('Failed to fetch data.');
      }
    });
  }

  // function handleSelection(selectedText, selectedValue) {
  //   // Perform the desired action here
  //   console.log(`Selected: ${selectedText}, Value: ${selectedValue}`);
  //   // You can add more actions based on the selection
  // }

  // function cleanInput(input) {
  //   // Trim whitespace from both sides of the string
  //   input = input.trim();
  //   // Remove backslashes
  //   input = input.replace(/\\/g, '');
  //   // Allow specific tags and capture attributes
  //   const allowedTags = ['strong', 'em', 'b', 'i', 'u', 'div'];
  //   const tagRegex = new RegExp(`<\/?(${allowedTags.join('|')})(\\s+[^>]*)?>`, 'gi');
  //   // Create a temporary DOM element to escape HTML
  //   const tempElement = document.createElement('div');
  //   tempElement.innerText = input; // This automatically escapes HTML
  //   // Get the escaped HTML
  //   const sanitizedInput = tempElement.innerHTML;
  //   // Reintroduce allowed tags while preserving class attributes
  //   return sanitizedInput.replace(/&lt;/g, '<')
  //                        .replace(/&gt;/g, '>')
  //                        .replace(/&amp;/g, '&')
  //                        .replace(tagRegex, (match) => match);
  // }

  function cleanInput(input) {
    // Trim whitespace from both sides of the string
    input = input.trim();
    // Remove backslashes
    input = input.replace(/\\/g, '');
    
    // Allow specific tags without attributes
    const allowedTags = ['strong', 'em', 'b', 'i', 'u', 'div'];
    const tagRegex = new RegExp(`<\/?(${allowedTags.join('|')})>`, 'gi');
    
    // Create a temporary DOM element to escape HTML
    const tempElement = document.createElement('div');
    tempElement.innerText = input; // This automatically escapes HTML

    // Get the escaped HTML
    const sanitizedInput = tempElement.innerHTML;

    // Reintroduce allowed tags without attributes
    return sanitizedInput.replace(/&lt;/g, '<')
                         .replace(/&gt;/g, '>')
                         .replace(/&amp;/g, '&')
                         .replace(tagRegex, (match) => match);
  }

  function showAlert(message, duration) {
    const alertBox = document.getElementById('customAlert');
    const alertCard = document.getElementById('alert-card');

    alertBox.textContent = message; // Set the alert message
    alertBox.style.display = 'block'; // Show the alert
    alertCard.style.display = 'block'; // Show the alert

    // Trigger a reflow to restart the transition
    alertCard.offsetHeight; // This forces a reflow
    alertBox.offsetHeight; // This forces a reflow

    // Set the opacity to 1 to fade in
    alertCard.style.opacity = '0.75';
    alertBox.style.opacity = '1';

    // Set a timer to hide the alert after the specified duration
    setTimeout(() => {
      // Fade out the alert
      alertCard.style.opacity = '0';
      alertBox.style.opacity = '0';

      // After the fade-out, hide the elements
      setTimeout(() => {
          alertBox.style.display = 'none'; // Hide the alert
          alertCard.style.display = 'none'; // Hide the alert
      }, 500); // Match this duration with the CSS transition duration
  }, duration);
  }
});