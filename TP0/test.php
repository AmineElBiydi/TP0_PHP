<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <title>Emai Filtre</title>
</head>

<body>
<?php
$emailValid=[];
$emailInvalid=[];
$contentt="";
$FILE= fopen("Email.txt", "r");
if ($FILE) {
    $contentt = fread($FILE, filesize("Email.txt"));
    fclose($FILE);
} else {
    $contentt = "Erreur lors de l'ouverture du fichier.";
}


function filtreMaill(String $email, array &$emailValid, array &$emailInvalid ){
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $emailInvalid[]=$email;
    }
    else {
        $emailValid[]=$email;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Découper le contenu ligne par ligne
    $emails = explode("\n", $contentt);

    // Filtrer chaque email
    foreach ($emails as $email) {
        $email = trim($email); // Supprimer les espaces
        if (!empty($email)) {
            filtreMaill($email, $emailValid, $emailInvalid);
        }
    }
}

?>
<?php if (isset($contentt)): ?>
    <p> la liste des emails qui existe  </p>
    <?php echo nl2br(htmlspecialchars($contentt)); ?>
<?php endif; ?>

<form method="POST" action="">
    <button id="filtrerEmail" name="filtrerEmail" > Filtrer les adresses emails </button>
</form>


<h3 > Liste des adresses emails valides </h3>
<?php if (!empty($emailValid)) : ?>
    <h3 style="color:green;">Liste des adresses emails valides :</h3>
    <?php foreach ($emailValid as $valid) : ?>
        <p><?= $valid ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($emailInvalid)) : ?>
    <h3 style="color: red;">Liste des adresses emails invalides :</h3>
    <?php foreach ($emailInvalid as $invalid) : ?>
        <p><?= $invalid?></p>
    <?php endforeach; ?>
<?php endif; ?>

</body>


</html>