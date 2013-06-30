<?php // views/elements/user/emails.ctp
?>

<?php foreach ($user['UserEmail'] as $userEmail): ?>
  <div>
    <li><?php echo $userEmail['email']; ?></li>
  </div>
<?php endforeach; ?>
