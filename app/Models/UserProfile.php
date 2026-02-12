<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $full_name
 * @property string|null $profile_picture
 * @property string $phone_number
 * @property string|null $blood_group
 * @property string $aadhaar_number
 * @property string|null $pan_number
 * @property string $address
 * @property string $state
 * @property string $district
 * @property string $block
 * @property string $gram_panchayat
 * @property string $pin_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 */
class UserProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'full_name',
        'profile_picture',
        'phone_number',
        'blood_group',
        'aadhaar_number',
        'pan_number',
        'address',
        'state',
        'district',
        'block',
        'gram_panchayat',
        'pin_code',
    ];

    /**
     * Relationships
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the URL for the profile picture.
     */
    public function getProfilePictureUrl()
    {
        if (!$this->profile_picture) {
            return null;
        }

        clearstatcache();
        $path = $this->profile_picture;

        // Physical check: If the file doesn't exist in the real storage folder,
        // we return null so the UI shows a nice initial/placeholder instead of a broken link.
        $realPath = storage_path('app/public/' . $path);
        if (!file_exists($realPath)) {
            // Also check common shared hosting alternative
            $altPath = base_path('storage/app/public/' . $path);
            if (!file_exists($altPath)) {
                return null;
            }
        }

        // If we reach here, the file exists! 
        // We use the bridge because we know symlinks are disabled on your Hostinger plan.
        return route('storage.bridge', ['path' => $path]);
    }
}

