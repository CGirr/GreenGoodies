<?php

namespace App\Model;

class MediaModel
{
    public function __construct(
        public readonly string $link,
        public readonly ?string $alt,
        public readonly string $type,
    ) {
    }
}
