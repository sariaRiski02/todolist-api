<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Todo extends Model
{
    use HasFactory;

    public $table = 'todos';
    public $primaryKey = 'id';
    public $keyType = 'string';
    public $dateFormat = 'd-m-Y H:i:s';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'completed',
        'id_user'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
        static::creating(function ($model) {
            $model->completed = false;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
