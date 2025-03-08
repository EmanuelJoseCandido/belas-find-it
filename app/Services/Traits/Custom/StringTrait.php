<?php

namespace App\Traits\Custom;
use illuminate\Support\Str;

trait StringTrait
{
    /**
     * Cria um slug
     *
     * @param string $text
     * @param string $separator
     * @return string
     */
    public function createSlug(string $text, ?string $separator = '-'): string
    {

        $text = preg_replace('~[^\pL\d]+~u', $separator, $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, $separator);
        $text = preg_replace('~-+~', $separator, $text);
        $text = strtolower($text);

        return $text;
    }

    /**
     * Cria um label nome
     *
     * @param string $name
     * @return string
     */
    public function createLabelName(string $name): string
    {
        $name = explode(" ", $name);
        $firstName = $name[0];

        if (count($name) == 1) {
            return $firstName;
        }

        $lastName = $name[count($name) - 1];
        $labelName = $firstName . " " . $lastName;

        return $labelName;
    }

    /**
     * Cria um slug e label nome
     *
     * @param array $attributes
     * @return array
     */
    public function createSpecialNames(array $attributes): array
    {
        $attributes['label_name'] = $this->createLabelName($attributes['name']);
        $attributes['slug'] = $this->createSlug($attributes['label_name']);

        return $attributes;
    }

    /**
     * Cria um encodeBasic
     *
     * @param  string $text
     * @return string
     */
    public function encodeBasic(string $text): string
    {
        $length = config('custom.length_verification_code');
        $leftRadom = Str::random($length);
        $rightRadom = Str::random($length);
        $textEncode = base64_encode($text);

        return $leftRadom . $textEncode . $rightRadom;
    }
}
