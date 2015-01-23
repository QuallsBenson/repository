#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;
use Designplug\Repository\CLI\Command\Generate;

require dirname(__FILE__) .'/../../vendor/autoload.php';

$application = new Application();
$application->add(new Generate);
$application->run();
