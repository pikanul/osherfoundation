<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $appends = ['news_image_url', 'attachment_url', 'banner_image_url', 'pdf_file_url'];

    public function category(){

        return $this->belongsTo(NewsCategory::class , 'news_category_id');
    }

    public function getNewsImageUrlAttribute()
    {
        return dynamic_asset($this->news_image);
    }

    public function getAttachmentUrlAttribute()
    {
        if (!$this->attachment) {
            return null;
        }

        if ((int) ($this->attachment_is_link ?? 0) === 1 || filter_var($this->attachment, FILTER_VALIDATE_URL)) {
            return $this->attachment;
        }

        return dynamic_asset($this->attachment);
    }

    public function getBannerImageUrlAttribute()
    {
        return $this->attachment_url;
    }

    public function getPdfFileUrlAttribute()
    {
        $pdfFileId = (int) ($this->pdf_file_id ?? 0);
        if ($pdfFileId <= 0) {
            return null;
        }

        return dynamic_asset($pdfFileId);
    }

}
