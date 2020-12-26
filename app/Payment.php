<?php

namespace App;

use App\Contract\SearchTrait;
use App\Contract\UuidGeneratorTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Payment extends Model
{
    use SoftDeletes;
    use UuidGeneratorTrait;
    use SearchTrait;

    /** Status */
    const STATUS_PENDING = 'pending';
    const STATUS_REFUSED = 'refused';
    const STATUS_APPROVED = 'approved';

    protected $table = 'payments';

    protected $fillable = [
        'date',
        'account_holder',
        'amount',
        'customer_number',
        'customer_name',
        'sales_order',
        'bank_id',
        'transaction_number'
    ];

    protected $dates = ['date'];

    public $search_fields = [
        'account_holder',
        'customer_name',
        'sales_order',
        'transaction_number'
    ];

    public function __construct(array $attributes = [])
    {
        $this->status = self::STATUS_PENDING;
        parent::__construct($attributes);
    }

    /**
     * Bank
     *
     * @return BelongsTo
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id')->withTrashed();
    }

    /**
     * Attach document to payment
     *
     * @param string $base64
     * @param string $filename
     * @return string
     */
    public function attachDocument(string $base64, string $filename): string
    {
        $explode = explode(',', $base64);
        $filename = sprintf('%s-%s', $filename, ((string) time()));

        if (strpos($explode[0], 'image/png') > 0) {
            $format = 'png';
        } elseif (strpos($explode[0], 'image/jpg') > 0 || strpos($explode[0], 'image/jpeg') > 0) {
            $format = 'jpg';
        } else {
            return false;
        }

        $path = sprintf('payment/%s/%s.%s', $this->uuid, $filename, $format);
        Storage::disk('public')->put($path, base64_decode($explode[1]));

        return $path;
    }

    /**
     * Payments pending
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }
}
