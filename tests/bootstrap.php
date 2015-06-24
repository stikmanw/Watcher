<?php

require __DIR__ . '/../vendor/autoload.php';

//register_shutdown_function(function() {
//        $tmp = sys_get_temp_dir();
//        $files = glob($tmp . "/WatcherTest/*");
//        foreach ($files as $file) {
//            @unlink($file);
//        }
//
//        //rmdir($tmp . "/WatcherTest");
//    });