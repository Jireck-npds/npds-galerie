<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/* Module de gestion de galeries pour NPDS                              */
/*                                                                      */
/* (c) 2004-2005 Tribal-Dolphin - http://www.tribal-dolphin.net         */
/* (c) 2007 Xgonin, Lopez - http://modules.npds.org                     */
/* MAJ conformité XHTML pour REvolution 10.02 par jpb/phr en mars 2010  */
/* MAJ Dev - 2011                                                       */
/* MAJ jpb, phr - 2017 renommé npds_galerie pour Rev 16                 */
/* v 3.0                                                                */
/* Spanish Language File                                                */
/************************************************************************/

function gal_translate($phrase) {
   switch ($phrase) {
      case " par ": $tmp = " por "; break;
      case " proposé par ": $tmp = " propuesto por "; break;
      case "A": $tmp = "A"; break;
      case "Accès à la galerie": $tmp = "Acceso a la galería"; break;
      case "Accès pour": $tmp = "Acceso para"; break; 
      case "Accueil": $tmp = "Inicio"; break;
      case "Adresse e-mail du destinataire": $tmp = "Email del destinatario"; break;
      case "Affectation vers la galerie choisie.": $tmp = "Assignment to the chosen gallery"; break;
      case "Affectation": $tmp = "afectación"; break;
      case "affichage(s)": $tmp = "vista(s)"; break;
      case "Affichées :": $tmp = "Mostradas :"; break;
      case "Affichées": $tmp = "Desplegado"; break;
      case "Afficher des photos aléatoires ?": $tmp = "¿Ver fotos aleatorias?"; break;
      case "Afficher les commentaires ?": $tmp = "¿Mostrar comentarios?"; break;
      case "Afficher les derniers ajouts ?": $tmp = "¿Ver las últimas imágenes agregadas?";break;
      case "Afficher les votes ?": $tmp = "¿Mostrar los votos?"; break;
      case "Ajout catégorie": $tmp = "Agregar categoria"; break;
      case "Ajout galerie": $tmp = "Agregar galería"; break;
      case "Ajout images": $tmp = "Agregar foto"; break;
      case "Ajout sous-catégorie": $tmp = "Agregar Subcategoría"; break;
      case "Ajouter": $tmp = "Agregar"; break;
      case "Ajouter": $tmp = "Agregar"; break;
      case "Ajoutez votre commentaire": $tmp = "A&ntilde;adir un comentario"; break;
      case "Arborescence": $tmp = "Estructura de árbol"; break; 
      case "Aucune catégorie trouvée": $tmp = "Ninguna categor&iacute;a encontrada "; break;
      case "Aucune galerie trouvée": $tmp = "Ningun album encontrado "; break;
      case "Aucune image dans le dossier": $tmp = "No images in the folder"; break;
      case "Aucune image trouvée": $tmp = "Ninguna imagen encontrada "; break;
      case "Catégorie non supprimée": $tmp = "Categoría no eliminada"; break;
      case "Catégorie non supprimée": $tmp = "Category not deleted"; break;
      case "Catégorie parente": $tmp = "Categoría principal"; break; 
      case "Catégorie": $tmp = "Categoría"; break;
      case "Catégories": $tmp = "Categor&iacute;as"; break;
      case "Choisissez": $tmp = "Elegir"; break; 
      case "Cliquer pour déplier": $tmp = "Haga clic para expandir"; break;
      case "Cliquer sur image": $tmp = "Haga clic en la foto"; break;
      case "Commentaire": $tmp = "Comentario"; break;
      case "commentaire(s)": $tmp = "comentario(s)"; break;
      case "Commentaire(s)": $tmp = "Comentario(s)"; break;
      case "Configuration": $tmp = "configuración"; break;
      case "Confirmer": $tmp = "Confirmar"; break;
      case "Création des images et imagettes dans": $tmp = "Creating images and thumbnails in the folder"; break;
      case "Créée le": $tmp = "Creado el"; break;
      case "De la part de": $tmp = "De la parte de"; break;
      case "Derniers ajouts": $tmp = "&Uacute;ltimas imagenes a&ntilde;adidas"; break;
      case "des images les plus commentées": $tmp = "la mayoria de los conocidos"; break;
      case "des images les plus notées": $tmp = "la mayoria de los cuadros"; break;
      case "Des photos viennent d'être proposées dans la galerie photo du site ": $tmp = "Fotos fueron presentados en la galeria de fotos del sitio "; break;
      case "Description :": $tmp = "Descripcion :"; break;
      case "Description": $tmp = "Descripción"; break;
      case "Diaporama": $tmp = "Diapositiva"; break;
      case "Dimension maximale de l'image en pixels": $tmp = "Tamaño máximo de la imagen en píxeles"; break;
      case "Dimension maximale de l'image incorrecte": $tmp = "Tamaño máximo de la imagen incorrecta"; break;
      case "Dimension maximale de la miniature en pixels": $tmp = "Tamaño máximo de la miniatura en píxeles"; break;
      case "Dimension maximale de la miniature incorrecte": $tmp = "Tamaño máximo de la miniatura incorrecta"; break;
      case "Dimensions :": $tmp = "Tama&ntilde;o :"; break;
      case "Dimensions": $tmp = "Dimensiones"; break;
      case "E-carte": $tmp = "E-tarjeta"; break;
      case "Effacer": $tmp = "Borrar"; break;
      case "Envoyer comme e-carte": $tmp = "Enviar una E-tarjeta"; break;
      case "Envoyer": $tmp = "Enviar"; break;
      case "Erreur": $tmp = "Error"; break;
      case "Export catégorie": $tmp = "Exportar categoría"; break;
      case "Exporter": $tmp = "Exportar"; break;
      case "fois": $tmp = "vez(ces)"; break;
      case "Galerie Privée, connectez vous": $tmp = " Galeria privado, Conectarle"; break;
      case "Galerie temporaire": $tmp = "Temporal de la galeria"; break; 
      case "Galerie": $tmp = "galería"; break;
      case "Galeries de photos": $tmp = "Galerías de fotos"; break;
      case "Galeries": $tmp = "Albumes"; break;
      case "Image :": $tmp = "Cuadro :"; break;
      case "Image 1": $tmp = "Foto 1"; break; 
      case "Image 2": $tmp = "Foto 2"; break; 
      case "Image 3": $tmp = "Foto 3"; break; 
      case "Image 4": $tmp = "Foto 4"; break; 
      case "Image 5": $tmp = "Foto 5"; break;
      case "Image": $tmp = "Foto"; break;
      case "Images du dossier": $tmp = "Folder images"; break;
      case "Images vues": $tmp = "Imágenes vistas"; break;
      case "IMAGES": $tmp = "CUADROS"; break;
      case "Import images": $tmp = "Importa fotos"; break;
      case "Importer": $tmp = "Importar"; break;
      case "Informations sur l'image": $tmp = "Informaciones de la imagen"; break;
      case "L'adresse mail du destinataire est incorrecte.": $tmp = "El Email del destinatario no es correcto."; break;
      case "Le message ne peut être vide.": $tmp = "El mensaje no puede estar vacio."; break;
      case "Le nom du destinataire ne peut être vide.": $tmp = "El nombre del destinatario no puede estar vacio."; break;
      case "Le sujet ne peut être vide.": $tmp = "El asunto no puede estar vacio."; break;
      case "Les anonymes peuvent envoyer des E-Cartes ?": $tmp = "¿Anónimo puede enviar ECards?"; break;
      case "Les anonymes peuvent poster un commentaire ?": $tmp = "¿Anónimo puede hacer comentarios?"; break;
      case "Les anonymes peuvent voter ?": $tmp = "¿Anónimo puede votar?"; break;
      case "Les images importées seront supprimées du dossier": $tmp = "Imported images will be deleted from the folder"; break;
      case "Message": $tmp = "Mensaje"; break;
      case "Nom de la catégorie": $tmp = "Nombre de categoría"; break;
      case "Nom de la galerie": $tmp = "Nombre de galeria"; break;
      case "Nom de la sous-catégorie": $tmp = "Nombre de la subcategoría"; break; 
      case "Nom du destinataire": $tmp = "Nombre del destinatario"; break;
      case "Nombre d'images à afficher dans le top commentaires": $tmp = "Número de imágenes para mostrar en los comentarios principales"; break;
      case "Nombre d'images à afficher dans le top votes": $tmp = "Número de imágenes para mostrar en los votos más altos"; break;
      case "Nombre d'images par ligne": $tmp = "Número de imágenes por línea"; break;
      case "Nombre d'images par page": $tmp = "Número de imágenes por página"; break;
      case "Nombre d'images": $tmp = "Numero de cuadros"; break; 
      case "Nombre d'images": $tmp = "Número de imágenes"; break;
      case "Nombre de catégories": $tmp = "Numero de categorias"; break;
      case "Nombre de catégories": $tmp = "Numero de categorias"; break; 
      case "Nombre de commentaires": $tmp = "Numero de comentarios"; break;
      case "Nombre de commentaires": $tmp = "Numero de comentarios"; break;
      case "Nombre de commentaires": $tmp = "Numero de commentarios"; break; 
      case "Nombre de galeries": $tmp = "Numero de galerias"; break;
      case "Nombre de galeries": $tmp = "Numero de galerias"; break; 
      case "Nombre de notes": $tmp = "Number of notes"; break; 
      case "Nombre de notes": $tmp = "Numero de votos"; break; 
      case "Nombre de sous-catégories": $tmp = "Numero de subcategorias"; break; 
      case "Nombre de vote(s)": $tmp = "Número de votos"; break; 
      case "Nombre de votes": $tmp = "Numero de votos"; break;
      case "Note ": $tmp = "Nota "; break;
      case "Note (": $tmp = "Nota ("; break;
      case "Noter cette image": $tmp = "Notar esta imagen"; break;
      case "Notifier par email l'administrateur de la proposition de photos ?": $tmp = "¿Notificar al administrador de la propuesta de fotografía por correo electrónico?"; break;
      case "Nouvelle soumission de Photos": $tmp = "Fotos nueva presentaci&oacute;n"; break;
      case "Photo envoyée avec succés, elle sera traitée par le webmaster": $tmp = "Foto enviada con exito, sera manejado por el webmaster";
      case "Photos aléatoires": $tmp = "Fotograf&iacute;as aleatorias"; break;
      case "Pour toutes les images de cet import.": $tmp = "For all images of this import."; break;
      case "Proposer des images": $tmp = "Para proponer cuadros"; break;
      case "Proposer des images": $tmp = "Proponer algunos cuadros"; break;
      case "Résultat": $tmp = "Result"; break;
      case "Sélectionner une galerie": $tmp = "Seleccione una galería"; break;
      case "Sélectionner votre image": $tmp = "Seleccione su imagen"; break; 
      case "Si votre e-carte ne s'affiche pas correctement, cliquez ici": $tmp = "Si su E-tarjeta no se v&eacute; correctamente, haga clic aqu&iacute;"; break;
      case "Sous-catégorie supprimée": $tmp = "Subcategoría eliminada"; break;
      case "Sous-catégorie": $tmp = "Subcategoría no eliminada"; break;
      case "Sous-catégorie": $tmp = "Subcategoría"; break;
      case "Sujet": $tmp = "Asunto"; break;
      case "Suspendre le Diaporama": $tmp = "Suspender la Diapositiva"; break;
      case "Tableau récapitulatif": $tmp = "Tabla de resumen"; break;
      case "Taille du fichier :": $tmp = "Tama&ntilde;o del fichero:"; break;
      case "Taille du fichier": $tmp = "Tamaño de archivo"; break;
      case "Top-Commentaires": $tmp = "Top-Comentario"; break; 
      case "Top-Votes": $tmp = "Top-Votos"; break;
      case "Top": $tmp = "Top"; break;
      case "Une e-carte pour vous": $tmp = "Una E-tarjeta para usted"; break;
      case "Valider": $tmp = "Validar"; break;
      case "vote(s)": $tmp = "voto(s)"; break;
      case "votes) :": $tmp = "votos):"; break;
      case "Votre adresse e-mail": $tmp = "Su Email"; break;
      case "Votre adresse mail est incorrecte.": $tmp = "Su Email no es valida."; break;
      case "Votre E-CARTE à été envoyé.": $tmp = "Su E-tarjeta fu&eacute; enviada."; break;
      case "Votre E-CARTE n'à pas été envoyé.": $tmp = "Su E-tarjeta no fu&eacute; enviada."; break;
      case "Votre nom": $tmp = "Su nombre"; break;
      case "Vous avez déjà commenté cette photo": $tmp = "Lo sentimos, pero ya coment&oacute; esta foto"; break;
      case "Vous avez déjà noté cette photo": $tmp = "Lo sentimos, pero ya not&oacute; esta foto"; break;
      case "Vous n'avez accés à aucune galerie": $tmp = "No tienes acceso"; break;
      default: $tmp = "Necesita ser traducido [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>