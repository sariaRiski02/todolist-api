<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
