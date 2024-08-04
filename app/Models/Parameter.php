<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parameter extends Model
{
    use HasFactory;

    public const PARAMETER_TYPE_WITH_IMAGES = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parameters';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function images(): HasMany
    {
        return $this->hasMany(ParameterImage::class);
    }
}
