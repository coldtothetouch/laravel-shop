<?php

namespace Tests\Unit;

use App\Http\Controllers\HomeController;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_page_is_working()
    {
        $response = $this->get(action(HomeController::class));

        $response->assertOk();
    }
}
