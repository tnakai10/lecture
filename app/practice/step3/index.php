<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Step 3: PHP のロジック</title>
</head>
<body>
  <h1>こんにちは！</h1>
  <p>現在の日時: <?php echo date('Y年m月d日 H:i:s'); ?></p>
  <p>今日は<?php echo date('l'); ?>です。</p>

  <?php if (date('H') < 12): ?>
    <p>午前中です。おはようございます！</p>
  <?php else: ?>
    <p>午後です。こんにちは！</p>
  <?php endif; ?>

  <h2>1 から 5 までのリスト</h2>
  <ul>
    <?php for ($i = 1; $i <= 5; $i++): ?>
      <li>項目 <?php echo $i; ?></li>
    <?php endfor; ?>
  </ul>
</body>
</html>
