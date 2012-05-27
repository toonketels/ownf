<?php $name = isset($name) ? $name : 'World'; ?>

Hello <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>


