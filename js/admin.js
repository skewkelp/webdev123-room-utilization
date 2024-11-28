$(document).ready(function () { 
  // Event listener for navigation links
  $(".nav-link").on("click", function (e) {
    e.preventDefault(); // Prevent default anchor click behavior
    $(".nav-link").removeClass("link-active"); // Remove active class from all links
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
  // Event listener for the dashboard link
  $("#roomlist-link").on("click", function (e) {
    e.preventDefault(); // Prevent default behavior
    viewroomList(); // Call the function to load analytics
  });

  // Event listener for the products link
  $("#roomstatus-link").on("click", function (e) {
    e.preventDefault(); // Prevent default behavior
    viewroomStatus(); // Call the function to load products
  });

  $("#products-link").on("click", function (e) {
    e.preventDefault(); // Prevent default behavior
    viewProducts(); // Call the function to load products
  });


  // Determine which page to load based on the current URL
  let url = window.location.href;
  if (url.endsWith("room-list")) {
    $("#roomlist-link").trigger("click"); // Trigger the dashboard click event
  } else if (url.endsWith("room-status")) {
    $("#roomstatus-link").trigger("click"); // Trigger the roomstatus click 
  } else if (url.endsWith("products")) {
    $("#products-link").trigger("click"); // Trigger the products click event
  } else {
    $("#roomlist-link").trigger("click"); // Default to dashboard if no specific page
  }

  // Function to load analytics view
  function viewroomList() {
    $.ajax({
      type: "GET", // Use GET request
      url: "viewroomlist.php", // URL for the analytics view
      dataType: "html", // Expect HTML response
      success: function (response) {
        $(".content-page").html(response); // Load the response into the content area
         // Call function to load the chart

        var table = $("#table-room-list").DataTable({
          dom: "rtp",
          pageLength: 10,
          ordering: false
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
          table.search(this.value).draw(); // Search products based on input
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
      
          // Call the AJAX function
          editRoom(this.dataset.id).always(function() {
              // Re-enable the button after the AJAX call completes
              button.prop("disabled", false);
          });
        });
        
      },
    });
  }

  function viewroomStatus() {
    $.ajax({
      type: "GET", // Use GET request
      url: "../class-room-status/viewclass-status.php", // URL for the analytics view
      dataType: "html", // Expect HTML response
      success: function (response) {
        $(".content-page").html(response); // Load the response into the content area

        var table = $("#table-room-status").DataTable({
            dom: "rtp", // Set DataTable options
            pageLength: 10, // Default page length
            ordering: false, // Disable ordering
        });
    
        // Form event listener
        $("#room-form").on("submit", function (e) {
            e.preventDefault(); // Prevent default behavior
    
            // Get values from the dropdowns
            const roomName = $('#room-name-filter').val();
            const roomType = $('#room-type-filter').val();
            const status = $('#room-status-filter').val();
            const action = e.originalEvent.submitter.value; // Get the value of the button that triggered the submit

            // Debugging: Log the retrieved values
            console.log("Room Name:", roomName, "Room Type:", roomType, "Status:", status, "Action:", action);
            // Clear previous filters
            table.search('').columns().search('').draw();
    
            if (action === "filter") {
                // Apply filters based on selected values
                if (roomName && roomName !== "choose") {
                    table.column(1).search(roomName); // Filter by room name (column 1)
                }
    
                if (roomType && roomType !== "choose") {
                    table.column(2).search(roomType); // Filter by room type (column 2)
                }
    
                if (status && status !== "choose") {
                    table.column(9).search(status); // Filter by status (column 3)
                }
                // Redraw the table after setting the filters
                table.draw();
            } else if (action === "all") {
                // Logic to show all records or reset filters
                table.search('').columns().search('').draw(); // Clear all filters
            }
        });
        
         // Call function to load the chart
         $("#add-room-status").on("click", function (e) {
           e.preventDefault(); // Prevent default behavior
           // addstatus(); // Call function to add status
         });
         
        $(".room-schedule").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          //viewschedule(); // Call the function to view class schedule
        });

        $(".room-status").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          // statusChange(); // Call the function to occupy or unoccupy a room
        });

        $(".edit-room-status").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
      
          const button = $(this); // Reference to the clicked button
          button.prop("disabled", true); // Disable the button
      
          // Call the AJAX function
          // editroomStatus(this.dataset.id).always(function() {
          //     // Re-enable the button after the AJAX call completes
          //     button.prop("disabled", false);
          // });
        });
        
      },
    });
  }


  $(".room-status").on("click", function (e) {
    e.preventDefault(); // Prevent default behavior
    viewroomList(); // Call the function to load products
  });

  
  // Function to show the add product modal
  function editRoom(roomId) {
    $.ajax({
      type: "GET", // Use GET request
      url: "../admin/roomlist/edit.php?v=" + new Date().getTime(), // URL to get product data
      dataType: "html", // Expect JSON response
      success: function (view) {
        fetchroomlistRecord(roomId);
        fetchroomType();
        // Assuming 'view' contains the new content you want to display
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdropedit").modal("show"); // Show the modal
        $("#staticBackdropedit").attr("data-id", roomId);

        // Event listener for the add product form submission
        $("#form-edit-room").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          updateRoom(roomId); // Call function to save product
        });
      },
    });
  }

  //updateRoom
  function updateRoom(roomId) {
    $.ajax({
      type: "POST", // Use POST request
      url: `../admin/roomlist/update-room.php?id=${roomId}`, // URL for saving room
      data: $("form").serialize(), // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        if (response.status === "error") {
          // Handle validation errors
          if (response.nameErr) {
            $("#room-name").addClass("is-invalid"); // Mark field as invalid
            $("#room-name").next(".invalid-feedback").text(response.nameErr).show(); // Show error message
          } else {
            $("#room-name").removeClass("is-invalid"); // Remove invalid class if no error
          }
          
          if (response.typeErr) {
            $("#room-type").addClass("is-invalid");
            $("#room-type")
              .next(".invalid-feedback")
              .text(response.typeErr)
              .show();
          } else {
            $("#room-type").removeClass("is-invalid");
          }
          
        } else if (response.status === "success") {
          // On success, hide modal and reset form
          $("#staticBackdropedit").modal("hide");
          $("form")[0].reset(); // Reset the form
          // Optionally, reload products to show new entry
          viewroomList();
        }
      },
    });
  }



    // Function to save a new product
  function updateProduct(productId) {
    $.ajax({
      type: "POST", // Use POST request
      url: `../products/update-product.php?id=${roomId}`, // URL for saving product
      data: $("form").serialize(), // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        if (response.status === "error") {
          // Handle validation errors
          if (response.codeErr) {
            $("#code").addClass("is-invalid"); // Mark field as invalid
            $("#code").next(".invalid-feedback").text(response.codeErr).show(); // Show error message
          } else {
            $("#code").removeClass("is-invalid"); // Remove invalid class if no error
          }
          if (response.nameErr) {
            $("#name").addClass("is-invalid");
            $("#name").next(".invalid-feedback").text(response.nameErr).show();
          } else {
            $("#name").removeClass("is-invalid");
          }
          if (response.categoryErr) {
            $("#category").addClass("is-invalid");
            $("#category")
              .next(".invalid-feedback")
              .text(response.categoryErr)
              .show();
          } else {
            $("#category").removeClass("is-invalid");
          }
          if (response.priceErr) {
            $("#price").addClass("is-invalid");
            $("#price")
              .next(".invalid-feedback")
              .text(response.priceErr)
              .show();
          } else {
            $("#price").removeClass("is-invalid");
          }
        } else if (response.status === "success") {
          // On success, hide modal and reset form
          $("#staticBackdropedit").modal("hide");
          $("form")[0].reset(); // Reset the form
          // Optionally, reload products to show new entry
          viewProducts();
        }
      },
    });
  }
    
  function stockProduct(){
    $.ajax({
      type: "GET", // Use GET request
      url: "../products/stock-product.html", // URL for add product view
      dataType: "html", // Expect HTML response
      success: function (view) {
        $(".modal-container").html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal

        // Event listener for the add product form submission
        $("#form-stock-product").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          saveStock(); // Call function to save product
        });
      },
    });
  }

  function saveStock() {
    $.ajax({
      type: "POST", // Use POST request
      url: "../products/stock-product.php", // URL for saving product
      data: $("form").serialize(), // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        if (response.status === "error") {
          // Handle validation errors
         
          if (response.quantityErr) {
            $("#quantity").addClass("is-invalid");
            $("#quantity")
              .next(".invalid-feedback")
              .text(response.quantityErr)
              .show();
          } else {
            $("#quantity").removeClass("is-invalid");
          }

          if (response.statusErr) {
            $("#status").addClass("is-invalid");
            $("#status")
              .next(".invalid-feedback")
              .text(response.statusErr)
              .show();
          } else {
            $("#status").removeClass("is-invalid");
          }

        } else if (response.status === "success") {
          // On success, hide modal and reset form
          $("#staticBackdrop").modal("hide");
          $("form")[0].reset(); // Reset the form
          // Optionally, reload products to show new entry
          viewProducts();
        }
      },
    });
  }

  //function to show the add room modal
  function addRoom() {
    $.ajax({
      type: "GET", // Use GET request
      url: "../admin/roomlist/add.html", // URL for add product view
      dataType: "html", // Expect HTML response
      success: function (view) {
        $(".modal-container").html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal

        fetchroomType(); // Load room type for the select input

        // Event listener for the add product form submission
        $("#form-add-room").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          saveRoom(); // Call function to save product
        });
      },
    });
  }


  //test, test1

  // Function to save a new room
  function saveRoom(){
    $.ajax({
      type: "POST", // Use POST request
      url: "../admin/roomlist/save-room.php", // URL for saving room
      data: $("form").serialize(), // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        if (response.status === "error") {
          // Handle validation errors
          if (response.nameErr){
            $("#room-name").addClass("is-invalid"); // Mark field as invalid
            $("#room-name").next(".invalid-feedback").text(response.nameErr).show(); // Show error message
          } else {
            $("#room-name").removeClass("is-invalid"); // Remove invalid class if no error
          }
          
          if (response.typeErr) {
            $("#room-type").addClass("is-invalid");
            $("#room-type")
              .next(".invalid-feedback")
              .text(response.typeErr)
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


  // Function to fetch room type
  function fetchroomType(){
    $.ajax({
      url: "../admin/roomlist/fetch-roomtype.php", // URL for fetching categories
      type: "GET", // Use GET request
      dataType: "json", // Expect JSON response
      success: function (data) {
        // Clear existing options and add a default "Select" option
        $("#room-type").empty().append('<option value="">--Select--</option>');

        // Append each category to the select dropdown
        $.each(data, function (index, room) {
          $("#room-type").append(
            $("<option>", {
              value: room.type_id, // Value attribute
              text: room.r_type // Displayed text
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

  function fetchroomlistRecord(roomId) {
    $.ajax({
      url: `../admin/roomlist/fetch-room.php?id=${roomId}`, // URL for fetching categories
      type: "GET", // Use GET request
      dataType: "json", // Expect JSON response
      success: function (room) {
        $("#room-name").val(room.room_name); // val(name of var initialized within fetch-room.php  .   refers to room.class.php public var)
        $("#room-type").val(room.room_type).trigger("change"); //
      },

      error: function (xhr, status, error) {
        alert('Failed to fetch roomlist.');
        console.error("Error fetching roomlist:", status, error);
      }
    });
  }




});
