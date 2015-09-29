<?php

use Lfj\ConfigurationBuilder\ConfigurationBuilder;

class ConfigBuilderTest extends PHPUnit_Framework_TestCase
{
    public function testAddFileDataProvider()
    {
        return array(
            array(
                array(
                    __DIR__.'/../config/directory1/file1.global.php',
                ),
                include __DIR__.'/../config/directory1/file1.global.php',
            ),
            array(
                array(
                    __DIR__.'/../config/directory1/file1.global.php',
                    __DIR__.'/../config/directory1/file1.local.php',
                ),
                include __DIR__.'/../config/directory1/file1.local.php',
            ),
            array(
                array(
                    __DIR__.'/../config/directory1/file1.local.php',
                    __DIR__.'/../config/directory1/file1.global.php',
                ),
                include __DIR__.'/../config/directory1/expected-local-global.php',
            ),
        );
    }

    /**
     * @dataProvider testAddFileDataProvider
     */
    public function testAddFile($files, $expected)
    {
        $configurationBuilder = new ConfigurationBuilder();

        foreach ($files as $item) {
            $configurationBuilder->addFile($item);
        }

        $result = $configurationBuilder->build();
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider testAddFileDataProvider
     */
    public function testAddFiles($files, $expected)
    {
        $configurationBuilder = new ConfigurationBuilder();

        $configurationBuilder->addFiles($files);

        $result = $configurationBuilder->build();
        $this->assertEquals($expected, $result);
    }

    public function testAddArrayDataProvider()
    {
        return array(
            array(
                array(
                    array(
                        'key1' => 'value1',
                        'key2' => 'value2',
                    ),
                    array(
                        'key1' => 'value1',
                        'key3' => 'value3',
                    ),
                ),
                array(
                    'key1' => 'value1',
                    'key2' => 'value2',
                    'key3' => 'value3',
                ),
            ),
            array(
                array(
                    array(
                        'key1' => 'value1',
                        'key2' => 'value2',
                    ),
                    array(
                        'key1' => 'value1',
                        'key2' => 'value2A',
                    ),
                ),
                array(
                    'key1' => 'value1',
                    'key2' => 'value2A',
                ),
            ),
        );
    }

    /**
     * @dataProvider testAddArrayDataProvider
     */
    public function testAddArray($arrays, $expected)
    {
        $configurationBuilder = new ConfigurationBuilder();

        foreach($arrays as $array) {
            $configurationBuilder->addArray($array);
        }

        $result = $configurationBuilder->build();
        $this->assertEquals($expected, $result);
    }


    public function testAddDirectoryDataProvider()
    {
        return array(
            array(
                array(
                    __DIR__.'/../config/directory1/*.{global,local}.php',
                ),
                include __DIR__.'/../config/directory1/file1.local.php',
            ),
            array(
                array(
                    __DIR__.'/../config/directory1/*.{local,global}.php',
                ),
                include __DIR__.'/../config/directory1/expected-local-global.php',
            ),
            array(
                array(
                    __DIR__.'/../config/directory1/*.{global,local}.php',
                    __DIR__.'/../config/directory2/*.{local,global}.php',
                ),
                include __DIR__.'/../config/directory2/expected-multi.php',
            ),
        );
    }

    /**
     * @dataProvider testAddDirectoryDataProvider
     */
    public function testAddDirectory($directories, $expected)
    {
        $configurationBuilder = new ConfigurationBuilder();

        foreach ($directories as $item) {
            $configurationBuilder->addDirectory($item);
        }

        $result = $configurationBuilder->build();
        $this->assertEquals($expected, $result);
    }
}
