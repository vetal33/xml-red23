<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    const TYPE_XML_NORMATIVE = 'xml-normative';
    const STATUS_ACTIVE = 'active';
    const STATUS_VALID = 'valid';
    const STATUS_INACTIVE = 'inactive';
    const STATUSES_LABEL = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_VALID => 'Valid',
        self::STATUS_INACTIVE => 'Inactive',
    ];

    const TYPES_LABEL = [
        self::TYPE_XML_NORMATIVE => 'Xml Normative',
    ];

    protected $fillable = [
        'name',
        'user_id',
        'file_name',
        'type',
        'mime_type',
        'path',
        'status',
        'disk',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
