<?php

class Unoconv{

    public static function convert($originFilePath, $outputDirPath, $toFormat)
    {   
        set_time_limit(2000);  
        $command = 'python ' . realpath(dirname(__FILE__)) . '/unoconv --format %s --output %s %s';
        $command = sprintf($command, $toFormat, $outputDirPath, $originFilePath);
        system($command, $output);

        return $output;
    }

    public static function convertToPdf($originFilePath, $outputDirPath)
    {
        return self::convert($originFilePath, $outputDirPath, 'pdf');
    }

    public static function makeThumb($originFilePath, $outputDirPath)
    {
        set_time_limit(1000);
        $command = 'python ' . realpath(dirname(__FILE__)) .'/unoconv --format png -e PageRange=1-1 --output %s %s';
        $command = sprintf($command, $outputDirPath, $originFilePath);
        system($command, $output);

        return $output;
    }
}