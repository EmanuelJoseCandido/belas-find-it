<?php

namespace App\Traits\Custom;

use Carbon\Carbon;

trait CalculatorTrait
{
    /**
     * Salary calculator
     *
     * @param int $year
     * @param int $month
     * @param int $numberOfFaults
     * @param float $salary
     * @return float|int
     */
    public function calculateNetSalary(int $year, int $month, int $numberOfFaults, float $salary): float|int
    {
        $numberOfDays   = Carbon::createFromDate($year, $month)->endOfMonth()->day;
        $discountValue  = ($salary / $numberOfDays) * $numberOfFaults;
        return ($salary - $discountValue);
    }
}
