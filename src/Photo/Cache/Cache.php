<?php

namespace Photo\Cache;

class Cache
{
    public function isCached($data)
    {
        $encode = base64_encode($data);
    }
}
