<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('external_id',
    'first_name',
    'last_name',
    'phone',
    'email',
    'city',
    'source',
    'utm_campaign',
    'product',
    'budget_uah',
    'status',
    'manager',
    'comment',
    'next_contact_at', )]
final class Lead extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'budget_uah' => 'decimal:2',
            'next_contact_at' => 'datetime',
        ];
    }
}
