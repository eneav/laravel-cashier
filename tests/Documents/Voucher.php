<?php
/**
 * Created by enea dhack - 17/06/17 02:47 PM
 */

namespace Enea\Tests\Documents;


use Enea\Cashier\Contracts\BusinessOwner;
use Enea\Cashier\Contracts\DocumentContract;

class Voucher implements DocumentContract
{

    /**
     * Get tax percentage
     *
     * @return int
     */
    public function getTaxPercentageAttribute(): int
    {
        return 0;
    }

    /**
     * Returns the owner of social reason
     *
     * @return BusinessOwner
     * */
    public function getBusinessOwner(): ?BusinessOwner
    {
        return null;
    }
}