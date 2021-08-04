<?php
    //changing script links
    $list = scandir('/home/oleg/PhpstormProjects/mathtest/');
    foreach ($list as $item) {
        if ( !preg_match('/htm/', $item) ) continue;
        $path = '/home/oleg/PhpstormProjects/mathtest/'.$item;

        file_put_contents($path, str_replace( 'http://mathtest/', 'https://mathtest.st8.ru/', file_get_contents($path) ) );
    }
    //webpack's dev mode
    file_put_contents('/home/oleg/PhpstormProjects/mathtest/webpack.config.js', file_get_contents('/home/oleg/PhpstormProjects/mathtest/devtool/WebPackProd.js') );
    //build
    shell_exec('cd /home/oleg/PhpstormProjects/mathtest/; npm run build');
    //export zip
    $path = '/home/oleg/PhpstormProjects/mathtest/';
    shell_exec("tar -cvf mathtest.tar {$path}app {$path}server");
    $command = "tar -rvf mathtest.tar";
    foreach ($list as $value) { if ( preg_match('/htm|ico/', $value) ) $command.=" {$path}{$value}"; }
    shell_exec($command);