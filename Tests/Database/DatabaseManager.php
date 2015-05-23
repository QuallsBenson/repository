<?php namespace Quallsbenson\Repository\Tests\Database;

use Quallsbenson\Repository\Database\DatabaseManagerInterface;

class DatabaseManager implements DatabaseManagerInterface{

	function connect(){
		return 'connected';
	}

	function isConnected(){
		return 'is connected';
	}

	function insert(){
		return 'insert';
	}

	function delete(){
		return 'delete';
	}

}
