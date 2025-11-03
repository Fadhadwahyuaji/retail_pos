<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table            = 'roles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'KdRole',
        'nama_role'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Custom Methods

    /**
     * Get role by code
     */
    public function getRoleByCode($kdRole)
    {
        return $this->where('KdRole', $kdRole)->first();
    }

    /**
     * Get dropdown options
     */
    public function getDropdownOptions()
    {
        $roles = $this->findAll();
        $options = ['' => '-- Pilih Role --'];

        foreach ($roles as $role) {
            $options[$role['id']] = $role['nama_role'];
        }

        return $options;
    }

    /**
     * Check if role requires outlet
     */
    public function requiresOutlet($kdRole)
    {
        return in_array(strtoupper($kdRole), ['MNG', 'KSR', 'MANAGER', 'KASIR']);
    }
}
