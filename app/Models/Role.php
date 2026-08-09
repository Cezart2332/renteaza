<?php

namespace App\Models;

use App\Observers\RoleObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingTrait;

class Role extends UuidModel
{
    use HasFactory;

    protected $table = 'roles';

    public $incrementing = false;

    protected $fillable = ['id', 'name'];

    // public function permissions()
    // {
    //     return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    // }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }

    public function generateTags(): array
    {
        return [
            'roles',
            'role_id:' . $this->id,
            'role_name:' . $this->name,
        ];
    }

    /**
     * Transform the audit data to store UUID in auditable_uuid and fallback for auditable_id.
     *
     * @param  array  $data
     * @return array
     */
    public function transformAudit(array $data): array
    {
        if (Arr::has($data, 'auditable_id') && $this->isUuid($data['auditable_id'])) {
            $data['auditable_uuid'] = $data['auditable_id'];
            $data['auditable_id'] = crc32($data['auditable_uuid']);
        }

        return $data;
    }
}
