<?php


interface Table
{
    public function create($data);
    public function read($data);
    public function update($data);
    public function delete($data);
}