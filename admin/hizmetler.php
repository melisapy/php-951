<?php require_once('header.php'); ?>
<!-- Admin Body Section Start -->
<div class="row">
    <div class="col-md-6">
        <h3>Hizmetler</h3>
    </div>
    <div class="col-md-6">
        <!-- Button trigger modal -->
        <div class="text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Hizmet Ekle
            </button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Yeni Hizmet Ekle</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="text" name="baslik" placeholder="Hizmet Adını Girin" class="form-control mb-3">
                            <textarea name="aciklama" id="aciklama" placeholder="Hizmet Açıklaması"></textarea>
                            <script>
                                ClassicEditor
                                    .create(document.querySelector('#aciklama'))
                                    .then(editor => {
                                        editor.ui.view.editable.element.style.height = '200px';
                                        editor.ui.view.element.style.width = '100%';
                                    })
                                    .catch(error => {
                                        console.error(error);
                                    });
                            </script>
                            <label for="" class="mt-3">Banner Ekle</label>
                            <input type="file" name="gorsel" class="form-control mb-3" required>
                            <input type="submit" value="Kaydet" class="btn btn-success w-100" name="kaydet">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Görsel</th>
                    <th>Hizmet Adı</th>
                    <th>Açıklama</th>
                    <th>Düzenle</th>
                    <th>Sil</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!-- Admin Body Section End -->


<!-- Services Add Module Start -->

<?php
if(isset($_POST['kaydet'])){

    $gorsel = '../assets/img/'.$_FILES['gorsel']['name'];

    if(move_uploaded_file($_FILES['gorsel']['tmp_name'],$gorsel)){
        $hizmetEkle = $db -> prepare('insert into hizmetler(baslik,aciklama,gorsel) values(?,?,?)');
        $hizmetEkle -> execute(array($_POST['baslik'],$_POST['aciklama'],$gorsel));

        if($hizmetEkle -> rowCount()){
            echo '<script>alert("Hizmet Eklendi")</script><meta http-equiv="refresh" content="0; url=hizmetler.php">';
        } else {
            echo '<script>alert("Hata Oluştu")</script><meta http-equiv="refresh" content="0; url=hizmetler.php">';
        }
    }
}
?>
<!-- Services Add Module End -->

<?php require_once('footer.php'); ?>