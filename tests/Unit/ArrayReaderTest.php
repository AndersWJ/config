<?php

namespace Tests\Unit;

use Exception;
use InvalidArgumentException;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Awj\Config\Reader\ArrayReader;
use org\bovigo\vfs\vfsStreamWrapper;
use Awj\Config\Contract\ConfigReader;
use org\bovigo\vfs\vfsStreamDirectory;

class ArrayReaderTest extends TestCase
{
    /**
     * @throws \org\bovigo\vfs\vfsStreamException
     */
    public function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('configPath'));
        vfsStream::create([
            'config.php' => '<?php return ["key" => "value"];',
            'readme.nd'  => 'hello world'
        ]);
    }

    /** @test */
    public function it_throws_an_exception_if_the_config_path_is_invalid()
    {
        $reader = null;

        try {
            $reader = new ArrayReader('/this/path/does/not/exist');
        } catch (Exception $exception) {
            $this->assertInstanceOf(InvalidArgumentException::class, $exception);
            $this->assertEquals('Path /this/path/does/not/exist not found for configuration', $exception->getMessage());
        }

        $this->assertNull($reader);
    }

    /** @test */
    public function it_can_initialize_the_ArrayReader_if_the_path_is_set()
    {
        $reader = new ArrayReader(vfsStream::url('configPath'));

        $this->assertInstanceOf(ArrayReader::class, $reader);
        $this->assertInstanceOf(ConfigReader::class, $reader);
    }

    /** @test */
    public function it_returns_null_if_the_config_key_is_an_empty_string()
    {
        $reader = new ArrayReader(vfsStream::url('configPath'));

        $this->assertNull($reader->get(''));
    }

    /** @test */
    public function it_returns_null_if_the_config_key_is_not_a_string()
    {
        $reader = new ArrayReader(vfsStream::url('configPath'));

        $this->assertNull($reader->get(12));
    }

    /** @test */
    public function it_returns_null_if_the_config_key_does_not_exist()
    {
        $reader = new ArrayReader(vfsStream::url('configPath'));

        $this->assertNull($reader->get('key.does.not.exist'));
    }

    /** @test */
    public function it_returns_a_value_from_the_configuration()
    {
        $reader = new ArrayReader(vfsStream::url('configPath'));

        $this->assertEquals('value', $reader->get('config.key'));
        $this->assertEquals([
            'key' => 'value'
        ], $reader->get('config'));
    }
}
