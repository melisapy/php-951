<?php require_once('header.php'); ?>
<!-- Admin Body Section Start -->
<div class="row">
    <div class="col-md-6">
        <h3>Yeni Yazı Ekle</h3>
    </div>
    <div class="col-md-6 text-end">
        <a href="yazilar.php" class="btn btn-info text-white">Tümünü Gör</a>
    </div>
</div>
<div class="row">
    <form action="" class="row" enctype="multipart/form-data" method="post">
        <div class="col-md-9">
            <input type="text" name="baslik" placeholder="Başlık Girin" class="form-control mb-2">
            <textarea name="aciklama" id="editor1" placeholder="Blog Yazınızı Girin"></textarea>
            <script>
                ClassicEditor
                    .create(document.querySelector('#editor1'))
                    .then(editor => {
                        editor.ui.view.editable.element.style.height = '200px';
                        editor.ui.view.element.style.width = '100%';
                    })
                    .catch(error => {
                        console.error(error);
                    });
            </script>
            <textarea name="meta" placeholder="Kısa Açıklama (Max. 160 Karakter)" rows="4" class="form-control mt-2"></textarea>
        </div>
        <div class="col-md-3">
            <label for="durum">Durum</label>
            <select name="durum" id="durum" class="form-control">
                <option value="">Seçiniz</option>
                <option value="Taslak">Taslak</option>
                <option value="Yayında">Yayınla</option>
            </select>
            <div class="my-2">
                <label>Tarih</label>
                <input type="date" name="tarih" class="form-control">
            </div>
            <label>Kategori</label>
            <select name="kategori" class="form-control" required>
                <option value="">Seçiniz</option>
                <?php
                $katSec = $db->prepare('select * from kategoriler order by katAdi asc');
                $katSec->execute();

                if ($katSec->rowCount()) {
                    foreach ($katSec as $katSecSatir) {
                ?>
                        <option value="<?php echo $katSecSatir['katAdi']; ?>"><?php echo $katSecSatir['katAdi'] . " - " . $katSecSatir['katTuru']; ?></option>
                <?php
                    }
                }
                ?>
            </select>
            <div class="my-2">
                <label>Öne Çıkan Görsel</label>
                <input type="file" name="gorsel" class="form-control">
            </div>
            <button type="submit" class="btn btn-success w-100" name="yaziKaydet">Kaydet</button>
            <?php
            if (isset($_POST['yaziKaydet'])) {
                $baslik = $_POST['baslik'];
                $aciklama = $_POST['aciklama'];
                $meta = $_POST['meta'];
                $durum = $_POST['durum'];
                $tarih = $_POST['tarih'];
                $kategori = $_POST['kategori'];
                $gorsel = '../assets/img/' . $_FILES['gorsel']['name']; //name dosyanın adını yakalar.
                if (move_uploaded_file($_FILES['gorsel']['tmp_name'], $gorsel)) {
                    $kaydet = $db->prepare('insert into yazilar(baslik,aciklama,meta,durum,tarih,kategori,gorsel) values(?,?,?,?,?,?,?)');
                    $kaydet->execute(array($baslik, $aciklama, $meta, $durum, $tarih, $kategori, $gorsel));

                    if ($kaydet->rowCount()) {
                        echo '<div class="alert alert-success">Kayıt Edildi</div>';
                    } else {
                        echo '<div class="alert alert-danger">Hata Oluştu</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger">Görsel Yüklenemedi</div>';
                }
            }
            ?>
        </div>
    </form>
</div>
<!-- Admin Body Section End -->
<?php require_once('footer.php'); ?>