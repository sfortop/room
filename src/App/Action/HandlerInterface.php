<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */

/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Action;


use Zend\Diactoros\ServerRequest;

interface HandlerInterface
{
    public function handle(ServerRequest $request);
}