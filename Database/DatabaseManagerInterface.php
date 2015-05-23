<?php namespace Quallsbenson\Repository\Database;



interface DatabaseManagerInterface{

	public function connect();
	public function isConnected();
	
} 