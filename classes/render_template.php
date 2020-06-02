<?php

class render_template
{
    public $temp, $data;

    function __construct($temp, $data)
    {
        $this->temp = $temp;
        $this->data = $data;
    }
    function render()
    {
        $f = file_get_contents($this->temp);
        preg_match_all('/{{(.+?)}}/',$f, $m );
        return str_replace($m[0], $this->data, $f);
    }

}