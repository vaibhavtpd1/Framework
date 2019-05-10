<?php

interface DatabaseInterface
{
    public function query($sql, $params = []);
    public function insert($table, $fields = []);
    public function update($table, $id, $fields = []);
    public function delete($table, $id);
    public function results();
    public function error();
}
?>