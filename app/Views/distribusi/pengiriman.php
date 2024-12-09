<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>

<div class="container mt-5">
    <h1 class="mb-4 text-center">Konfirmasi Pengiriman</h1>
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b>Berhasil</b>
                <?= session()->getFlashdata('success'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>

                <?= session()->getFlashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>
    <!-- Informasi Timbangan -->
    <div class="mt-4">
        <h5>Daftar Barang</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($timbangan['barang'])): ?>
                    <?php foreach ($timbangan['barang'] as $index => $barang): ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><?= $barang['nama_barang']; ?></td>
                            <td><?= $barang['jumlah']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada data barang</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <form action="<?= site_url('distribusi/pengiriman/simpan_konfirmasi/' . $timbangan['id_timbangan_bersih']); ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field(); ?>
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf-token">

        <!-- Signature Canvas -->
        <div class="canvas-sig-id">
            <p>Tanda tangan di bawah jika sudah dikirim ini:</p>
            <div class="canvas-wrapper">
                <canvas id="sig-canvas"></canvas>
            </div>
            <div class="mt-2">
                <button type="button" class="btn btn-primary" id="sig-submitBtn">Simpan Tanda Tangan</button>
                <button type="button" class="btn btn-secondary" id="sig-clearBtn">Hapus</button>
            </div>

            <!-- Hidden Input for Signature -->
            <textarea id="sig-dataUrl" name="signature_data" class="form-control d-none" required></textarea>
            <!-- Preview Signature -->
            <div class="mt-4">
                <h5>Pratinjau Tanda Tangan</h5>
                <img id="sig-image" src="" alt="Pratinjau tanda tangan akan muncul di sini" class="img-fluid border p-2 rounded" />
            </div>
            <!-- Submit Button -->
            <div class="mt-4">
                <button type="submit" class="btn btn-success">Konfirmasi Pengiriman</button>
            </div>
        </div>
    </form>
</div>
<script>
    (function() {
        // Polyfill requestAnimationFrame
        window.requestAnimFrame = (function(callback) {
            return window.requestAnimationFrame ||
                window.webkitRequestAnimationFrame ||
                window.mozRequestAnimationFrame ||
                window.oRequestAnimationFrame ||
                window.msRequestAnimationFrame ||
                function(callback) {
                    window.setTimeout(callback, 1000 / 60);
                };
        })();

        const canvas = document.getElementById("sig-canvas");
        const ctx = canvas.getContext("2d");
        ctx.strokeStyle = "#222222";
        ctx.lineWidth = 4;

        let drawing = false;
        let mousePos = {
            x: 0,
            y: 0
        };
        let lastPos = mousePos;

        // Fungsi untuk menyesuaikan ukuran canvas
        function resizeCanvas() {
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width; // Sesuaikan lebar canvas
            canvas.height = rect.width * (160 / 620); // Sesuaikan tinggi berdasarkan rasio
            ctx.strokeStyle = "#222222"; // Menetapkan warna stroke lagi
            ctx.lineWidth = 4; // Menetapkan ukuran garis
        }

        // Panggil resizeCanvas saat halaman dimuat
        resizeCanvas();

        // Panggil resizeCanvas jika ukuran layar berubah
        window.addEventListener('resize', resizeCanvas);

        // Event listeners for mouse
        canvas.addEventListener("mousedown", (e) => {
            drawing = true;
            lastPos = getMousePos(canvas, e);
        });

        canvas.addEventListener("mouseup", () => {
            drawing = false;
        });

        canvas.addEventListener("mousemove", (e) => {
            mousePos = getMousePos(canvas, e);
        });

        // Event listeners for touch
        canvas.addEventListener("touchstart", (e) => {
            const touch = e.touches[0];
            const me = new MouseEvent("mousedown", {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            canvas.dispatchEvent(me);
        });

        canvas.addEventListener("touchmove", (e) => {
            const touch = e.touches[0];
            const me = new MouseEvent("mousemove", {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            canvas.dispatchEvent(me);
        });

        canvas.addEventListener("touchend", () => {
            const me = new MouseEvent("mouseup", {});
            canvas.dispatchEvent(me);
        });

        // Get mouse position
        function getMousePos(canvasDom, mouseEvent) {
            const rect = canvasDom.getBoundingClientRect();
            return {
                x: mouseEvent.clientX - rect.left,
                y: mouseEvent.clientY - rect.top
            };
        }

        // Render canvas
        function renderCanvas() {
            if (drawing) {
                ctx.moveTo(lastPos.x, lastPos.y);
                ctx.lineTo(mousePos.x, mousePos.y);
                ctx.stroke();
                lastPos = mousePos;
            }
        }

        // Clear canvas
        function clearCanvas() {
            canvas.width = canvas.width;
        }

        // Prevent scrolling when touching canvas
        document.body.addEventListener("touchstart", (e) => {
            if (e.target === canvas) {
                e.preventDefault();
            }
        });
        document.body.addEventListener("touchend", (e) => {
            if (e.target === canvas) {
                e.preventDefault();
            }
        });
        document.body.addEventListener("touchmove", (e) => {
            if (e.target === canvas) {
                e.preventDefault();
            }
        });

        // Animation loop
        (function drawLoop() {
            requestAnimFrame(drawLoop);
            renderCanvas();
        })();

        // Clear button
        const clearBtn = document.getElementById("sig-clearBtn");
        clearBtn.addEventListener("click", () => {
            clearCanvas();
            document.getElementById("sig-dataUrl").value = "";
            document.getElementById("sig-image").setAttribute("src", "");
        });

        // Submit button
        const submitBtn = document.getElementById("sig-submitBtn");
        submitBtn.addEventListener("click", () => {
            const dataUrl = canvas.toDataURL();
            document.getElementById("sig-dataUrl").value = dataUrl;
            document.getElementById("sig-image").setAttribute("src", dataUrl);

            fetch('/distribusi/pengiriman/konfirmasi/refresh-csrf')
                .then(response => response.json())
                .then(data => {
                    const csrfField = document.querySelector('input[name="<?= csrf_token() ?>"]');
                    if (csrfField && data.csrf_token) {
                        csrfField.value = data.csrf_token;
                    }
                });

            alert("Tanda tangan berhasil disimpan!");
        });

        // Menambahkan handler untuk submit form
        document.querySelector('form').addEventListener('submit', function(e) {
            // Dapatkan token CSRF yang terkini
            const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]').value;

            // Anda dapat menambahkan validasi atau logging tambahan di sini
            console.log('CSRF Token:', csrfToken);
        });
    })();
</script>



<?= $this->endSection(); ?>