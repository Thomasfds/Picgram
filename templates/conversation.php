<?php
$conversation = $db->prepare("SELECT * FROM messages WHERE membre_id = $id GROUP BY conv_id

");
$conversation->execute();
$conversation = $conversation->fetchAll();
?>

  <!-- Modal conversation -->
  <div class="modal fade" id="conversation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Vos conversations</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <?php foreach ($conversation as $conversation) : ?>

            <p>
              <?php if ($conversation['pseudo'] != $pseudo) { ?>
                <a href="<?= $domain; ?>/conversation.php?id=<?php echo $conversation['conv_id']; ?>"><?php echo $conversation['pseudo']; ?>
                </a>
              <?php } ?>
            </p>
          <?php endforeach; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>