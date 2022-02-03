<div class="col-12 filmContainer">
    <div class="col-12 naslovFilm">
        <h3 class="movieHeaderSingle"><?php echo $sNazivFilma ?></h3>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="slikacontainer" style="text-align:center;">
                <img class="slikasingle" src="<?php echo $sIstaknutaSlika?>" alt="">
            </div>
            <div class="filmOpis">
                <p>Ocjena: <i class="fas fa-star"> </i> <?php echo  $ocjena_list["0"]->name .'/10' ?> </p>
                <p>Glavni glumci: <?php foreach($glavni_glumci_list as $glumac){
                    $x++;
                    if($x == count($glavni_glumci_list)) echo $glumac->name;
                    else echo $glumac->name .', ';
                } ?></p>
                <p>Redatelj: <?php echo  $redatelj_list["0"]->name ?></p>
            </div>
        </div>
        <div class="col-6">
            <div class="opisContainer">
                <h3 class="opisHeader">Opis filma</h3>
                <p class="opisOpis"><?php echo nl2br($PostContent) ?> </p>
            </div>
        </div>
        <div class="col-3">
            <div class="nagreadeContainer">
                <?php
                if(count($nagrade_list)>=1){
                    echo '<h3 class="opisHeaderNagrade">Nagrade</h3>';
                    echo '<ul>';
                    foreach($nagrade_list as $nagrada){
                        echo '<li>'.$nagrada->name.'</li><br>';
                    }
                    echo '</ul>';
                } 
                ?>
            </div>
        </div>
    </div>
     
</div>
<div class="col-md-12" style="text-align:center;">  
    <button class="buttonPosudi">POSUDI FILM</button>
    <div style="display: none;" id="nedovoljnofilmova">Nema dostupnih primjeraka!</div>
</div>