<?php 
use yii\helpers\imdat;
?>
<h3>Garanti süresi hatırlatması</h3>   

<table>
    <tr class="alert alert-info">
        <td  style="color:red">Cihaz Türü</td>
        <td>Marka</td>
        <td>Model</td>
        <td>Alım Tarihi</td>
        <td>Garanti Bitisi</td>
        <td>Ürün Anahtarı</td>
    </tr> 
    <hr>
    <?php 
        foreach ($data as $key => $value) {
            if ($value[0]==1) {
    ?>
                <tr class="alert alert-danger" >
                    <td><?= $value[1][0] ?></td>
                    <td><?= $value[1][1] ?></td>
                    <td><?= $value[1][2] ?></td>
                    <td><?= imdat::mysqltowebdate($value[1][3]) ?></td>
                    <td><?= imdat::mysqltowebdate($value[1][4]) ?></td>
                    <td><?= $value[1][5] ?></td>
                </tr> 
    <?php
            }elseif ($value[0]==3) {
    ?><hr>
                <tr class="alert alert-warning">
                    <td><?= $value[1][0] ?></td>
                    <td><?= $value[1][1] ?></td>
                    <td><?= $value[1][2] ?></td>
                    <td><?= imdat::mysqltowebdate($value[1][3]) ?></td>
                    <td><?= imdat::mysqltowebdate($value[1][4]) ?></td>
                    <td><?= $value[1][5] ?></td>
                </tr> 
    <?php

            }elseif ($value[0]==6) {
    ?><hr>
                <tr class="alert alert-success">
                    <td><?= $value[1][0] ?></td>
                    <td><?= $value[1][1] ?></td>
                    <td><?= $value[1][2] ?></td>
                    <td><?= imdat::mysqltowebdate($value[1][3]) ?></td>
                    <td><?= imdat::mysqltowebdate($value[1][4]) ?></td>
                    <td><?= $value[1][5] ?></td>
                </tr> 
    <?php

            }

        }
    ?>

</table>