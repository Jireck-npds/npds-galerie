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
/************************************************************************/

function PrintFormCat() {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisFile;
   $num = sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0'"));
   echo '
      <h3 class="my-3">'.gal_translate("Catégorie").'<span class="badge badge-secondary float-right" title="'.gal_translate("Nombre de catégories").'" data-toggle="tooltip" data-placement="left">'.$num[0].'</span></h3>
      <h4 class="my-4">'.gal_translate("Ajout catégorie").'</h4>
      <hr />
      <form id="creercat" action="'.$ThisFile.'" method="post" name="FormCat">
         <input type="hidden" name="subop" value="addcat" />
         <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="newcat">'.gal_translate("Nom de la catégorie").'</label>
            <div class="col-sm-8">
               <input type="text" class="form-control" name="newcat" id="newcat" required="required" maxlength="150" />
               <span class="help-block text-right" id="countcar_newcat"></span>
            </div>
         </div>
         <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="accescat">'.gal_translate("Accès pour").'</label>
            <div class="col-sm-8">
               <select class="custom-select" id="accescat" id="accescat">';
   echo Fab_Option_Group('');
   echo '
               </select>
            </div>
         </div>
         <div class="form-group row">
            <div class="col-sm-8 ml-auto">
               <button class="btn btn-primary" type="submit">'.gal_translate("Ajouter").'</button>
            </div>
         </div>
      </form>';
   $arg1 = '
   var formulid = ["creercat"]
   inpandfieldlen("newcat",150);';
   adminfoot('fv','',$arg1,'1');
}

function AddACat($newcat,$acces) {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisRedo;
   if (!empty($newcat)) {
      $newcat = addslashes(removeHack($newcat));
      if (sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' AND nom='$newcat'")))
         echo '<p class="lead text-warning"><i class="fa fa-info-circle mr-2"></i>'.gal_translate("Cette catégorie existe déjà").'</p>';
      else {
         if ($add = sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_cat VALUES ('','0','$newcat','$acces')"))
            redirect_url($ThisRedo);
         else
            echo '<p class="lead text-danger"><i class="fa fa-info-circle mr-2"></i>'.gal_translate("Erreur lors de l'ajout de la catégorie").'</p>';
      }
   } else
      redirect_url($ThisRedo."&subop=formcat");
}

function PrintFormSSCat() {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisFile, $ThisRedo;
   $qnum = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0'"));
   if ($qnum == 0) redirect_url($ThisRedo);
   PrintJavaCodeGal();
   $num = sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."tdgal_cat WHERE cid!=0"));
   echo '
   <h3 class="my-3">'.gal_translate("Sous-catégorie").'<span class="badge badge-secondary float-right" title="'.gal_translate("Nombre de sous-catégories").'" data-toggle="tooltip" data-placement="left">'.$num[0].'</span></h3>
   <h4 class="my-4">'.gal_translate("Ajout sous-catégorie").'</h4>
   <hr />
   <form id="creerscat" action="'.$ThisFile.'" method="post" name="FormCreer">
      <input type="hidden" name="subop" value="addsscat" />
      <div class="form-group row">
         <label class="col-sm-4 col-form-label" for="catparente">'.gal_translate("Catégorie parente").'</label>
         <div class="col-sm-8">
            <select class="custom-select" name="cat" id="catparente" onChange="remplirAcces(this.selectedIndex,this.options[this.selectedIndex].text);">
               <option value="none" selected="selected">'.gal_translate("Choisissez").'</option>';
   $query = sql_query("SELECT id,nom,acces FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ORDER BY nom ASC");
   while ($row = sql_fetch_row($query)) {
      echo '
               <option value="'.$row[0].'">'.stripslashes($row[1]).' ('.Get_Name_Group('',$row[2]).')</option>';
   }
      echo '
            </select>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-sm-4 col-form-label" for="newsscat">'.gal_translate("Nom de la sous-catégorie").' '.$row[2].'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="newsscat" id="newsscat" placeholder="" required="required" />
            <span class="help-block text-right" id="countcar_newsscat"></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-sm-4 col-form-label" for="accessscat">'.gal_translate("Accès pour").'</label>
         <div class="col-sm-8">
            <select class="custom-select" id="accessscat">
            '.Fab_Option_Group().'
            </select>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-8 ml-auto">
            <input class="btn btn-primary" type="submit" value="'.gal_translate("Ajouter").'" />
         </div>
      </div>
   </form>';
   $arg1 = '
   var formulid = ["creerscat"]
   inpandfieldlen("newsscat",150);';
   adminfoot('fv','',$arg1,'1');

}

function AddSsCat($idparent,$newcat,$acces) {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisRedo;
   if (!empty($newcat)) {
      $newcat = addslashes(removeHack($newcat));
      if (sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='$idparent' AND nom='$newcat'")))
         echo '
         <div class="alert alert-danger lead">'.gal_translate("Cette sous-catégorie existe déjà").'</div>';
      else {
         if ($add = sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_cat VALUES ('','$idparent','$newcat','$acces')"))
            redirect_url($ThisRedo);
         else
            echo '
         <div class="alert alert-danger lead ">'.gal_translate("Erreur lors de l'ajout de la sous-catégorie").'</div>';
      }
   }
   else
      redirect_url($ThisRedo."&subop=formsscat");
}

function PrintCreerGalery() {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisFile, $ThisRedo;
   $qnum = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_cat"));
   if ($qnum == 0)
      redirect_url($ThisRedo);
   PrintJavaCodeGal();

   $num = sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."tdgal_gal"));
   $num[0] = ($num[0] -1);
   echo '
   <h3 class="my-3">'.gal_translate("Galeries").'<span class="badge badge-secondary float-right" title="'.gal_translate("Nombre de galeries").'" data-toggle="tooltip" data-placement="left">'.$num[0].'</span></h3>
   <h4>'.gal_translate("Ajout galerie").'</h4>
   <hr/>
   <form id="creergalerie" action="'.$ThisFile.'" method="post" name="FormCreer">
      <input type="hidden" name="subop" value="addsscat" />
      <input type="hidden" name="subop" value="creegal" />
      <div class="form-group row">
         <label class="col-sm-4 col-form-label" for="galcat">'.gal_translate("Catégorie").'</label>
         <div class="col-sm-8">
            <select class="custom-select" name="galcat" id="galcat" onChange="remplirAcces(this.selectedIndex,this.options[this.selectedIndex].text);" />
               <option value="none" selected="selected">'.gal_translate("Choisissez").'</option>';
   echo cat_arbo('');
   echo '
            </select>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-sm-4 col-form-label" for="newgal">'.gal_translate("Nom de la galerie").' '.$row[2].'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" id="newgal" name="newgal" id="newgal" placeholder="" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="col-sm-4 col-form-label" for="droitacces">'.gal_translate("Accès pour").'</label>
         <div class="col-sm-8">
            <select class="custom-select" name="acces" id="droitacces">';
   echo Fab_Option_Group('');
   echo '
            </select>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-8 ml-auto">
            <button class="btn btn-primary" type="submit">'.gal_translate("Ajouter").'</button>
         </div>
      </div>
   </form>';
}

