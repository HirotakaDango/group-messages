<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <style>
      body, html {
        margin: 0;
        padding: 0;
        height: 100%;
      }

      .iframe-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
      }

      .iframe-container iframe {
        max-width: 500px;
        width: 100%;
        height: 90vh; /* Set height to 80% of viewport height by default */
        border: none;
      }

      /* Media query for screens up to 767px width */
      @media (max-width: 767px) {
        .iframe-container iframe {
          height: 100%; /* Set height to 100% of viewport height on smaller screens */
        }
      }
    </style>
  </head>
  <body>
    <div class="iframe-container">
      <iframe class="rounded-4 shadow" src="message.php"></iframe>
    </div>
  </body>
</html>
