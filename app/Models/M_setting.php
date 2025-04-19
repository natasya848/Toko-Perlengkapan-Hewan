<?php

namespace App\Models;
use CodeIgniter\Model;

class M_setting extends Model
{
	protected $table = 'setting';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'foto'];

    public function getSetting()
	{
	    return $this->db->table('setting')->get()->getRowArray();
	}

    public function tampil1()
{
    return $this->db->table('setting')->get()->getRowArray(); 
    // ATAU cukup: return $this->first();
}


    public function updateSetting($data)
    {
        return $this->update(1, $data); 
    }
}