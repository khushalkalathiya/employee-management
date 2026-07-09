<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Override;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'joining_date',
        'password',
        'is_active',
        'last_login_at',
    ];

    protected $appends = [
        'full_name',
        'initials',
        'avatar',
        'role',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->useDisk(config('media-library.disk_name'))
            ->singleFile();
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function ensureEmployeeExists(): Employee
    {
        $employee = $this->employee;
        if (!$employee) {
            $employee = $this->employee()->firstOrCreate([], [
                'employee_code' => 'EMP-' . str_pad($this->id, 5, '0', STR_PAD_LEFT),
            ]);
            $this->setRelation('employee', $employee);
        }
        return $employee;
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    public function getInitialsAttribute()
    {
        return substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1);
    }

    public function getAvatarAttribute()
    {
        return $this->getFirstMediaUrl('avatar') ?? null;
    }

    public function getRoleAttribute()
    {
        return $this->roles->first()?->name ?? null;
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_participants')
            ->using(ConversationParticipant::class)
            ->withPivot([
                'id',
                'role',
                'joined_at',
                'last_read_message_id',
                'last_read_at',
                'is_muted',
                'is_archived',
            ])
            ->withTimestamps();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
}
