<?php

namespace App\Enums\Essentials;

use App\Exceptions\InvalidInputException;

enum FilePathsEnum: string
{
    //:something is change dynamic

    /**
     * Images, pictures
     */
    case PROFILE = "private/employee/pics";
    case CONSTRUCTION = "construction/:id/";
    case STORE = "private/products/store";
    case STORAGE = "private/products/storage";
    case ITEM_SERVICE = "item_service";
    case STORAGE_PRODUCTS = "storage/products/:product_slug/";




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
