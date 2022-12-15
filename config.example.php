<?php
/*
 * File: config.php
 * File Created: Saturday, 6th August 2022 8:49:42 pm
 * Author: hiimcody1
 * 
 * Last Modified: Wednesday, 14th December 2022 8:08:31 pm
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

    //Door Randomizer Config
    const DoorsBranch   = "/path/to/doors";
    const DoorsVersion  = "/version/from/branch";
    const EnemizerPath  = "/path/to/enemizer";
    const CreateBPS     = true;
    const OutputDir     = "/path/to/output/bps";
    const Python        = "/path/to/python";
    const RomFilePath   = "/path/to/baserom";
    const ScriptName    = "./DungeonRandomizer.py";
}
?>