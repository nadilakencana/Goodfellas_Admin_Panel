<div class="content-qr d-flex justify-content-center align-items-center pt-3 ">
    <div class="card-qr bg-white d-flex flex-column justify-content-center align-items-center">
        <div class="header-card-qr d-flex justify-content-center align-items-center gap-4">
            <img src="{{ asset('asset/assets/image/LOGO BLACK -1.png') }}" alt="" class="logo w-20 h-20" width="60"
                height="50">
            <p class="text-header m-0 fw-600">{{ $table->meja }}</p>
        </div>
        <div class="body-card-qr">
            <p class="card-text text-center "><?php echo DNS2D::getBarcodeHTML($table->link, 'QRCODE', 5, 5); ?>
            </p>
        </div>

        <p class="text-center"> Scan QR code to order menu</p>

    </div>
</div>
<div type="button" class="btn download btn-primary cursor-pointer d-flex justify-content-center align-items-center mt-4">
    Download QR
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
 <script src="https://superal.github.io/canvas2image/canvas2image.js"></script>
    <script>
        document.querySelector('.download').addEventListener('click', function() {
            html2canvas(document.querySelector('.content-qr'), {
                width: 1080, height: 1080,
                onrendered: function(canvas) {
                  return Canvas2Image.saveAsPNG(canvas);
                }
            });
        });
    </script>