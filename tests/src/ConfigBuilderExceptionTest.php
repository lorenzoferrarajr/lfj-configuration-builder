<?php

use Lfj\ConfigurationBuilder\ConfigurationBuilder;

class ConfigurationBuilderExceptionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Lfj\ConfigurationBuilder\Exception\FileNotExistsException
     */
    public function testFileNotExistsException()
    {
        $configurationBuilder = new ConfigurationBuilder();

        $configurationBuilder->withFile('file-does-not-exist');
        $configurationBuilder->build();
    }

    /**
     * @expectedException Lfj\ConfigurationBuilder\Exception\NotFileException
     */
    public function testNotFileException()
    {
        $configurationBuilder = new ConfigurationBuilder();

        $configurationBuilder->withFile(__DIR__);
        $configurationBuilder->build();
    }

    /**
     * @expectedException Lfj\ConfigurationBuilder\Exception\NotArrayException
     */
    public function testNotArrayException()
    {
        $configurationBuilder = new ConfigurationBuilder();

        $configurationBuilder->withFile(__DIR__.'/../config/not-array.php');
        $configurationBuilder->build();
    }
}
