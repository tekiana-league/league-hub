<?php
	function db_connect()
	{
		// Attempt a connection to the DB
		$link = pg_connect(getenv("DATABASE_URL"));
		
		return $link;
	}
	
	function db_verify_conn($link)
	{
		// Ping the DB connection
		return pg_ping($link);
	}
	
	function db_disconnect($link)
	{
		// Close the Db connection
		pg_close($link);
	}
	
	function db_exec($link, $statement, $arguments)
	{
		// Prepare the statement for execution
		$stmt = pg_prepare($link, "", $statement);
		
		// Bind the arguments and execute, storing the result
		$result = pg_execute($link, "", $arguments);
		
		// Return the result
		return $result;
	}
?>