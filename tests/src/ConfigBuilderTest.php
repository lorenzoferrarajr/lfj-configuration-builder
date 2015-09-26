<?php

use Lfj\ConfigurationBuilder\ConfigurationBuilder;

class ConfigBuilderTest extends PHPUnit_Framework_TestCase
{
    public function testWithFileDataProvider()
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
     * @dataProvider testWithFileDataProvider
     */
    public function testWithFile($files, $expected)
    {
        $configurationBuilder = new ConfigurationBuilder();

        foreach ($files as $item) {
            $configurationBuilder->withFile($item);
        }

        $result = $configurationBuilder->build();
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider testWithFileDataProvider
     */
    public function testWithFiles($files, $expected)
    {
        $configurationBuilder = new ConfigurationBuilder();

        $configurationBuilder->withFiles($files);

        $result = $configurationBuilder->build();
        $this->assertEquals($expected, $result);
    }

    public function testWithArrayDataProvider()
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
     * @dataProvider testWithArrayDataProvider
     */
    public function testWithArray($arrays, $expected)
    {
        $configurationBuilder = new ConfigurationBuilder();

        foreach($arrays as $array) {
            $configurationBuilder->withArray($array);
        }

        $result = $configurationBuilder->build();
        $this->assertEquals($expected, $result);
    }


    public function testWithDirectoryDataProvider()
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
     * @dataProvider testWithDirectoryDataProvider
     */
    public function testWithDirectory($directories, $expected)
    {
        $configurationBuilder = new ConfigurationBuilder();

        foreach ($directories as $item) {
            $configurationBuilder->withDirectory($item);
        }

        $result = $configurationBuilder->build();
        $this->assertEquals($expected, $result);
    }
}