function AddNewGal($galcat,$newgal,$acces) {
   global $ModPath, $ModStart, $gmt, $NPDS_Prefix, $ThisRedo, $acces, $ThisFile;
   if (!empty($newgal)) {
      $newgal = addslashes(removeHack($newgal));
      if (sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='$galcat' AND nom='$newgal'")))
         echo '
      <div class="alert alert-danger lead"><i class="fa fa-info-circle mr-2"></i>'.gal_translate("Cette galerie existe déjà").'</div>';
      else {
         $regdate = time()+((integer)$gmt*3600);
         if ($add = sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_gal VALUES ('','$galcat','$newgal','$regdate','$acces')")) {
            $new_gal_id = sql_last_id();
//   echo '<h4><i class="fa fa-plus"></i> '.gal_translate("Ajouter des photos à cette nouvelle galerie").'</h4>';
   echo '
   <form enctype="multipart/form-data" method="post" action="'.$ThisFile.'" name="FormImgs" lang="'.language_iso(1,'','').'">
      <input type="hidden" name="subop" value="addimgs" />
      <input type="hidden" name="imggal" value="'.$new_gal_id.'" />';

   $i=1;
   do {
      echo '
      <div class="form-group">
         <label class="">'.gal_translate("Image").' '.$i.'</label>
         <div class="input-group mb-2 mr-sm-2">
            <div class="input-group-prepend" onclick="reset2($(\'#newcard'.$i.'\'),'.$i.');">
               <div class="input-group-text"><i class="fa fa-refresh"></i></div>
            </div>
            <div class="custom-file">
               <input type="file" class="custom-file-input" name="newcard'.$i.'" id="newcard'.$i.'" />
               <label id="lab'.$i.'" class="custom-file-label" for="newcard'.$i.'">'.gal_translate("Sélectionner votre image").'</label>
            </div>
         </div>
      </div>
      <div class="form-group">
         <input type="text" class="form-control" id="newdesc'.$i.'"  name="newdesc'.$i.'" placeholder="'.gal_translate("Description").'">
      </div>';
      $i++;
   }
   while($i<=5);
   echo '
      <div class="form-group">
         <button class="btn btn-outline-primary" type="submit">'.gal_translate("Ajouter").'</button>
      </div>
   </form>
   <script type="text/javascript">
      //<![CDATA[
         $(".custom-file-input").on("change",function(){
            $(this).next(".custom-file-label").addClass("selected").html($(this).val().split(\'\\\\\').pop());
         });
         window.reset2 = function (e,f) {
            e.wrap("<form>").closest("form").get(0).reset();
            e.unwrap();
            event.preventDefault();
            $("#lab"+f).html("'.gal_translate("Sélectionner votre image").'")
         };
      //]]>
   </script>';
         } else
            echo '<div class="alert alert-danger">'.gal_translate("Erreur lors de l'ajout de la galerie").'</div>';
      }
   } else
      redirect_url($ThisRedo."&subop=formcregal");
}

function select_arbo($sel) {
   global $NPDS_Prefix;

   $ibid='<option value="-1">'.gal_translate("Galerie temporaire").'</option>';
   $sql_cat = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ORDER BY nom ASC");
   $num_cat = sql_num_rows($sql_cat);
   if ($num_cat != 0) {
      $sql_sscat = "SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid!=0";
      $sql_gal = "SELECT * FROM ".$NPDS_Prefix."tdgal_gal";
      // CATEGORIE
      while ($row_cat = sql_fetch_row($sql_cat)) {
         $ibid.='<optgroup label="'.stripslashes($row_cat[2]).'">';
         $queryX = sql_query("SELECT id, nom  FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         while ($rowX_gal = sql_fetch_row($queryX)) {
            if ($rowX_gal[0] == $sel) { $IsSelected = ' selected'; } else { $IsSelected = ''; }
            $ibid.='<option value="'.$rowX_gal[0].'"'.$IsSelected.'>'.stripslashes($rowX_gal[1]).' </option>';
         } // Fin Galerie Catégorie

         // SOUS-CATEGORIE
         $query = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         while ($row_sscat = sql_fetch_row($query)) {
            $ibid.='<optgroup label="&nbsp;&nbsp;'.stripslashes($row_sscat[2]).'">';
            $querx = sql_query("SELECT id, nom FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_sscat[0]."' ORDER BY nom ASC");
            while ($row_gal = sql_fetch_row($querx)) {
               if ($row_gal[0] == $sel) $IsSelected = ' selected="selected"'; else $IsSelected = '';
               $ibid.='<option value="'.$row_gal[0].'"'.$IsSelected.'>'.stripslashes($row_gal[1]).' </option>';
            } // Fin Galerie Sous Catégorie
            $ibid.='</optgroup>';
         } // Fin Sous Catégorie
         $ibid.='</optgroup>';
      } // Fin Catégorie
   }
   return ($ibid);
}
function cat_arbo($sel) {
   global $NPDS_Prefix;
   $ibid='';
   $queryX = sql_query("SELECT id,nom,acces FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ORDER BY nom ASC");
   while ($rowX = sql_fetch_row($queryX)) {
      if ($sel==$rowX[0]) $selected='selected="selected"'; else $selected='';
      $ibid.='<option value="'.$rowX[0].'" '.$selected.'>'.stripslashes($rowX[1]).' ('.Get_Name_Group("",$rowX[2]).')</option>';
      $queryY = sql_query("SELECT id,nom,acces FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='".$rowX[0]."' ORDER BY nom ASC");
      while ($rowY = sql_fetch_row($queryY)) {
         if ($sel==$rowY[0]) $selected='selected="selected"'; else $selected='';
         $ibid.='<option value="'.$rowY[0].'" '.$selected.'>&nbsp;&nbsp;'.stripslashes($rowY[1]).' ('.Get_Name_Group("",$rowY[2]).')</option>';
      }
   }
   return ($ibid);
}

function PrintFormImgs() {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisFile, $ThisRedo;

   $qnum = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_cat"));
   if ($qnum == 0)
      redirect_url($ThisRedo);
   echo '
   <h3 class="my-3">'.gal_translate("Images").'</h3>
   <h4>'.gal_translate("Ajout images").'</h4>
   <hr />
   <form enctype="multipart/form-data" method="post" action="'.$ThisFile.'" name="FormImgs" lang="'.language_iso(1,'','').'">
      <input type="hidden" name="subop" value="addimgs">
      <div class="form-group">
         <label class="w-100" for="imggal">'.gal_translate("Affectation").'</label>
         <div class="">
            <select name="imggal" id="imggal" class="custom-select">';
   echo select_arbo('');
   echo '
            </select>
            <span class="help-block">'.gal_translate("Sélectionner une galerie").'</span>
         </div>
      </div>';

   $i=1;
   do {
      echo '
      <div class="form-group">
         <label class="">'.gal_translate("Image").' '.$i.'</label>
         <div class="input-group mb-2 mr-sm-2">
            <div class="input-group-prepend" onclick="reset2($(\'#newcard'.$i.'\'),'.$i.');">
               <div class="input-group-text"><i class="fa fa-refresh"></i></div>
            </div>
            <div class="custom-file">
               <input type="file" class="custom-file-input" name="newcard'.$i.'" id="newcard'.$i.'" />
               <label id="lab'.$i.'" class="custom-file-label" for="newcard'.$i.'">'.gal_translate("Sélectionner votre image").'</label>
            </div>
         </div>
      </div>
      <div class="form-group">
         <label class="sr-only" for="newdesc'.$i.'">'.gal_translate("Description").'</label>
         <input type="text" class="form-control" id="newdesc'.$i.'"  name="newdesc'.$i.'" placeholder="'.gal_translate("Description").'">
      </div>';
      $i++;
   }
   while($i<=5);
   echo '
      <div class="form-group">
         <button class="btn btn-outline-primary" type="submit">'.gal_translate("Ajouter").'</button>
      </div>
   </form>
   <script type="text/javascript">
      //<![CDATA[
         $(".custom-file-input").on("change",function(){
            $(this).next(".custom-file-label").addClass("selected").html($(this).val().split(\'\\\\\').pop());
         });
         window.reset2 = function (e,f) {
            e.wrap("<form>").closest("form").get(0).reset();
            e.unwrap();
            event.preventDefault();
            $("#lab"+f).html("'.gal_translate("Sélectionner votre image").'")
         };
      //]]>
   </script>';
}

/*******************************************************/
function AddImgs($imgscat,$newcard1,$newdesc1,$newcard2,$newdesc2,$newcard3,$newdesc3,$newcard4,$newdesc4,$newcard5,$newdesc5) {
   global $language, $MaxSizeImg, $MaxSizeThumb, $ModPath, $ModStart, $NPDS_Prefix;
   include_once("modules/upload/lang/upload.lang-$language.php");
   include_once("modules/upload/clsUpload.php");

   $year = date("Y"); $month = date("m"); $day = date("d");
   $hour = date("H"); $min = date("i"); $sec = date("s");

   $i=1;
   while($i <= 5) {
      $img = "newcard$i";
      $tit = "newdesc$i";
      if (!empty($$img)) {
         $newimg = stripslashes(removeHack($$img));
         if (!empty($$tit))
            $newtit = addslashes(removeHack($$tit));
         else
            $newtit = '';
         $upload = new Upload();
         $upload->maxupload_size=200000*100;
         $origin_filename = trim($upload->getFileName("newcard".$i));
         $filename_ext = strtolower(substr(strrchr($origin_filename, "."),1));

         if ( ($filename_ext=="jpg") or ($filename_ext=="gif") or ($filename_ext=="png") ) {
            $newfilename = $year.$month.$day.$hour.$min.$sec."-".$i.".".$filename_ext;
            if ($upload->saveAs($newfilename,"modules/$ModPath/imgs/", "newcard".$i,true)) {
               if ((function_exists('gd_info')) or extension_loaded('gd')) {
                  @CreateThumb($newfilename, "modules/$ModPath/imgs/", "modules/$ModPath/imgs/", $MaxSizeImg, $filename_ext);
                  @CreateThumb($newfilename, "modules/$ModPath/imgs/", "modules/$ModPath/mini/", $MaxSizeThumb, $filename_ext);
               }
                  echo '<ul class="list-group">';
               if (sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_img VALUES ('','$imgscat','$newfilename','$newtit','','0','0')")) {
                  echo '<li class="list-group-item list-group-item-success"><i class="fa fa-info-circle mr-2"></i>'.gal_translate("Image ajoutée avec succès").'</li>';
               } else {
                  echo '<li class="list-group-item list-group-item-danger"><i class="fa fa-info-circle mr-2"></i>'.gal_translate("Impossible d'ajouter l'image en BDD").'</li>';
                  @unlink ("modules/$ModPath/imgs/$newfilename");
                  @unlink ("modules/$ModPath/mini/$newfilename");
               }
            } else {
               echo '<li class="list-group-item list-group-item-danger"><i class="fa fa-info-circle mr-2"></i>'.$upload->errors.'</li>';
            }
         } else {
            if ($filename_ext!="")
               echo '<li class="list-group-item list-group-item-danger"><i class="fa fa-info-circle mr-2"></i>'.gal_translate("Ce fichier n'est pas un fichier jpg ou gif").'</li>';
         }
         echo '</ul>';
      }
      $i++;
   }
}

function PrintFormConfig() {
   global $ModPath, $ModStart, $ThisFile, $MaxSizeImg, $MaxSizeThumb, $imglign, $imgpage, $nbtopcomment, $nbtopvote, $view_alea, $view_last, $vote_anon, $comm_anon, $post_anon, $aff_vote, $aff_comm, $notif_admin;

   echo '
   <h3 class="mt-3"><i class="fa fa-cogs mr-2" aria-hidden="true"></i>'.gal_translate("Configuration").'</h3>
   <hr />
   <form id="formconfig" action="'.$ThisFile.'" method="post" name="FormConfig">
      <input type="hidden" name="subop" value="wrtconfig" />
      <fieldset disabled>
      <div class="form-group row">
         <label class="col-sm-8 col-form-label" for="maxszimg">'.gal_translate("Dimension maximale de l'image en pixels").'&nbsp;(1024px Max)</label>
         <div class="col-sm-4">
            <input type="text" class="form-control" id="maxszimg" name="maxszimg"  value="'.$MaxSizeImg.'" placeholder="" />
         </div>
      </div>
      <div class="form-group row">
         <label class="col-sm-8 col-form-label" for="maxszthb">'.gal_translate("Dimension maximale de la miniature en pixels").'&nbsp;(240px Max)</label>
         <div class="col-sm-4">
            <input type="text" class="form-control" id="maxszthb" name="maxszthb"  value="'.$MaxSizeThumb.'" placeholder="" />
         </div>
      </div>
      <div class="form-group row">
         <label class="col-sm-8 col-form-label" for="nbimlg">'.gal_translate("Nombre d'images par ligne").'</label>
         <div class="col-sm-4">
            <input type="text" class="form-control" id="nbimlg" name="nbimlg"  value="'.$imglign.'" placeholder="" />
         </div>
      </div>
      </fieldset>
      <div class="form-group row">
         <label class="col-sm-8 col-form-label" for="nbimpg">'.gal_translate("Nombre d'images par page").'</label>
         <div class="col-sm-4">
            <input type="text" class="form-control" id="nbimpg" name="nbimpg" value="'.$imgpage.'" placeholder="8" maxlength="2" required="required" />
            <span class="help-block text-right" id="countcar_nbimpg"></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-sm-8 col-form-label" for="nbimcomment">'.gal_translate("Nombre d'images à afficher dans le top commentaires").'</label>
         <div class="col-sm-4">
            <input type="text" class="form-control" id="nbimcomment" name="nbimcomment"  value="'.$nbtopcomment.'" placeholder="10" maxlength="2" required="required" />
            <span class="help-block text-right" id="countcar_nbimcomment"></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-sm-8 col-form-label" for="nbimvote">'.gal_translate("Nombre d'images à afficher dans le top votes").'</label>
         <div class="col-sm-4">
            <input type="text" class="form-control" id="nbimvote" name="nbimvote" value="'.$nbtopvote.'" placeholder="10" maxlength="2" required="required" />
            <span class="help-block text-right" id="countcar_nbimvote"></span>
         </div>
      </div>';

   if ($view_alea) { $rad1 = ' checked="checked"'; $rad2 = ''; } else { $rad1 = ''; $rad2 = ' checked="checked"'; }
   echo '
      <div class="form-group row">
         <label class="col-sm-8 col-form-label">'.gal_translate("Afficher des photos aléatoires ?").'</label>
         <div class="col-sm-4 my-2">
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="viewalea_y" name="viewalea" value="true"'.$rad1.' />
               <label class="custom-control-label" for="viewalea_y">'.adm_translate("Oui").'</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="viewalea_n" name="viewalea" value="false"'.$rad2.' />
               <label class="custom-control-label" for="viewalea_n">'.adm_translate("Non").'</label>
            </div>
         </div>
      </div>';
   if ($view_last) { $rad1 = ' checked="checked"'; $rad2 = ''; } else { $rad1 = ''; ' checked="checked"'; }
   echo '
      <div class="form-group row">
         <label class="col-sm-8 col-form-label">'.gal_translate("Afficher les derniers ajouts ?").'</label>
         <div class="col-sm-4 my-2">
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="viewlast_y" name="viewlast" value="true"'.$rad1.' />
               <label class="custom-control-label" for="viewlast_y">'.adm_translate("Oui").'</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="viewlast_n" name="viewlast" value="false"'.$rad2.' />
               <label class="custom-control-label" for="viewlast_n">'.adm_translate("Non").'</label>
            </div>
         </div>
      </div>';
   if ($aff_vote) { $rad1 = ' checked="checked"'; $rad2 = ''; } else { $rad1 = ''; $rad2 = ' checked="checked"'; }
   echo '
      <div class="form-group row">
         <label class="col-sm-8 col-form-label">'.gal_translate("Afficher les votes ?").'</label>
         <div class="col-sm-4 my-2">
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="votegal_y" name="votegal" value="true"'.$rad1.' />
               <label class="custom-control-label" for="votegal_y">'.adm_translate("Oui").'</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="votegal_n" name="votegal" value="false"'.$rad2.' />
               <label class="custom-control-label" for="votegal_n">'.adm_translate("Non").'</label>
            </div>
         </div>
      </div>';
   if ($aff_comm) { $rad1 = ' checked="checked"'; $rad2 = ''; } else { $rad1 = ''; $rad2 = ' checked="checked"'; }
   echo '
      <div class="form-group row">
         <label class="col-sm-8 col-form-label">'.gal_translate("Afficher les commentaires ?").'</label>
         <div class="col-sm-4 my-2">
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="commgal_y" name="commgal" value="true"'.$rad1.' />
               <label class="custom-control-label" for="commgal_y">'.adm_translate("Oui").'</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="commgal_n" name="commgal" value="false"'.$rad2.' />
               <label class="custom-control-label" for="commgal_n">'.adm_translate("Non").'</label>
            </div>
         </div>
      </div>';
   if ($vote_anon) { $rad1 = ' checked="checked"'; $rad2 = ''; } else { $rad1 = ''; $rad2 = ' checked="checked"'; }
   echo '
      <div class="form-group row">
         <label class="col-sm-8 col-form-label">'.gal_translate("Les anonymes peuvent voter ?").'</label>
         <div class="col-sm-4 my-2">
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="votano_y" name="votano" value="true"'.$rad1.' />
               <label class="custom-control-label" for="votano_y">'.adm_translate("Oui").'</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="votano_n" name="votano" value="false"'.$rad2.' />
               <label class="custom-control-label" for="votano_n">'.adm_translate("Non").'</label>
            </div>
         </div>
      </div>';
   if ($comm_anon) { $rad1 = ' checked="checked"'; $rad2 = ''; } else { $rad1 = ''; $rad2 = ' checked="checked"'; }
   echo '
      <div class="form-group row">
         <label class="col-sm-8 col-form-label">'.gal_translate("Les anonymes peuvent poster un commentaire ?").'</label>
         <div class="col-sm-4 my-2">
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="comano_y" name="comano" value="true"'.$rad1.' />
               <label class="custom-control-label" for="comano_y">'.adm_translate("Oui").'</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="comano_n" name="comano" value="false"'.$rad2.'>
               <label class="custom-control-label" for="comano_n">'.adm_translate("Non").'</label>
            </div>
         </div>
      </div>';
   if ($post_anon) { $rad1 = ' checked="checked"'; $rad2 = ''; } else { $rad1 = ''; $rad2 = ' checked="checked"'; }
   echo '
      <div class="form-group row">
         <label class="col-sm-8 col-form-label">'.gal_translate("Les anonymes peuvent envoyer des E-Cartes ?").'</label>
         <div class="col-sm-4 my-2">
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="postano_y" name="postano" value="true"'.$rad1.' />
               <label class="custom-control-label" for="postano_y">'.adm_translate("Oui").'</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="postano_n" name="postano" value="false"'.$rad2.' />
               <label class="custom-control-label" for="postano_n">'.adm_translate("Non").'</label>
            </div>
         </div>
      </div>';
   if ($notif_admin) { $rad1 = ' checked="checked"'; $rad2 = ''; } else { $rad1 = ''; $rad2 = ' checked="checked"'; }
   echo '
      <div class="form-group row">
         <label class="col-sm-8 col-form-label">'.gal_translate("Notifier par email l'administrateur de la proposition de photos ?").'</label>
         <div class="col-sm-4 my-2">
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="notifadmin_y" name="notifadmin" value="true"'.$rad1.' />
               <label class="custom-control-label" for="notifadmin_y">'.adm_translate("Oui").'</span>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="notifadmin_n" name="notifadmin" value="false"'.$rad2.' />
               <label class="custom-control-label" for="notifadmin_n">'.adm_translate("Non").'</span>
            </div>
         </div>
      </div>
      <button class="btn btn-primary" type="submit">'.gal_translate("Valider").'</button>
      </form>';
   $fv_parametres = '
      nbimpg: {
         validators: {
            regexp: {
               regexp:/^\d{1,2}$/,
               message: "0-9"
            },
            between: {
               min: 1,
               max: 50,
               message: "1 ... 50"
            }
         }
      },
      nbimvote: {
         validators: {
            regexp: {
               regexp:/^\d{1,2}$/,
               message: "0-9"
            },
            between: {
               min: 1,
               max: 50,
               message: "1 ... 50"
            }
         }
      },
      nbimcomment: {
         validators: {
            regexp: {
               regexp:/^\d{1,2}$/,
               message: "0-9"
            },
            between: {
               min: 1,
               max: 50,
               message: "1 ... 50"
            }
         }
      },';
   $arg1='
   var formulid = ["formconfig"];
   inpandfieldlen("nbimpg",2);
   inpandfieldlen("nbimvote",2);
   inpandfieldlen("nbimcomment",2);';
   adminfoot('fv',$fv_parametres,$arg1,'no');
}

function WriteConfig($maxszimg,$maxszthb,$nbimlg,$nbimpg,$nbimcomment,$nbimvote,$viewalea,$viewlast,$vote,$comm,$votano,$comano,$postano,$notifadmin) {
   global $ModPath, $ModStart, $ThisRedo;

   if (!is_integer($maxszimg) && ($maxszimg > 1024)) {
      $msg_erreur = gal_translate("Dimension maximale de l'image incorrecte");
      $erreur=true;
   }
   
   if (!is_integer($maxszthb) && ($maxszthb > 300) && !isset($erreur)) {
      $msg_erreur = gal_translate("Dimension maximale de la miniature incorrecte");
      $erreur=true;
   }

   if (isset($erreur)) {
      echo '<p class="lead text-danger">'.$msg_erreur.'</p>';
      exit;
   }
   
   //if ($nbimpg < $nbimlg) $nbimpg = $nbimlg;//
   
   
   $filename = "modules/".$ModPath."/gal_conf.php";
   $content = "<?php\n";
   $content.= "/************************************************************************/\n";
   $content.= "/* DUNE by NPDS                                                         */\n";
   $content.= "/*                                                                      */\n";
   $content.= "/* NPDS Copyright (c) 2002-".date('Y')." by Philippe Brunier                     */\n";
   $content.= "/*                                                                      */\n";
   $content.= "/* This program is free software. You can redistribute it and/or modify */\n";
   $content.= "/* it under the terms of the GNU General Public License as published by */\n";
   $content.= "/* the Free Software Foundation; either version 2 of the License.       */\n";
   $content.= "/* Module de gestion de galeries pour NPDS                              */\n";
   $content.= "/*                                                                      */\n";
   $content.= "/* (c) 2004-2005 Tribal-Dolphin - http://www.tribal-dolphin.net         */\n";
   $content.= "/* (c) 2007 Xgonin, Lopez - http://modules.npds.org                     */\n";
   $content.= "/* MAJ conformité XHTML pour REvolution 10.02 par jpb/phr en mars 2010  */\n";
   $content.= "/* MAJ Dev - 2011                                                       */\n";
   $content.= "/* MAJ jpb, phr - 2017 renommé npds_galerie pour Rev 16                 */\n";
   $content.= "/* v 3.0                                                                */\n";
   $content.= "/************************************************************************/\n\n";
   $content.= "// Dimension max des images\n";
//   $content.= "\$MaxSizeImg = ".$maxszimg.";\n\n";
   $content.= "\$MaxSizeImg = 1000;\n\n";
   $content.= "// Dimension max des images miniatures\n";
//   $content.= "\$MaxSizeThumb = ".$maxszthb.";\n\n";
   $content.= "\$MaxSizeThumb = 300;\n\n";
   $content.= "// Nombre d'images par ligne\n";
//   $content.= "\$imglign = ".$nbimlg.";\n\n";
   $content.= "\$imglign = 4;\n\n";
   $content.= "// Nombre de photos par page\n";
   $content.= "\$imgpage = ".$nbimpg.";\n\n";
   $content.= "// Nombre d'images à afficher dans le top commentaires\n";
   if (!$nbimcomment) $nbimcomment=5;
   $content.= "\$nbtopcomment = ".$nbimcomment.";\n\n";
   $content.= "// Nombre d'images à afficher dans le top votes\n";
   if (!$nbimvote) $nbimvote=5;
   $content.= "\$nbtopvote = ".$nbimvote.";\n\n";
   $content.= "// Personnalisation de l'affichage\n";
   $content.= "\$view_alea = ".$viewalea.";\n";
   $content.= "\$view_last = ".$viewlast.";\n";
   $content.= "\$aff_vote = ".$vote.";\n";
   $content.= "\$aff_comm = ".$comm.";\n\n";
   $content.= "// Autorisations pour les anonymes\n";
   $content.= "\$vote_anon = ".$votano.";\n";
   $content.= "\$comm_anon = ".$comano.";\n";
   $content.= "\$post_anon = ".$postano.";\n\n";
   $content.= "// Notification admin par email de la proposition\n";
   $content.= "\$notif_admin = ".$notifadmin.";\n\n";
   $content.= "// Version du module\n";
   $content.= "\$npds_gal_version = \"V 3.0\";\n";
   $content.= "?>";
     
   if ($myfile = fopen("$filename", "wb")) {
      fwrite($myfile, "$content");
      fclose($myfile);
      unset($content);
      redirect_url($ThisRedo);
   } else
      redirect_url($ThisRedo."&subop=config");
}

function PrintArbo() {
   global $ModPath, $ModStart, $ThisFile, $NPDS_Prefix;
   echo '
   <script type="text/javascript">
   //<![CDATA[
      function aff_image(img_id, img_src) {
         var image_open = new Image();
         image_open.src = img_src;
         var image_closed = new Image();
         image_closed.src = "modules/'.$ModPath.'/data/img.png";
         if (document.all) {
            if (document.all[img_id].src == image_closed.src) {
               document.all[img_id].src = image_open.src;
            } else {
               document.all[img_id].src = image_closed.src;
            }
        } else {
           if (document.getElementById(img_id).src == image_closed.src) {
              document.getElementById(img_id).src = image_open.src;
           } else {
               document.getElementById(img_id).src = image_closed.src;
            }
         }
      }
      $(document).ready(function(){ 
         $("#ckball").change(function(){
            $(".ckgt").prop("checked", $(this).prop("checked"));
         });
      });
   //]]>
   </script>';

   $queryZ = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='-1' ORDER BY id");
   $nb_img = sql_num_rows($queryZ);
   $j=0; $affgaltemp='';
   if ($nb_img == 0)
      $affgaltemp.= '<p class="card-text"><i class="fa fa-info-circle mr-2"></i>'.gal_translate("Vide").'</p>';
   else {
      $affgaltemp.= '
      <label class="custom-check custom-checkbox">
         <span class="custom-check-description"></span>
         <input type="checkbox" class="custom-check-input" id="ckball" />
         <span class="custom-check-indicator bg-danger"></span>
      </label>
      <div class="row px-3">';
      while ($rowZ_img = sql_fetch_row($queryZ)) {
         if ($rowZ_img[6]==1)  {$cla=' alert-danger '; $j++;} else $cla='alert-secondary';
         $affgaltemp.= '
            <div class="col-lg-3 col-sm-4 border rounded py-2 my-2 '.$cla.'">
               <label class="custom-check custom-checkbox">
                  <span class="custom-check-description"></span>
                  <input form="delbatch-1" type="checkbox" class="custom-check-input ckgt" name="imgids[]" value="'.$rowZ_img[0].'" />
                  <span class="custom-check-indicator bg-danger"></span>
               </label>
               <div class="text-center">
                  <a href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal&amp;op=one-img&amp;galid=-1&amp;pos='.$rowZ_img[0].'" target="_blank"><img class="img-fluid img-thumbnail mb-1" src="modules/'.$ModPath.'/mini/'.$rowZ_img[2].'" alt="'.$rowZ_img[3].'" data-toggle="tooltip" data-placement="top"  title="'.$rowZ_img[3].'" /></a><br />
                  <small>ID : '.$rowZ_img[2].'</small>
               </div>
               <div class="mt-2">
                  '.stripslashes($rowZ_img[3]).'
               </div>
               <div class="text-center mt-3">';
         if ($rowZ_img[6]==1)
            $affgaltemp.= '
                  <a class="btn btn-sm" href="'.$ThisFile.'&amp;subop=validimg&amp;imgid='.$rowZ_img[0].'"><i class="fa fa-check fa-2x align-middle" title="Valider" data-toggle="tooltip"></i></a>';
         else
            $affgaltemp.= '
                  <a class="btn btn-sm" href="'.$ThisFile.'&amp;subop=editimg&amp;imgid='.$rowZ_img[0].'"><i class="fa fa-edit fa-2x align-middle" title="Editer" data-toggle="tooltip"></i></a>';
         $affgaltemp.= '
                  <a class="btn btn-sm" href="'.$ThisFile.'&amp;subop=delimg&amp;imgid='.$rowZ_img[0].'"><i class="fa fa-trash-o fa-2x text-danger" title="Effacer" data-toggle="tooltip"></i></a>
               </div>
            </div>';
     }
     $affgaltemp.= '
         </div>';
   }
   echo '
   <div class="card mb-3">
      <div class="card-body">
         <h5>
            <a data-toggle="collapse" href="#gt" aria-expanded="false" aria-checks="gt">
            <i class="toggle-icon fa fa-caret-down fa-lg mr-2" data-toggle="tooltip" data-placement="top" title="'.gal_translate("Cliquer pour déplier").'"></i></a>Galerie temporaire
            <span class="float-right"><span class="badge badge-danger badge-pill" title="'.gal_translate("Nombre d'images à valider").'" data-toggle="tooltip" data-placement="left">'.$j.'</span>
            <span class="badge badge-success badge-pill ml-2" title="'.gal_translate("Nombre d'images").'" data-toggle="tooltip" data-placement="left">'.$nb_img.'</span></span>
         </h5>
      </div>
      <div class="card-body collapse" id="gt">
         '.$affgaltemp.'
         <form action="'.$ThisFile.'&amp;subop=delimgbatch" method="post" id="delbatch-1">
            <button class="btn btn-outline-danger form-check btn-sm mt-2" type="submit"><i class="fa fa-check-square fa-lg mr-1"></i>'.gal_translate("Effacer").'</button>
         </form>
      </div>
   </div>';
   // <== galerie temporaire

   $sql_cat = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ORDER BY nom ASC");
   $num_cat = sql_num_rows($sql_cat);
   if ($num_cat == 0)
      echo '<div class="alert alert-danger lead">'.gal_translate("Aucune catégorie trouvée").'</div>';
   else {

/*
      $sql_sscat = "SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid!=0";
      $num_sscat = sql_num_rows(sql_query($sql_sscat));

      $sql_gal = "SELECT * FROM ".$NPDS_Prefix."tdgal_gal";
      $num_gal = sql_num_rows(sql_query($sql_gal));
*/

      // CATEGORIE
      while ($row_cat = sql_fetch_row($sql_cat)) {
         $queryX = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         $nb_galparcat = sql_num_rows($queryX);
         $queryS = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         $nb_souscat = sql_num_rows($queryS);
         $affcatgal=''; $affsoucat='';
         $nbto_imgpargal=0;
         while ($rowX_gal = sql_fetch_row($queryX)) {
            $queryZ = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$rowX_gal[0]."' ORDER BY ordre,id,noaff");
            $nb_imgpargal = sql_num_rows($queryZ);
            $nbto_imgpargal += $nb_imgpargal;//
            $affcatgal .= '
            <hr class="mt-0" />
            <h5 class="mx-3 mb-3 lead">';
            if($nb_imgpargal>0)
               $affcatgal .= '
               <a class="ml-3" data-toggle="collapse" href="#galcat'.$rowX_gal[0].'" aria-expanded="false" aria-checks="galcat'.$rowX_gal[0].'">
               <i class="toggle-icon fa fa-caret-down fa-lg mr-2" data-toggle="tooltip" data-placement="top" title="'.gal_translate("Cliquer pour déplier").'"></i></a>';
            else
               $affcatgal .= '
               <a class="ml-2"><i class=" mr-2 "></i></a>';
            $affcatgal .= stripslashes($rowX_gal[2]).' <small>( '.gal_translate("Galerie").' )</small>
               <span class="badge badge-success badge-pill ml-2" title="'.gal_translate("Nombre d'images").'" data-toggle="tooltip" data-placement="right">'.$nb_imgpargal.'</span>
               <span class="float-right mr-3"><a href="'.$ThisFile.'&amp;subop=editgal&amp;galid='.$rowX_gal[0].'"><i class="fa fa-edit align-middle" title="'.gal_translate("Editer").' '.gal_translate("Galerie").'" data-toggle="tooltip"></i></a><a class="" href="'.$ThisFile.'&amp;subop=delgal&amp;galid='.$rowX_gal[0].'"><i class="fa fa-trash-o text-danger ml-3" title="'.gal_translate("Effacer").' '.gal_translate("Galerie").'" data-toggle="tooltip"></i></a></span>
            </h5>
            <div class="card-body collapse" id="galcat'.$rowX_gal[0].'">
               <form action="'.$ThisFile.'&amp;subop=ordre" method="post" name="FormArbo'.$rowX_gal[0].'">
                  <input type="hidden" name="subop" value="ordre" />';
            $i=1;
            $affcatgal .= '
                  <div class="row px-3">';
            $affcatgalimg='';
            while ($rowZ_img = sql_fetch_row($queryZ)) {
               if ($rowZ_img[6]==1) $cla=' alert-danger '; else $cla='alert-secondary';
               $affcatgalimg .= '
                  <div class="col-md-3 col-sm-4 border rounded py-2 my-2 '.$cla.'">
                     <label class="custom-check custom-checkbox">
                        <span class="custom-check-description"></span>
                        <input form="delbatch'.$rowX_gal[0].'" type="checkbox" class="custom-check-input" name="imgids[]" value="'.$rowZ_img[0].'" />
                        <span class="custom-check-indicator bg-danger"></span>
                     </label>';
               if ($rowZ_img[6]==1)
                  $affcatgalimg .= '
                     <div class="text-center form-group">
                        <a href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal&amp;op=one-img&amp;galid='.$rowX_gal[0].'&amp;pos='.$rowZ_img[0].'" target="_blank"><img class="img-fluid mb-1" src="modules/'.$ModPath.'/mini/'.$rowZ_img[2].'"  alt="mini/'.$rowZ_img[2].'" data-toggle="tooltip" data-placement="top"  title="mini/'.$rowZ_img[2].'" /></a>
                     </div>';
               else
                  $affcatgalimg .= '
                     <div class="text-center form-group">
                        <a href="javascript: void(0);" onMouseDown="aff_image(\'image'.$rowX_gal[0].'_'.$i.'\',\'modules/'.$ModPath.'/mini/'.$rowZ_img[2].'\');">
                           <img class="img-fluid mb-1" src="modules/'.$ModPath.'/data/img.png" id="image'.$rowX_gal[0].'_'.$i.'" alt="mini/'.$rowZ_img[2].'" data-toggle="tooltip" data-placement="right" title="mini/'.$rowZ_img[2].'" />
                        </a>
                     </div>';
               $affcatgalimg .= '
                     <div class="form-group">
                        <textarea class="form-control" name="desc['.$i.']" rows="2" >'.stripslashes($rowZ_img[3]).'</textarea>
                     </div>
                     <div class="form-group">
                        <input class="form-control" type="number" name="ordre['.$i.']" value="'.$rowZ_img[5].'" min="0" />
                     </div>
                     <input type="hidden" name="img_id['.$i.']" value="'.$rowZ_img[0].'" />
                     <div class="d-flex justify-content-center">';

               if ($rowZ_img[6]==1)
                  $affcatgalimg .= '
                        <a class="btn btn-sm" href="'.$ThisFile.'&amp;subop=validimg&amp;imgid='.$rowZ_img[0].'"><i class="fa fa-check fa-2x align-middle" title="Valider" data-toggle="tooltip"></i></a>';
               else
                  $affcatgalimg .= '
                        <a class="btn btn-sm" href="'.$ThisFile.'&amp;subop=editimg&amp;imgid='.$rowZ_img[0].'"><i class="fa fa-edit fa-2x align-middle" title="Editer" data-toggle="tooltip"></i></a>';
               $affcatgalimg .= '
                        <a class="btn btn-sm" href="'.$ThisFile.'&amp;subop=delimg&amp;imgid='.$rowZ_img[0].'"><i class="fa fa-trash-o fa-2x text-danger" title="Effacer" data-toggle="tooltip"></i></a>';
               $i++;
               $affcatgalimg .= '
                     </div>
                  </div>';
            }
            $affcatgal .= $affcatgalimg;
            if ($i!=1)
               $affcatgal .= '
                  <div class="form-group w-100">
                     <button class="btn btn-outline-primary form-check btn-sm mt-2" type="submit">'.gal_translate("Valider").'</button>
                  </div>';
            $affcatgal .= '
                  </div>
               </form>';
            if ($i!=1) 
               $affcatgal .= '
               <form action="'.$ThisFile.'&amp;subop=delimgbatch" method="post" id="delbatch'.$rowX_gal[0].'">
                  <button class="btn btn-outline-danger form-check btn-sm mt-2" type="submit"><i class="fa fa-check-square fa-lg mr-1"></i>'.gal_translate("Effacer").'</button>
               </form>';
            $affcatgal .= '
            </div>';
         }
        // SOUS-CATEGORIE
        $nb_to_galparsouscat=0; $nbto_imgpargalscat=0;
         while ($row_sscat = sql_fetch_row($queryS)) {
            $querx = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_sscat[0]."' ORDER BY nom ASC");
            $nb_galparsouscat = sql_num_rows($querx);
            $affsoucatgal='';
            while ($row_gal = sql_fetch_row($querx)) {
               $querz = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$row_gal[0]."' ORDER BY ordre,id,noaff");
               $nb_imgpargalscat = sql_num_rows($querz);
               $nbto_imgpargalscat += $nb_imgpargalscat;
               $affsoucatgal .= '
               <div class="mx-3">
                  <h5 class="ml-3 lead">
                     <a class="" data-toggle="collapse" href="#galscat'.$row_gal[0].'" aria-expanded="false" aria-checks="galscat'.$row_sscat[0].'">
                     <i class="toggle-icon fa fa-caret-down fa-lg mr-2" data-toggle="tooltip" data-placement="top" title="'.gal_translate("Cliquer pour déplier").'"></i></a>'.stripslashes($row_gal[2]).' <small>( '.gal_translate("Galerie").' )</small>
                     <span class="badge badge-success badge-pill ml-2" title="'.gal_translate("Nombre d'images").'" data-toggle="tooltip" data-placement="right">'.$nb_imgpargalscat.'</span>
                     <span class="float-right mr-3"><a class="" href="'.$ThisFile.'&amp;subop=editgal&amp;galid='.$row_gal[0].'"><i class="fa fa-edit" title="'.gal_translate("Editer").' '.gal_translate("Galerie").'" data-toggle="tooltip"></i></a><a class="" href="'.$ThisFile.'&amp;subop=delgal&amp;galid='.$row_gal[0].'"><i class="fa fa-trash-o text-danger ml-3" title="'.gal_translate("Effacer").' '.gal_translate("Galerie").'" data-toggle="tooltip"></i></a></span>
                  </h5>
               </div>
               <div class="card-body collapse" id="galscat'.$row_gal[0].'">
                  <form action="'.$ThisFile.'&amp;subop=ordre" method="post" name="FormArbo'.$row_gal[0].'">
                     <input type="hidden" name="subop" value="ordre" />';
               $i=1;
               $affsoucatgal .= '
                     <div class="row px-3">';
               $affsoucatgalimg='';
               while($row_img = sql_fetch_row($querz)) {
                  $affsoucatgalimg .= '
                        <div class="col-md-3 col-sm-4 border rounded py-2 my-2 '.$cla.'">';
                  $affsoucatgalimg .= '
                           <label class="custom-check custom-checkbox">
                              <span class="custom-check-description"></span>
                              <input form="delbatch'.$row_gal[0].'" type="checkbox" class="custom-check-input" name="imgids[]" value="'.$row_img[0].'" />
                              <span class="custom-check-indicator bg-danger"></span>
                           </label>';
                  if ($row_img[6]==1)
                     $affsoucatgalimg .= '
                           <div class="text-center form-group">
                              <a href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal&amp;op=one-img&amp;galid='.$row_gal[0].'&amp;pos='.$row_img[0].'" target="_blank"><img class="img-fluid mb-1" src="modules/'.$ModPath.'/mini/'.$row_img[2].'" alt="mini/'.$row_img[2].'" data-toggle="tooltip" data-placement="top"  title="mini/'.$row_img[2].'" /></a>
                           </div>';
                  else
                     $affsoucatgalimg .= '
                           <div class="text-center form-group">
                              <a href="javascript: void(0);" onMouseDown="aff_image(\'image'.$row_gal[0].'_'.$i.'\',\'modules/'.$ModPath.'/mini/'.$row_img[2].'\');">
                                 <img class="img-fluid mb-1" src="modules/'.$ModPath.'/data/img.png" id="image'.$row_gal[0].'_'.$i.'" alt="mini/'.$row_img[2].'" data-toggle="tooltip" data-placement="right" title="mini/'.$row_img[2].'" />
                              </a>
                           </div>';
                  $affsoucatgalimg .= '
                           <div class="form-group">
                              <textarea class="form-control" name="desc['.$i.']" rows="2" >'.stripslashes($row_img[3]).'</textarea>
                           </div>
                           <div class="form-group">
                              <input class="form-control" type="number" name="ordre['.$i.']" value="'.$row_img[5].'" min="0" />
                           </div>
                           <input type="hidden" name="img_id['.$i.']" value="'.$row_img[0].'" />
                           <div class="text-center">';
                  $i++;
                  if ($row_img[6]==1)
                     $affsoucatgalimg .= '
                              <span><a href="'.$ThisFile.'&amp;subop=validimg&amp;imgid='.$row_img[0].'">Valider</a>';
                  else
                     $affsoucatgalimg .= '<span><a class="btn btn-sm" href="'.$ThisFile.'&amp;subop=editimg&amp;imgid='.$row_img[0].'"><i class="fa fa-edit fa-2x align-middle" title="Editer" data-toggle="tooltip"></i></a>';
                  $affsoucatgalimg .= '<a class="btn btn-sm" href="'.$ThisFile.'&amp;subop=delimg&amp;imgid='.$row_img[0].'"><i class="fa fa-trash-o fa-2x text-danger" title="Effacer" data-toggle="tooltip"></i></a></span>
                           </div>
                        </div>';
               }
               $affsoucatgal .= $affsoucatgalimg;

               if ($i!=1)
                  $affsoucatgal .='
                     <div class="form-group w-100">
                        <button class="btn btn-outline-primary form-check btn-sm mt-2" type="submit">'.gal_translate("Valider").'</button>
                     </div>';
               $affsoucatgal .='
                     </div>
                  </form>
                  <form action="'.$ThisFile.'&amp;subop=delimgbatch" method="post" id="delbatch'.$row_gal[0].'">
                     <button class="btn btn-outline-danger form-check btn-sm mt-2" type="submit"><i class="fa fa-check-square fa-lg mr-1"></i>'.gal_translate("Effacer").'</button>
                  </form>
               </div>';
            }
            $affsoucat .= '
            <hr class="mt-0" />
               <h5 class="mx-3 mb-3">';
            if($nb_galparsouscat>0)
               $affsoucat .='
                  <a class="ml-3" data-toggle="collapse" href="#scat'.$row_sscat[0].'" aria-expanded="false" aria-checks="scat'.$row_sscat[0].'"><i class="toggle-icon fa fa-caret-down fa-lg mr-2" data-toggle="tooltip" data-placement="top" title="'.gal_translate("Cliquer pour déplier").'"></i></a>';
            else
               $affsoucat .= '
                  <a class="ml-2"><i class=" mr-2 "></i></a>';
            $affsoucat .= stripslashes($row_sscat[2]).' <small>( '.gal_translate("Sous-catégorie").' )</small>
                  <span class="badge badge-secondary badge-pill ml-2" title="'.gal_translate("Nombre de galeries").'" data-toggle="tooltip" data-placement="right">'.$nb_galparsouscat.'</span>
                  <span class="badge badge-success badge-pill ml-2" title="'.gal_translate("Nombre d'images").'" data-toggle="tooltip" data-placement="right">'.$nbto_imgpargalscat.'</span>

                  <span class="float-right mr-3"><a href="'.$ThisFile.'&amp;subop=editcat&amp;catid='.$row_sscat[0].'"><i class="fa fa-edit" title="'.gal_translate("Editer").' '.gal_translate("Sous-catégorie").'" data-toggle="tooltip"></i></a><a class="" href="'.$ThisFile.'&amp;subop=delsscat&amp;sscatid='.$row_sscat[0].'"><i class="fa fa-trash-o text-danger ml-3" data-original-title="'.gal_translate("Effacer").' '.gal_translate("Sous-catégorie").'" data-toggle="tooltip"></i></a></span>
               </h5>
            <div class="collapse" id="scat'.$row_sscat[0].'">';
           // SOUS-CATEGORIE

            $affsoucat .= $affsoucatgal;
            $affsoucat .= '
            </div>';
            $nb_to_galparsouscat += $nb_galparsouscat;
         }
         echo '
   <div class="card mb-3">
      <div class="p-3 bg-light">
         <h5>';
         if($nb_souscat > 0 or $nb_galparcat > 0)
            echo '
            <a data-toggle="collapse" href="#cat'.$row_cat[0].'" aria-expanded="false" aria-checks="cat'.$row_cat[0].'"><i class="toggle-icon fa fa-caret-down fa-lg mr-2" data-toggle="tooltip" data-placement="top" title="'.gal_translate("Cliquer pour déplier").'"></i></a>';
            
            echo stripslashes($row_cat[2]).' <small>( '.gal_translate("Catégorie").' )</small>
            <span class="badge badge-dark badge-pill ml-2" title="'.gal_translate("Nombre de sous-catégories").'" data-toggle="tooltip" data-placement="right">'.$nb_souscat.'</span>
            <span class="badge badge-secondary badge-pill ml-2" title="'.gal_translate("Nombre de galeries").'" data-toggle="tooltip" data-placement="right">'.($nb_galparcat+$nb_to_galparsouscat).'</span>
            <span class="badge badge-success badge-pill ml-2" title="'.gal_translate("Nombre d'images").'" data-toggle="tooltip" data-placement="right">'.($nbto_imgpargal+$nbto_imgpargalscat).'</span>
            <span class="float-right"><a href="'.$ThisFile.'&amp;subop=editcat&amp;catid='.$row_cat[0].'"><i class="fa fa-edit align-middle" title="'.gal_translate("Editer").' '.gal_translate("Catégorie").'" data-toggle="tooltip"></i></a><a href="'.$ThisFile.'&amp;subop=delcat&amp;catid='.$row_cat[0].'"><i class="fa fa-trash-o text-danger  ml-3" title="'.gal_translate("Effacer").' '.gal_translate("Catégorie").'" data-toggle="tooltip"></i></a></span>
         </h5>
      </div>
      <div class="collapse " id="cat'.$row_cat[0].'">
      '.$affcatgal.$affsoucat.'
      </div>
   </div>';
      }
   }
}

function DelCat($id,$go) {
   global $ThisFile, $NPDS_Prefix, $ModPath;
   if (empty($go)) {
      $q_cat = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'");
      $r_cat = sql_fetch_row($q_cat);
      echo '
      <div class="alert alert-danger lead">'.gal_translate("Vous allez supprimer la catégorie").' : '.$r_cat[0].'</div>
      <a href="'.$ThisFile.'&amp;subop=delcat&amp;catid='.$id.'&amp;go=true" class=" btn btn-outline-danger btn-sm">'.gal_translate("Confirmer").'</a> <a class="btn btn-outline-secondary btn-sm" href="'.$ThisFile.'">'.gal_translate("Annuler").'</a>';
   } else {
      $q_cat = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'");
      $r_cat = sql_fetch_row($q_cat);
      $q_sscat = sql_query("SELECT nom,id FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='$id'");
      echo '
      <h5 class="font-weight-bold">'.gal_translate("Catégorie").' '.$r_cat[0].'</h5>';
      // Il peut ne pas y avoir de sous-catégories
      $r_sscat = sql_fetch_row($q_sscat);
      do {
         echo ''.$r_sscat[0].'';
         $q_gal = sql_query("SELECT nom,id,cid FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='$r_sscat[1]' OR cid='$id'");
         while ($r_gal = sql_fetch_row($q_gal)) {
            if ($r_gal[2]==$r_sscat[1])
               $remp='';
            else
               $remp='';
            echo ''.$remp.''.$r_gal[0].'';
            $q_img = sql_query("SELECT name,id FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='$r_gal[1]'");
            while ($r_img = sql_fetch_row($q_img)) {
               $m_img = "modules/$ModPath/mini/$r_img[0]";
               $g_img = "modules/$ModPath/imgs/$r_img[0]";
               echo '<ul class="list-group">';
               echo '<li class="list-group-item">'.$r_img[0].'</li>';
               if (@unlink($m_img))
                  echo '<li class="list-group-item list-group-item-success">'.gal_translate("Miniature supprimée").'</li>';
               else
                  echo '<li class="list-group-item list-group-item-danger">'.gal_translate("Miniature non supprimée").'</li>';
               if (@unlink($g_img))
                  echo '<li class="list-group-item list-group-item-success">'.gal_translate("Image supprimée").'</li>';
               else
                  echo '<li class="list-group-item list-group-item-danger">'.gal_translate("Image non supprimée").'</li>';
               if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='$r_img[1]'"))
                  echo '<li class="list-group-item list-group-item-success">'.gal_translate("Votes supprimés").'</li>';
               else
                  echo '<li class="list-group-item list-group-item-danger">'.gal_translate("Votes non supprimés").'</li>';
               if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$r_img[1]'"))
                  echo '<li class="list-group-item list-group-item-success">'.gal_translate("Commentaires supprimés").'</li>';
               else
                  echo '<li class="list-group-item list-group-item-danger">'.gal_translate("Commentaires non supprimés").'</li>';
               if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_img WHERE id='$r_img[1]'"))
                  echo '<li class="list-group-item list-group-item-success">'.gal_translate("Enregistrement supprimé").'</li>';
               else
                  echo '<li class="list-group-item list-group-item-danger">'.gal_translate("Enregistrement non supprimé").'</li>';
            } // Fin du while img
            if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$r_gal[1]'")) {
               echo '<li class="list-group-item list-group-item-success">'.$remp.'&nbsp;&nbsp;&nbsp; '.gal_translate("Galerie supprimée").'</li>';
            } else {
               echo '<li class="list-group-item list-group-item-danger">'.$remp.' '.gal_translate("Galerie non supprimée").'</li>';
            }
         } // Fin du while galerie
         if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='$id'")) {
            echo '<li class="list-group-item list-group-item-success">'.gal_translate("Sous-catégorie supprimée").'</li>';
         } else {
            echo '<li class="list-group-item list-group-item-danger">'.gal_translate("Sous-catégorie non supprimée").'</li>';
         }
      } while ($r_sscat = sql_fetch_row($q_sscat));
       // SousCat
      if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'")) {
         echo '<li class="list-group-item list-group-item-success">'.gal_translate("Catégorie supprimée").'</li>';
      } else {
         echo '<li class="list-group-item list-group-item-danger">'.gal_translate("Catégorie non supprimée").'</li>';
      }
      echo '</ul>';
   }
}

