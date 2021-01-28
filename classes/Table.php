<?php


interface Table
{
    public function create($data);
    public function read($data);
    public function update($data, $column);
    public function delete($data);
}