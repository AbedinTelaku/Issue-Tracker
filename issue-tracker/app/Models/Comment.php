<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_id',
        'author_name',
        'body',
    ];

    /**
     * Get the issue that owns the comment.
     */
    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }
}
