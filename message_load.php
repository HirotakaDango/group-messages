<?php
session_start();
$db = new SQLite3('messaging.db');

$result = $db->query("SELECT messages.id, messages.email, messages.message, messages.date, users.username 
                      FROM messages 
                      JOIN users ON messages.email = users.email 
                      ORDER BY messages.date DESC");

$messages = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
  $messages[] = $row;
}
?>

<?php foreach (array_reverse($messages) as $message): ?>
  <div class="card p-3 shadow border-0 my-2 <?= $message['email'] == $_SESSION['email'] ? 'text-align-right ms-auto bg-light-subtle rounded-top-0 rounded-start-4 rounded-bottom-4' : 'text-align-left me-auto bg-secondary-subtle rounded-top-0 rounded-end-4 rounded-bottom-4' ?>" style="max-width: 250px;">
    <div class="position-relative">
      <?php if ($message['email'] == $_SESSION['email']): ?>
        <div class="dropdown z-2 position-absolute top-0 end-0">
          <button class="btn border-0 p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-three-dots-vertical text-white link-body-emphasis" style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4), 2px 2px 4px rgba(0, 0, 0, 0.3), 3px 3px 6px rgba(0, 0, 0, 0.2); text-stroke: 2;"></i>
          </button>
          <ul class="dropdown-menu">
            <li>
              <button class="dropdown-item delete-message" data-id="<?= $message['id'] ?>">Delete</button>
            </li>
          </ul>
        </div>
      <?php endif; ?>
    </div>
    <strong>@<?= $message['username'] ?></strong><br>
    <?php
      if (!function_exists('getYouTubeVideoId')) {
        function getYouTubeVideoId($urlCommentThread)
        {
          $videoIdThread = '';
          $patternThread = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
          if (preg_match($patternThread, $urlCommentThread, $matchesThread)) {
            $videoIdThread = $matchesThread[1];
          }
          return $videoIdThread;
        }
      }

      $mainTextThread = isset($message['message']) ? $message['message'] : '';

      if (!empty($mainTextThread)) {
        $paragraphsThread = explode("\n", $mainTextThread);

        foreach ($paragraphsThread as $indexThread => $paragraphThread) {
          $textWithoutTagsThread = strip_tags($paragraphThread);
          $patternThread = '/\bhttps?:\/\/\S+/i';

          $formattedTextThread = preg_replace_callback($patternThread, function ($matchesThread) {
            $urlThread = htmlspecialchars($matchesThread[0]);

            // Check if the URL ends with .png, .jpg, .jpeg, or .webp
            if (preg_match('/\.(png|jpg|jpeg|webp)$/i', $urlThread)) {
              return '<a href="' . $urlThread . '" target="_blank"><img class="img-fluid rounded-4" loading="lazy" src="' . $urlThread . '" alt="Image"></a>';
            } elseif (strpos($urlThread, 'youtube.com') !== false) {
              // If the URL is from YouTube, embed it as an iframe with a very low-resolution thumbnail
              $videoIdThread = getYouTubeVideoId($urlThread);
              if ($videoIdThread) {
                $thumbnailUrlThread = 'https://img.youtube.com/vi/' . $videoIdThread . '/default.jpg';
                return '<div class="w-100 overflow-hidden position-relative ratio ratio-16x9"><iframe loading="lazy" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" class="rounded-4 position-absolute top-0 bottom-0 start-0 end-0 w-100 h-100 border-0 shadow" src="https://www.youtube.com/embed/' . $videoIdThread . '" frameborder="0" allowfullscreen></iframe></div>';
              } else {
                return '<a href="' . $urlThread . '">' . $urlThread . '</a>';
              }
            } else {
              return '<a href="' . $urlThread . '" target="_blank">' . $urlThread . '</a>';
            }
          }, $textWithoutTagsThread);

          echo '<p class="text-break mb-2">' . $formattedTextThread . '</p>';
        }
      }
    ?>
    <small class="text-end mt-3"><?= date('l, j F Y | H:i', strtotime($message['date'])) ?></small>
  </div>
<?php endforeach; ?>