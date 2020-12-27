<?php

namespace App;

use App\Contract\SearchTrait;
use App\Contract\UuidGeneratorTrait;
use Carbon\Carbon;
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
     * Status available
     *
     * @return array
     */
    public static function statusAvailable(): array
    {
        return [
            self::STATUS_PENDING => __('status.' . self::STATUS_PENDING),
            self::STATUS_APPROVED => __('status.' . self::STATUS_APPROVED),
            self::STATUS_REFUSED => __('status.' . self::STATUS_REFUSED)
        ];
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

    /**
     * Payments report
     *
     * @param Builder $query
     * @param array $params
     * @return Builder
     * @throws \Exception
     */
    public function scopeReport(Builder $query, array $params): Builder
    {
        $start = new Carbon($params['start']);
        $end = new Carbon($params['end']);

        $query
            ->with(['bank'])
            ->whereBetween('date', [$start, $end])
            ->orderBy('date', 'DESC')
        ;

        if ($params['bank_id']) {
            $query->where('bank_id', $params['bank_id']);
        }

        if ($params['status']) {
            $query->where('status', $params['status']);
        }

        if ($params['sales_order']) {
            $query->where('sales_order',  $params['sales_order']);
        }

        if ($params['transaction_number']) {
            $query->where('transaction_number', $params['transaction_number']);
        }

        if ($params['account_holder']) {
            $query->whereRaw(sprintf('lower(account_holder) LIKE \'%%%s%%\'', strtolower($params['account_holder'])));
        }

        if ($params['customer_name']) {
            $query->whereRaw(sprintf('lower(customer_name) LIKE \'%%%s%%\'', strtolower($params['customer_name'])));
        }

        return $query;
    }
}
