<?php

namespace App\Model;

/**
 * Data Transfer Object for Media entity.
 * Contains image information for product display.
 */
readonly class MediaModel
{
    public function __construct(
        public string $link,
        public ?string $alt,
        public string $type,
    ) {
    }
}
