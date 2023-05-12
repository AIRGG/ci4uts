<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Mahasiswa;

class MahasiswaController extends BaseController
{
    public function index()
    {
        //
        // return view('templates/header', $data)
        //     . view('news/index')
        //     . view('templates/footer');
        return view('uts/mahasiswa/index');
    }

    public function getAll()
    {
        # code...
        $db      = \Config\Database::connect();
        $query = "SELECT * FROM mahasiswa a LEFT JOIN prodi b ON a.id_prodi=b.id";
        $query=$db->query($query);
        $data = $query->getResultArray();
        return $this->response->setJSON($data);
    }

    public function prosesAdd()
    {
        $data = [
            'nim' => $this->request->getPost('nim'),
            'nama' => $this->request->getPost('nama'),
            'id_prodi' => $this->request->getPost('prodi'),
        ];

        // -- image
        $img = $this->request->getFile('foto');
        $imgName = '';
        if($img->getSize() > 0) {
            $imgName = $img->getRandomName();
            // $img->move('/public/foto/', $imgName);
            $img->move(ROOTPATH . 'public/foto/', $imgName);
        }
        $data['foto'] = $imgName;
        // -- image

        $prodiModel = new Mahasiswa();
        $prodiModel->save($data);
        return $this->response->setJSON([
            'error' => false,
            'message' => 'Sukses Add!'
        ]);
    }
    public function prosesEdit()
    {
        $id = $this->request->getPost('oldid');
        $data = [
            'nim' => $this->request->getPost('nim'),
            'nama' => $this->request->getPost('nama'),
            'id_prodi' => $this->request->getPost('prodi'),
        ];

        // -- image
        $img = $this->request->getFile('foto');
        $imgName = $imgName = $this->request->getPost('foto-old');;
        if($img->getSize() > 0) {
            $imgName = $img->getRandomName();
            // $img->move('/public/foto/', $imgName);
            $img->move(ROOTPATH . 'public/foto/', $imgName);
        }
        $data['foto'] = $imgName;
        // -- image

        $prodiModel = new Mahasiswa();
        $prodiModel->update($id, $data);
        return $this->response->setJSON([
            'error' => false,
            'message' => 'Sukses Edit!'
        ]);
    }
    public function prosesDelete()
    {
        $id = $this->request->getPost('oldid');
        $prodiModel = new Mahasiswa();
        // $prodi = $prodiModel->find($id);
        $prodiModel->delete($id);
        return $this->response->setJSON([
            'error' => false,
            'message' => 'Sukses Delete!'
        ]);
    }
}
