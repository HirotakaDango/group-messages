<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: session.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Chat</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php include('bootstrap.php'); ?>
    <style>
      /* For Webkit-based browsers */
      ::-webkit-scrollbar {
        width: 0;
        height: 0;
        border-radius: 10px;
      }

      ::-webkit-scrollbar-track {
        border-radius: 0;
      }

      ::-webkit-scrollbar-thumb {
        border-radius: 0;
      }

      #chat-messages {
        overflow-y: auto;
        max-height: 100vh; /* Adjust as necessary */
      }
    </style>
  </head>
  <body>
    <div class="container px-0 bg-dark-subtle" style="max-width: 500px;">
      <h5 class="text-start bg-body-tertiary w-100 p-3 fixed-top container" style="max-width: 500px;">Welcome, <?php echo $_SESSION['username']; ?>!</h5>
      <div class="vh-100 overflow-auto" id="chat-container">
        <div class="container" style="padding-top: 4em; padding-bottom: 4em;" id="chat-messages"></div>
      </div>
      <div class="fixed-bottom container py-3" style="max-width: 500px;">
        <form id="message-form">
          <div class="input-group w-100 rounded-0 shadow-lg">
            <textarea id="message-input" class="form-control bg-body-tertiary border-0 rounded-start-5 focus-ring focus-ring-<?php include($_SERVER['DOCUMENT_ROOT'] . '/appearance/mode.php'); ?>" style="height: 40px; max-height: 150px;" placeholder="Type a message..." aria-label="Type a message..." aria-describedby="basic-addon2" 
            onkeydown="if(event.keyCode == 13) { this.style.height = (parseInt(this.style.height) + 10) + 'px'; return true; }"
            onkeyup="this.style.height = '40px'; var newHeight = (this.scrollHeight + 10 * (this.value.split(/\r?\n/).length - 1)) + 'px'; if (parseInt(newHeight) > 150) { this.style.height = '150px'; } else { this.style.height = newHeight; }"></textarea>
            <button type="submit" class="btn bg-body-tertiary border-0 rounded-end-5"><i class="bi bi-send-fill"></i></button>
          </div>
        </form>
      </div>
    </div>
    <script>
      function loadMessages() {
        $.get('message_load.php', function(data) {
          $('#chat-messages').html(data);
          // Scroll to the bottom of the chat messages container
          $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
        });
      }

      // Initial load and refresh every 5 seconds
      $(document).ready(function() {
        loadMessages();
        setInterval(loadMessages, 5000);

        // Handle form submission
        $('#message-form').submit(function(e) {
          e.preventDefault();
          $.post('send_message.php', {message: $('#message-input').val()}, function() {
            $('#message-input').val('');
            loadMessages();
          });
        });

        // Handle message deletion
        $(document).on('click', '.delete-message', function() {
          var messageId = $(this).data('id');
          $.post('delete_message.php', {id: messageId}, function() {
            loadMessages();
          });
        });
      });
    </script>
  </body>
</html>