<?php
ini_set('memory_limit','100M');
try {
	$dbh = new PDO('mysql:host=127.0.0.1;dbname=onlytest', 'root', '123456', array(
		PDO::ATTR_PERSISTENT => true));

	$t1 = microtime(true);

	for($j = 1;$j<=500;$j++) {
		$rows = array();
		$i = ($j-1)*10000+1;
		$max = $j*10000;
		for (; $i<=$max; $i++) {
			array_push($rows, array($i, rand(1,3), 'jajajaj'.$i));
		}
		
		$args = array_fill(0, count($rows) * count($rows[0]), '?');
		$params = array();

		$query = "INSERT INTO test_index (id, type, content) VALUES ";
		foreach ($rows as $row) {
			$query .= "(?,?,?),";
			foreach ($row as $value) {
				$params[] = $value;
			}
		}
		$query = substr($query, 0, -1);

		$stmt = $dbh->prepare($query);
		if (!$stmt) {
			echo "\nPDO::errorInfo():\n";
			print_r($dbh->errorInfo());
		}
		$r = $stmt->execute($params);
		unset($query);
		unset($params);
		unset($rows);
	}
	$t2 = microtime(true);
	echo (($t2 - $t1) * 1000) . 'ms';
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}