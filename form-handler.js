// JavaScript (form-handler.js)
document.getElementById('myForm').addEventListener('submit', function(event) {
  event.preventDefault();
  document.getElementById('myForm').style.display = 'none';
  event.preventDefault();

  const formData = new FormData(this);

  fetch('formhandler.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(result => {
    try {
      const parsedResult = JSON.parse(result);
      document.getElementById('status').textContent = `${parsedResult.status}`;
      document.getElementById('confirmation').textContent = `${parsedResult.confirmation}`;
      document.getElementById('message').textContent = `${parsedResult.message}`;
    } catch (e) {
      document.getElementById('liveRegion').textContent = `Thank you, your message has been sent:

${result}`;
    }
    console.log('Success:', result);
  })
  .catch(error => {
    const errorMessage = 'An error occurred. Please try again.';
    document.getElementById('status').textContent = errorMessage;
    console.error('Error:', error);
  });
});
