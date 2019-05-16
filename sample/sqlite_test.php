<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-04-24
 * Time: 10:14
 */
$db = new SQLite3("./sqlite.db");
$db->exec("create table foo( bar STRING)");
$db->exec("INSERT INTO foo (bar) VALUES ('This is a test')");

$result = $db->query('SELECT bar FROM foo');

var_dump($result->fetchArray());