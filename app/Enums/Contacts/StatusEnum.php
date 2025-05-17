<?php

namespace App\Enums\Contacts;

use App\Exceptions\InvalidInputException;

enum StatusEnum: string
{
    case PENDING = "pendente";
    case IN_PROGRESS = "em_andamento";
    case RESOLVED = "resolvido";
    case CLOSED = "encerrado";

    /**
     * Get status value by name
     * or throw an error
     *
     * @param string $name
     * @return string
     * @throws InvalidInputException
     */
    public function getValue(string $name): string
    {
        foreach ($this as $status) {
            if ($status->name == $name)
                return $status->value;
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
