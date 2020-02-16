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
	
	function db_select($link, $statement, $arguments)
	{
		// Prepare the statement for execution
		$stmt = pg_prepare($link, "", $statement);
		
		// Bind the arguments and execute, storing the result
		$res = pg_execute($link, "", $arguments);
		
		$result = array();
		if (!$res || (pg_num_rows($res) == 0))
		{
			echo "<script>console.log('PHP: ".'empty array'."');</script>";
		}
		else
		{
			echo "<script>console.log('PHP: ".'non-empty array'."');</script>";
			$result = pg_fetch_all();
			/*while ($row = pg_fetch_array($result))
			{
				array_push($result, $row);
			}*/
		}
		
		// Return the result
		return $result;
	}
?>