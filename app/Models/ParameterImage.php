<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParameterImage extends Model
{
    public const PARAMETER_IMAGE_STORAGE_FOLDER = 'public/images';

    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parameter_images';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(Parameter::class);
    }
}
