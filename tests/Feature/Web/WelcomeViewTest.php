<?php

namespace Tests\Feature\Web;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WelcomeViewTest extends TestCase
{
    #[Test]
    public function application_returns_welcome_view_on_root_path(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    }
}
