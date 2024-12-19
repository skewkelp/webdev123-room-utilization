let pickSemester = false;

$(document).ready(function () { 
  //hide restricted elements
  function hideRestrictedElements() {
    try {
        const userPermissions = window.userPermissions || {};
        
        // Log permissions for debugging
        console.debug('User Permissions:', userPermissions);
        
        // Hide admin elements
        if (!userPermissions.isAdmin) {
            $('.admin').addClass('d-none');
        }
        
        // Hide staff elements
        if (!userPermissions.isStaff) {
            $('.staff').addClass('d-none');
        }
        
        // Hide elements requiring either permission
        if (!userPermissions.isAdmin && !userPermissions.isStaff) {
            $('.restricted').addClass('d-none');
        }
    } catch (error) {
        console.error('Error in hideRestrictedElements:', error);
    }
  }   

  // Initial hide
  hideRestrictedElements();
 
  // Single ajaxComplete handler
  $(document).ajaxComplete(function() {
    hideRestrictedElements();
  });

  // Alternative approach using MutationObserver
  const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.addedNodes.length) {
            hideRestrictedElements();
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
 
         if (sidebar.width() === 260) {
             sidebar.addClass("collapsed");
             navLabel.toggle();
             content.css("margin-left", "70px");
         } else {
             sidebar.removeClass("collapsed");
             navLabel.toggle();
             content.css("margin-left", "260px");
         }
 
         // Re-enable the button after the action
         $("#burger").prop('disabled', false);
     }, 300); //0.3 secs

  });

  
  //SIDE BAR NAVIGATION LINK BUTTON
  // Event listener for the roomlist-link
  $("#roomlist-link").on("click", function (e) {
    e.preventDefault(); // Prevent default behavior
    viewroomList(); // Call the function to load analytics
  });


  // Event listener for the roomstatus-link
  $("#roomstatus-link").on("click", function (e) {
    e.preventDefault();
    
    // Check if semester is picked for this session
    $.ajax({
        url: '../fetch-data/check-semester-picked.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
          
            if (response.semesterPicked) {
                viewroomStatus();
            } else {
                semesterLoad();
            }
        },
        error: function() {
            // Fallback to semester picker on error
            semesterLoad();
        }
    });
  });

  //Event listener for the roomschedule-link
  $("#roomschedule-link").on("click", function (e) {
    e.preventDefault(); // Prevent default behavior
    viewroomSchedule(); // Call the function to load products
  });

  $("#profile-link").on("click", function (e) {
    e.preventDefault(); // Prevent default behavior
    viewProfile(); // Call the function to load products
  });

  // href="room-schedule" id="roomschedule-link" 

  // Determine which page to load based on the current URL
  let url = window.location.href;
  if (url.endsWith("room-list")) {
    $("#roomlist-link").trigger("click"); // Trigger the dashboard click event
  } else if (url.endsWith("room-status")) {
    $("#roomstatus-link").trigger("click"); // Trigger the roomstatus click 
  } else if (url.endsWith("room-schedule")) {
    $("#roomschedule-link").trigger("click"); // Trigger the products click event
  } else if (url.endsWith("profile-page")) {
    $("#profile-link").trigger("click"); // Trigger the products click event
  } else {
    $("#roomlist-link").trigger("click"); // Default to dashboard if no specific page
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

        $(".edit-room").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
      
          const button = $(this); // Reference to the clicked button
          button.prop("disabled", true); // Disable the button
      
           // Call the AJAX function
          // editRoom(this.dataset.id);

          // Call the AJAX function
          // editRoom(this.dataset.id).always(function() {
          //   button.prop("disabled", false); // Re-enable the button after AJAX completes
          // });


        });
        
      },
      error: function (xhr, status, error) {
        alert('Failed to load viewProfile.php.');
        console.error("Error loading viewProfile.php:", status, error);
      }
    });
  }

  

  // $(".room-status").on("click", function (e) {
  //   e.preventDefault(); // Prevent default behavior
  //   viewroomList(); // Call the function to load products
  // });


  function semesterLoad() {
    $.ajax({
      type: "GET",
      url: "../class-room-status/choose-semester.php",
      dataType: "html",
      success: function (response) {
        $(".content-page").html(response);
        
        fetchSemester();
        
        // Event handler for semester form submission
        $("#form-semester").on("submit", function (e) {
          e.preventDefault();
          // Save semester choice
          semesterPick($(this)); // Pass the form element to the function
        });
      }
    });
  }

  function semesterPick(form){
    $.ajax({
      url: '../class-room-status/save-semester.php',
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
          viewroomStatus();
        }
      },
      error: function(xhr, status, error) {
        alert('Failed to save semester.');
        console.error("Error saving semester:", status, error);
      }
    });
  }
  



  // Function to load room status view
  function viewroomStatus() {
    $.ajax({
      type: "GET", // Use GET request
      url: "../class-room-status/viewclass-status.php", // URL for the analytics view
      dataType: "html", // Expect HTML response
      success: function (response) {
        $(".content-page").html(response); // Load the response into the content area
        
        // Initialize DataTable for class details first
        var tableDetails = $("#table-class-details").DataTable({
            dom: "rtp",
            pageLength: 10,
            ordering: false
        });
        
        // Then bind the search event
        $("#search-class-details").on("keyup", function () {
            console.log("Search triggered", this.value);
            console.log("Table instance:", tableDetails);
            tableDetails.search(this.value).draw();
        });

        // Get the select element
        const selectDay = document.getElementById("day");

        // Function to set the current day in the dropdown, real-time
        function setCurrentDay() {
          const options = ["Sunday","Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
          const currentDayIndex = new Date().getDay();  //
          const currentDay = options[currentDayIndex]; // Get current day name

          // Set the dropdown value to the current day
          selectDay.value = currentDay;
          fetchDayData(); // Fetch data for the current day
        }

        function fetchDayData() {
          const selectedDay = selectDay.value;
          console.log("Selected option:", selectedDay);

          // Make an AJAX call to fetch data based on the selected day
          $.ajax({
              type: "POST", // Use POST request
              url: "../fetch-data/fetch-scheduled-classday.php", // URL to your PHP script that handles the request
              data: { selected_day: selectedDay }, // Send selected day as data
              success: function(response) {
                  // Update the table body with the fetched data
                  $("#table-room-status tbody").html(response);
              },
              error: function(xhr, status, error) {
                  console.error("Error fetching data:", error);
              }
          });
        }

        function initializeDataTable() {
          if ($.fn.DataTable.isDataTable('#table-room-status')) {
              $('#table-room-status').DataTable().destroy();
          }
          
          var table = $("#table-room-status").DataTable({
              dom: "rtp",
              pageLength: 10,
              ordering: false,
          });

          // Bind custom input to DataTable search
          $("#custom-search").off('keyup').on("keyup", function () {
              table.search(this.value).draw();
          });

          // Room form filter handler
          $("#room-form").off('submit').on("submit", function (e) {
              e.preventDefault();

              const roomName = $('#room-name-filter').val();
              const roomType = $('#room-type-filter').val();
              const status = $('#room-status-filter').val();
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

              const subjectCode = $('#subject-code-filter').val();
              const subjectType = $('#subject-type-filter').val();
              const section = $('#section-filter').val();
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

        // // Initialize
        setCurrentDay();
        selectDay.addEventListener("change", fetchDayData);
        initializeDataTable();

        
        //EVENT LISTENER FOR CLASS DETAILS
        //call function to load modal form add class details
        
        //add class details
        $("#add-class-details").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          addclassDetails();
        });

        $(".edit-class-details").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
      
          const button = $(this); // Reference to the clicked button
          button.prop("disabled", true); // Disable the button
      
          // Call the AJAX function
          editclassDetails(this.dataset.id).always(function() {
            button.prop("disabled", false); // Re-enable the button after AJAX completes
          });
        });
        

        $(".delete-class-details").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
      
          const button = $(this); // Reference to the clicked button
          button.prop("disabled", true); // Disable the button
          
          deletingclassDetails(this.dataset.id).always(function() {
            button.prop("disabled", false); // Re-enable the button after AJAX completes
          });
        });
        //end ---


        
         // Call function to load modal form class status
        $("#add-room-status").on("click", function (e) {
           e.preventDefault(); // Prevent default behavior
          addroomStatus(); // Call function to add status
        });
         

        $('#table-room-status').on('click', '.room-schedule, .room-status, .edit-room-status, .display-status, .delete-room-status', function(e) {
          e.preventDefault();
          const button = $(this);
          button.prop("disabled", true);
          
          if ($(this).hasClass('edit-room-status')) {
              editroomStatus(this.dataset.id).always(function() {
                  button.prop("disabled", false);
              });
          } 
          else if ($(this).hasClass('room-schedule')) {
              viewSchedule(this.dataset.id).always(function() {
                  button.prop("disabled", false);
              });
          }
          else if ($(this).hasClass('room-status')) {
              statusChange(this.dataset.id).always(function() {
                  button.prop("disabled", false);
              });
          }
          else if ($(this).hasClass('display-status')) {
              displayStatus().always(function() {
                  button.prop("disabled", false);
              });
          }
          else if ($(this).hasClass('delete-room-status')) {
              deleteconfirmationStatus(this.dataset.id).always(function() {
                  button.prop("disabled", false);
              });
          }
        });

      },
    });
  }


  //Function to load room schedule view, load content
  //edit here
  function viewroomSchedule() {
    $.ajax({
      type: "GET", // Use GET request
      url: "../room-schedule/viewroom-schedule.php", // URL for the analytics view
      dataType: "html", // Expect HTML response
      success: function (response) {
        $(".content-page").html(response); // Load the response into the content are
        
        var table = $("#table-room-schedule").DataTable({
            dom: "rtp", // Set DataTable options
            pageLength: 10, // Default page length
            ordering: false, // Disable ordering
        });
    
        // Bind custom input to DataTable search
        $("#custom-search").on("keyup", function () {
          table.search(this.value).draw(); // Search room based on input
        });

    

       
      },
    });
  }


  // Function to load analytics view
  function viewroomList() {
    $.ajax({
      type: "GET", // Use GET request
      url: "../room-list/viewroomlist.php", // URL for the analytics view
      dataType: "html", // Expect HTML response
      success: function (response) {
        $(".content-page").html(response); // Load the response into the content area
         // Call function to load the chart

        var table = $("#table-room-list").DataTable({
          dom: "rtp",
          pageLength: 10,
          ordering: false
        });
        
        // Bind custom input to DataTable search
        $("#custom-search").on("keyup", function () {
          table.search(this.value).draw(); // Search room based on input
        });
        
        // Bind change event for room name filter
        $("#roomname-filter").on("change", function () {
          if (this.value !== "choose") {
            table.column(1).search(this.value).draw(); // Filter by room name (column 1)
          } else {
            // Clear the filter for the room name column if "choose" is selected
            table.column(1).search('').draw();
          }
        });
        
        // Bind change event for room type filter
        $("#roomtype-filter").on("change", function () {
          if (this.value !== "choose") {
            table.column(2).search(this.value).draw(); // Filter by room type (column 2)
          } else {
            // Clear the filter for the room type column if "choose" is selected
            table.column(2).search('').draw();
          }
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

        $(".room-schedule").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          // editRoom(); // Call the function to load products
        });

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
    });
  }

  //Function for ROOM LIST, MODAL AJAX
  // Function to show the add product modal
  function editRoom(roomCode, roomNo) {
    return $.ajax({
      type: "GET", // Use GET request
      url: "../room-list/edit.php?v=" + new Date().getTime(), // URL to get product data
      dataType: "html", // Expect JSON response
      success: function (view) {
        // Assuming 'view' contains the new content you want to display
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal
        $("#staticBackdroped").attr("data-id", roomCode, roomNo);

        const modal = $('#staticBackdrop');
        
        //Old fetching call 
        // fetchroomlistRecord(roomCode, roomNo);
        // fetchroomType();

        //Promise chaining, load fetchroomType returns data/promise then runs fetchroomlistRecord once after promise received
        fetchroomType()
        .then(() => {
            return fetchroomlistRecord(roomCode, roomNo);
        })
        .catch(error => {
            console.error("Error in room type loading sequence:", error);
        });

        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal); // Pass modal to closeModal function
        }); 

        // Event listener for the add product form submission
        $("#form-edit-room").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          updateRoom(roomCode, roomNo); // Call function to save product
        });
      },
    });
  }

  //updateRoom
  function updateRoom(roomCode, roomNo) {
    $.ajax({
      type: "POST", // Use POST request
      url: `../room-list/update-room.php?roomCode=${roomCode}&roomNo=${roomNo}`, // URL for saving room
      data: $("form").serialize(), // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        if (response.status === "error") {
          // Handle validation errors
          if (response.generalErr){
            $("#general-error").removeClass("d-none").html(response.generalErr);
          } else {
            $("#general-error").addClass("d-none");
          }

          if (response.room_nameErr) {
            $("#room-name").addClass("is-invalid"); // Mark field as invalid
            $("#room-name").next(".invalid-feedback").text(response.room_nameErr).show(); // Show error message
          } else {
            $("#room-name").removeClass("is-invalid"); // Remove invalid class if no error
          }
          
          if (response.room_typeErr) {
            $("#room-type").addClass("is-invalid");
            $("#room-type")
              .next(".invalid-feedback")
              .text(response.room_typeErr)
              .show();
          } else {
            $("#room-type").removeClass("is-invalid");
          }
          
        } else if (response.status === "success") {
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("form")[0].reset(); // Reset the form
          // Optionally, reload products to show new entry
          viewroomList();
        }
      },
    });
  }

  //function to show the add room modal
  function addRoom() {
    $.ajax({
      type: "GET", // Use GET request
      url: "../room-list/add.html?v=" + new Date().getTime(), // URL for add product view
      dataType: "html", // Expect HTML response
      success: function (view) {
        $(".modal-container").html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal

        const modal = $('#staticBackdrop');

        fetchroomType(); // Load room type for the select input

        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal); // Pass modal to closeModal function
        }); 

        // Event listener for the add product form submission
        $("#form-add-room").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          saveRoom(); // Call function to save product
        });
      },
    });
  }

  // Function to save a new room
  function saveRoom(){
    $.ajax({
      type: "POST", // Use POST request
      url: "../room-list/save-room.php", // URL for saving room
      data: $("form").serialize(), // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        if (response.status === "error") {
          // Handle validation errors
          if (response.generalErr){
            $("#general-error").removeClass("d-none").html(response.generalErr);
          } else {
            $("#general-error").addClass("d-none");
          }

          if (response.room_nameErr){
            $("#room-name").addClass("is-invalid"); // Mark field as invalid
            $("#room-name").next(".invalid-feedback").text(response.room_nameErr).show(); // Show error message
          } else {
            $("#room-name").removeClass("is-invalid"); // Remove invalid class if no error
          }
          
          if (response.room_typeErr) {
            $("#room-type").addClass("is-invalid");
            $("#room-type")
              .next(".invalid-feedback")
              .text(response.room_typeErr)
              .show();
          } else {
            $("#room-type").removeClass("is-invalid");
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
        alert('Failed to load save room.php.');
        console.error("Error saving php room:", status, error);
      }

    });
  }

  //Function for class details, MODAL AJAX
  //add room status
  function addclassDetails() {
    $.ajax({
      type: "GET", // Use GET request
      url: "../class-room-status/add-class-detail.html?v=" + new Date().getTime(), // URL for add product view
      dataType: "html", // Expect HTML response
      success: function (view) {
        $(".modal-container").html(view); // Load the modal view
        console.log("Modal content loaded successfully.");
        $("#staticBackdrop").modal("show");
        
        const modal = $('#staticBackdrop');

        fetchSubject();//fetchsubject
        fetchSection();//fetchsection
        fetchTeacher();//fetchTeacher list

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
        alert("An error occurred while loading the modal: " + error);
      }
    });
  }

  //Function for class details, php handling
  //save class details
  function saveclassDetails(){
    // Debug what's being sent
    const formClassDetails = $("#form-add").serialize();
    console.log("Sending data:", formClassDetails);
    
    $.ajax({
      type: "POST", // Use POST request
      url: "../class-room-status/save-class-detail.php", // URL for saving room
      data: formClassDetails, // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        console.log("Response received:", response);
        if (response.status === "error") {
          // Handle validation errors 

          //Check if class id is already existing
          if (response.generalErr){
            $("#general-error").removeClass("d-none").html(response.generalErr);
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
            $(".subject-type").siblings(".invalid-feedback").text(response.subject_typeErr).show();
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
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-add")[0].reset(); // Reset the form
          // Optionally, reload page to show new entry
          viewroomStatus();
        }
      },
      error: function (xhr, status, error) {
        alert('Failed to load save-room-status.php.');
        console.error("Error saving php room status:", status, error);
      }

    });
  }

  function editclassDetails(classDetailsPK) {
    // Split the composite ID into its parts
      const [classId, subjectId] = classDetailsPK.split('|');
      
      return $.ajax({
        type: "GET", // Use GET request
        url: "../class-room-status/edit-class-detail.html?v=" + new Date().getTime(), // URL 
        dataType: "html", // Expect JSON response
        success: function (view) {
          // Assuming 'view' contains the new content you want to display
          $(".modal-container").empty().html(view); // Load the modal view
          $("#staticBackdrop").modal("show"); // Show the modal
          $("#staticBackdroped").attr("data-id", classDetailsPK);

          const modal =  $('#staticBackdrop');
              
              // Then fetch and populate the data
              $.ajax({
                  url: `../fetch-data/fetch-class-detail.php?classId=${classId}&subjectId=${subjectId}`, //2 parameters separated by &
                dataType: "json",
                success: function(data) {
                    console.log('Fetched data:', data);

                    $('#original-class-id').val(data.id);
                    $('#class-id').val(data.id);

                    $('#original-subject-id').val(data.subject_id);
                    $('#dropdown-subject').val(data.subject_);
                    $('#hidden-subject-id').val(data.subject_id);

                    $('#dropdown-section').val(data.section_);
                    $('#hidden-section-id').val(data.section_id);

                    $('#dropdown-teacher').val(data.teacher_);
                    $('#hidden-teacher-assigned').val(data.teacher_id);

                    $('#dropdown-room').val(data.room_);
                    $('#hidden-room-id').val(data.room_id);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching status record:", error);
                }
            });

            fetchSubject();
            fetchSection();
            fetchTeacher();
            fetchroomName();
            
            $(".modal-close").on("click", function (e) {
                e.preventDefault();
                closeModal(modal);
            }); 

            $("#form-edit").on("submit", function (e) {
                e.preventDefault();
                updateclassDetails();
            });
        },
        error: function (xhr, status, error) {
            alert("An error occurred while loading the modal: " + error);
        }
    });
  }

  function updateclassDetails(){
    // Debug what's being sent
    const formClassDetails = $("#form-edit").serialize();
    console.log("Sending data:", formClassDetails);
    
    $.ajax({
      type: "POST", // Use POST request
      url: "../class-room-status/update-class-detail.php", // URL for saving room
      data: formClassDetails, // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        console.log("Response received:", response);
        if (response.status === "error") {
          // Handle validation errors

          //Check if class id is already existing
          if (response.existing_classErr){
            $("#existing-class-error").removeClass("d-none").text(response.existing_classErr);
          } else {
            $("#existing-class-error").addClass("d-none");
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

          if(response.room_idErr){
            $("#dropdown-room").addClass("is-invalid");
            $("#dropdown-room").siblings(".invalid-feedback").text(response.room_idErr).show();
          } else {
            $("#dropdown-room").removeClass("is-invalid");
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
        alert('Failed to load save-room-status.php.');
        console.error("Error saving php room status:", status, error);
      }

    });
  }

  //Load delete modal
  function deletingclassDetails(classDetailsPK){
    
    // Split the composite ID into its parts
    const [classId, subjectId] = classDetailsPK.split('|');
    
    return $.ajax({
      type: "GET", // Use GET request
      url: "../class-room-status/deleting-class-detail.html?v=" + new Date().getTime(), // URL to get product data
      dataType: "html", // Expect JSON response
      success: function (view) {
        // Assuming 'view' contains the new content you want to display
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal
        $("#staticBackdroped").attr("data-id", classDetailsPK);

        const modal = $('#staticBackdrop');
        
        $.ajax({
          url: `../fetch-data/fetch-class-detail.php?classId=${classId}&subjectId=${subjectId}`,
          dataType: "json",
          success: function(data) {
              console.log('Fetched data:', data.id, data.subject_id); // For debugging
              //Fetch class id from query
              $('#hidden-class-id').val(data.id);
              //Fetch class subject id
              $('#hidden-subject-id').val(data.subject_id);
          },
          error: function(xhr, status, error) {
              console.error("Error fetching status record:", error);
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
      error: function (xhr, status, error) {
        alert("An error occurred while loading the modal: " + error);
      }
    });
  } 

  function deleteclassDetails(){
    const submitButton = $("#form-delete button[type='submit']");
    submitButton.prop('disabled', true);
    
    const formClassDetails = $("#form-delete").serialize();
    console.log("Sending data:", formClassDetails);

    $.ajax({
      type: "POST", // Use POST request
      url: "../class-room-status/delete-class-details.php", // URL for saving room
      data: formClassDetails, // Serialize the form data for submission, Add ID to form data
      dataType: "json", // Expect JSON response
      success: function (response) {
        console.log("Response received:", response);
        if (response.status === "success") {
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-delete")[0].reset(); // Reset the form
          // Optionally, reload page to show new entry
          viewroomStatus();
        }
      },
      error: function (xhr, status, error) {
        alert('Failed to load delete-room-status.php.');
        console.error("Error deleting class schedule status:", status, error);
      }

    });
  }



  //Function for room status, MODAL AJAX
  //add room status
  function addroomStatus() {
    $.ajax({
      type: "GET", // Use GET request
      url: "../class-room-status/add-room-status.html?v=" + new Date().getTime(), // URL for add product view
      dataType: "html", // Expect HTML response
      success: function (view) {
        $(".modal-container").html(view); // Load the modal view
        console.log("Modal content loaded successfully.");
        $("#staticBackdrop").modal("show");
        
        const modal = $('#staticBackdrop');

        fetchClasses();
     
        $(".modal-close").on("click", function (e) {
          e.preventDefault();
          closeModal(modal); // Pass modal to closeModal function
        }); 

        // Event listener for the add room status form submission
        $("#form-add").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          const button = $(this); // Reference to the clicked button
          button.prop("disabled", true); // Disable the button
          saveroomStatus(); // Call function to save room status
      
        });
        
      },
      error: function (xhr, status, error) {
        alert("An error occurred while loading the modal: " + error);
      }
    });
  }

  function saveroomStatus(){
    const submitButton = $("#form-add button[type='submit']");
    submitButton.prop('disabled', true);

    // Debug what's being sent  
    const formaddclassStatus = $("#form-add").serialize();
    console.log("Sending data:", formaddclassStatus);

    $.ajax({
      type: "POST", // Use POST request
      url: "../class-room-status/save-room-status.php", // URL for saving room
      data: formaddclassStatus, // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        if (response.status === "error") {
          // Handle validation errors
          if (response.existing_classErr){
            $("#existing-class-error").removeClass("d-none").text(response.existing_classErr);
          } else {
            $("#existing-class-error").addClass("d-none");
          }

          if (response.class_PKErr){
            $("#dropdown-class-id").addClass("is-invalid"); // Mark field as invalid
            $("#dropdown-class-id").siblings(".invalid-feedback").text(response.class_PKErr).show(); // Show error message
          } else {
            $("#dropdown-class-id").removeClass("is-invalid"); // Remove invalid class if no error
          }
          
          if (response.start_timeErr){
            $("#start-time").addClass("is-invalid"); // Mark field as invalid
            $("#start-time").siblings(".invalid-feedback").text(response.start_timeErr).show(); // Show error message
          } else {
            $("#start-time").removeClass("is-invalid"); // Remove invalid class if no error
          }

          if (response.end_timeErr){
            $("#end-time").addClass("is-invalid"); // Mark field as invalid
            $("#end-time").siblings(".invalid-feedback").text(response.end_timeErr).show(); // Show error message
          } else {
            $("#end-time").removeClass("is-invalid"); // Remove invalid class if no error
          }

          if (response.day_idErr){
            $(".day-id").addClass("is-invalid"); // Mark field as invalid
            $(".day-id").siblings(".invalid-feedback").text(response.day_idErr).show(); // Show error message
          } else {
            $(".day-id").removeClass("is-invalid"); // Remove invalid class if no error
          }
          
        } else if (response.status === "success") {
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-add")[0].reset(); // Reset the form
          // Optionally, reload roomlist to show new entry
          viewroomStatus();
        }
      },
      error: function (xhr, status, error) {
        alert('Failed to load save-room-status.php.');
        console.error("Error saving php room status:", status, error);
      }

    });
  }

  function editroomStatus(roomstatusId){
    return $.ajax({
      type: "GET", // Use GET request
      url: "../class-room-status/edit-room-status.php?v=" + new Date().getTime(), // URL to get product data
      dataType: "html", // Expect JSON response
      success: function (view) {
        // Assuming 'view' contains the new content you want to display
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal
        $("#staticBackdroped").attr("data-id", roomstatusId);

        const modal = $('#staticBackdrop');
        
        // Then fetch and populate the data
        $.ajax({
          url: `../fetch-data/fetch-room-status.php?id=${roomstatusId}`,
          dataType: "json",
          success: function(data){
              console.log('Fetched data:', data); // For debugging
              console.log('Original Day ID:', data.day_id);
              // console.log('Hidden field value after setting:', $('#hidden-original-day-id').val());

              //Fetch class status id
              $('#hidden-class-status-id').val(data.class_status_id);
              //Fetch class id
              $('#hidden-class-day-id').val(data.class_day_id);
              $('#hidden-original-day-id').val(data.day_id);//what day
              $('#hidden-class-time-id').val(data.class_time_id);//what time
              $('#hidden-original-class-id').val(data.class_id);//what class
              $('#original-subject-id').val(data.subject_id);//what subject
              $('#hidden-original-start-time').val(data.start_time);//what start time
              $('#hidden-original-end-time').val(data.end_time);//what end time


              $('#dropdown-class-id').val(data.class_display);
              $('#hidden-class-id').val(data.class_id);

              //Fetch-class-time-id

              // Populate time fields
              $('#start-time').val(data.start_time);
              $('#end-time').val(data.end_time);
              

              // Check the appropriate day checkbox
              $(`input[name="day-id"][value="${data.day_id}"]`).prop('checked', true);
          },
          error: function(xhr, status, error) {
              console.error("Error fetching status record:", error);
          }
        });

        fetchClasses();

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
      error: function (xhr, status, error) {
        alert("An error occurred while loading the modal: " + error);
      }
    });
  }

  function updateroomStatus(){
    const submitButton = $("#form-edit button[type='submit']");
    submitButton.prop('disabled', true);

    // Debug what's being sent
    const formClassStatus = $("#form-edit").serialize();
    console.log("Sending data:", formClassStatus);

    $.ajax({
      type: "POST", // Use POST request
      url: "../class-room-status/update-room-status.php?v=" + new Date().getTime(), // URL for saving room
      data: formClassStatus, // Serialize the form data for submission, Add ID to form data
      dataType: "json", // Expect JSON response
      success: function (response) {
        if (response.status === "error") {
          // Handle validation errors
          if (response.existing_classErr){
            $("#existing-class-error").removeClass("d-none").text(response.existing_classErr);
          } else {
            $("#existing-class-error").addClass("d-none");
          }

          if (response.class_PKErr){
            $("#dropdown-class-id").addClass("is-invalid"); // Mark field as invalid
            $("#dropdown-class-id").siblings(".invalid-feedback").text(response.class_PKErr).show(); // Show error message
          } else {
            $("#dropdown-class-id").removeClass("is-invalid"); // Remove invalid class if no error
          }
         
          if (response.start_timeErr){
            $("#start-time").addClass("is-invalid"); // Mark field as invalid
            $("#start-time").next(".invalid-feedback").text(response.start_timeErr).show(); // Show error message
          } else {
            $("#start-time").removeClass("is-invalid"); // Remove invalid class if no error
          }

          if (response.end_timeErr){
            $("#end-time").addClass("is-invalid"); // Mark field as invalid
            $("#end-time").next(".invalid-feedback").text(response.end_timeErr).show(); // Show error message
          } else {
            $("#end-time").removeClass("is-invalid"); // Remove invalid class if no error
          }

          if (response.day_idErr){
            $(".day-id").addClass("is-invalid"); // Mark field as invalid
            $(".day-id").next(".invalid-feedback").text(response.day_idErr).show(); // Show error message
          } else {
            $(".day-id").removeClass("is-invalid"); // Remove invalid class if no error
          }
          

          $(".modal-close").on("click", function (e) {
            e.preventDefault();
            closeModal(modal); // Pass modal to closeModal function
          }); 
          
        } else if (response.status === "success") {
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-edit")[0].reset(); // Reset the form
          // Optionally, reload roomlist to show new entry
          viewroomStatus();
        }else if(response.status === "logicerror"){

        }
      },
      error: function (xhr, status, error) {
        alert('Failed to load update-room-status.php.');
        console.error("Error updating class schedule status:", status, error);
      }

    });

  }

  //Load delete modal
  function deleteconfirmationStatus(roomstatusId){
    return $.ajax({
      type: "GET", // Use GET request
      url: "../class-room-status/delete-confirmation-status.html?v=" + new Date().getTime(), // URL to get product data
      dataType: "html", // Expect JSON response
      success: function (view) {
        // Assuming 'view' contains the new content you want to display
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal
        $("#staticBackdroped").attr("data-id", roomstatusId);

        const modal = $('#staticBackdrop');
        
        $.ajax({
          url: `../fetch-data/fetch-room-status.php?id=${roomstatusId}`,
          dataType: "json",
          success: function(data) {
              console.log('Fetched data:', data); // For debugging
              //Fetch class status id
              $('#hidden-class-status-id').val(data.class_status_id);
              //Fetch class id
              $('#hidden-class-day-id').val(data.class_day_id);
              
              $('#hidden-class-time-id').val(data.class_time_id);
              
              $('#hidden-class-id').val(data.class_id);
              //Fetch-class-time-id

            
          },
          error: function(xhr, status, error) {
              console.error("Error fetching status record:", error);
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
      error: function (xhr, status, error) {
        alert("An error occurred while loading the modal: " + error);
      }
    });
  }

  function deleteroomStatus(){
    const submitButton = $("#form-delete button[type='submit']");
    submitButton.prop('disabled', true);

    // Debug what's being sent
    const formdeleteClassStatus = $("#form-delete").serialize();
    console.log("Sending data:", formdeleteClassStatus);
    
    $.ajax({
      type: "POST", // Use POST request
      url: "../class-room-status/delete-room-status.php", // URL for saving room
      data: formdeleteClassStatus, // Serialize the form data for submission, Add ID to form data
      dataType: "json", // Expect JSON response
      success: function (response) {
        if (response.status === "error") {
          // Handle validation errors
          
          
        } else if (response.status === "success") {
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("#form-delete")[0].reset(); // Reset the form
          // Optionally, reload roomlist to show new entry
          viewroomStatus();
        }
      },
      error: function (xhr, status, error) {
        alert('Failed to load delete-room-status.php.');
        console.error("Error deleting class schedule status:", status, error);
      }

    });
  }

  // Function to fetch room type
  function fetchroomType(){
    return $.ajax({
      url: "../fetch-data/fetch-roomtype.php", // URL for fetching categories
      type: "GET", // Use GET request
      dataType: "json", // Expect JSON response
      success: function (data) {
        // Clear existing options and add a default "Select" option
        $("#room-type").empty().append('<option value="">--Select--</option>');

        // Append each category to the select dropdown
        $.each(data, function (index, room) {
          $("#room-type").append(
            $("<option>", {
              value: room.room_type_id, // Value attribute
              text: room.room_type_desc // Displayed text
            })
          );
        });
      },
      error: function (xhr, status, error) {
        console.error("Error fetching room type:", error);
        alert('Failed to fetch roomtype.');
      }
    });
  }

  //function to fetch record list of room
  function fetchroomlistRecord(roomCode, roomNo) {
    return $.ajax({
      url: `../fetch-data/fetch-room.php?roomCode=${roomCode}&roomNo=${roomNo}`, // URL for fetching room
      dataType: "json", // Expect JSON response
      success: function (room) {
        $("#room-name").val(`${room.room_name}`); // val(name of var initialized within fetch-room.php  .   refers to room.class.php query var)
        $("#room-type").val(`${room.room_code}`).trigger("change"); //
      },
      error: function (xhr, status, error) {
        alert('Failed to fetch roomlist.');
        console.error("Error fetching roomlist:", status, error);
      }
    });
  }

  //function to fetch room name, goes to roomlist folder, fetch-room-name
  function fetchroomName() {
    $.ajax({
        url: "../fetch-data/fetch-room-name.php", // URL for fetching categories
        type: "GET", // Use GET request
        dataType: "json", // Expect JSON response
        success: function (data) {
            const dropdownList = $('#dropdown-list-name');
            dropdownList.empty(); // Clear existing options
            
            // Append each category to the dropdown list
            $.each(data, function (index, room){
              dropdownList.append(
                  $("<div>", {
                      text: room.room_name, // Displayed text
                      'data-value': room.id // Value attribute, 
                  })// room.attributefromTable
              );
            });
            
            // Open dropdown on input click
            $('#dropdown-room').on('click', function(event) {
              event.stopPropagation(); // Prevent click from bubbling
              // Close other dropdowns
              $('.dropdown-list').not(dropdownList).hide();
              dropdownList.toggle(); // Toggle the current dropdown
              filterItems(); // Reset display based on current input
            });


            // Filter items based on input
            $('#dropdown-room').on('input', function() {
              filterItems();
            });

            // Select an item and update the input value
            dropdownList.on('click', 'div', function(event) {
              event.stopPropagation(); // Prevent click from bubbling
              const selectedText = $(this).text();//Get the displayed text
              const selectedValue = $(this).data('value');//Get data value 
              
              $('#dropdown-room').val(selectedText); // Set the input to displayed text
              $('#hidden-room-id').val(selectedValue); // Set a hidden input to the room ID

              dropdownList.hide();  // Close dropdown
            });

            // Function to filter items
            function filterItems() {
              const filter = $('#dropdown-room').val().toLowerCase();
              let hasVisibleItems = false;

              dropdownList.children('div').each(function() {
                const item = $(this);
                if (item.text().toLowerCase().includes(filter)) {
                    item.show(); // Show item
                    hasVisibleItems = true;
                } else {
                    item.hide(); // Hide item
                }
              });

              // Show or hide the dropdown if there are visible items
              dropdownList.toggle(hasVisibleItems); 
            }

            // Close dropdown when clicking outside
            $(document).on('click', function(event) {
              if (!$(event.target).closest('.dropdown').length) {
                  dropdownList.hide();
              }
            });
        },
        error: function (xhr, status, error) {
          console.error("Error fetching room:", error);
          alert('Failed to fetch room.');
        }
    });
  }

  function fetchSubject() {
    $.ajax({
        url: "../fetch-data/fetch-subject.php", // URL for fetching categories
        type: "GET", // Use GET request
        dataType: "json", // Expect JSON response
        success: function (data) {
          const dropdownList = $('#dropdown-list-subject');
          dropdownList.empty(); // Clear existing options
          
          // Append each category to the dropdown list
          $.each(data, function (index, subject) {
            dropdownList.append(
              $("<div>", {
                text: subject.subject_id, // Displayed text
                'data-value': subject.subject_id // Value attribute
              })
            );
          });
          
          // Open dropdown on input click
          $('#dropdown-subject').on('click', function(event) {
            event.stopPropagation(); // Prevent click from bubbling
            // Close other dropdowns
            $('.dropdown-list').not(dropdownList).hide(); 
            dropdownList.toggle();
            filterItems(); // Reset display based on current input
          });

          // Filter items based on input
          $('#dropdown-subject').on('input', function() {
            filterItems();
          });

          // Select an item and update the input value
          dropdownList.on('click', 'div', function(event) {
            event.stopPropagation(); // Prevent click from bubbling
            
            const selectedText = $(this).text();//Get the displayed text
            const selectedValue = $(this).data('value');//Get data value 
            
            $('#dropdown-subject').val(selectedText); // Set the input to displayed text
            $('#hidden-subject-id').val(selectedValue); // Set a hidden input to the room ID
          
            // $('#dropdown-subject').val($(this).text());//shows selected displayed text on input field
            // $('#dropdown-subject').val($(this).data('value')); // Keep focus for further searching
            dropdownList.hide(); // Close dropdown
          });

          // Function to filter items
          function filterItems() {
            const filter = $('#dropdown-subject').val().toLowerCase();
            let hasVisibleItems = false;

            dropdownList.children('div').each(function() {
              const item = $(this);
              if (item.text().toLowerCase().includes(filter)) {
                  item.show(); // Show item
                  hasVisibleItems = true;
              } else {
                  item.hide(); // Hide item
              }
            });

            // Show or hide the dropdown if there are visible items
            dropdownList.toggle(hasVisibleItems); 
          }

          // Close dropdown when clicking outside
          $(document).on('click', function(event) {
            if (!$(event.target).closest('.dropdown').length) {
              dropdownList.hide();
            }
          });
        },
      error: function (xhr, status, error) {
        console.error("Error fetching subject:", error);
        alert('Failed to fetch subject.');
      }
    });
  }
  
  function fetchSection() {
    $.ajax({
      url: "../fetch-data/fetch-section.php", // URL for fetching categories
      type: "GET", // Use GET request
      dataType: "json", // Expect JSON response
      success: function (data) {
        const dropdownList = $('#dropdown-list-section');
        dropdownList.empty(); // Clear existing options
        
        // Append each category to the dropdown list
        $.each(data, function (index, section) {
          dropdownList.append(
            $("<div>", {
              text: `${section.course_abbr}${section.year_level}${section.section}`, // Displayed text
              'data-value': `${section.course_abbr}|${section.year_level}|${section.section}` // Value attribute
            })
          );
        });
        
        // Open dropdown on input click
        $('#dropdown-section').on('click', function(event) {
          event.stopPropagation(); // Prevent click from bubbling
          // Close other dropdowns
          $('.dropdown-list').not(dropdownList).hide(); 
          dropdownList.toggle();
          filterItems(); // Reset display based on current input
        });

        // Filter items based on input
        $('#dropdown-section').on('input', function() {
          filterItems();
        });

        // Select an item and update the input value
        dropdownList.on('click', 'div', function(event) {
          event.stopPropagation(); // Prevent click from bubbling
          const selectedText = $(this).text(); // Get the displayed text
          const selectedValue = $(this).data('value'); // Get the data-value (ID)
          $('#dropdown-section').val(selectedText); // Set the input to displayed text
          $('#hidden-section-id').val(selectedValue); // Set a hidden input to the room ID
        
          // $('#dropdown-section').val($(this).text());//shows selected displayed text on input field
          // $('#dropdown-section').val($(this).data('value')); // Set input value to selected item
          dropdownList.hide(); // Close dropdown
        });

        // Function to filter items
        function filterItems() {
          const filter = $('#dropdown-section').val().toLowerCase();
          let hasVisibleItems = false;

          dropdownList.children('div').each(function() {
            const item = $(this);
            if (item.text().toLowerCase().includes(filter)) {
              item.show(); // Show item
              hasVisibleItems = true;
            } else {
              item.hide(); // Hide item
            }
          });

          // Show or hide the dropdown if there are visible items
          dropdownList.toggle(hasVisibleItems); 
        }

        // Close dropdown when clicking outside
        $(document).on('click', function(event) {
          if (!$(event.target).closest('.dropdown').length) {
            dropdownList.hide();
          }
        });
      },
      error: function (xhr, status, error) {
        console.error("Error fetching section:", error);
        alert('Failed to fetch section.');
      }
    });
  }

  function fetchTeacher() {//hidden-teacher-assigned
    $.ajax({
        url: "../fetch-data/fetch-teacher.php", // URL for fetching categories
        type: "GET", // Use GET request
        dataType: "json", // Expect JSON response
        success: function (data) {
          const dropdownList = $('#dropdown-list-teacher');
          dropdownList.empty(); // Clear existing options
          
          // Append each category to the dropdown list
          $.each(data, function (index, teacher) {
            dropdownList.append(
              $("<div>", {
                text: teacher.teacher_name, // Displayed text
                'data-value': teacher.faculty_id // Value attribute
              })
            );
          });
          
            // Open dropdown on input click
          $('#dropdown-teacher').on('click', function(event) {
            event.stopPropagation(); // Prevent click from bubbling
            // Close other dropdowns
            $('.dropdown-list').not(dropdownList).hide(); 
            dropdownList.toggle();
            filterItems(); // Reset display based on current input
          });

          // Filter items based on input
          $('#dropdown-teacher').on('input', function() {
            filterItems();
          });

          // Select an item and update the input value
          dropdownList.on('click', 'div', function(event) {
            event.stopPropagation(); // Prevent click from bubbling
            const selectedText = $(this).text(); // Get the displayed text
            const selectedValue = $(this).data('value'); // Get the data-value (ID)
            $('#dropdown-teacher').val(selectedText); // Set the input to displayed text
            $('#hidden-teacher-assigned').val(selectedValue); // Set a hidden input to the room ID
            // $('#dropdown-teacher').val($(this).text());//shows selected displayed text on input field
            // $('#dropdown-teacher').val($(this).data('value')); // Set input value to selected item
            dropdownList.hide(); // Close dropdown
          });

          // Function to filter items
          function filterItems() {
            const filter = $('#dropdown-teacher').val().toLowerCase();
            let hasVisibleItems = false;

            dropdownList.children('div').each(function() {
              const item = $(this);
              if (item.text().toLowerCase().includes(filter)) {
                item.show(); // Show item
                hasVisibleItems = true;
              } else {
                item.hide(); // Hide item
              }
            });

            // Show or hide the dropdown if there are visible items
            dropdownList.toggle(hasVisibleItems); 
          }

          // Close dropdown when clicking outside
          $(document).on('click', function(event) {
            if (!$(event.target).closest('.dropdown').length) {
              dropdownList.hide();
            }
          });
        },
        error: function (xhr, status, error) {
          console.error("Error fetching Teacher:", error);
          alert('Failed to fetch Teacher.');
        }
    });
  }

  function fetchSemester() {
    $.ajax({
      url: "../fetch-data/fetch-semesterList.php",
      type: "GET",
      dataType: "json",
      success: function(data) {
        console.log(data);
        const dropdownList = $('#dropdown-list-semester');
        dropdownList.empty(); // Clear existing options
        
        // Append each category to the dropdown list
        $.each(data, function (index, semester) {
          dropdownList.append(
            $("<div>", {
              text: semester.semester_desc, // Displayed text
              'data-value': `${semester.semester_id}|${semester.school_year}`// Value attribute
            })
          );
        });
        
        // Open dropdown on input click
        $('#dropdown-semester').on('click', function(event) {
          event.stopPropagation(); // Prevent click from bubbling
          // Close other dropdowns
          $('.dropdown-list').not(dropdownList).hide(); 
          dropdownList.toggle();
          filterItems(); // Reset display based on current input
        });

        // Filter items based on input
        $('#dropdown-semester').on('input', function() {
          filterItems();
        });

        // Select an item and update the input value
        dropdownList.on('click', 'div', function(event) {
          event.stopPropagation(); // Prevent click from bubbling
          
          const selectedText = $(this).text(); // Get the displayed text
          const selectedValue = $(this).data('value'); // Get the semester ID
          
          $('#dropdown-semester').val(selectedText); // Set the visible input to the semester text
          $('#hidden-semester-id').val(selectedValue); // Set the hidden input to the semester ID
        
          dropdownList.hide(); // Close dropdown
        });

        // Function to filter items
        function filterItems() {
          const filter = $('#dropdown-semester').val().toLowerCase();
          let hasVisibleItems = false;

          dropdownList.children('div').each(function() {
            const item = $(this);
            if (item.text().toLowerCase().includes(filter)) {
                item.show(); // Show item
                hasVisibleItems = true;
            } else {
                item.hide(); // Hide item
            }
          });

          // Show or hide the dropdown if there are visible items
          dropdownList.toggle(hasVisibleItems); 
        }

        // Close dropdown when clicking outside
        $(document).on('click', function(event) {
          if (!$(event.target).closest('.dropdown').length) {
            dropdownList.hide();
          }
        });

      }
    });
  }

  function fetchClasses() {
    $.ajax({
      url: "../fetch-data/fetch-classes.php",
      type: "GET",
      dataType: "json",
      success: function(data) {
        console.log(data);
        const dropdownList = $('#dropdown-list-class-id');
        dropdownList.empty(); // Clear existing options
        
        // Append each category to the dropdown list
        $.each(data, function (index, classes) {
          dropdownList.append(
            $("<div>", {
              text: classes.class_display, // Displayed text
              'data-value': `${classes.class_id}|${classes.subject_id}`// Value attribute
            })
          );
        });
        
        // Open dropdown on input click
        $('#dropdown-class-id').on('click', function(event) {
          event.stopPropagation(); // Prevent click from bubbling
          // Close other dropdowns
          $('.dropdown-list').not(dropdownList).hide(); 
          dropdownList.toggle();
          filterItems(); // Reset display based on current input
        });

        // Filter items based on input
        $('#dropdown-class-id').on('input', function() {
          filterItems();
        });

        // Select an item and update the input value
        dropdownList.on('click', 'div', function(event) {
          event.stopPropagation(); // Prevent click from bubbling
          
          const selectedText = $(this).text(); // Get the displayed text
          const selectedValue = $(this).data('value'); // Get the semester ID
          
          $('#dropdown-class-id').val(selectedText); // Set the visible input to the semester text
          $('#hidden-class-id').val(selectedValue); // Set the hidden input to the semester ID
        
          dropdownList.hide(); // Close dropdown
        });

        // Function to filter items
        function filterItems() {
          const filter = $('#dropdown-class-id').val().toLowerCase();
          let hasVisibleItems = false;

          dropdownList.children('div').each(function() {
            const item = $(this);
            if (item.text().toLowerCase().includes(filter)) {
                item.show(); // Show item
                hasVisibleItems = true;
            } else {
                item.hide(); // Hide item
            }
          });

          // Show or hide the dropdown if there are visible items
          dropdownList.toggle(hasVisibleItems); 
        }

        // Close dropdown when clicking outside
        $(document).on('click', function(event) {
          if (!$(event.target).closest('.dropdown').length) {
            dropdownList.hide();
          }
        });

      }
    });
  }


});