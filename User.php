<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!session()->get('nama') or session()->get('rule') != 1) {
            return redirect()->to(base_url() . "/dashboard");
        }
        echo view('user');
    }

    public function muatData()
    {
        echo json_encode($this->userModel->where("hapus", NULL)->findAll());
    }

    public function tambah()
{
    $nama = $this->request->getPost('nama');
    $password = $this->request->getPost('password');
    $jabatan = $this->request->getPost('jabatan'); // Jabatan harus sesuai (0, 1, 2)

    $password = password_hash($password, PASSWORD_DEFAULT); // Hash password
    
    $this->userModel->insert([
        'nama' => $nama,
        'password' => $password,
        'rule' => $jabatan // Rule langsung menggunakan nilai dari input
    ]);

    return $this->response->setJSON(['status' => 'success']);
}


    public function update()
    {
        $id = $this->request->getPost('id');
        $nama = $this->request->getPost('nama');
        $password = $this->request->getPost('password');
        $jabatan = $this->request->getPost('jabatan');
    
        if ($password != "") {
            $password = password_hash($password, PASSWORD_DEFAULT); // Hash password jika diperlukan
        }
    
        $this->userModel->update($id, [
            'nama' => $nama,
            'password' => $password,
            'rule' => $jabatan
        ]);
    
        return $this->response->setJSON(['status' => 'success']);
    }

    public function hapus()
    {
        date_default_timezone_set("Asia/Jakarta");
        $id = $this->request->getPost("id");
        if ($id) {
            $tanggal = date('Y-m-d h:m:s', strtotime('today'));
            $this->userModel->update($id, ["hapus" => $tanggal]);
            echo json_encode("");
        } else {
            echo json_encode("id kosong");
        }
    }
}
