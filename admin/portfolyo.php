<?php
require_once('header.php');


if (isset($_GET['deleteID'])) {
    $id = $_GET['deleteID'];

    $projeSil = $db->prepare('delete from portfolyo where id=?');
    $projeSil->execute(array($id));

    if ($projeSil->rowCount()) {
        echo '<script>alert("Proje Silindi")</script><meta http-equiv="refresh" content="0; url=portfolyo.php">';
    } else {
        echo '<script>alert("Hata Oluştu")</script><meta http-equiv="refresh" content="0; url=portfolyo.php">';
    }
} elseif (isset($_GET['updateID'])) {
    $id = $_GET['updateID'];

    $projeGuncelle = $db->prepare('select * from portfolyo where id=?');
    $projeGuncelle->execute(array($id));
    $projeGuncelleSatir = $projeGuncelle->fetch();

    echo '
        <script>
            document.addEventListener("DOMContentLoaded", function () {
            var myModal = new bootstrap.Modal(document.getElementById("exampleModal"));
            myModal.show();
            });
        </script>
    ';
}
?>
<!-- Admin Body Section Start -->
<div class="row">
    <div class="col-md-6">
        <h3>Portfolyo</h3>
    </div>
    <div class="col-md-6">
        <!-- Button trigger modal -->
        <div class="text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Yeni Ekle
            </button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Yeni Proje Ekle</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" class="row" enctype="multipart/form-data">
                            <div class="col-12">
                                <input type="text" name="projeAdi" placeholder="Proje Adı Girin" class="form-control">
                            </div>
                            <div class="col-12 my-3">
                                <textarea name="aciklama" placeholder="Proje Açıklaması Girin" rows="6" class="form-control"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label>Firma Adı</label>
                                <input type="text" name="kurum" placeholder="Firma Adı Girin" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Proje Türü</label>
                                <select name="projeTuru" class="form-control">
                                    <option value="">Seçiniz</option>
                                    <option value="Bireysel">Bireysel</option>
                                    <option value="Kurumsal">Kurumsal</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Hizmet</label>
                                <select name="hizmet" class="form-control">
                                    <option value="">Seçiniz</option>
                                    <option value="Dijital Pazarlama Hizmeti">Dijital Pazarlama Hizmeti</option>
                                    <option value="Grafik Tasarımı Hizmeti">Grafik Tasarımı Hizmeti</option>
                                    <option value="Web Tasarımı Hizmeti">Web Tasarımı Hizmeti</option>
                                </select>
                            </div>
                            <div class="col-md-4 my-3">
                                <label>Kullanılan Teknolojiler</label>
                                <input type="text" name="teknoloji" placeholder="Kullanılan Teknolojiler" class="form-control">
                            </div>
                            <div class="col-md-4 my-3">
                                <label>Proje Adresi</label>
                                <input type="url" name="adres" placeholder="https://wwww.projeadresi.com" class="form-control">
                            </div>
                            <div class="col-md-4 my-3">
                                <label>Proje Görseli</label>
                                <input type="file" name="gorsel" class="form-control">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success w-100" name="projeKaydet">Kaydet</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width:150px;">Görsel</th>
                    <th>Proje</th>
                    <th>Firma</th>
                    <th>Hizmet</th>
                    <th>Düzenle</th>
                    <th>Sil</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $projeList = $db->prepare('select * from portfolyo order by id desc');
                $projeList->execute();

                if ($projeList->rowCount()) {
                    foreach ($projeList as $projeListSatir) {
                ?>
                        <tr>
                            <td><img src="<?php echo $projeListSatir['gorsel']; ?>" class="w-100"></td>
                            <td><?php echo $projeListSatir['projeAdi']; ?></td>
                            <td><?php echo $projeListSatir['kurum']; ?></td>
                            <td><?php echo $projeListSatir['hizmet']; ?></td>
                            <td><a href="portfolyo.php?updateID=<?php echo $projeListSatir['id']; ?>" class="btn btn-warning">Düzenle</a></td>
                            <td><a href="portfolyo.php?deleteID=<?php echo $projeListSatir['id']; ?>" class="btn btn-danger">Sil</a></td>
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

<!-- Portfolio Save Module Start -->
<?php
if (isset($_POST['projeKaydet'])) {
    $gorsel = '../assets/img/' . $_FILES['gorsel']['name'];

    if (move_uploaded_file($_FILES['gorsel']['tmp_name'], $gorsel)) {
        $yeniProje = $db->prepare('insert into portfolyo(projeAdi,aciklama,kurum,projeTuru,hizmet,teknoloji,adres,gorsel) values(?,?,?,?,?,?,?,?)');
        $yeniProje->execute(array($_POST['projeAdi'], $_POST['aciklama'], $_POST['kurum'], $_POST['projeTuru'], $_POST['hizmet'], $_POST['teknoloji'], $_POST['adres'], $gorsel));

        if ($yeniProje->rowCount()) {
            echo '<script>alert("Proje Kaydedildi")</script><meta http-equiv="refresh" content="0; url=portfolyo.php">';
        } else {
            echo '<script>alert("Hata Oluştu")</script><meta http-equiv="refresh" content="0; url=portfolyo.php">';
        }
    }
}
?>
<!-- Portfolio Save Module End -->


<!-- Update Modal Start -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo $projeGuncelleSatir['projeAdi']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" class="row" enctype="multipart/form-data">
                    <div class="col-12">
                        <input type="text" name="projeAdiUP" value="<?php echo $projeGuncelleSatir['projeAdi']; ?>" class="form-control">
                    </div>
                    <div class="col-12 my-3">
                        <textarea name="aciklamaUP" rows="6" class="form-control"><?php echo $projeGuncelleSatir['aciklama']; ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label>Firma Adı</label>
                        <input type="text" name="kurumUP" value="<?php echo $projeGuncelleSatir['kurum']; ?>" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Proje Türü</label>
                        <select name="projeTuruUP" class="form-control">
                            <option value="<?php echo $projeGuncelleSatir['projeTuru']; ?>"><?php echo $projeGuncelleSatir['projeTuru']; ?></option>
                            <option value="Bireysel">Bireysel</option>
                            <option value="Kurumsal">Kurumsal</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Hizmet</label>
                        <select name="hizmetUP" class="form-control">
                            <option value="<?php echo $projeGuncelleSatir['hizmet']; ?>"><?php echo $projeGuncelleSatir['hizmet']; ?></option>
                            <option value="Dijital Pazarlama Hizmeti">Dijital Pazarlama Hizmeti</option>
                            <option value="Grafik Tasarımı Hizmeti">Grafik Tasarımı Hizmeti</option>
                            <option value="Web Tasarımı Hizmeti">Web Tasarımı Hizmeti</option>
                        </select>
                    </div>
                    <div class="col-md-4 my-3">
                        <label>Kullanılan Teknolojiler</label>
                        <input type="text" name="teknolojiUP" value="<?php echo $projeGuncelleSatir['teknoloji']; ?>" class="form-control">
                    </div>
                    <div class="col-md-4 my-3">
                        <label>Proje Adresi</label>
                        <input type="url" name="adresUP" value="<?php echo $projeGuncelleSatir['adres']; ?>" class="form-control">
                    </div>
                    <div class="col-md-4 my-3">
                        <label>Proje Görseli: <b><?php echo substr($projeGuncelleSatir['gorsel'],14); ?></b></label>
                        <input type="file" name="gorselUP" class="form-control">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success w-100" name="guncelle">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Update Modal End -->

<!-- Update Module Start -->

<?php

if(isset($_POST['guncelle'])){
    $gorsel = '../assets/img/'.$_FILES['gorselUP']['name'];

    if(move_uploaded_file($_FILES['gorselUP']['tmp_name'],$gorsel)){
        $guncelle = $db -> prepare('update portfolyo set projeAdi=?, aciklama=?, kurum=?, projeTuru=?, hizmet=?, teknoloji=?, adres=?, gorsel=? where id=?');
        $guncelle -> execute(array($_POST['projeAdiUP'], $_POST['aciklamaUP'], $_POST['kurumUP'], $_POST['projeTuruUP'], $_POST['hizmetUP'], $_POST['teknolojiUP'], $_POST['adresUP'],$gorsel,$id));

        if($guncelle -> rowCount()){
            echo '<script>alert("Güncelleme Başarılı")</script><meta http-equiv="refresh" content="0; url=portfolyo.php">';
        } else {
            echo '<script>alert("Hata Oluştu")</script><meta http-equiv="refresh" content="0; url=portfolyo.php">';
        }
    } else {
        $guncelle = $db -> prepare('update portfolyo set projeAdi=?, aciklama=?, kurum=?, projeTuru=?, hizmet=?, teknoloji=?, adres=? where id=?');
        $guncelle -> execute(array($_POST['projeAdiUP'], $_POST['aciklamaUP'], $_POST['kurumUP'], $_POST['projeTuruUP'], $_POST['hizmetUP'], $_POST['teknolojiUP'], $_POST['adresUP'],$id));

        if($guncelle -> rowCount()){
            echo '<script>alert("Güncelleme Başarılı")</script><meta http-equiv="refresh" content="0; url=portfolyo.php">';
        } else {
            echo '<script>alert("Hata Oluştu")</script><meta http-equiv="refresh" content="0; url=portfolyo.php">';
        }
    }
}

?>

<!-- Update Module End -->



<?php require_once('footer.php'); ?>