function DelSsCat($id,$go) {
   global $ThisFile, $NPDS_Prefix, $ModPath;
   if (empty($go)) {
      $q_sscat = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'");
      $r_sscat = sql_fetch_row($q_sscat);
      echo '
      <div class="alert alert-danger lead">'.gal_translate("Vous allez supprimer la sous-catégorie").' : '.$r_sscat[0].'</div>
      <a class="btn btn-outline-danger btn-sm mr-2" href="'.$ThisFile.'&amp;subop=delsscat&amp;sscatid='.$id.'&amp;go=true">
      '.gal_translate("Confirmer").'</a><a class="btn btn-outline-secondary btn-sm" href="'.$ThisFile.'">'.gal_translate("Annuler").'</a>'; 
   } else {
      $q_sscat = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'");
      $r_sscat = sql_fetch_row($q_sscat);
      $q_gal = sql_query("SELECT nom,id FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='$id'");

      echo "<table class=\"table\" width=\"100%\" cellspacing=\"2\" cellpadding=\"0\" border=\"0\">";
      echo "<tr><td colspan=\"2\" class=\"header\"><strong>&nbsp;".$r_sscat[0]."</strong></td></tr>";
      while ($r_gal = sql_fetch_row($q_gal)) {
         
         echo "<tr><td colspan=\"2\">&nbsp;&nbsp;&nbsp;".$r_gal[0]."</td></tr>";
         $q_img = sql_query("SELECT name,id FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='$r_gal[1]'");
         while ($r_img = sql_fetch_row($q_img)) {
            $m_img = "modules/$ModPath/mini/$r_img[0]";
            $g_img = "modules/$ModPath/imgs/$r_img[0]";
            
            echo "<tr><td colspan=\"2\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$r_img[0]."</td></tr>";
            if (@unlink($m_img)) {
               echo "<tr><td width=\"40%\"></td><td>".gal_translate("Miniature supprimée")."</td></tr>";
            } else {
               echo "<tr><td width=\"40%\"></td><td class=\"text-danger\">".gal_translate("Miniature non supprimée")."</td></tr>";
            }
            if (@unlink($g_img)) {
               echo "<tr><td></td><td>".gal_translate("Image supprimée")."</td></tr>";
            } else {
               echo "<tr><td></td><td class=\"text-danger\">".gal_translate("Image non supprimée")."</td></tr>";
            }
            if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='$r_img[1]'")) {
               echo "<tr><td></td><td>".gal_translate("Votes supprimés")."</td></tr>";
            } else {
               echo "<tr><td></td><td class=\"text-danger\">".gal_translate("Votes non supprimés")."</td></tr>";
            }
            if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$r_img[1]'")) {
               echo "<tr><td></td><td>".gal_translate("Commentaires supprimés")."</td></tr>";
            } else {
               echo "<tr><td></td><td class=\"text-danger\">".gal_translate("Commentaires non supprimés")."</td></tr>";
            }
            if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_img WHERE id='$r_img[1]'")) {
               echo "<tr><td></td><td>".gal_translate("Enregistrement supprimé")."</td></tr>";
            } else {
               echo "<tr><td></td><td class=\"text-danger\">".gal_translate("Enregistrement non supprimé")."</td></tr>";
            }
         } // Fin du while img
         if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$r_gal[1]'")) {
            echo "<tr><td colspan=\"2\">&nbsp;&nbsp;&nbsp;".gal_translate("Galerie supprimée")."</td></tr>";
         } else {
            echo "<tr><td colspan=\"2\" class=\"text-danger\">&nbsp;&nbsp;&nbsp;".gal_translate("Galerie non supprimée")."</td></tr>";
         }
      } // Fin du while galerie
      
      if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'")) {
         echo "<tr><td colspan=\"2\"><strong>".gal_translate("Sous-catégorie supprimée")."</strong></td></tr>";
      } else {
         echo "<tr><td colspan=\"2\" class=\"text-danger\">".gal_translate("Sous-catégorie non supprimée")."</td></tr>";
      }
      echo "</table>";
   }
}

