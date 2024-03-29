<?php
/*
 * File: class.DRSeed.php
 * File Created: Wednesday, 1st February 2023 3:31:14 pm
 * Author: hiimcody1
 * 
 * Last Modified: Wednesday, 1st February 2023 3:32:16 pm
 * Modified By: hiimcody1
 * 
 * License: MIT License https://opensource.org/licenses/MIT
 */

class DRSeed {
    public int $id;
    public string $hash;
    public int $seed;
    public string $build;
    public string $logic;
    public string $flags;
    public string $created_at;
    public string $updated_at;
    public string $meta;
    public string $patch;

    public function serialize() {
        $rep = Array(
            "hash" => $this->hash,
            "seed" => $this->seed,
            "build" => $this->build,
            "logic" => $this->logic,
            "flags" => $this->flags,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "meta" => $this->meta,
            "patch" => base64_encode($this->patch),
        );
        return $rep;
    }
}

?>