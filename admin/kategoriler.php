<?php
require_once('header.php');

if (isset($_GET['katDelete'])) {
    $id = $_GET['katDelete'];

    $katSil = $db->prepare('delete from kategoriler where id=?');
    $katSil->execute(array($id));

    if ($katSil->rowCount()) {
        echo '<script>alert("Kayıt Silindi")</script><meta http-equiv="refresh" content="1; url=kategoriler.php">';
    } else {
        echo '<script>alert("Kayıt Silinemedi")</script><meta http-equiv="refresh" content="1; url=kategoriler.php">';
    }
} else if (isset($_GET['updateId'])) {
    $id = $_GET['updateId'];

    $selectKat = $db->prepare('select * from kategoriler where id=?');
    $selectKat->execute(array($id));
    $selectKatSatir = $selectKat->fetch();

    echo '<script>
            document.addEventListener("DOMContentLoaded", function () {
            var myModal = new bootstrap.Modal(document.getElementById("exampleModal"));
            myModal.show();
            });
        </script>';
}
?>



<!-- Admin Body Section Start -->
<div class="row">
    <div class="col-12">
        <h3>Kategoriler</h3>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <form action="" method="post">
            <input type="text" name="katAdi" placeholder="Kategori Adı" class="form-control">
            <select name="katTuru" class="form-control my-2">
                <option value="">Seçiniz</option>
                <option value="Alt Kategori">Alt Kategori</option>
                <option value="Üst Kategori">Üst Kategori</option>
            </select>
            <label>Üst Kategori</label>
            <select name="ustKat" class="form-control">
                <option value="">Seçiniz</option>
            </select>
            <textarea name="aciklama" placeholder="Açıklama" rows="4" class="form-control my-2"></textarea>
            <input type="submit" value="Kaydet" class="btn btn-success w-100" name="kaydet">
        </form>

        <!-- Kategori Insert Module Start -->
        <?php
        if (isset($_POST['kaydet'])) {
            $katAdi = $_POST['katAdi'];
            $katTuru = $_POST['katTuru'];
            $ustKat = $_POST['ustKat'];
            $aciklama = $_POST['aciklama'];

            $katKaydet = $db->prepare('insert into kategoriler(katAdi,katTuru,ustKat,aciklama) values(?,?,?,?)');
            $katKaydet->execute(array($katAdi, $katTuru, $ustKat, $aciklama));

            if ($katKaydet->rowCount()) {
                echo '<div class="alert alert-success mt-2 text-center">Kayıt Başarılı</div><meta http-equiv="refresh" content="1; url=kategoriler.php">';
            } else {
                echo '<div class="alert alert-danger mt-2 text-center">Hata Oluştu</div><meta http-equiv="refresh" content="1; url=kategoriler.php">';
            }
        }
        ?>
        <!-- Kategori Insert Module End -->
    </div>

    <div class="col-md-9">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Türü</th>
                    <th>Üst Kat.</th>
                    <th>Açıklama</th>
                    <th>Düzenle</th>
                    <th>Sil</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $katSec = $db->prepare('select * from kategoriler order by katAdi asc');
                $katSec->execute();

                if ($katSec->rowCount()) {
                    foreach ($katSec as $katSecSatir) {
                ?>
                        <tr>
                            <td><?php echo $katSecSatir['katAdi']; ?></td>
                            <td><?php echo $katSecSatir['katTuru']; ?></td>
                            <td><?php echo $katSecSatir['ustKat']; ?></td>
                            <td><?php echo $katSecSatir['aciklama']; ?></td>
                            <td><a href="kategoriler.php?updateId=<?php echo $katSecSatir['id']; ?>" class="btn btn-warning">Düzenle</a></td>
                            <td>
                                <a href="kategoriler.php?katDelete=<?php echo $katSecSatir['id']; ?>" class="btn btn-danger">Sil</a>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Start -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sayfa Yüklendiğinde Gösterilen Modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <input type="text" name="katAdiUp" value="<?php echo $selectKatSatir['katAdi']; ?>" class="form-control">
                    <select name="katTuruUp" class="form-control my-2">
                        <option value="<?php echo $selectKatSatir['katTuru']; ?>"><?php echo $selectKatSatir['katTuru']; ?></option>
                        <option value="Alt Kategori">Alt Kategori</option>
                        <option value="Üst Kategori">Üst Kategori</option>
                    </select>
                    <label>Üst Kategori</label>
                    <select name="ustKatUp" class="form-control">
                        <option value="<?php echo $selectKatSatir['ustKat']; ?>"><?php echo $selectKatSatir['ustKat']; ?></option>
                        <?php
                        $katList = $db->prepare('select * from kategoriler order by katAdi asc');
                        $katList->execute();
                        if ($katList->rowCount()) {
                            foreach ($katList as $katListSatir) {
                        ?>
                                <option value="<?php echo $katListSatir['katAdi']; ?>"><?php echo $katListSatir['katAdi']; ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                    <textarea name="aciklamaUp" rows="4" class="form-control my-2"><?php echo $selectKatSatir['aciklama']; ?></textarea>
                    <input type="hidden" name="ID" value="<?php echo $selectKatSatir['id']; ?>">
                    <input type="submit" value="Kaydet" class="btn btn-success w-100" name="kaydetUp">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal End -->

<!-- Update Module Start -->
<?php
if (isset($_POST['kaydetUp'])) {
    $katAdiUp = $_POST['katAdiUp'];
    $katTuruUp = $_POST['katTuruUp'];
    $aciklamaUp = $_POST['aciklamaUp'];
    $ustKatUp = $_POST['ustKatUp'];
    $ID = $_POST['ID'];

    $guncelle = $db->prepare('update kategoriler set katAdi=?, katTuru=?, aciklama=?, ustKat=? where id=?');
    $guncelle->execute(array($katAdiUp, $katTuruUp, $aciklamaUp, $ustKatUp, $ID));

    if ($guncelle->rowCount()) {
        echo '<script>alert("Güncelleme Başarılı")</script><meta http-equiv="refresh" content="0; url=kategoriler.php">';
    } else {
        echo '<script>alert("Hata Oluştu")</script><meta http-equiv="refresh" content="0; url=kategoriler.php">';
    }
}
?>
<!-- Update Module End -->

<!-- Admin Body Section End -->
<?php require_once('footer.php'); ?>