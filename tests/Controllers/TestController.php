<?php

namespace Jmarcos16\Mine\Tests\Controllers;

use Jmarcos16\Mine\Attribute\Route;

class TestController
{
    #[Route('/test')]
    public function index()
    {
        echo 'Hello from TestController';
    }

    #[Route('/test/{id}', methods: ['GET'])]
    public function show()
    {
        echo 'Hello from TestController::show';
    }
}