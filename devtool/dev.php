<?php
    //project link
    $project = '/home/oleg/PhpstormProjects/mathtest/';
    //changing script links
    $list = scandir($project);
    foreach ($list as $item) {
        if ( !preg_match('/htm/', $item) ) continue;
        $path = $project.$item;

        file_put_contents($path, str_replace( 'https://mathtest.st8.ru/', 'http://mathtest/', file_get_contents($path) ) );
    }
    //delete
    $map = scandir("{$project}app");
    foreach ( $map as $i ) {
        shell_exec("rm '{$project}app/'$i");
    }
    //webpack's dev mode
    file_put_contents("{$project}webpack.config.js", file_get_contents("{$project}devtool/WebPackDev.js") );
    //build
    shell_exec("cd $project; npm run build");

