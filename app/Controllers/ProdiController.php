<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Prodi;

class ProdiController extends BaseController
{
    public function index()
    {
        //
        $prodi = new Prodi();
        $data = $prodi->findAll();
        return view('uts/prodi/index', ['data' => $data]);
    }

    public function getAll()
    {
        # code...
        $prodi = new Prodi();
        $data = $prodi->findAll();
        return $this->response->setJSON($data);
    }

    public function prosesAdd()
    {
        $data = [
            'nama_prodi' => $this->request->getPost('nama_prodi')
        ];
        $prodiModel = new Prodi();
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
            'nama_prodi' => $this->request->getPost('nama_prodi')
        ];

        $prodiModel = new Prodi();
        $prodiModel->update($id, $data);
        return $this->response->setJSON([
            'error' => false,
            'message' => 'Sukses Edit!'
        ]);
    }
    public function prosesDelete()
    {
        $id = $this->request->getPost('oldid');
        $prodiModel = new Prodi();
        // $prodi = $prodiModel->find($id);
        $prodiModel->delete($id);
        return $this->response->setJSON([
            'error' => false,
            'message' => 'Sukses Delete!'
        ]);
    }
}
