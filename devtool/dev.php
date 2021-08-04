<?php
    //changing script links
    $list = scandir('/home/oleg/PhpstormProjects/mathtest/');
    foreach ($list as $item) {
        if ( !preg_match('/htm/', $item) ) continue;
        $path = '/home/oleg/PhpstormProjects/mathtest/'.$item;

        file_put_contents($path, str_replace( 'https://mathtest.st8.ru/', 'http://mathtest/', file_get_contents($path) ) );
    }
    //webpack's dev mode
    file_put_contents('/home/oleg/PhpstormProjects/mathtest/webpack.config.js', file_get_contents('/home/oleg/PhpstormProjects/mathtest/devtool/WebPackDev.js') );
    //build
    shell_exec('cd /home/oleg/PhpstormProjects/mathtest/; npm run build');

