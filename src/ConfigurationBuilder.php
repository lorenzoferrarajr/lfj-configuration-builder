<?php

namespace Lfj\ConfigurationBuilder;

use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;

class ConfigurationBuilder
{
    protected $list;
    protected $files;

    public function __construct()
    {
        $this->list = array();
        $this->files = array();
    }

    public function addFile($file)
    {
        $this->list[] = array(
            'type' => 'file',
            'value' => $file,
        );

        $this->files[] = $file;

        return $this;
    }

    public function addFiles(array $files)
    {
        $this->list[] = array(
            'type' => 'files',
            'value' => $files,
        );

        foreach ($files as $i) {
            $this->files[] = $i;
        }

        return $this;
    }

    public function addDirectory($glob)
    {
        $this->list[] = array(
            'type' => 'directory',
            'value' => $glob,
        );

        foreach (Glob::glob($glob, Glob::GLOB_BRACE) as $i) {
            $this->files[] = $i;
        }

        return $this;
    }

    public function addArray(array $array)
    {
        $this->list[] = array(
            'type' => 'array',
            'value' => $array,
        );

        return $this;
    }

    public function build()
    {
        $config = array();
        foreach ($this->list as $index => $file) {
            $config = $this->merge($config, $this->readConfigAtIndex($index));
        }

        return $config;
    }

    private function readConfigAtIndex($i)
    {
        $data = $this->list[$i];

        $files = array();
        $config = array();

        if ('file' === $data['type']) {
            $files = array($data['value']);
        }

        if ('files' === $data['type']) {
            $files = $data['value'];
        }

        if ('directory' === $data['type']) {
            $files = glob($data['value'], GLOB_BRACE);
        }

        if ('file' === $data['type'] || 'files' === $data['type'] || 'directory' === $data['type']) {
            foreach ($files as $file) {

                if (!file_exists($file)) {
                    throw new Exception\FileNotExistsException("The element '$file' does not exist");
                }

                if (!is_file($file)) {
                    throw new Exception\NotFileException("The element '$file' is not a file");
                }

                if (!is_readable($file)) {
                    throw new Exception\FileNotReadableException("The file '$file' is not readable");
                }

                $configTmp = include $file;

                if (!is_array($configTmp)) {
                    throw new Exception\NotArrayException("The file '$file' is not returning an array");
                }

                $config = $this->merge($config, $configTmp);
            }
        }

        if ('array' === $data['type']) {
            $config = $data['value'];
        }

        return $config;
    }

    private function merge(array $array1, array $array2)
    {
        return ArrayUtils::merge($array1, $array2);
    }
}
