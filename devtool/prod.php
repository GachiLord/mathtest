<?php
    //project link
    $project = '/home/oleg/PhpstormProjects/mathtest/';
    //changing script links
    $list = scandir($project);
    foreach ($list as $item) {
        if ( !preg_match('/htm/', $item) ) continue;
        $path = $project.$item;

        file_put_contents($path, str_replace( 'http://mathtest/', 'https://mathtest.st8.ru/', file_get_contents($path) ) );
    }
    //webpack's dev mode
    file_put_contents("{$project}webpack.config.js", file_get_contents("{$project}devtool/WebPackProd.js") );
    //delete
    $map = scandir("{$project}app");
    foreach ( $map as $i ) {
        shell_exec("rm '{$project}app/'$i");
    }
    //build
    shell_exec("cd $project; npm run build");
    //export zip
    shell_exec("tar -cvf mathtest.tar {$project}app {$project}server");
    $command = "tar -rvf mathtest.tar";
    foreach ($list as $value) { if ( preg_match('/htm|ico/', $value) ) $command.=" {$project}{$value}"; }
    shell_exec($command);