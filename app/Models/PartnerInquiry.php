<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerInquiry extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'partnership_interests' => 'array',
        'collaboration_types' => 'array',
        'accuracy_consent' => 'boolean',
        'processing_consent' => 'boolean',
        'read_status' => 'boolean',
    ];

    public function getDocumentUrlAttribute(): ?string
    {
        return $this->document_upload_id ? dynamic_asset($this->document_upload_id) : null;
    }
}
