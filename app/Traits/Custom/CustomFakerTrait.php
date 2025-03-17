<?php

namespace App\Traits\Custom;

use App\Enums\Essentials\FilePathsEnum;
use App\Traits\Essentials\GeneratePDFTrait;
use PDF;

trait CustomFakerTrait
{
    use StringTrait, GeneratePDFTrait;

    public function angolanPhoneNumber(): string
    {
        $areaCodes = ['91', '92', '93', '94', '95', '99'];
        $areaCode = $areaCodes[rand(0, 5)] .  rand(0, 9);
        $part1 = rand(100, 999); // 3 dígitos
        $part2 = rand(100, 999); // 3 dígitos
        $number = "{$part1} {$part2}";

        return "+244 {$areaCode} {$number}";
    }

    public function newEmail(string $name, string $born_at): string
    {
        $email_name = $this->createSlug($name, '_'); // . '_' . str_replace('-', '_', $born_at);
        $email = "$email_name@serafrica.ao";
        return $email;
    }

    public function cv(array $data): string
    {
        $pdf = PDF::loadView('generate.fake_cv', $data);
        $path=  str_replace(':employee_slug', $data['slug'], FilePathsEnum::CV->value);
        $path = $this->saveDomPDFFile($pdf, $path);

        return $path;
    }

    public function tin(string $born_at)
    {
        return 'AO' . $born_at . rand(100000000000, 999999999999);
    }
}
