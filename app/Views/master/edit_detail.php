<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-100 mt-10 mb-20">
    <div class="flex justify-between items-center mb-8 border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 border-none">Edit Isi Materi</h2>
            <p class="text-gray-500 text-sm">Perbarui konten sub-bab kamu di sini sob.</p>
        </div>
        <a href="/master/detail/<?= $materi['tutorial_id'] ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm transition font-semibold">← Kembali</a>
    </div>

    <form action="/master/detail/update/<?= $materi['id'] ?>" method="POST" id="form-materi" class="space-y-6">
        <?= csrf_field() ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label class="block text-gray-700 font-bold mb-2">Nama Bab</label>
                <input type="text" name="bab" value="<?= $materi['bab'] ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition" required>
            </div>
            <div class="form-group">
                <label class="block text-gray-700 font-bold mb-2">Nama Sub Bab</label>
                <input type="text" name="sub_bab" value="<?= $materi['sub_bab'] ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition" required>
            </div>
        </div>

        <div class="form-group">
            <label class="block text-gray-700 font-bold mb-2">Isi Materi</label>
            <input type="hidden" name="isi_materi" id="isi_materi">

            <div class="rounded-lg overflow-hidden border border-gray-300">
                <div id="editor-container" style="height: 450px;" class="bg-white text-base">
                    <?= $materi['isi_materi'] ?>
                </div>
            </div>
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                Simpan Perubahan Materi
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
    // 1. Inisialisasi Editor
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Sedang memuat materi lama...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['link', 'image', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['clean']
            ]
        }
    });

    // 2. Logika Simpan
    var form = document.getElementById('form-materi');
    form.onsubmit = function(e) {
        // Ambil isi dari editor ke input hidden
        var isiHtml = quill.root.innerHTML;

        // Cek jika kosong banget
        if (isiHtml === '<p><br></p>') {
            e.preventDefault();
            Swal.fire('Eitss!', 'Isi materi nggak boleh kosong ya sob!', 'warning');
            return false;
        }

        document.getElementById('isi_materi').value = isiHtml;
    };
    var form = document.getElementById('form-materi');
    form.onsubmit = function(e) {
        e.preventDefault();

        var isiHtml = quill.root.innerHTML;

        // Cek jika kosong
        if (isiHtml === '<p><br></p>' || isiHtml.trim() === '') {
            Swal.fire('Eitss!', 'Isi materi tidak boleh kosong!', 'warning');
            return false;
        }

        document.getElementById('isi_materi').value = isiHtml;

        Swal.fire({
            title: 'Simpan Perubahan?',
            text: "Pastikan materi sudah rapi dan siap dibaca mahasiswa!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    };
</script>
<?= $this->endSection() ?>
