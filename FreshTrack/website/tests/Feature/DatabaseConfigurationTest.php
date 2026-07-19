<?php

namespace Tests\Feature;

use Tests\TestCase;

class DatabaseConfigurationTest extends TestCase
{
    public function test_default_database_connection_is_mysql(): void
    {
        $this->assertSame('mysql', config('database.default'));
        $this->assertSame('mysql', config('database.connections.mysql.driver'));
    }
}
