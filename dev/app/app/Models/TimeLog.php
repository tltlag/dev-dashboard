<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeLog extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'clockodo_entry_id',
        'call_history_id',
        'date',
        'duration',
        'start_time',
        'end_time',
        'client_id',
        'client_name',
        'clockodo_project_id',
        'clockodo_project_name',
        'service_id',
        'service_name',
        'service_description',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    // protected $casts = [
    //     'date' => 'date:Y-m-d',
    //     'start_time' => 'datetime:H:i:s',
    //     'end_time' => 'datetime:H:i:s',
    // ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array<string>
     */
    protected $dates = ['deleted_at'];
}
