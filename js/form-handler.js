// JavaScript (form-handler.js)

document.getElementById("myForm").addEventListener("submit", function (event) {
  event.preventDefault();
  document.getElementById("myForm").style.display = "none";
  event.preventDefault();

  const formData = new FormData(this);

  function purifyInput(input) {
    if (window.DOMPurify) {
      const sanitizedInput = DOMPurify.sanitize(input, {
        ALLOWED_TAGS: ["b", "i", "em", "strong", "p", "br"],
        ALLOWED_ATTR: [],
      });
      return sanitizedInput;
    } else {
      console.error("DOMPurify is not loaded!");
      return input;
    }
  }

  fetch("../formhandler.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((result) => {
      try {
        const parsedResult = JSON.parse(result);
        const message = purifyInput(parsedResult.message);
        document.getElementById("status").textContent =
          `${parsedResult.status}`;
        document.getElementById("confirmation").textContent =
          `${parsedResult.confirmation}`;
        document.getElementById("message").innerHTML = `${message}`;
      } catch (e) {
        document.getElementById("liveRegion").textContent =
          `Thank you, your message has been sent:

${result}`;
      }
      console.log("Success:", result);
    })
    .catch((error) => {
      const errorMessage = "An error occurred. Please try again.";
      document.getElementById("status").textContent = errorMessage;
      console.error("Error:", error);
    });
});
