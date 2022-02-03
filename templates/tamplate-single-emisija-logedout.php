<div class="col-12 filmContainer">
    <div class="col-12 naslovFilm">
        <h3 class="movieHeaderSingle"><?php echo $sNazivEmisije ?></h3>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="slikacontainer" style="text-align:center;">
                <img class="slikasingle" src="<?php echo $sIstaknutaSlika?>" alt="">
            </div>
            <div class="filmOpis">
                <p>Vrsta emisije: <?php echo $vrsta_emisije_list["0"]->name ?></p>
            </div>
        </div>
        <div class="col-6">
            <div class="opisContainer">
                <h3 class="opisHeader">Opis emisije</h3>
                <p class="opisOpis"><?php echo nl2br($PostContent) ?> </p>
            </div>
        </div>
        <div class="col-3">
            <div class="epizodeContainer">
                <h4 class="eh4izodeNaslov">U emisiji sudjeluju</h4>
                <?php 
                if(count($voditelji_list)>1){
                    echo '<p>Voditelji: ';
                    foreach($voditelji_list as $voditelj){
                        $x++;
                        if($x == count($voditelji_list)) echo $voditelj->name;
                        else echo $voditelj->name .', ';
                    }
                    echo'</p>';
                }elseif(count($voditelji_list)==1){
                    echo '<p>Voditelj: '.$voditelji_list["0"]->name.'</p>';
                }

                if(count($gosti_list)>1){
                    echo '<p>Gosti: ';
                    foreach($gosti_list as $gost){
                        $q++;
                        if($q == count($gosti_list)) echo $gost->name;
                        else echo $gost->name .', ';
                    }
                    echo'</p>';
                }elseif(count($gosti_list) == 1){
                    echo '<p>Gost: '.$gosti_list["0"]->name.'</p>';
                }

                if(count($natjecatelji_list)>1){
                    echo '<p>Natjecatelji: ';
                    foreach($natjecatelji_list as $natjecatelj){
                        $y++;
                        if($y == count($natjecatelji_list)) echo $natjecatelj->name;
                        else echo $natjecatelj->name .', ';
                    }
                    echo'</p>';
                }elseif(count($natjecatelji_list)==1){
                    echo '<p>Natjecatelj: '.$natjecatelji_list["0"]->name.'</p>';
                }

                if(count($ziri_list)>1){
                    echo '<p>Članovi žirija: ';
                    foreach($ziri_list as $clan){
                        $z++;
                        if($z == count($ziri_list)) echo $clan->name;
                        else echo $clan->name .', ';
                    }
                    echo'</p>';
                }elseif(count($ziri_list)==1){
                    echo '<p>Član žirija: '.$ziri_list["0"]->name.'</p>';
                }
                ?>
            </div>
        </div>
    </div>
     
</div>