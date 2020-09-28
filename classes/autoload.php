<?php
spl_autoload_register(function ($class_name) {
    include str_replace('classes', '', $class_name) . '.php';
});