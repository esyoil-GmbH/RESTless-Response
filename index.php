<?php

use RESTless\Response;

include ("vendor/autoload.php");

Response::getInstance()
    ->isJson()
    ->error(-1)
    ->meta(['author' => 'BG'])
    ->content(["contains" => "nothing realy"])
    ->send();
