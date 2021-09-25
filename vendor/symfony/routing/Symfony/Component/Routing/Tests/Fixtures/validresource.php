<?php
$collection = $loader->import('validpattern.php');
$collection->addDefaults(array(
    'foo' => 123,
));
$collection->addRequirements(array(
    'foo' => '\d+',
));
$collection->addOptions(array(
    'foo' => 'bar',
));
$collection->setCondition('context.getMethod() == "POST"');
$collection->addPrefix('/prefix');
return $collection;
