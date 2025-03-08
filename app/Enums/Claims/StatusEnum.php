<?php

namespace App\Enums\Claims;

use App\Exceptions\InvalidInputException;

enum StatusEnum: string
{
    case PENDING = "pendente";
    case APPROVED = "aprovado";
    case REJECTED = "rejeitado";



    /**
     * Get Gender value by name
     * or throw an error
     *
     * @param string $name
     * @return string
     * @throws InvalidInputException
     */
    public function getValue(string $name): string
    {
        foreach ($this as $gender) {
            if ($gender->name == $name)
                return $gender->value;
        }

        throw new InvalidInputException();
    }

    /**
     * values
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
