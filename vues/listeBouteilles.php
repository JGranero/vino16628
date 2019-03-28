
<div class="boutons_listeBouteilles">
    <!-- Bouton Ajouter une bouteille -->
    <div class="boutonSolo boutonHaut">
        <button><a href="index.php?requete=nouvelleBouteilleCellier&idCellier=<?= $data['idCellier'] ?>"><i class="fas fa-plus"></i>  Ajouter une bouteille</a></button>
    </div>

    <!-- Bouton d'affichage des bouteilles -->
    <div class="boutonAfficher">
        <button><a href="#tableau_bouteille"><i class="fas fa-list"></i></a></button>
        <button><a href="#vignette_bouteille"><i class="fas fa-th"></i></a></button>
    </div>
</div>  





<div class="listeBouteilletab">

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>SAQ</th>
                <th>Type</th>
                <th>Pays</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Format</th>
                <th>Date d'achat</th>
                <th>Garder Jusqu'à</th>
                <th>Notes</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($data['listeBouteilles'] as $cle => $bouteille) {
        ?>
        <tr>
            <td><?php echo $bouteille['nom'] ?></td>
            <td><a href="<?php echo $bouteille['url_saq']?>">Saq</a></td>
            <td><?php echo $bouteille['type'] ?></td>
            <td><?php echo $bouteille['pays'] ?></td>
            <td><?php echo $bouteille['prix'] ?></td>
            <td><?php echo $bouteille['quantite'] ?></td>
            <td><?php echo $bouteille['format'] ?></td>
            <td><?php echo $bouteille['date_achat'] ?></td>
            <td><?php echo $bouteille['garde_jusqua'] ?></td>
            <td><?php echo $bouteille['notes'] ?></td>
            <td>
                <button class='btnAjouter'><i class="fas fa-plus"></i></button>
                <button class='btnBoire'><i class="fas fa-minus"></i></button>
                <button class='btnModifier'><a href="index.php?requete=modifierBouteille&idBouteille=<?= $bouteille['id_bouteille'] ?>"><i class="fas fa-edit"></i></a></button>
                <button class='btnSupprimer'><i class="fas fa-trash-alt"></i></button>
            </td>
        </tr>

		<?php
	    }
	    ?>
        </tbody>
    </table>
</div>

<div class="listeBouteille">    
    <?php
    foreach ($data['listeBouteilles'] as $cle => $bouteille) {
    ?>

    <!-- Carte -->
    <div class="carte bouteille" data-id="<?php echo $bouteille['id_bouteille'] ?>">
        <!-- Titre -->
        <div class="carte-titre">
            <h4><?php echo $bouteille['nom'] ?></h4>
        </div>

        <div class="carte-description">
            <!-- Texte -->
            <div class="carte-texte">Quantité : <span class="quantite"><?php echo $bouteille['quantite'] ?></span></div>
            <div class="carte-texte">Pays : <?php echo $bouteille['pays'] ?></div>
            <div class="carte-texte">Type : <?php echo $bouteille['type'] ?></div>
            <div class="carte-texte">Millesime : <?php echo $bouteille['millesime'] ?></div>
            <div class="carte-texte"><a href="<?php echo $bouteille['url_saq'] ?>">Voir SAQ</a></div>
        </div>

        <!-- Carte image -->
        <div class="carte-image">
            <?php

            if ($bouteille['url_img'] == null){
                ?>
                <img src="./images/bouteille_vin.png" alt="Image de la bouteille">

                <?php
            }
            else{
                ?>
                <img src="https:<?php echo $bouteille['url_img'] ?>" alt="Image de la bouteille">

                <?php

            }
            ?>
        </div>

        <!-- Bouton -->
        <div class="carte-pied">
            <button class='btnAjouter'><i class="fas fa-plus"></i></button>
            <button class='btnBoire'><i class="fas fa-minus"></i></button>
            <button class='btnModifier'><a href="index.php?requete=modifierBouteille&idBouteille=<?= $bouteille['id_bouteille'] ?>"><i class="fas fa-edit"></i></a></button>
            <button class='btnSupprimer'><i class="fas fa-trash-alt"></i></button>
        </div>
    </div>
    
    <?php
    }
    ?>
</div>
