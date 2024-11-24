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
     setTimeout(function() {
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
  if (url.endsWith("dashboard")) {
    $("#roomlist-link").trigger("click"); // Trigger the dashboard click event
  } else if (url.endsWith("room-status")) {
    $("#roomstatus-link").trigger("click"); // Trigger the products click 
  } else if (url.endsWith("products")) {
    $("#products-link").trigger("click"); // Trigger the products click event
  } else {
    $("#roomlist-link").trigger("click"); // Default to dashboard if no specific page
  }

  // Function to load analytics view
  function viewroomList() {
    $.ajax({
      type: "GET", // Use GET request
      url: "roomlist.php", // URL for the analytics view
      dataType: "html", // Expect HTML response
      success: function (response) {
        $(".content-page").html(response); // Load the response into the content area
         // Call function to load the chart

        $(".room-status").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          editRoom(); // Call the function to load products
        });

        $(".room-schedule").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          editRoom(); // Call the function to load products
        });

         $(".edit-room").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          editRoom(this.dataset.id); // Call the function to load products
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
      url: "../admin/roomlist/edit.php", // URL to get product data
      dataType: "html", // Expect JSON response
      success: function (view) {
        
        fetchRecord(roomId);
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

    // Function to save a new product
  function updateRoom(roomId) {
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




  

  // Function to show the add product modal
  function addProduct() {
    $.ajax({
      type: "GET", // Use GET request
      url: "../products/add-product.html", // URL for add product view
      dataType: "html", // Expect HTML response
      success: function (view) {
        $(".modal-container").html(view); // Load the modal view
        $("#staticBackdrop").modal("show"); // Show the modal

        fetchCategories(); // Load categories for the select input

        // Event listener for the add product form submission
        $("#form-add-product").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          saveProduct(); // Call function to save product
        });
      },
    });
  }

  // Function to save a new product
  function saveProduct() {
    $.ajax({
      type: "POST", // Use POST request
      url: "../products/add-product.php", // URL for saving product
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
          $("#staticBackdrop").modal("hide");
          $("form")[0].reset(); // Reset the form
          // Optionally, reload products to show new entry
          viewProducts();
        }
      },
    });
  }

  


  // Function to fetch product categories
  function fetchCategories() {
    $.ajax({
      url: "../roomlist/fetch-roomtype.php", // URL for fetching categories
      type: "GET", // Use GET request
      dataType: "json", // Expect JSON response
      success: function (data) {
        // Clear existing options and add a default "Select" option
        $("#room-type").empty().append('<option value="">--Select--</option>');

        // Append each category to the select dropdown
        $.each(data, function (index, roomtype) {
          $("#room-type").append(
            $("<option>", {
              value: roomtype.id, // Value attribute
              text: roomtype.name, // Displayed text
            })
          );
        });
      },
    });
  }

  function fetchRecord(productId) {
    $.ajax({
      url: `../products/fetch-product.php?id=${productId}`, // URL for fetching categories
      type: "POST", // Use GET request
      dataType: "json", // Expect JSON response
      success: function (product) {
        $("#code").val(product.code);
        $("#name").val(product.name);
        $("#category").val(product.category_id).trigger("change"); // Set the selected category
        $("#price").val(product.price);
      },
    });
  }
});
