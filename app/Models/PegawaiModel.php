<?php

namespace App\Models;

use CodeIgniter\Model;

class PegawaiModel extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';
    protected $allowedFields = ['nomor_pegawai', 'nama_pegawai', 'role_pegawai', 'username', 'password'];

    public function getPegawaiById($id)
    {
        // Mengambil satu pegawai berdasarkan ID
        return $this->where('id_pegawai', $id)->first();
    }


    public function getPegawaiByRole($role)
    {
        return $this->where('role_pegawai', $role)->findAll();
    }
    public function getPegawaiByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function insertPegawai($data)
    {
        // Hash password sebelum menyimpan
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->insert($data);
    }

    public function updatePegawai($id, $data)
    {
        // Hash password jika ada perubahan password
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']); // Jika password kosong, tidak perlu update
        }
        return $this->update($id, $data);
    }
}
