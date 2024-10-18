/** JavaScript for form handling **/
document.getElementById('myForm').addEventListener('submit', function(event) {
  event.preventDefault();  // Prevent the default form submission

  // Gather form data
  const formData = new FormData(this);

  // Send form data via AJAX to the PHP script
  fetch('formhandler.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())  // Expecting a text response from PHP
  .then(result => {
    // Update the ARIA live region and the DOM with the response from PHP
    document.getElementById('liveRegion').innerHTML = result;
    document.getElementById('responseMessage').innerHTML = result;
  })
  .catch(error => {
    // Handle errors and notify the live region
    const errorMessage = 'Error submitting form!';
    document.getElementById('liveRegion').innerHTML = errorMessage;
    document.getElementById('responseMessage').innerHTML = errorMessage;
    console.error('Error:', error);
  });
});
