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
	
	function db_select($link, $stmt, ...$args)
	{
		// Prepare the statement for execution
		$counter = 1;
		$stmt = str_replace(';', '', $stmt);
		foreach ($args as $i)
		{
			$stmt = str_replace('$'.strval($counter), '\''.strval($i).'\'', $stmt);
			$counter++;
		}
		//echo "<script>console.log(\"PHP: ".$stmt."\");</script>";
		
		// Bind the arguments and execute, storing the result
		$res = pg_query($link, $stmt);
		
		// Store the result into an array
		$result = array();
		if (!$res || (pg_num_rows($res) == 0))
		{
			// Do nothing
			//echo "<script>console.log('PHP: ".'empty array'."');</script>";
		}
		else
		{
			// Store the result into the array
			//echo "<script>console.log('PHP: ".'non-empty array'."');</script>";
			$result = pg_fetch_all($res);
		}
		
		// Return the result
		return $result;
	}
	
	function db_exec($link, $stmt, ...$args)
	{
		// Prepare the statement for execution
		$counter = 1;
		$stmt = str_replace(';', '', $stmt);
		foreach ($args as $i)
		{
			$stmt = str_replace('$'.strval($counter), '\''.strval($i).'\'', $stmt);
			$counter++;
		}
		//echo "<script>console.log(\"PHP: ".$stmt."\");</script>";
		
		// Bind the arguments and execute, storing the result
		$res = pg_query($link, $stmt);
		
		// Return the appropriate boolean result
		if (!$res)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
?>