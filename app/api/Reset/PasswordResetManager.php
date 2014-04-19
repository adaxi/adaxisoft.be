<?php
define( 'HIGH_IMPORTANCE', 10 );
define( 'MEDIUM_IMPORTANCE', 20 );
define( 'LOW_IMPORTANCE', 40 );


class PasswordResetManager {

	private static function getDatabase() {
		$database = new PDO( 'sqlite:./Reset/data/database.db' );
		$database->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		self::createTables( $database );
		return $database;
	}

	private static function createTables($database) {
		$database->exec(
		   "CREATE TABLE IF NOT EXISTS websites (
                    id INTEGER PRIMARY KEY,
                    title TEXT,
                    importance INTEGER,
                    url TEXT)"
		);
		return $database;
	}

	public static function getList($limit = 100, $offset = 0, $importance = LOW_IMPORTANCE) {
		$database = self::getDatabase();
		$select = "SELECT * FROM websites WHERE importance <= :IMPORTANCE ORDER BY title LIMIT :LIMIT OFFSET :OFFSET";
		$statement = $database->prepare( $select );
		$statement->bindValue( ":IMPORTANCE", $importance );
		$statement->bindValue( ":OFFSET", $limit * $offset );
		$statement->bindValue( ":LIMIT", $limit );
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_CLASS);
	}

	public static function insert($record) {
		
		if (array_key_exists( 'title', $record )) {
			if (! preg_match( '/[ \w]+/', $record['title'] )) {
				throw new Exception( "The title must only consist of alphanumeric characters" );
			}
		} else {
			throw new Exception( "The title is required" );
		}
		
		if (array_key_exists( 'importance', $record )) {
			if (! preg_match( '/\d+/', $record['importance'] )) {
				throw new Exception( "The importance must be a number" );
			}
		} else {
			throw new Exception( "The title is required" );
		}
		
		if (!array_key_exists( 'url', $record )) {
			throw new Exception( "The url is required" );
		}
		
		$database = self::getDatabase();
		$insert = "INSERT INTO websites (title, importance, url) VALUES (:TITLE, :IMPORTANCE, :URL)";
		$statement = $database->prepare( $insert );
		$statement->bindValue( ":TITLE", $record['title'] );
		$statement->bindValue( ":IMPORTANCE", $record['importance'] );
		$statement->bindValue( ":URL", $record['url'] );
		$statement->execute();
	}

	public static function update($record, $id) {
		if (empty($id)) {
			throw new Exception("The record ID is required to update the record.");
		}
		
		$database = self::getDatabase();
		$update = "UPDATE websites SET %s WHERE id = :ID";
		
		$bind = array();
		if (array_key_exists( 'title', $record )) {
			if (! preg_match( '/[ \w]+/', $record['title'] )) {
				throw new Exception( "The title must only consist of alphanumeric characters" );
			}
			$bind[] = "title = :TITLE";
			$values[':TITLE'] = $record['title'];
		}
		
		if (array_key_exists( 'importance', $record )) {
			if (! preg_match( '/\d+/', $record['importance'] )) {
				throw new Exception( "The importance must be a number" );
			}
			$bind[] = "importance = :IMPORTANCE";
			$values[':IMPORTANCE'] = $record['importance'];
		}
		
		if (!array_key_exists( 'url', $record )) {
			$bind[] = "url = :URL";
			$values[':URL'] = $record['url'];
		}
		if ($bind) {
			$update = sprintf($update, implode(",", $bind));
			$statement = $database->prepare($update);
			$statement->bindValue(":ID", $id);
			foreach ($values as $parameter => $value) {
				$statement->bindValue($parameter, $value);
			}
			$statement->execute();
		}
	}
	
	public static function delete($id){
		if (empty($id)) {
			throw new Exception("The record ID is required to delete the record.");
		}
		$database = self::getDatabase();
		$delete = "DELETE FROM websites WHERE id = :ID";
		$statement = $database->prepare( $delete );
		$statement->bindValue( ":ID", $id );
		$statement->execute();
	}
}