function DelGal($id,$go) {
   global $ThisFile, $NPDS_Prefix, $ModPath;
   if (empty($go)) {
      $q_gal = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$id'");
      $r_gal = sql_fetch_row($q_gal);
      echo '
      <div class="alert alert-danger lead">
         <i class="fa fa-info-circle mr-2"></i>'.gal_translate("Vous allez supprimer").' : <strong>'.$r_gal[0].'</strong>
      </div>
      <a class="btn btn-outline-danger btn-sm mr-2" href="'.$ThisFile.'&amp;subop=delgal&amp;galid='.$id.'&amp;go=true">'.gal_translate("Confirmer").'</a><a class="btn btn-outline-secondary btn-sm" href="'.$ThisFile.'">'.gal_translate("Annuler").'</a>';
   } else {
      $q_gal = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$id'");
      $r_gal = sql_fetch_row($q_gal);
      $q_img = sql_query("SELECT name,id FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='$id'");

       if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$id'"))
         echo '<div class="alert alert-success">'.gal_translate("Galerie").' '.$r_gal[0].' '.gal_translate("supprimée").'</div>';
      else
         echo '<div class="alert alert-danger">'.gal_translate("Galerie").' '.$r_gal[0].' '.gal_translate(" non supprimée").'</div>';

      while ($r_img = sql_fetch_row($q_img)) {
         $m_img = "modules/$ModPath/mini/$r_img[0]";
         $g_img = "modules/$ModPath/imgs/$r_img[0]";
         echo '
         <ul class="list-group">
            <li class="list-group-item lead font-weight-bold">'.$r_img[0].'</li>';
         if (@unlink($m_img))
            echo '
            <li class="list-group-item list-group-item-success">'.gal_translate("Miniature supprimée").'</li>';
         else
            echo '
            <li class="list-group-item list-group-item-danger">'.gal_translate("Miniature non supprimée").'</li>';
         if (@unlink($g_img))
            echo '
            <li class="list-group-item list-group-item-success">'.gal_translate("Image supprimée").'</li>';
         else
            echo '
            <li class="list-group-item list-group-item-danger">'.gal_translate("Image non supprimée").'</li>';
         if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='$r_img[1]'"))
            echo '
            <li class="list-group-item list-group-item-success">'.gal_translate("Votes supprimés").'</li>';
         else
            echo '
            <li class="list-group-item list-group-item-danger">'.gal_translate("Votes non supprimés").'</li>';
         if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$r_img[1]'"))
            echo '
            <li class="list-group-item list-group-item-success">'.gal_translate("Commentaires supprimés").'</li>';
         else
            echo '
            <li class="list-group-item list-group-item-danger">'.gal_translate("Commentaires non supprimés").'</li>';
         if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_img WHERE id='$r_img[1]'"))
            echo '
            <li class="list-group-item list-group-item-success">'.gal_translate("Enregistrement supprimé").'</li>';
         else
            echo '
            <li class="list-group-item list-group-item-danger">'.gal_translate("Enregistrement non supprimé").'</li>';
      }
      echo '
         </ul>';
   }
}

