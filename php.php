<?php

class a {
    public function b()
    {
        return $this;
    }

    public function c(){
        echo '213';
    }
}

$m = 'b';

$a = new a();

$a->$m();