<?php

namespace Tests\Feature;

use Exception;
use RuntimeException;
use Awj\Config\Config;
use PHPUnit\Framework\TestCase;
use Awj\Config\Contract\ConfigReader;

class ConfigTest extends TestCase
{
    /** @test */
    public function it_throws_a_exception_if_it_has_not_been_initialized()
    {
        $value = null;

        try {
            $value = Config::read('someKey');
        } catch (Exception $exception) {
            $this->assertInstanceOf(RuntimeException::class, $exception);
            $this->assertEquals('No config reader has been initialized', $exception->getMessage());
        }

        $this->assertNull($value);
    }

    /** @test */
    public function it_can_be_initialized_statically()
    {
        $reader = $this->createMock(ConfigReader::class);

        try {
            Config::initialize($reader);
        } catch (Exception $exception) {
            $this->fail($exception->getMessage());
        }

        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_get_values_from_the_config_statically()
    {
        $reader = $this->createMock(ConfigReader::class);
        $reader->method('get')->willReturn('someValue');

        Config::initialize($reader);

        $this->assertEquals('someValue', Config::read('someKey'));
    }
}
