#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;
use Quallsbenson\Repository\CLI\Command\GenerateCommand;

require dirname(__FILE__) .'/../../vendor/autoload.php';

$application = new Application();
$application->add(new GenerateCommand);
$application->run();
