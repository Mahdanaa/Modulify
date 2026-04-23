<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Pager\Pager;

class MasterTutorialModel extends Model
{
    protected $table            = 'master_tutorials';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['judul', 'kode_mk', 'url_presentation', 'url_finished', 'creator_email'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    /**
     * Pager instance after pagination
     */
    public $pager;
    protected $updatedField  = 'updated_at';

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateUniqueUrl'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Generate unique URL slugs before inserting record
     *
     * @param array $data
     * @return array
     */
    protected function generateUniqueUrl(array $data)
    {
        if (isset($data['data']['judul'])) {
            $slug = url_title($data['data']['judul'], '-', true);
            $acak_pres = bin2hex(random_bytes(8));
            $acak_fin  = bin2hex(random_bytes(8));
            $data['data']['url_presentation'] = $slug . '-' . $acak_pres;
            $data['data']['url_finished']     = 'finished-' . $slug . '-' . $acak_fin;
        }
        return $data;
    }
}
