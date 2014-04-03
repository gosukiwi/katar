<h1>Test Katar</h1>

<?php if($age > 22): ?>
    <p>The age is bigger than 22</p>
<?php else: ?>
    <p>The age is not bigger than 22</p>
<?php endif; ?>

<h2>For demonstration</h2>

<?php $for_index = 0; foreach($people as $person): ?>
    <p><?php echo $person->name; ?></p>
<?php $for_index++; endforeach; ?>

<p>My name is <?php echo $name; ?></p>
