<div class="col-12 filmContainer">
    <div class="col-12 naslovFilm">
        <h3 class="movieHeaderSingle"><?php echo $sNazivSerije ?></h3>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="slikacontainer" style="text-align:center;">
                <img class="slikasingle" src="<?php echo $sIstaknutaSlika?>" alt="">
            </div>
            <div class="filmOpis">
                <p>Ocjena: <i class="fas fa-star"> </i> <?php echo  ' '.$ocjena["0"]->name ?>/10 </p>
                <p>Glavni glumci: <?php foreach($glavni_glumci_list as $glumac){
                    $x++;
                    if($x == count($glavni_glumci_list)) echo $glumac->name;
                    else echo $glumac->name .', ';
                } ?></p>
                <p>Broj sezona: <?php echo $broj_sezona["0"]->name?></p>
            </div>
        </div>
        <div class="col-6">
            <div class="opisContainer">
                <h3 class="opisHeader">Opis serije</h3>
                <p class="opisOpis"><?php echo nl2br($PostContent) ?> </p>
                <br>
                <br>
                <p>Žanr: <?php foreach($zanr as $z){
                    $y++;
                    if(count($zanr)== $y) echo $z->name;
                    else echo $z->name.', ';
                } ?></p>
            </div>
        </div>
        <div class="col-3">
            <div class="epizodeContainer">
                <h4 class="eh4izodeNaslov">Epizode</h4>
                <?php 
                echo '<ul class="epizodeLista">';
                foreach($epizode_list as $sezona){
                    if($sezona->parent == 0){
                        echo '<li>'. $sezona->name . '</li>';
                        echo '<ul class="epizodeLista"';
                        foreach($epizode_list as $epizoda){
                            if($sezona->term_id == $epizoda->parent){
                                echo '<li> '. $epizoda->name .'</li> ';
                            }
                        }
                        echo '</ul>';
                    } 
                } 
                echo '</ul>';?>
            </div>
        </div>
    </div>
     
</div>
<div class="col-md-12" style="text-align:center;">  
    <button class="buttonPosudi">POSUDI SERIJU</button>
    <div style="display: none;" id="nedovoljnofilmova">Nema dostupnih primjeraka!</div>
</div>