function EditImg($id) {
   global $ThisFile, $NPDS_Prefix, $ModPath;
   $queryA = sql_query("SELECT name,comment,gal_id FROM ".$NPDS_Prefix."tdgal_img WHERE id='$id'");
   $rowA = sql_fetch_row($queryA);
   echo '
   <h4>'.gal_translate("Edition").'</h4>
   <hr />
   <div class="row">
      <div class="col-sm-5 d-flex align-items-center py-0 mx-auto">
         <img class="img-fluid img-thumbnail" src="modules/'.$ModPath.'/mini/'.$rowA[0].'" alt="'.$rowA[0].'" data-toggle="tooltip" data-placement="bottom" title="'.$rowA[0].'" />
      </div>
      <div class="col-sm-7">
         <form id="formmodifimg" action="'.$ThisFile.'" method="post" name="FormModifImg">
            <input type="hidden" name="subop" value="doeditimg" />
            <input type="hidden" name="imgid" value="'.$id.'" />
            <div class="form-group">
               <label class="col-form-label" for="imggal">'.gal_translate("Galeries").'</label>
               <select id="imggal" name="imggal" class="custom-select">';
   echo select_arbo($rowA[2]);
   echo '
               </select>
            </div>
            <div class="form-group">
               <label class="col-form-label" for="newdesc">'.gal_translate("Description").'</label>
               <textarea class="form-control" type="text" id="newdesc" name="newdesc" rows="3" maxlength="255">'.stripslashes($rowA[1]).'</textarea>
               <span class="help-block text-right" id="countcar_newdesc"></span>
            </div>
            <input class="btn btn-primary" type="submit" value="'.gal_translate("Modifier").'">
         </form>
      </div>
   </div>';
      $arg1 = '
   var formulid = ["formmodifimg"]
   inpandfieldlen("newdesc",255);';
   adminfoot('fv','',$arg1,'1');

   $qcomment = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$id' ORDER BY comtimestamp DESC");
   $num_comm = sql_num_rows($qcomment);
   echo '
      <ul class="list-group mt-4">';
   while ($rowC = sql_fetch_row($qcomment)) {
      echo '
         <li class="d-flex list-group-item list-group-item-light justify-content-between align-items-left">'.userpopover($rowC[2],40).' '.$rowC[2].' '.gal_translate("a posté le").' '.date(translate("dateinternal"),$rowC[5]).'<span class="ml-auto"><a href="'.$ThisFile.'&amp;subop=delcomimg&amp;id='.$rowC[0].'&amp;picid='.$rowC[1].'"><i class="fa fa-trash-o fa-lg text-danger" title="Effacer" data-toggle="tooltip"></i></a></span></li>
         <li class="list-group-item">'.stripslashes($rowC[3]).'</li>';
   }
   echo '
      </ul>';
}

