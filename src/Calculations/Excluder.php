<?php
/**
 * Created on 30/09/17 by enea dhack.
 */

namespace Enea\Cashier\Calculations;

use Closure;
use Enea\Cashier\Helpers;
use Enea\Cashier\Modifiers\TaxContract;
use Illuminate\Support\Collection;

class Excluder
{
    /**
     * @var Collection
     */
    protected $taxes;

    /**
     * IncludedTaxDiscounter constructor.
     *
     * @param Collection $taxes
     */
    public function __construct(Collection $taxes)
    {
        $this->taxes = $taxes->filter(function (TaxContract $tax) {
            return $tax->isIncluded();
        });
    }

    /**
     * @param $amount
     * @return float
     */
    public function getCleanTotalTaxIncluded($amount)
    {
        $amount -= $this->getTotalValueIncluded();
        $percentage = $this->getFormatPercentageToExtract() + 1;

        return $amount / $percentage * $this->getFormatPercentageToExtract();
    }

    /**
     * @param $amount
     * @return float
     */
    public function getTotalTaxIncluded($amount)
    {
        return Helpers::decimalFormat($this->getCleanTotalTaxIncluded($amount));
    }

    /**
     * Get the instance as an array.
     *
     * @param $amount
     * @return array
     */
    public function toArray($amount)
    {
        return [
            'modifier_percentage' => $this->getPercentageToExtract(),
            'modifier_value' => $this->getTotalValueIncluded(),
            'clean_total' => $this->getCleanTotalTaxIncluded($amount),
            'total' => $this->getTotalTaxIncluded($amount),
        ];
    }

    /**
     * Returns the percentage to extract from the amount.
     *
     * @return float
     */
    public function getPercentageToExtract()
    {
        return $this->taxes->filter(function (TaxContract $tax) {
            return $tax->isPercentage();
        })->sum($this->modifierValue());
    }

    /**
     * Returns the value to extract from the amount.
     *
     * @return float
     */
    public function getTotalValueIncluded()
    {
        return $this->taxes->filter(function (TaxContract $tax) {
            return ! $tax->isPercentage();
        })->sum($this->modifierValue());
    }

    /**
     * Returns the modifier value of tax.
     *
     * @return Closure
     */
    protected function modifierValue()
    {
        return function (TaxContract $tax) {
            return $tax->getModifierValue();
        };
    }

    /**
     * Returns the percentage to extract with format.
     *
     * @return float
     */
    public function getFormatPercentageToExtract()
    {
        return Helpers::toPercentage($this->getPercentageToExtract());
    }
}
