// Function to show the edit product modal
function editProduct(productId) {
$.ajax({
type: "GET", // Use GET request
url: `../products/get-product.php?id=${productId}`, // URL to get product data
dataType: "json", // Expect JSON response
success: function (product) {
// Populate the modal with product data
$("#code").val(product.code);
$("#name").val(product.name);
$("#category").val(product.category_id); // Set the selected category
$("#price").val(product.price);

// Show the modal
$("#staticBackdrop").modal("show");

// Fetch categories to ensure the dropdown is populated
fetchCategories();

// Event listener for the edit product form submission
$("#form-edit-product").on("submit", function (e) {
e.preventDefault(); // Prevent default form submission
updateProduct(productId); // Call function to update product
});
},
});
}

// Function to update a product
function updateProduct(productId) {
$.ajax({
type: "POST", // Use POST request
url: `../products/edit-product.php?id=${productId}`, // URL for updating product
data: $("form").serialize(), // Serialize the form data for submission
dataType: "json", // Expect JSON response
success: function (response) {
if (response.status === "error") {
// Handle validation errors
handleValidationErrors(response);
} else if (response.status === "success") {
// On success, hide modal and reset form
$("#staticBackdrop").modal("hide");
$("form")[0].reset(); // Reset the form
viewProducts(); // Reload products to show updated entry
}
},
});
}

// Function to handle validation errors
function handleValidationErrors(response) {
if (response.codeErr) {
$("#code").addClass("is-invalid"); // Mark field as invalid
$("#code").next(".invalid-feedback").text(response.codeErr).show(); // Show error message
} else {
$("#code").removeClass("is-invalid");
}
if (response.nameErr) {
$("#name").addClass("is-invalid");
$("#name").next(".invalid-feedback").text(response.nameErr).show();
} else {
$("#name").removeClass("is-invalid");
}
if (response.categoryErr) {
$("#category").addClass("is-invalid");
$("#category").next(".invalid-feedback").text(response.categoryErr).show();
} else {
$("#category").removeClass("is-invalid");
}
if (response.priceErr) {
$("#price").addClass("is-invalid");
$("#price").next(".invalid-feedback").text(response.priceErr).show();
} else {
$("#price").removeClass("is-invalid");
}
}

// Event listener for the edit button
$(document).on("click", ".edit-product", function (e) {
e.preventDefault(); // Prevent default behavior
const productId = $(this).data("id"); // Get product ID from data attribute
editProduct(productId); // Call the function to show edit modal
});