function DoEditImg($id,$imggal,$newdesc) {
   global $ThisRedo, $NPDS_Prefix;
   $newtit = addslashes(removeHack($newdesc));
   if ($imggal=='') $imggal="-1";
   if (sql_query("UPDATE ".$NPDS_Prefix."tdgal_img SET gal_id='$imggal', comment='$newtit' WHERE id='$id'"))
      redirect_url($ThisRedo."&subop=viewarbo");
   else {
      echo '
      <script type="text/javascript">
         //<![CDATA[
            alert("Erreur lors de la modification de l\'image");
         //]]>
      </script>';
      redirect_url($ThisRedo."&subop=editimg&imgid=$id");
   }
}

function DelImg($id,$go) {
   global $ThisFile, $NPDS_Prefix, $ModPath, $ThisRedo;
   if (empty($go)) {
      $q_img = sql_query("SELECT name FROM ".$NPDS_Prefix."tdgal_img WHERE id='$id'");
      $r_img = sql_fetch_row($q_img);
      echo '
      <div class="alert alert-danger lead">'.gal_translate("Vous allez supprimer une image").' : '.$r_img[0].'</div>
      <a class="btn btn-outline-danger btn-sm mr-2" href="'.$ThisFile.'&amp;subop=delimg&amp;imgid='.$id.'&amp;go=true">'.gal_translate("Confirmer").'</a>
      <a class="btn btn-outline-secondary btn-sm" href="'.$ThisFile.'">'.gal_translate("Annuler").'</a>';
   } else {
      $q_img = sql_query("SELECT name FROM ".$NPDS_Prefix."tdgal_img WHERE id='$id'");
      $r_img = sql_fetch_row($q_img);
      $m_img = "modules/$ModPath/mini/$r_img[0]";
      $g_img = "modules/$ModPath/imgs/$r_img[0]";
      echo '
      <ul class="list-group">
         <li class="list-group-item lead font-weight-bold">'.$r_img[0].'</li>';
      if (@unlink($m_img))
         echo '
         <li class="list-group-item list-group-item-success">'.gal_translate("Miniature supprimée").'</li>';
      else
         echo '
         <li class="list-group-item list-group-item-danger">'.gal_translate("Miniature non supprimée").'</li>';
      if (@unlink($g_img))
         echo '
         <li class="list-group-item list-group-item-success">'.gal_translate("Image supprimée").'</li>';
      else
         echo '
         <li class="list-group-item list-group-item-danger">'.gal_translate("Image non supprimée").'</li>';
      if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='$id'"))
         echo '
         <li class="list-group-item list-group-item-success">'.gal_translate("Votes supprimés").'</li>';
      else
         echo '
         <li class="list-group-item list-group-item-danger">'.gal_translate("Votes non supprimés").'</li>';
      if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$id'"))
         echo '
         <li class="list-group-item list-group-item-success">'.gal_translate("Commentaires supprimés").'</li>';
      else
         echo '
         <li class="list-group-item list-group-item-danger">'.gal_translate("Commentaires non supprimés").'</li>';
      if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_img WHERE id='$id'"))
         echo '
         <li class="list-group-item list-group-item-success"><strong>'.gal_translate("Enregistrement supprimé").'</strong></li>';
      else
         echo '
         <li class="list-group-item list-group-item-danger">'.gal_translate("Enregistrement non supprimé").'</li>';
      echo '
      </ul>';
//      redirect_url($ThisRedo);
   }
}

