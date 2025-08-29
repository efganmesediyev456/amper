<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SiteSetting extends BaseModel
{
    use HasFactory;

    public $table = 'site_settiings';

    public $translatedAttributes = ['header_offer'];

    protected $fillable = [
        'header_logo',
        'footer_logo',
        'favicon',
        'chat_whatsapp_number'
    ];
}