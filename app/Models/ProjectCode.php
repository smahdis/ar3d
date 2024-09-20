<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class ProjectCode extends Model
{
    use HasFactory,AsSource, Attachable;
    public $timestamps = false;
    protected $fillable = [
        'project_id',
        'code',
        'status',
    ];
}