function DelImgBatch($imgids,$go) {
   global $ThisFile, $NPDS_Prefix, $ModPath, $ThisRedo, $imgids;
   $nbdel=count($imgids);
   $imgtodel='';
   foreach ($imgids as $v_id) {
   $imgtodel .= 'imgids[]='.$v_id.'&';
   }
   if (empty($go)) {
      echo '
      <div class="alert alert-danger lead">'.gal_translate("Vous allez supprimer").' '.$nbdel.' '.gal_translate("image(s)").'</div>
      <a class="btn btn-outline-danger btn-sm mr-2" href="'.$ThisFile.'&amp;subop=delimgbatch&amp;'.$imgtodel.'go=true">'.gal_translate("Confirmer").'</a>
      <a class="btn btn-outline-secondary btn-sm" href="'.$ThisFile.'">'.gal_translate("Annuler").'</a>';
   } 
   else {
      foreach ($imgids as $v_id) {
         $q_img = sql_query("SELECT name FROM ".$NPDS_Prefix."tdgal_img WHERE id='$v_id'");
         $r_img = sql_fetch_row($q_img);
         $m_img = "modules/$ModPath/mini/$r_img[0]";
         $g_img = "modules/$ModPath/imgs/$r_img[0]";
         @unlink($m_img);
         @unlink($g_img);
         sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='$v_id'");
         sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$v_id'");
         sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_img WHERE id='$v_id'");
      }
   }
}

function DelComImg($id, $picid) {
   global $ThisRedo, $NPDS_Prefix;
   sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$picid' AND id='$id'");
   redirect_url($ThisRedo."&subop=editimg&imgid=$picid");
}

function DoValidImg($id) {
   global $ThisRedo, $NPDS_Prefix;
   if (sql_query("UPDATE ".$NPDS_Prefix."tdgal_img SET noaff='0' WHERE id='$id'"))
      redirect_url($ThisRedo."&subop=viewarbo");
}

function Edit($type,$id) {
   global $ThisFile, $NPDS_Prefix, $ThisRedo;
   if ($type=="Cat") $query = "SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'";
   if ($type=="Gal") $query = "SELECT * FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$id'";
   $result = sql_query($query);
   if (!$row=sql_fetch_row($result))
      redirect_url($ThisRedo);
   else
      $actualname = stripslashes($row[2]);
   echo '
   <form class="mt-3" id="formrename" action="'.$ThisFile.'" method="post" name="FormRename">
      <input type="hidden" name="subop" value="rename" />
      <input type="hidden" name="type" value="'.$type.'" />
      <input type="hidden" name="gcid" value="'.$id.'" />
      <h5 class="my-3">'.gal_translate("Edition").' ';
      if ($type=="Gal")
         echo strtolower(gal_translate("Galerie"));
      else
         echo strtolower(gal_translate("Catégorie"));
      echo ' : <span class="text-muted">'.$actualname.'</span></h5>
      <hr />';
   //déplacement d'une galerie
   if ($type=="Gal") {
      echo '
      <div class="form-group row">
         <label class="col-sm-4 form-control-label" for="newgalcat">'.gal_translate("Catégorie").'</label>
         <div class="col-sm-8">
            <select class="custom-select" name="newgalcat" id="newgalcat" >';
      echo cat_arbo($row[1]);
      echo '
            </select>
         </div>
      </div>';
   }
   echo '
      <div class="form-group row">
         <label class="col-sm-4 col-form-label" for="newacces">'.gal_translate("Accès pour").'</label>
         <div class="col-sm-8">';
   if ($type=="Cat")
      echo '
            <select class="custom-select" type="select" name="newacces" id="newacces" >'.Fab_Option_Group($row[3]).'</select>';
   if ($type=="Gal")
      echo '
            <select class="custom-select" type="select" name="newacces" id="newacces" >'.Fab_Option_Group($row[4]).'</select>';
   echo '
         </div>
      </div>
      <div class="form-group row">
         <label class="col-sm-4 col-form-label" for="newname">'.gal_translate("Nouveau nom").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="newname" id="newname" maxlength="150" required="required" />
            <span class="help-block text-right" id="countcar_newname"></span>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-8 ml-auto">
            <button class="btn btn-primary" type="submit">'.gal_translate("Modifier").'</button>
         </div>
      </div>
   </form>';
   $arg1 = '
   var formulid = ["formrename"]
   inpandfieldlen("newname",150);';
   adminfoot('fv','',$arg1,'1');
}

function ChangeName($type,$id,$valeur,$galcat,$acces) {
   global $NPDS_Prefix, $ThisRedo;
   if ($type=="Cat") $query = "UPDATE ".$NPDS_Prefix."tdgal_cat SET nom=\"$valeur\", acces=\"$acces\" WHERE id=$id";
   if ($type=="Gal") $query = "UPDATE ".$NPDS_Prefix."tdgal_gal SET cid=\"$galcat\", nom=\"$valeur\", acces=\"$acces\" WHERE id=$id";
   $update = sql_query($query);
   redirect_url($ThisRedo);
}

function PrintJavaCodeGal() {
   global $NPDS_Prefix;
   $query = sql_query("SELECT groupe_id, groupe_name FROM ".$NPDS_Prefix."groupes ORDER BY groupe_name");
   $nbgrp = sql_num_rows($query);
   while ($mX = sql_fetch_row($query)) {
      $tmp_groupe[$mX[0]]=$mX[1];
   }

   echo "<script type=\"text/javascript\">\n//<![CDATA[\n";
   echo "var cde_all = new Array;\n";
   echo "var txt_all = new Array;\n";
   echo "var cde_usr = new Array;\n";
   echo "var txt_usr = new Array;\n";
   echo "cde_all[0] = '0'; txt_all[0] = '".adm_translate("Public")."';\n";
   echo "cde_usr[0] = '1'; txt_usr[0] = '".adm_translate("Utilisateur enregistré")."';\n";
   echo "cde_all[1] = '1'; txt_all[1] = '".adm_translate("Utilisateur enregistré")."';\n";
   echo "cde_usr[1] = '-127'; txt_usr[1] = '".gal_translate("Administrateurs")."';\n";
   echo "cde_all[2] = '-127'; txt_all[2] = '".gal_translate("Administrateurs")."';\n";
   if (count($tmp_groupe) != 0) {
      $i = 3;
      while (list($val, $nom) = each($tmp_groupe)) {
         echo "cde_usr[".($i-1)."] = '".$val."'; txt_usr[".($i-1)."] = '".$nom."';\n";
         echo "cde_all[".$i."] = '".$val."'; txt_all[".$i."] = '".$nom."';\n";
         $i++;
      }
   }
   echo "\n";
   echo "function verif() {\n";
   echo "  if (document.layers) {\n";
   echo "    formulaire = document.forms.FormCreer;\n";
   echo "  } else {\n";
   echo "    formulaire = document.FormCreer;\n";
   echo "  }\n";
   echo "  formulaire.acces.options.length = 1;\n";
   echo "}\n\n";
   echo "function remplirAcces(index,code) {\n";
   echo "  verif();\n";
   echo "  if(code.substring(code.lastIndexOf('(')+1) == '".adm_translate("Public").")') { //All\n";
   echo "    formulaire.acces.options.length = cde_all.length;\n";
   echo "    for(i=0; i<cde_all.length; i++) {\n";
   echo "      formulaire.acces.options[i].value = cde_all[i];\n";
   echo "      formulaire.acces.options[i].text = txt_all[i];\n";
   echo "    }\n";
   echo "  } else if(code.substring(code.lastIndexOf('(')+1) == '".adm_translate("Utilisateur enregistré").")') { //User\n";
   echo "    formulaire.acces.options.length = cde_usr.length;\n";
   echo "    for(i=0; i<cde_usr.length; i++) {\n";
   echo "      formulaire.acces.options[i].value = cde_usr[i];\n";
   echo "      formulaire.acces.options[i].text = txt_usr[i];\n";
   echo "    }\n";
   echo "  } else {\n";
   echo "    formulaire.acces.options.length = 1;\n";
   echo "    for(i=0; i<cde_all.length; i++) {\n;";
   echo "      if(code.substring(code.lastIndexOf('(')+1) == txt_all[i]+')') {\n";
   echo "        formulaire.acces.options[0].value = cde_all[i];\n";
   echo "        formulaire.acces.options[0].text = txt_all[i];\n";
   echo "      }\n";
   echo "    }\n";
   echo "  }\n";
   echo "}";
   echo "\n//]]>\n</script>\n";
}

function Fab_Option_Group($GrpActu='0') {
   settype($txt,'string');
   $tmp_group = Get_Name_Group('list', $GrpActu);
   while (list($val, $nom) = each($tmp_group)) {
      if ($val == $GrpActu)
         $txt.= '<option value="'.$val.'" selected="selected">&nbsp;'.$nom.'&nbsp;</option>';
      else
         $txt.= '<option value="'.$val.'">&nbsp;'.$nom.'&nbsp;</option>';
   }
   return $txt;
}

function Get_Name_Group($ordre, $GrpActu) {
   $tmp_groupe = liste_group('');
   $tmp_groupe[127] = gal_translate("Administrateurs");
   $tmp_groupe[0] = adm_translate("Public");
   $tmp_groupe[1] = adm_translate("Utilisateur enregistré");
   if ($ordre=='list') {
      asort($tmp_groupe);
      return ($tmp_groupe);
   } else
      return ($tmp_groupe[abs($GrpActu)]);
}

function GetGalCat($galcid) {
   global $NPDS_Prefix;
   $query = sql_query("SELECT nom,cid FROM ".$NPDS_Prefix."tdgal_cat WHERE id='".$galcid."'");
   $row = sql_fetch_row($query);
   if ($row[1] == 0)
      return stripslashes($row[0]);
   else {
      $queryX = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='".$row[1]."'");
      $rowX = sql_fetch_row($queryX);
      return stripslashes($rowX[0])." - ".stripslashes($row[0]);
   }
}

