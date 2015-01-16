<?php namespace Designplug\Repository\Database;



interface DatabaseManagerInterface{

	public function connect();
	public function isConnected();
	
} 