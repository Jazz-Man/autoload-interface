<?php

use JazzMan\AutoloadInterface\AutoloadInterface;

if (! \function_exists('app_autoload_classes')) {
    /**
     * @param array $classes
     */
    function app_autoload_classes(array $classes)
    {
        foreach ($classes as $class) {
            // Help opcache.preload discover always-needed symbols
            \class_exists($class);

            try {
                $_class = new \ReflectionClass($class);
                if ($_class->implementsInterface(AutoloadInterface::class)) {
                    /** @var AutoloadInterface $instance */
                    $instance = $_class->newInstance();
                    $instance->load();
                }
            } catch (\ReflectionException $e) {
                error_log($e);
            }
        }
    }
}
