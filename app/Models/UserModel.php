<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'role_id',
        'nama',
        'email',
        'password',
        'outlet_id',
        'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'role_id'   => 'required|integer|is_not_unique[roles.id]',
        'nama'      => 'required|max_length[100]',
        'email'     => 'required|valid_email|max_length[100]|is_unique[users.email]',
        'password'  => 'permit_empty|min_length[6]',
        'outlet_id' => 'permit_empty|integer',
        'is_active' => 'in_list[0,1]'
    ];

    protected $validationMessages = [
        'role_id' => [
            'required'      => 'Role harus dipilih',
            'is_not_unique' => 'Role tidak valid'
        ],
        'nama' => [
            'required'   => 'Nama harus diisi',
            'max_length' => 'Nama maksimal 100 karakter'
        ],
        'email' => [
            'required'    => 'Email harus diisi',
            'valid_email' => 'Email tidak valid',
            'is_unique'   => 'Email sudah digunakan'
        ],
        'password' => [
            'min_length' => 'Password minimal 6 karakter'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    public function getUpdateValidationRules($id)
    {
        $rules = $this->validationRules;
        $rules['email'] = "required|valid_email|max_length[100]|is_unique[users.email,id,{$id}]";
        return $rules;
    }

    /**
     * Get validation rules for create
     */
    public function getCreateValidationRules()
    {
        $rules = $this->validationRules;
        $rules['password'] = 'required|min_length[6]';
        return $rules;
    }

    /**
     * Hash password before insert/update
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            if (!empty($data['data']['password']) && trim($data['data']['password']) !== '') {
                $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            } else {
                // Remove password field if empty during update
                unset($data['data']['password']);
            }
        }

        return $data;
    }

    // Custom Methods

    /**
     * Get users with role and outlet information
     */
    public function getUsersWithDetails()
    {
        return $this->select('users.*, roles.nama_role, outlets.nama_outlet, outlets.KdStore')
            ->join('roles', 'roles.id = users.role_id')
            ->join('outlets', 'outlets.id = users.outlet_id', 'left')
            ->orderBy('users.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get user by email (for login)
     */
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Get users by role
     */
    public function getUsersByRole($roleId)
    {
        return $this->where('role_id', $roleId)->findAll();
    }

    /**
     * Get users by outlet
     */
    public function getUsersByOutlet($outletId)
    {
        return $this->where('outlet_id', $outletId)->findAll();
    }

    /**
     * Get active users
     */
    public function getActiveUsers()
    {
        return $this->where('is_active', 1)->findAll();
    }

    /**
     * Toggle user status
     */
    public function toggleStatus($id)
    {
        $user = $this->find($id);
        if ($user) {
            $newStatus = $user['is_active'] == 1 ? 0 : 1;
            return $this->update($id, ['is_active' => $newStatus]);
        }
        return false;
    }

    /**
     * Check if user can be deleted
     */
    public function canDelete($id)
    {
        // Check if user has transactions
        $db = \Config\Database::connect();

        $user = $this->find($id);
        if (!$user) return false;

        // Check transaksi_header by email/nama
        $builder = $db->table('transaksi_header');
        $count = $builder->where('Kasir', $user['email'])
            ->orWhere('Kasir', $user['nama'])
            ->countAllResults();

        return $count === 0;
    }

    /**
     * Verify password
     */
    public function verifyPassword($email, $password)
    {
        $user = $this->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Change password
     */
    public function changePassword($id, $newPassword)
    {
        return $this->update($id, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
    }

    /**
     * Get users with pagination and filters
     */
    public function getUsersPaginated($perPage = 10, $filters = [])
    {
        $builder = $this->select('users.*, roles.nama_role, outlets.nama_outlet, outlets.KdStore')
            ->join('roles', 'roles.id = users.role_id')
            ->join('outlets', 'outlets.id = users.outlet_id', 'left');

        // Apply filters
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('users.nama', $filters['search'])
                ->orLike('users.email', $filters['search'])
                ->groupEnd();
        }

        if (!empty($filters['role_id'])) {
            $builder->where('users.role_id', $filters['role_id']);
        }

        if (!empty($filters['outlet_id'])) {
            $builder->where('users.outlet_id', $filters['outlet_id']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $builder->where('users.is_active', $filters['is_active']);
        }

        return [
            'data' => $builder->orderBy('users.created_at', 'DESC')
                ->paginate($perPage),
            'pager' => $this->pager
        ];
    }

    /**
     * Validate outlet requirement based on role
     */
    public function validateOutletForRole($roleId, $outletId)
    {
        // Get role info
        $db = \Config\Database::connect();
        $role = $db->table('roles')->where('id', $roleId)->get()->getRowArray();

        if (!$role) return false;

        // Admin Pusat tidak perlu outlet (outlet_id = NULL atau 0)
        if (in_array($role['KdRole'], ['AD', 'ADM', 'ADMIN'])) {
            return true;
        }

        // Manajer dan Kasir wajib punya outlet
        if (in_array($role['KdRole'], ['MG', 'MNG', 'KS', 'KSR', 'MANAGER', 'KASIR'])) {
            return !empty($outletId) && $outletId > 0;
        }

        return true;
    }

    /**
     * Get user statistics
     */
    // public function getUserStats()
    // {
    //     $db = \Config\Database::connect();

    //     return [
    //         'total' => $this->countAll(),
    //         'active' => $this->where('is_active', 1)->countAllResults(false),
    //         'inactive' => $this->where('is_active', 0)->countAllResults(false),
    //         'by_role' => $db->table('users')
    //             ->select('roles.nama_role, COUNT(*) as total')
    //             ->join('roles', 'roles.id = users.role_id')
    //             ->groupBy('users.role_id')
    //             ->get()
    //             ->getResultArray()
    //     ];
    // }
}
