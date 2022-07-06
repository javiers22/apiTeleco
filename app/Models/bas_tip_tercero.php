<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bas_tip_tercero extends Model
{
    protected $table = 'bas_tercero';

    protected $appends = [
        'cla_tercero'
     ];
}
