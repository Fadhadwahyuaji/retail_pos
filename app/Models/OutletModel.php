<?php

namespace App\Models;

use CodeIgniter\Model;

class OutletModel extends Model
{
    protected $table            = 'outlets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'KdStore',
        'nama_outlet',
        'alamat',
        'telepon',
        'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'KdStore'     => 'required|max_length[2]',
        'nama_outlet' => 'required|max_length[100]',
        'alamat'      => 'permit_empty|max_length[1000]',
        'telepon'     => 'permit_empty|max_length[20]',
        'is_active'   => 'in_list[0,1]'
    ];

    protected $validationMessages = [
        'KdStore' => [
            'required'   => 'Kode Store harus diisi',
            'max_length' => 'Kode Store maksimal 2 karakter',
            'is_unique'  => 'Kode Store sudah digunakan'
        ],
        'nama_outlet' => [
            'required'   => 'Nama Outlet harus diisi',
            'max_length' => 'Nama Outlet maksimal 100 karakter'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Custom Methods

    /**
     * Get all active outlets
     */
    public function getActiveOutlets()
    {
        return $this->where('is_active', 1)->findAll();
    }

    /**
     * Get outlet by KdStore
     */
    public function getByKdStore($kdStore)
    {
        return $this->where('KdStore', $kdStore)->first();
    }

    /**
     * Toggle outlet status (active/inactive)
     */
    public function toggleStatus($id)
    {
        $outlet = $this->find($id);
        if ($outlet) {
            $newStatus = $outlet['is_active'] == 1 ? 0 : 1;
            return $this->update($id, ['is_active' => $newStatus]);
        }
        return false;
    }

    /**
     * Get outlets with user count
     */
    public function getOutletsWithUserCount()
    {
        return $this->select('outlets.*, COUNT(users.id) as user_count')
            ->join('users', 'users.outlet_id = outlets.id', 'left')
            ->groupBy('outlets.id')
            ->findAll();
    }

    /**
     * Check if outlet has transactions
     */
    public function hasTransactions($kdStore)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('transaksi_header');
        $count = $builder->where('KdStore', $kdStore)->countAllResults();
        return $count > 0;
    }

    /**
     * Get outlet options for dropdown
     */
    public function getDropdownOptions()
    {
        $outlets = $this->getActiveOutlets();
        $options = ['' => '-- Pilih Outlet --'];

        foreach ($outlets as $outlet) {
            $options[$outlet['id']] = $outlet['KdStore'] . ' - ' . $outlet['nama_outlet'];
        }

        return $options;
    }
}
