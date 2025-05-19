<?php

namespace App\Enums\Essentials;

use App\Exceptions\InvalidInputException;

enum FilePathsEnum: string
{
    //:something is change dynamic

    case ITEM_SERVICE = "items";

    /**
     * Get privilege value by name
     * or throw an error
     *
     * @param string $name
     * @return int
     * @throws InvalidInputException
     */
    public function getValue(string $name): int
    {
        foreach ($this as $label)
            if ($label->name == $name)
                return $label->value;

        throw new InvalidInputException();
    }
}
