<p>Goodbye sir.</p>

<?php
  use Symfony\Component\Routing;
  $generator = new Routing\Generator\UrlGenerator($routes, $context);
  $link =  $generator->generate('hello', array('name' => 'Toon'), TRUE);
?>

<p><a href="<?php echo $link; ?>">Say hi to Toon</a></p>
