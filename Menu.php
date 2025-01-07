<?php

namespace App\Controllers;

use App\Models\MenuModel;

class Menu extends BaseController
{
    public function __construct()
    {
        date_default_timezone_set("Asia/Jakarta");
        $this->menuModel = new MenuModel();
    }

    public function index()
    {
        if (!session()->get('nama') || session()->get('rule') != 1) {
            return redirect()->to(base_url() . "/dashboard");
        }
        echo view('menu');
    }

    public function muatData()
    {
        echo json_encode($this->menuModel->where("hapus", NULL)->findAll());
    }

    public function tambah() {
        // Ambil data dari input
        $nama = $this->request->getPost("nama");
        $harga = $this->request->getPost("harga");
        $jenis = $this->request->getPost("jenis");
        $deskripsi = $this->request->getPost("deskripsi"); // Ambil deskripsi dari input
    
        // Validasi data input
        if (empty($nama) || empty($harga) || empty($jenis)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Nama, harga, dan jenis tidak boleh kosong.'
            ]);
        }
    
        if (!is_numeric($harga)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Harga harus berupa angka.'
            ]);
        }
    
        // Simpan data ke database
        try {
            $data = [
                "nama" => $nama,
                "harga" => $harga,
                "jenis" => $jenis,
                "deskripsi" => $deskripsi, // Pastikan deskripsi disertakan
                "foto" => "default.jpg",
                "status" => 0
            ];
    
            $this->menuModel->save($data);
    
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data menu berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            // Tangani error
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ]);
        }
    }
    

    public function hapus()
    {
        $id = $this->request->getPost("id");
        if ($id) {
            $tanggal = date('Y-m-d H:i:s');
            $this->menuModel->update($id, ["hapus" => $tanggal]);
            echo json_encode(["status" => "success", "message" => "Menu berhasil dihapus"]);
        } else {
            echo json_encode(["status" => "error", "message" => "ID tidak valid"]);
        }
    }

    public function ubahStatus()
    {
        $this->menuModel->update($this->request->getPost("id"), ["status" => $this->request->getPost("status")]);
        echo json_encode(["status" => "success", "message" => "Status berhasil diubah"]);
    }

    public function getMenu()
    {
        echo json_encode($this->menuModel->where("id", $this->request->getPost("id"))->first());
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $data = [
            'nama' => $this->request->getPost('nama'),
            'harga' => $this->request->getPost('harga'),
            'jenis' => $this->request->getPost('jenis'),
            'deskripsi' => $this->request->getPost('deskripsi'), // Menambahkan deskripsi
        ];
    
        if ($id) {
            $this->menuModel->update($id, $data);
            echo json_encode(['status' => 'success', 'message' => 'Menu berhasil diupdate']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
        }
    }
    

    public function upload()
    {
        $data = array();

        $validation = \Config\Services::validation();

        $validation->setRules([
            'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,jpg,jpeg,gif,png,webp],'
        ]);

        if ($validation->withRequest($this->request)->run() == FALSE) {
            $data['success'] = 0;

            $data['error'] = $validation->getError('file'); // Error response
        } else {
            if ($file = $this->request->getFile('file')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $name = $file->getName();
                    $ext = $file->getClientExtension();

                    $newName = $file->getRandomName();

                    $namaMenu = $this->request->getPost("namaMenu");
                    $idMenu = $this->request->getPost("idMenu");

                    $namaMenu = str_replace(" ", "", strtolower($namaMenu) . "." . $ext);

                    $file->move('./public/images/menu', $namaMenu, true);

                    $this->menuModel->update($idMenu, ["foto" => $namaMenu]);

                    $data['success'] = 1;
                    $data['message'] = 'Foto Berhasil diupload :)';
                } else {
                    $data['success'] = 2;
                    $data['message'] = 'Foto gagal diupload.';
                }
            } else {
                $data['success'] = 2;
                $data['message'] = 'Foto gagal diupload.';
            }
        }
        return $this->response->setJSON($data);
    }
}