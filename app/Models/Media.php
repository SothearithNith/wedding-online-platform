<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;


class Media extends Model
{
    public const SOURCE_UPLOAD = 'upload';
    public const SOURCE_EXTERNAL = 'external';

    protected $table = 'media';

    protected $fillable = [
        'user_id',
        'source',
        'disk',
        'path',
        'original_url',
        'resource_type',
        'mime',
        'size',
        'name',
        'alt',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    private const MIMES = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'avif' => 'image/avif',
        'mp3' => 'audio/mpeg',
        'm4a' => 'audio/mp4',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Media $media) {
            if ($media->isUpload() && $media->path) {
                try {
                    Storage::disk($media->disk ?: 'public')->delete($media->path);
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        });
    }

    /* ----------------------------- relations / scopes ----------------------------- */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOwnedBy(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeUploads(Builder $query): Builder
    {
        return $query->where('source', self::SOURCE_UPLOAD);
    }

    public function scopeExternal(Builder $query): Builder
    {
        return $query->where('source', self::SOURCE_EXTERNAL);
    }


    public function isUpload(): bool
    {
        return $this->source === self::SOURCE_UPLOAD;
    }

    public function isExternal(): bool
    {
        return $this->source === self::SOURCE_EXTERNAL;
    }

    public function getUrlAttribute(): ?string
    {
        if ($this->isExternal() || ! $this->path) {
            return $this->original_url;
        }

        return Storage::disk($this->disk ?: config('filesystems.upload_disk', 'r2'))
            ->url($this->path);
    }

    public static function fromUpload(
        int $userId,
        string $path,
        ?string $name = null,
        ?string $mime = null,
        ?int $size = null,
        bool $isMusic = false,
        ?string $disk = null,
    ): self {
        $disk ??= config('filesystems.upload_disk', 'r2');

        return static::create([
            'user_id' => $userId,
            'source' => self::SOURCE_UPLOAD,
            'disk' => $disk,
            'path' => $path,
            'original_url' => null,
            'resource_type' => $isMusic ? 'audio' : 'image',
            'mime' => $mime,
            'size' => $size ?? 0,
            'name' => $name,
        ]);
    }

    public static function fromExternal(int $userId, string $url, bool $isMusic = false): self
    {
        $url  = trim($url);
        $path = (string) (parse_url($url, PHP_URL_PATH) ?: '');
        $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $isAudio = $isMusic || in_array($ext, ['mp3', 'm4a'], true);

        return static::firstOrCreate(
            [
                'user_id' => $userId,
                'source' => self::SOURCE_EXTERNAL,
                'original_url' => $url,
            ],
            [
                'disk' => null,
                'path' => null,
                'resource_type' => $isAudio ? 'audio' : 'image',
                'mime' => self::MIMES[$ext] ?? null,
                'size' => 0,
                'name' => basename($path) ?: null,
            ],
        );
    }
}
