@extends('tamu.layout.app')
@section('content')

@if (Session::has('success'))
<script>
    alert('Selamat Datang!, Silahkan masuk dan konfirmasi ke resepsionis');
</script>
@endif

@if (Session::has('failed'))
    <script>
        alert('Anda belum melakukan pengambilan gambar');
    </script>
@endif


<section class="content">
    <div class="image text-center">
        <img src="{{ asset('dist/img/logo-kemenkes.png') }}" alt="kemenkes" class="w-50 mt-3">
    </div>
    <div class="container-fluid d-flex justify-content-center align-items-center mb-3">
        <form id="form" action="{{ route('tamu.store', $id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="lokasi_datang" value="{{ $lobi }}">
            <div class="card mt-4">
                <div class="card-header">
                    <label class="font-weight-bold h5 pt-2">Form Tamu</label>
                </div>
                <div class="card-body">
                    <p class="text-sm" style="text-align: justify;">
                        Mohon untuk memastika area atau lokasi Pegawai/Pejabat yang akan dikunjungi dengan menanyakan kepada Resepsionis
                    </p>
                    <hr class="mt-2">
                    <div class="row" style="font-size: 14px;font-weight: bold;">
                        <div class="form-group">
                            <label class="col-md-3 col-form-label">Full Name*</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nama" placeholder="Nama lengkap" required>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <div class="col-md-3 col-form-label">NIK/NIP*</div>
                            <div class="col-md-9">
                                <input type="text" class="form-control number" name="nik_nip" placeholder="NIK / NIP" required>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <div class="col-md-3 col-form-label">Address*</div>
                            <div class="col-md-9">
                                <textarea class="form-control" name="alamat" placeholder="Alamat lengkap sesuai KTP" required></textarea>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <div class="col-md-3 col-form-label">Phone Number*</div>
                            <div class="col-md-9">
                                <input type="text" class="form-control number" name="no_telp" placeholder="No. Telepon aktif" required>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="col-md-3 col-form-label">Instance*</div>
                            <div class="col-md-9">
                                <select name="instansi" class="form-control" required>
                                    <option value="">-- Pilih Instansi --</option>
                                    @foreach($instansi as $row)
                                    <option value="{{ $row->id_instansi }}">{{ $row->instansi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="col-md-3 col-form-label">Instance Name*</div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nama_instansi" placeholder="Tulis (-) jika pilihan Instansi 'Pribadi'" required>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="col-md-3 col-form-label">Employee/Officer Name to Meet*</div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nama_tujuan" placeholder="Nama pegawai/pejabat yang ingin ditemui" required>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="col-md-3 col-form-label">Purpose*</div>
                            <div class="col-md-9">
                                <textarea class="form-control" name="keperluan" placeholder="Keperluan kunjungan" required></textarea>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="col-md-3 col-form-label">Destination Area*</div>
                            <div class="col-md-9">
                                <select name="area_id" class="form-control" required>
                                    <option value="">-- PILIH AREA TUJUAN --</option>
                                    @foreach ($area as $row)
                                    @if ($gedung == 1)
                                    <option value="{{ $row->id_area }}">
                                        {{ $row->nama_lantai.'  ('.$row->nama_ruang.') - '. $row->nama_sub_bagian }}
                                    </option>
                                    @else
                                    <option value="{{ $row->id_area }}">
                                        {{ $row->nama_lantai.' - '.$row->nama_sub_bagian }}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="col-md-3 col-form-label">Photo*</div>
                            <div class="col-md-9">
                                <a class="btn btn-default border-dark" id="openCameraButton" onclick="openCamera()">
                                    <i class="fas fa-camera"></i> Open Camera
                                </a>

                                <div id="captureForm" style="display: none;">
                                    <video id="video" width="640" height="280" autoplay></video>
                                    <a id="capture" type="button" onclick="captureImage()" class="btn btn-default border-dark mt-2 mb-2">
                                        <i class="fas fa-camera"></i> Take Photo (Ambil Foto)
                                    </a>
                                    <a id="reload" class="btn btn-default border-dark mt-2 mb-2 d-none" onclick="reloadCapture()">
                                        <i class="fas fa-camera-rotate"></i> Retake Photo (Ulang)
                                    </a>
                                    <canvas id="canvas" width="640" height="280" style="display: none;"></canvas>
                                    <input type="hidden" name="capturedImage" id="capturedImage" required>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary text-dark" onclick="return confirm('Apakah data yang diisi sudah benar ?')">
                        <b><i class="fa-solid fa-paper-plane"></i> KIRIM</b>
                    </button>
                </div>
            </div>

        </form>
    </div>
</section>

@section('js')
<script>
    $(function() {
        $('select[name="area_id"]').select2()
    })

    function confirmSubmit(event) {
        event.preventDefault(); // Prevent the default link behavior

        // Mengecek setiap input pada form
        const form = document.getElementById('form');
        const inputs = form.querySelectorAll('select[required], input[required], textarea[required]');
        let isFormValid = true;

        inputs.forEach(input => {
            if (input.hasAttribute('required') && input.value.trim() === '') {
                input.style.borderColor = 'red';
                isFormValid = false;
            } else {
                input.style.borderColor = '';
            }
        });

        if (isFormValid) {
            Swal.fire({
                title: 'Selesai ?',
                text: 'Pastikan data sudah terisi dengan benar',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else {
            Swal.fire({
                title: 'Isian belum lengkap',
                text: 'Silakan lengkapi semua isian yang diperlukan',
                icon: 'error',
            });
        }
    }
</script>

<script>
    const openCameraButton = document.getElementById('openCameraButton');
    const captureForm = document.getElementById('captureForm');
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureButton = document.getElementById('captureButton');
    const capturedImageInput = document.getElementById('capturedImage');
    let stream;

    function openCamera() {
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then((mediaStream) => {
                video.srcObject = mediaStream;
                stream = mediaStream;

                openCameraButton.style.display = 'none';
                captureForm.style.display = 'block';
                captureButton.style.display = 'block';
            })
            .catch((error) => {
                console.error('Error accessing camera: ', error);
            });
    }

    function captureImage() {
        const containerWidth = document.getElementById('video').offsetWidth; // replace 'video-container' with the actual container ID

        // Calculate the aspect ratio to maintain responsiveness
        const aspectRatio = video.videoWidth / video.videoHeight;
        const canvasWidth = containerWidth;
        const canvasHeight = containerWidth / aspectRatio;

        // Set canvas dimensions
        canvas.width = canvasWidth;
        canvas.height = canvasHeight;

        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvasWidth, canvasHeight);

        const capturedDataURL = canvas.toDataURL('image/png');
        capturedImageInput.value = capturedDataURL;

        // Stop video stream
        stream.getTracks().forEach(track => track.stop());

        // Hide video and show canvas
        video.style.display = 'none';
        canvas.style.display = 'block';

        $('#capture').addClass('d-none')
        $('#reload').removeClass('d-none')
    }

    function reloadCapture() {
        // Hentikan jejak video yang sedang berjalan
        stream.getTracks().forEach(track => track.stop());

        // Bersihkan gambar yang diambil sebelumnya
        capturedImageInput.value = '';

        // Reset tampilan elemen
        video.style.display = 'block';
        canvas.style.display = 'none';

        // Buka kembali kamera
        openCamera();
        $('#capture').removeClass('d-none')
        $('#reload').addClass('d-none')
    }

    // Open camera when the page loads
    // window.addEventListener('load', openCamera);
</script>
@endsection


@endsection
