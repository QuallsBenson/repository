<?php namespace Designplug\Repository\Tests\Database;

use Designplug\Repository\Database\DatabaseManagerInterface;

class DatabaseManager implements DatabaseManagerInterface{

	function connect(){
		return 'connected';
	}

	function isConnected(){
		return 'is connected';
	}

}