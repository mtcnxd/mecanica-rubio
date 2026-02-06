<?php

namespace App\Objects;

class CurrencyObject
{
    protected $name;
    protected $last;
    protected $current;
    protected $change;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->last = $data['last'];
        $this->current = $data['current'];
        $this->change = $data['change'];
    }

    public function getName(){ return $this->name; }

    public function getLast(){ return $this->last; }
}