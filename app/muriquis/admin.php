<?php
# NOUVEAU SOUS SITE

# etape de customization
# on recupere les couleurs / typo
/*
$cssString = file_get_contents('color_local.css');
$cssString = str_replace('[*bodyColor*]', '#00FF00', $cssString);

# ecrire dans un fichier css
file_put_contents('color_local.css', $cssString);
header('Location: /index.php');
