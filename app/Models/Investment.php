<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'symbol',
        'name',
        'type',
        'quantity',
        'purchase_price',
        'current_price',
        'total_value',
        'profit_loss',
        'profit_loss_percentage',
        'purchased_at',
    ];

    protected $casts = [
        'quantity' => 'decimal:8',
        'purchase_price' => 'decimal:2',
        'current_price' => 'decimal:2',
        'total_value' => 'decimal:2',
        'profit_loss' => 'decimal:2',
        'profit_loss_percentage' => 'decimal:2',
        'purchased_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function updateCurrentValue(): void
    {
        $this->total_value = $this->quantity * $this->current_price;
        $this->profit_loss = $this->total_value - ($this->quantity * $this->purchase_price);
        $this->profit_loss_percentage = (($this->current_price - $this->purchase_price) / $this->purchase_price) * 100;
        $this->save();
    }
}
