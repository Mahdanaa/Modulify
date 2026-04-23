<?php

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\Models\MasterTutorialModel;
use App\Models\TutorialContentModel;

class MateriController extends BaseController
{
    protected $masterModel;
    protected $contentModel;

    public function __construct()
    {
        $this->masterModel = new MasterTutorialModel();
        $this->contentModel = new TutorialContentModel();
    }

    public function baca($segment)
    {
        $materi = $this->masterModel->where('url_presentation', $segment)
                                    ->orWhere('url_finished', $segment)
                                    ->first();

        if (!$materi) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $isFinishedMode = ($materi['url_finished'] === $segment);

        if (!$isFinishedMode && $materi['url_presentation'] !== $segment) {
            return redirect()->to('/materi/' . $materi['url_presentation']);
        }

        if ($isFinishedMode) {
            $isiMateri = $this->contentModel->where('tutorial_id', $materi['id'])
                                            ->findAll();
        } else {
            $isiMateri = $this->contentModel->where('tutorial_id', $materi['id'])
                                            ->where('is_visible', 1)
                                            ->findAll();
        }

        return view('mahasiswa/baca', [
            'title'          => $materi['judul'],
            'materi'         => $materi,
            'isiMateri'      => $isiMateri,
            'isFinishedMode' => $isFinishedMode
        ]);
    }
}
