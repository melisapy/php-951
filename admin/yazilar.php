<?php
require_once('header.php');

if (isset($_GET['deleteID'])) {
    $id = $_GET['deleteID'];

    $postDelete = $db->prepare('delete from yazilar where id=?');
    $postDelete->execute(array($id));

    if ($postDelete->rowCount()) {
        echo '<script>alert("Kayıt Silindi")</script><meta http-equiv="refresh" content="0; url=yazilar.php">';
    } else {
        echo '<script>alert("Hata Oluştu")</script><meta http-equiv="refresh" content="0; url=yazilar.php">';
    }
} else if (isset($_GET['updateID'])) {
    $id = $_GET['updateID'];

    $postSelect = $db->prepare('select * from yazilar where id=?');
    $postSelect->execute(array($id));
    $postSelectSatir = $postSelect->fetch();

    echo '
        <script>
            document.addEventListener("DOMContentLoaded", function () {
            var myModal = new bootstrap.Modal(document.getElementById("exampleModal"));
            myModal.show();
            });
        </script>';
}

?>
<!-- Admin Body Section Start -->
<div class="row">
    <div class="col-md-6">
        <h3>Yazılar</h3>
    </div>
    <div class="col-md-6 text-end">
        <a href="yazi-ekle.php" class="btn btn-info text-white">Yeni Yazı Ekle</a>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 150px;">Görsel</th>
                    <th>Başlık</th>
                    <th>Açıklama</th>
                    <th>Kategori</th>
                    <th>Durum</th>
                    <th>Tarih</th>
                    <th>Düzenle</th>
                    <th>Sil</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $postList = $db->prepare('select * from yazilar order by id desc');
                $postList->execute();

                if ($postList->rowCount()) {
                    foreach ($postList as $postListSatir) {
                ?>
                        <tr>
                            <td><img src="<?php echo $postListSatir['gorsel']; ?>" class="w-100"></td>
                            <td><?php echo $postListSatir['baslik']; ?></td>
                            <td><?php echo substr($postListSatir['aciklama'], 0, 200); ?></td>
                            <td><?php echo $postListSatir['kategori']; ?></td>
                            <td><?php echo $postListSatir['durum']; ?></td>
                            <td><?php echo $postListSatir['tarih']; ?></td>
                            <td><a href="yazilar.php?updateID=<?php echo $postListSatir['id']; ?>" class="btn btn-warning">Düzenle</a></td>
                            <td><a href="yazilar.php?deleteID=<?php echo $postListSatir['id']; ?>" class="btn btn-danger">Sil</a></td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Admin Body Section End -->



<!-- Update Modal Start -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo $postSelectSatir['baslik']; ?> - Güncelle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form action="" class="row" enctype="multipart/form-data" method="post">
                    <div class="col-md-9">
                        <input type="text" name="baslik" value="<?php echo $postSelectSatir['baslik']; ?>" class="form-control mb-2">
                        <textarea name="aciklama" id="editor1"><?php echo $postSelectSatir['aciklama']; ?></textarea>
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
                        <textarea name="meta" rows="4" class="form-control mt-2"><?php echo $postSelectSatir['meta']; ?></textarea>
                    </div>
                    <div class="col-md-3">
                        <label for="durum">Durum</label>
                        <select name="durum" id="durum" class="form-control">
                            <option value="<?php echo $postSelectSatir['durum']; ?>"><?php echo $postSelectSatir['durum']; ?></option>
                            <option value="Taslak">Taslak</option>
                            <option value="Yayında">Yayınla</option>
                        </select>
                        <div class="my-2">
                            <label>Tarih</label>
                            <input type="date" name="tarih" class="form-control" value="<?php echo $postSelectSatir['tarih']; ?>">
                        </div>
                        <label>Kategori</label>
                        <select name="kategori" class="form-control" required>
                            <option value="<?php echo $postSelectSatir['kategori']; ?>"><?php echo $postSelectSatir['kategori']; ?></option>
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
                            <img src="<?php echo $postSelectSatir['gorsel']; ?>" class="w-100">
                            <input type="file" name="gorsel" class="form-control">
                        </div>
                        <input type="hidden" name="postNo" value="<?php echo $postSelectSatir['id']; ?>">
                        <button type="submit" class="btn btn-success w-100" name="yaziGuncelle">Güncelle</button>
                        <?php
                        /* Post Update Module Start */
                        if (isset($_POST['yaziGuncelle'])) {
                            $baslik = $_POST['baslik'];
                            $aciklama = $_POST['aciklama'];
                            $meta = $_POST['meta'];
                            $durum = $_POST['durum'];
                            $tarih = $_POST['tarih'];
                            $kategori = $_POST['kategori'];
                            $gorsel = '../assets/img/'.$_FILES['gorsel']['name'];
                            $postNo = $_POST['postNo'];

                            if(move_uploaded_file($_FILES['gorsel']['tmp_name'],$gorsel)){

                                $postUpdate = $db -> prepare('update yazilar set baslik=?, aciklama=?, meta=?, durum=?, tarih=?, kategori=?, gorsel=? where id=?');
                                $postUpdate -> execute(array($baslik, $aciklama, $meta, $durum, $tarih, $kategori, $gorsel, $postNo));

                                if($postUpdate -> rowCount()){
                                    echo '<script>alert("Güncelleme Başarılı")</script><meta http-equiv="refresh" content="0; url=yazilar.php">';
                                } else {
                                    echo '<script>alert("Hata Oluştu")</script><meta http-equiv="refresh" content="0; url=yazilar.php">';
                                }

                            } else {
                                $postUpdate = $db -> prepare('update yazilar set baslik=?, aciklama=?, meta=?, durum=?, tarih=?, kategori=? where id=?');
                                $postUpdate -> execute(array($baslik, $aciklama, $meta, $durum, $tarih, $kategori, $postNo));

                                if($postUpdate -> rowCount()){
                                    echo '<script>alert("Güncelleme Başarılı")</script><meta http-equiv="refresh" content="0; url=yazilar.php">';
                                } else {
                                    echo '<script>alert("Hata Oluştu")</script><meta http-equiv="refresh" content="0; url=yazilar.php">';
                                }
                            }
                        }
                        /* Post Update Module End */
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Update Modal End -->


<?php require_once('footer.php'); ?>