// CreateThumb($newfilename, "modules/$ModPath/imgs/", "modules/$ModPath/mini/", $MaxSizeThumb, $filename_ext);
function CreateThumb($Image, $Source, $Destination, $Max, $ext) {
   switch ($ext) {
      case (preg_match('/jpeg|jpg/i', $ext) ? true : false) :
         $src=@imagecreatefromjpeg($Source.$Image);
      break;
      case (preg_match('/gif/i', $ext) ? true : false) :
         $src=@imagecreatefromgif($Source.$Image);
      break;
      case (preg_match('/png/i', $ext) ? true : false) :
         $src=@imagecreatefrompng($Source.$Image);
      break;
   }
   if ($src) {
      $size = getimagesize($Source.$Image);
      $h_i = $size[1]; //hauteur
      $w_i = $size[0]; //largeur
      if (($h_i > $Max) || ($w_i > $Max)) {
         if ($h_i > $w_i) {
            $convert = $Max/$h_i;
            $h_i = $Max;
            $w_i = ceil($w_i*$convert);
         } else {
            $convert = $Max/$w_i;
            $w_i = $Max;
            $h_i = ceil($h_i*$convert);
         }
      }
      if (function_exists("imagecreatetruecolor"))
         $im = @imagecreatetruecolor($w_i, $h_i);
      else
         $im = @imagecreate($w_i, $h_i);
      @imagecopyresized($im, $src, 0, 0, 0, 0, $w_i, $h_i, $size[0], $size[1]);
      @imageinterlace ($im,1);
      switch ($ext) {
         case (preg_match('/jpeg|jpg/i', $ext) ? true : false) :
            @imagejpeg($im, $Destination.$Image, 100);
         break;
         case (preg_match('/gif/i', $ext) ? true : false) :
            @imagegif($im, $Destination.$Image);
         break;
         case (preg_match('/png/i', $ext) ? true : false) :
            @imagepng($im, $Destination.$Image, 6);
         break;
      }
      @chmod($Dest.$Image,0766);
   }
}

function import() {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisFile, $ThisRedo;
   $handle=opendir("modules/$ModPath/import");
   while ($file = readdir($handle)) $filelist[] = $file;
   closedir($handle);
   $j=0;
   foreach($filelist as $v) {
      if(preg_match('#\.gif|\.jpg|\.jpeg|\.png$#i', $v)) $j++;
   }
   echo '
   <h3 class="my-3">'.gal_translate("Images").'</h3>
   <hr />';
   if($j!=0) {
      echo '
   <h4>'.gal_translate("Import images").'<span class="badge badge-success float-right">'.$j.'</span></h4>
   <blockquote class="blockquote my-3">
      '.gal_translate("Images du dossier").' <code>/modules/npds_galerie/import</code><br />
      '.gal_translate("Création des images et imagettes dans").' <code>/modules/npds_galerie/imgs</code> &amp; <code>/modules/npds_galerie/mini</code><br />
      '.gal_translate("Affectation vers la galerie choisie.").'<br />
      '.gal_translate("Les images importées seront supprimées du dossier").' <code>/modules/npds_galerie/import</code>.
   </blockquote>
   <form id="massimport" method="post" action="'.$ThisFile.'" name="MassImport">
      <input type="hidden" name="subop" value="massimport" />
      <div class="form-group">
         <label class="col-form-label" for="imggal">'.gal_translate("Affectation").'</label>
         <select class="custom-select" name="imggal" id="imggal">';
      echo select_arbo('');
      echo '
         </select>
      </div>
      <div class="form-group">
         <label class="col-form-label" for="descri">'.gal_translate("Description").'</label>
         <textarea class="form-control" name="descri" id="descri" maxlength="255" rows="2"></textarea>
         <span class="help-block">'.gal_translate("Pour toutes les images de cet import.").'<span class="float-right" id="countcar_descri"></span></span>
      </div>
      <button class="btn btn-primary" type="submit">'.gal_translate("Importer").'</button>
   </form>';
      $arg1 = '
   var formulid = ["massimport"]
   inpandfieldlen("descri",255);';
      adminfoot('fv','',$arg1,'1');
   }
   else {
      echo '
      <h4 class="my-3">'.gal_translate("Import images").'<span class="badge badge-danger float-right">'.$j.'</span></h4>
      <div class="alert alert-danger">
            '.gal_translate("Aucune image dans le dossier").' <code>/modules/npds_galerie/import</code> !! <br />
      </div>';
   }
}

function massimport($imggal, $descri) {
   global $MaxSizeImg, $MaxSizeThumb, $ModPath, $ModStart, $NPDS_Prefix;

   $year = date("Y"); $month = date("m"); $day = date("d");
   $hour = date("H"); $min = date("i"); $sec = date("s");

   $handle=opendir("modules/$ModPath/import");
   while ($file = readdir($handle)) $filelist[] = $file;
   closedir($handle);
   asort($filelist);

   $i=1;
   while (list ($key, $file) = each ($filelist)) {
      if (preg_match('#\.gif|\.jpg|\.jpeg|\.png$#i', strtolower($file))) {
         $filename_ext = strtolower(substr(strrchr($file, "."),1));
         $newfilename = $year.$month.$day.$hour.$min.$sec."-".$i.".".$filename_ext;
         rename("modules/$ModPath/import/$file","modules/$ModPath/import/$newfilename");
         if ((function_exists('gd_info')) or extension_loaded('gd')) {
            @CreateThumb($newfilename, "modules/$ModPath/import/", "modules/$ModPath/imgs/", $MaxSizeImg, $filename_ext);
            @CreateThumb($newfilename, "modules/$ModPath/import/", "modules/$ModPath/mini/", $MaxSizeThumb, $filename_ext);
         }
      echo '<ul class="list-group">';
         if (sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_img VALUES ('','$imggal','$newfilename','$descri','','0','0')")) {
            echo '<li class="list-group-item list-group-item-success mb-1">'.gal_translate("Image ajoutée avec succès").' : '.$file.'</li>';
            $i++;
         } else {
            echo '<li class="list-group-item list-group-item-danger mb-1">'.gal_translate("Impossible d'ajouter l'image en BDD").'</li>';
            @unlink ("modules/$ModPath/imgs/$newfilename");
            @unlink ("modules/$ModPath/mini/$newfilename");
         }
         echo '</ul>';
         @unlink ("modules/$ModPath/import/$newfilename");
      }
   }
}

function ordre($ximg, $xordre, $xdesc) {
   global $ThisRedo, $NPDS_Prefix;
   while(list($ibid,$img_id)=each($ximg)) {
      echo $img_id, $xordre[$ibid].'<br />';
      sql_query("UPDATE ".$NPDS_Prefix."tdgal_img SET ordre='$xordre[$ibid]', comment='$xdesc[$ibid]' WHERE id='$img_id'");
   }
   redirect_url($ThisRedo."&subop=viewarbo");
}

function PrintExportCat() {
   global $NPDS_Prefix, $ThisFile;
   echo '
   <h5 class="mt-3">'.gal_translate("Export catégorie").'</h5>
   <hr />
   <form action="'.$ThisFile.'" method="post" name="FormCat">
      <input type="hidden" name="subop" value="massexport" />
      <div class="form-group">
         <label class= "col-form-label" for="cat">'.gal_translate("Nom de la catégorie").'</label>
         <select class="custom-select" name="cat" id="cat">
            <option value="none" selected="selected">'.gal_translate("Choisissez").'</option>';
   $query = sql_query("SELECT id,nom,acces FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ORDER BY nom ASC");
   while ($row = sql_fetch_row($query)) {
      echo '
            <option value='.$row[0].'>'.stripslashes($row[1]).'</option>';
   }
   echo '
         </select>
      </div>
      <button class="btn btn-primary" type="submit">'.gal_translate("Exporter").'</button>
   </form>';
}

function MassExportCat($cat) {
   global $NPDS_Prefix, $ThisRedo, $ModPath;

   $sql_cat = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$cat'");
   $num_cat = sql_num_rows($sql_cat);
   if ($num_cat != 0) {
      $sql_sscat = "SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid=$cat";
      $sql_gal = "SELECT * FROM ".$NPDS_Prefix."tdgal_gal";
      // CATEGORIE
      $nb_gal=0;
      $nb_img=0;
      while ($row_cat = sql_fetch_row($sql_cat)) {
         $ibid.="INSERT INTO tdgal_cat VALUES ($row_cat[0], $row_cat[1], '".htmlentities($row_cat[2])."',$row_cat[3]);\n";
         $queryX = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         while ($rowX_gal = sql_fetch_row($queryX)) {
            $ibid.="INSERT INTO tdgal_gal VALUES ($rowX_gal[0], $rowX_gal[1], '".htmlentities($rowX_gal[2])."', $rowX_gal[3], $rowX_gal[4]);\n";
            $nb_gal++;
            // trouver les images
            $queryZ = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$rowX_gal[0]."' ORDER BY ordre,id");
            while ($rowZ_img = sql_fetch_row($queryZ)) {
               copy("modules/$ModPath/mini/$rowZ_img[2]","modules/$ModPath/export/mini/$rowZ_img[2]");
               copy("modules/$ModPath/imgs/$rowZ_img[2]","modules/$ModPath/export/imgs/$rowZ_img[2]");
               $ibid.="INSERT INTO ".$NPDS_Prefix."tdgal_img VALUES (NULL, $rowX_gal[0], '".htmlentities($rowZ_img[2])."', '".htmlentities($rowZ_img[3])."', 0, $rowZ_img[5], 0);\n";
               $nb_img++;
            }
         }
         $ibid.="\n";
         // SOUS-CATEGORIE
         $query = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         while ($row_sscat = sql_fetch_row($query)) {
            $ibid.="INSERT INTO tdgal_cat VALUES ($row_sscat[0], $row_sscat[1], '".htmlentities($row_sscat[2])."',$row_sscat[3]);\n";
            $querx = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_sscat[0]."' ORDER BY nom ASC");
            while ($row_gal = sql_fetch_row($querx)) {
               $ibid.="INSERT INTO tdgal_gal VALUES ($row_gal[0], $row_gal[1], '".htmlentities($row_gal[2])."', $row_gal[3], $row_gal[4]);\n";
               $nb_gal++;
               // trouver les images
               $queryZ = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$row_gal[0]."' ORDER BY ordre,id");
               while ($rowZ_img = sql_fetch_row($queryZ)) {
                  copy("modules/$ModPath/mini/$rowZ_img[2]","modules/$ModPath/export/mini/$rowZ_img[2]");
                  copy("modules/$ModPath/imgs/$rowZ_img[2]","modules/$ModPath/export/imgs/$rowZ_img[2]");
                  $ibid.="INSERT INTO ".$NPDS_Prefix."tdgal_img VALUES (NULL, $row_gal[0], '".htmlentities($rowZ_img[2])."', '".htmlentities($rowZ_img[3])."', 0, $rowZ_img[5], 0);\n";
                  $nb_img++;
               }
            }
         }
      }
   }
   $ibid.="\n";
   $ibid.="# ----------------------------------------\n";
   $ibid.="# Nombre de galeries exportées $nb_gal\n";
   $ibid.="# Nombre d'images exportées : $nb_img\n";
   $ibid.="# ----------------------------------------\n";
   $ibid.="# Attention les numeros de catégories et  \n";
   $ibid.="# de galeries peuvent être en conflit avec\n";
   $ibid.="# ceux de votre TD-Galerie.  \n";
   $ibid.="# ----------------------------------------\n";
   
   if ($myfile = fopen("modules/$ModPath/export/sql/export.sql", "wb")) {
      fwrite($myfile, "$ibid");
      fclose($myfile);
      unset($content);
      redirect_url($ThisRedo);
   } else
      redirect_url($ThisRedo);
}
?>