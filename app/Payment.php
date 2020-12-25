<?php

namespace App;

use App\Contract\UuidGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;
    use UuidGeneratorTrait;

    /** Status */
    const STATUS_PENDING = 'pending';
    const STATUS_REFUSED = 'refused';
    const STATUS_APPROVED = 'approved';

    protected $table = 'payments';

    protected $fillable = [
        'date',
        'account_holder',
        'amount',
        'customer_name',
        'sales_order',
        'bank_id',
        'transaction_number'
    ];

    /**
     * Bank
     *
     * @return BelongsTo
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id')->withTrashed();
    }
}
