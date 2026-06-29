<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSignature extends Model
{
    protected $fillable = [
        'sender_email', 'display_name', 'designation', 'phone',
        'contact_email', 'website', 'linkedin', 'tagline',
        'photo_path', 'handwritten_path', 'template', 'accent_color', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public static array $templates = [
        'classic' => 'Classic — Photo left, info right',
        'minimal' => 'Minimal — Text only, clean lines',
        'bold'    => 'Bold — Coloured bar accent',
    ];

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? url('storage/' . $this->photo_path) : null;
    }

    public function getHandwrittenUrlAttribute(): ?string
    {
        return $this->handwritten_path ? url('storage/' . $this->handwritten_path) : null;
    }

    public function renderHtml(): string
    {
        $template = in_array($this->template, array_keys(self::$templates))
            ? $this->template
            : 'classic';

        return view("emails.partials.signature-{$template}", ['sig' => $this])->render();
    }
}
