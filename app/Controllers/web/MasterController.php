<?php

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\Models\MasterTutorialModel;
use App\Models\TutorialContentModel;

class MasterController extends BaseController
{
    protected $masterModel;
    protected $contentModel;

    public function __construct()
    {

        $this->masterModel = new MasterTutorialModel();
        $this->contentModel = new TutorialContentModel();
    }

    /**
     * Fetch matakuliah list from external API with authentication
     *
     * @return array List of matakuliah or empty array on failure
     */
    private function getMakul()
    {
        $client = \Config\Services::curlrequest();
        $token = session()->get('refreshToken');

        try {
            $response = $client->request('GET', env('API_BASE_URL') . '/getMakul', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ],
                'verify' => false,
                'http_errors' => false
            ]);

            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true) ?? [];
            }
        } catch (\Exception $e) {
            log_message('error', 'API Error - getMakul: ' . $e->getMessage());
        }

        return [];
    }

    public function index()
    {
        $makul = $this->getMakul();

        $tutorials = $this->masterModel->paginate(3, 'tutorials');

        $data = [
            "title" => 'Dashboard Master',
            'makul' => $makul,
            'tutorials' => $tutorials,
            'pager' => $this->masterModel->pager
        ];

        return view('master/index', $data);
    }

    public function create()
    {
        $makul = $this->getMakul();

        $data = [
            'title' => "Tambah Data",
            'makul' => $makul
        ];

        return view('master/create', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'judul' => 'required|min_length[3]|max_length[255]',
            'kode_mk' => 'required'
        ])) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->masterModel->save([
            'judul' => $this->request->getPost('judul'),
            'kode_mk' => $this->request->getPost('kode_mk'),
            'creator_email' => session()->get('email')
        ]);

        return redirect()->to('/master')->with('success', 'Modul baru berhasil ditambahkan!');
    }

    public function delete($id)
    {
        $tutorial = $this->masterModel->find($id);

        if (!$tutorial) {
            return redirect()->back()->with('error', 'Modul tidak ditemukan');
        }

        if ($tutorial['creator_email'] !== session()->get('email')) {
            return redirect()->back()->with('error', 'Anda tidak boleh menghapus modul orang lain!');
        }

        $this->masterModel->delete($id);
        return redirect()->to('/master')->with('success', 'Modul berhasil dihapus!');
    }

    public function edit($id)
    {
        $tutorial = $this->masterModel->find($id);

        if (!$tutorial) {
            return redirect()->back()->with('error', 'Modul tidak ditemukan');
        }

        if ($tutorial['creator_email'] !== session()->get('email')) {
            return redirect()->back()->with('error', 'Anda tidak boleh edit modul orang lain!');
        }

        $makul = $this->getMakul();

        return view('master/edit', [
            'title' => 'Edit Modul',
            'tutorial' => $tutorial,
            'makul'    => $makul
        ]);
    }

    public function update($id)
    {
        if (!$this->validate([
            'judul' => 'required|min_length[3]|max_length[255]',
            'kode_mk' => 'required'
        ])) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $tutorial = $this->masterModel->find($id);

        if (!$tutorial) {
            return redirect()->back()->with('error', 'Modul tidak ditemukan');
        }

        if ($tutorial['creator_email'] !== session()->get('email')) {
            return redirect()->back()->with('error', 'Anda tidak boleh edit modul orang lain!');
        }

        $this->masterModel->update($id, [
            'judul' => $this->request->getPost('judul'),
            'kode_mk' => $this->request->getPost('kode_mk')
        ]);

        return redirect()->to('/master')->with('success', 'Modul berhasil diubah!');
    }

    public function detail($id)
    {
        $tutorial = $this->masterModel->find($id);

        if (!$tutorial) {
            return redirect()->back()->with('error', 'Modul tidak ditemukan');
        }

        $contents = $this->contentModel->where('tutorial_id', $id)->paginate(3, 'contents');

        return view('master/detail', [
            'title' => 'Detail Module',
            'tutorial' => $tutorial,
            'contents' => $contents,
            'pager' => $this->contentModel->pager
        ]);
    }

    public function createDetail($id)
    {
        $tutorial = $this->masterModel->find($id);

        return view('master/create_detail', [
            'title'    => 'Tambah Sub-bab',
            'tutorial' => $tutorial
        ]);
    }

    public function storeDetail($id)
    {
        if (!$this->validate([
            'bab' => 'required|min_length[3]',
            'isi_materi' => 'required|min_length[10]'
        ])) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $isVisible = $this->request->getPost('is_visible') ? 1 : 0;

        $dataBaru = [
            'tutorial_id' => $id,
            'bab' => $this->request->getPost('bab'),
            'sub_bab' => $this->request->getPost('sub_bab'),
            'isi_materi' => $this->request->getPost('isi_materi'),
            'is_visible' => $isVisible
        ];

        $this->contentModel->insert($dataBaru);

        return redirect()->to('/master/detail/' . $id)->with('success', 'Sub-bab baru berhasil ditambahkan!');
    }

    public function uploadGambar()
    {
        $file = $this->request->getFile('image');

        if (!$file || !$file->isValid()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'File tidak ditemukan atau tidak valid']);
        }

        if ($file->hasMoved()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'File sudah dipindahkan']);
        }

        $aturan = [
            'image' => 'uploaded[image]|is_image[image]|mime_in[image,image/jpeg,image/pjpeg,image/png,image/x-png,image/webp]'
        ];

        if ($this->validate($aturan)) {
            $namaBaru = $file->getRandomName();
            $file->move(FCPATH . 'uploads/materi', $namaBaru);

            return $this->response->setJSON([
                'url' => base_url('uploads/materi/' . $namaBaru),
                'token' => csrf_hash()
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'error' => 'Format harus JPG/PNG/WebP',
            'details' => $this->validator->getErrors()
        ]);
    }

    public function toggleVisibility($id)
    {
        $materi = $this->contentModel->find($id);

        if (!$materi) {
            return $this->response->setJSON(['success' => false, 'message' => 'Materi tidak ditemukan']);
        }

        $statusBaru = ($materi['is_visible'] == 1) ? 0 : 1;
        $this->contentModel->update($id, ['is_visible' => $statusBaru]);

        return $this->response->setJSON([
            'success' => true,
            'new_status' => $statusBaru
        ]);
    }

    public function editDetail($id)
    {
        $materi = $this->contentModel->find($id);

        if (!$materi) {
            return redirect()->back()->with('error', 'Materi tidak ditemukan');
        }

        return view('master/edit_detail', ['title' => 'Edit Detail', 'materi' => $materi]);
    }

    public function updateDetail($id)
    {
        if (!$this->validate([
            'bab' => 'required|min_length[3]',
            'isi_materi' => 'required|min_length[10]'
        ])) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $materi = $this->contentModel->find($id);

        if (!$materi) {
            return redirect()->back()->with('error', 'Materi tidak ditemukan');
        }

        $this->contentModel->update($id, [
            'bab' => $this->request->getPost('bab'),
            'sub_bab' => $this->request->getPost('sub_bab'),
            'isi_materi' => $this->request->getPost('isi_materi')
        ]);

        return redirect()->to('/master/detail/' . $materi['tutorial_id'])->with('success', 'Materi berhasil diupdate!');
    }

    public function deleteDetail($id)
    {
        $materi = $this->contentModel->find($id);

        if (!$materi) {
            return redirect()->back()->with('error', 'Materi tidak ditemukan');
        }

        $tutorial = $this->masterModel->find($materi['tutorial_id']);

        if ($tutorial['creator_email'] !== session()->get('email')) {
            return redirect()->back()->with('error', 'Anda tidak boleh menghapus materi orang lain!');
        }

        $tutorial_id = $materi['tutorial_id'];
        $this->contentModel->delete($id);

        return redirect()->to('/master/detail/' . $tutorial_id)->with('success', 'Materi berhasil dihapus!');
    }
}
