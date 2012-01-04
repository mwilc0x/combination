<?php
/**
 * Combination Class
 *
 * This is our ORM (Object Relational Mapper). One instance of Combination 
 * corresponds to one row in the database. We use this class to abstract our 
 * SQL operations, so you're never directly writing SQL queries. Instead, you 
 * instantiate a new Combination object, and either create and save, or search 
 * and return its contents.
 */
class Combination {
	public $config;
	public $content;
	public $content_type;
}
?>
