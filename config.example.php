<?php
/*
 * File: config.php
 * File Created: Saturday, 6th August 2022 8:49:42 pm
 * Author: hiimcody1
 * 
 * Last Modified: Wednesday, 1st February 2023 3:35:28 pm
 * Modified By: hiimcody1
 * 
 * License: MIT License http://www.opensource.org/licenses/MIT
 */

class Config {
    //Application Config
    const Debug         = true;
    const TemporaryPath = "/tmp/alttpdr-";
    const TemplatesPath = __DIR__."/templates/";
    const RoutesPath    = __DIR__."/routes/";
    const Version       = "0.0.1-alpha";

    //Database Config
    const DBType            = "mysql";
    const DBAddress         = "127.0.0.1";
    const DBPort            = 3306;
    const DBName            = "dr";
    const DBUser            = "dr";
    const DBPass            = "dr";

    //Door Randomizer Config
    const DoorsBranch   = "/path/to/doors";
    const DoorsVersion  = "/version/from/branch";
    const EnemizerPath  = "/path/to/enemizer";
    const CreateBPS     = true;
    const OutputDir     = "/path/to/output/bps";
    const Python        = "/path/to/python";
    const RomFilePath   = "/path/to/baserom";
    const ScriptName    = "./DungeonRandomizer.py";
    const MaxGenAttempts= 5;
}
?>