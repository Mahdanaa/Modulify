<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md mt-10">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Tulis Materi Baru</h2>
        <a href="/master/detail/<?= $tutorial['id'] ?>" class="text-gray-500 hover:text-gray-700">← Batal</a>
    </div>

    <div class="bg-blue-50 text-blue-800 p-3 rounded mb-6 text-sm">
        Sedang menambahkan materi untuk modul: <strong><?= $tutorial['judul'] ?></strong>
    </div>

    <form action="/master/detail/store/<?= $tutorial['id'] ?>" method="POST" id="formMateri">
        <?= csrf_field() ?>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Nama Bab</label>
                <input type="text" name="bab" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Pengenalan PHP" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Nama Sub-bab</label>
                <input type="text" name="sub_bab" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Cara Kerja PHP" >
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Isi Materi</label>
            <input type="hidden" name="isi_materi" id="isi_materi" required>

            <div id="editor-container" class="h-64 bg-white rounded-b-lg"></div>
        </div>

        <div class="mb-6 flex items-center">
            <input type="checkbox" name="is_visible" id="is_visible" value="1" class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500 cursor-pointer">
            <label for="is_visible" class="ml-2 text-gray-700 cursor-pointer">
                <strong>Langsung Tayangkan?</strong> (Centang agar mahasiswa bisa langsung melihat materi ini).
            </label>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition">
            Simpan Materi
        </button>

    </form>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
    // 1. Aktifkan Editor
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Tulis isi materi, masukkan link, atau ketik source code di sini...',
        modules: {
            toolbar: {
                container: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'image', 'code-block'],
                    ['clean']
                ],
                handlers: {
                    image: function() {
                        var input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/png, image/jpeg, image/webp');
                        input.click();

                        input.onchange = () => {
                            var file = input.files[0];
                            var formData = new FormData();
                            formData.append('image', file);

                            // Ajax
                            fetch('/master/detail/upload', {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                                },
                                body: formData
                            })
                            .then(response => response.json())
                            .then(result => {
                                if(result.url) {
                                    // Jika sukses, sisipkan URL gambar asli ke dalam editor
                                    var range = quill.getSelection();
                                    var posisi = range ? range.index : 0;
                                    quill.insertEmbed(posisi, 'image', result.url);

                                    // Update CSRF token di form (untuk menghindari token expired)
                                    if(result.token) {
                                        var csrfField = document.querySelector('input[name="<?= csrf_token() ?>"]');
                                        if(csrfField) {
                                            csrfField.value = result.token;
                                        }
                                    }
                                } else {
                                    Swal.fire('Gagal', result.error || 'Format harus JPG/PNG/WebP', 'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('Error', 'Terjadi kesalahan saat upload', 'error');
                            });
                        };
                    }
                }
            }
        }
    });


    document.getElementById('formMateri').onsubmit = function() {
        var isiHtml = quill.root.innerHTML;
        if (isiHtml === '<p><br></p>') {
            Swal.fire('Woy', 'Isi tidak boelh kosong', 'warning');
            return false;
        }
        document.getElementById('isi_materi').value = isiHtml;
    };
</script>
<?= $this->endSection() ?>
