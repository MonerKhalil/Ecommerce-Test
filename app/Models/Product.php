<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id'];

    public array $fieldsSearch = ['name','description'];
    public array $fieldsLike = ['name','description'];
}
