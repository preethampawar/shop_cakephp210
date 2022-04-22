<?php
/** Adminer Editor - Compact database editor
* @link https://www.adminer.org/
* @author Jakub Vrana, https://www.vrana.cz/
* @copyright 2009 Jakub Vrana
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
* @version 4.7.6
*/error_reporting(6135);$Jb=!preg_match('~^(unsafe_raw)?$~',ini_get("filter.default"));if($Jb||ini_get("filter.default_flags")){foreach(array('_GET','_POST','_COOKIE','_SERVER')as$W){$Se=filter_input_array(constant("INPUT$W"),FILTER_UNSAFE_RAW);if($Se)$$W=$Se;}}if(function_exists("mb_internal_encoding"))mb_internal_encoding("8bit");function
connection(){global$f;return$f;}function
adminer(){global$b;return$b;}function
version(){global$ca;return$ca;}function
idf_unescape($u){$_c=substr($u,-1);return
str_replace($_c.$_c,$_c,substr($u,1,-1));}function
escape_string($W){return
substr(q($W),1,-1);}function
number($W){return
preg_replace('~[^0-9]+~','',$W);}function
number_type(){return'((?<!o)int(?!er)|numeric|real|float|double|decimal|money)';}function
remove_slashes($yd,$Jb=false){if(get_magic_quotes_gpc()){while(list($y,$W)=each($yd)){foreach($W
as$uc=>$V){unset($yd[$y][$uc]);if(is_array($V)){$yd[$y][stripslashes($uc)]=$V;$yd[]=&$yd[$y][stripslashes($uc)];}else$yd[$y][stripslashes($uc)]=($Jb?$V:stripslashes($V));}}}}function
bracket_escape($u,$ua=false){static$He=array(':'=>':1',']'=>':2','['=>':3','"'=>':4');return
strtr($u,($ua?array_flip($He):$He));}function
min_version($bf,$Ic="",$g=null){global$f;if(!$g)$g=$f;$Wd=$g->server_info;if($Ic&&preg_match('~([\d.]+)-MariaDB~',$Wd,$B)){$Wd=$B[1];$bf=$Ic;}return(version_compare($Wd,$bf)>=0);}function
charset($f){return(min_version("5.5.3",0,$f)?"utf8mb4":"utf8");}function
script($de,$Ge="\n"){return"<script".nonce().">$de</script>$Ge";}function
script_src($Xe){return"<script src='".h($Xe)."'".nonce()."></script>\n";}function
nonce(){return' nonce="'.get_nonce().'"';}function
target_blank(){return' target="_blank" rel="noreferrer noopener"';}function
h($ke){return
str_replace("\0","&#0;",htmlspecialchars($ke,ENT_QUOTES,'utf-8'));}function
nl_br($ke){return
str_replace("\n","<br>",$ke);}function
checkbox($E,$X,$Fa,$xc="",$cd="",$Ia="",$yc=""){$M="<input type='checkbox' name='$E' value='".h($X)."'".($Fa?" checked":"").($yc?" aria-labelledby='$yc'":"").">".($cd?script("qsl('input').onclick = function () { $cd };",""):"");return($xc!=""||$Ia?"<label".($Ia?" class='$Ia'":"").">$M".h($xc)."</label>":$M);}function
optionlist($F,$Rd=null,$Ze=false){$M="";foreach($F
as$uc=>$V){$gd=array($uc=>$V);if(is_array($V)){$M.='<optgroup label="'.h($uc).'">';$gd=$V;}foreach($gd
as$y=>$W)$M.='<option'.($Ze||is_string($y)?' value="'.h($y).'"':'').(($Ze||is_string($y)?(string)$y:$W)===$Rd?' selected':'').'>'.h($W);if(is_array($V))$M.='</optgroup>';}return$M;}function
html_select($E,$F,$X="",$bd=true,$yc=""){if($bd)return"<select name='".h($E)."'".($yc?" aria-labelledby='$yc'":"").">".optionlist($F,$X)."</select>".(is_string($bd)?script("qsl('select').onchange = function () { $bd };",""):"");$M="";foreach($F
as$y=>$W)$M.="<label><input type='radio' name='".h($E)."' value='".h($y)."'".($y==$X?" checked":"").">".h($W)."</label>";return$M;}function
select_input($c,$F,$X="",$bd="",$qd=""){$ue=($F?"select":"input");return"<$ue$c".($F?"><option value=''>$qd".optionlist($F,$X,true)."</select>":" size='10' value='".h($X)."' placeholder='$qd'>").($bd?script("qsl('$ue').onchange = $bd;",""):"");}function
confirm($C="",$Sd="qsl('input')"){return
script("$Sd.onclick = function () { return confirm('".($C?js_escape($C):'Are you sure?')."'); };","");}function
print_fieldset($t,$Bc,$ef=false){echo"<fieldset><legend>","<a href='#fieldset-$t'>$Bc</a>",script("qsl('a').onclick = partial(toggle, 'fieldset-$t');",""),"</legend>","<div id='fieldset-$t'".($ef?"":" class='hidden'").">\n";}function
bold($Aa,$Ia=""){return($Aa?" class='active $Ia'":($Ia?" class='$Ia'":""));}function
odd($M=' class="odd"'){static$s=0;if(!$M)$s=-1;return($s++%2?$M:'');}function
js_escape($ke){return
addcslashes($ke,"\r\n'\\/");}function
json_row($y,$W=null){static$Kb=true;if($Kb)echo"{";if($y!=""){echo($Kb?"":",")."\n\t\"".addcslashes($y,"\r\n\t\"\\/").'": '.($W!==null?'"'.addcslashes($W,"\r\n\"\\/").'"':'null');$Kb=false;}else{echo"\n}\n";$Kb=true;}}function
ini_bool($nc){$W=ini_get($nc);return(preg_match('~^(on|true|yes)$~i',$W)||(int)$W);}function
sid(){static$M;if($M===null)$M=(SID&&!($_COOKIE&&ini_bool("session.use_cookies")));return$M;}function
set_password($Y,$Q,$U,$I){$_SESSION["pwds"][$Y][$Q][$U]=($_COOKIE["adminer_key"]&&is_string($I)?array(encrypt_string($I,$_COOKIE["adminer_key"])):$I);}function
get_password(){$M=get_session("pwds");if(is_array($M))$M=($_COOKIE["adminer_key"]?decrypt_string($M[0],$_COOKIE["adminer_key"]):false);return$M;}function
q($ke){global$f;return$f->quote($ke);}function
get_vals($K,$d=0){global$f;$M=array();$L=$f->query($K);if(is_object($L)){while($N=$L->fetch_row())$M[]=$N[$d];}return$M;}function
get_key_vals($K,$g=null,$Zd=true){global$f;if(!is_object($g))$g=$f;$M=array();$L=$g->query($K);if(is_object($L)){while($N=$L->fetch_row()){if($Zd)$M[$N[0]]=$N[1];else$M[]=$N[0];}}return$M;}function
get_rows($K,$g=null,$k="<p class='error'>"){global$f;$Sa=(is_object($g)?$g:$f);$M=array();$L=$Sa->query($K);if(is_object($L)){while($N=$L->fetch_assoc())$M[]=$N;}elseif(!$L&&!is_object($g)&&$k&&defined("PAGE_HEADER"))echo$k.error()."\n";return$M;}function
unique_array($N,$v){foreach($v
as$lc){if(preg_match("~PRIMARY|UNIQUE~",$lc["type"])){$M=array();foreach($lc["columns"]as$y){if(!isset($N[$y]))continue
2;$M[$y]=$N[$y];}return$M;}}}function
escape_key($y){if(preg_match('(^([\w(]+)('.str_replace("_",".*",preg_quote(idf_escape("_"))).')([ \w)]+)$)',$y,$B))return$B[1].idf_escape(idf_unescape($B[2])).$B[3];return
idf_escape($y);}function
where($Z,$m=array()){global$f,$x;$M=array();foreach((array)$Z["where"]as$y=>$W){$y=bracket_escape($y,1);$d=escape_key($y);$M[]=$d.($x=="sql"&&is_numeric($W)&&preg_match('~\.~',$W)?" LIKE ".q($W):($x=="mssql"?" LIKE ".q(preg_replace('~[_%[]~','[\0]',$W)):" = ".unconvert_field($m[$y],q($W))));if($x=="sql"&&preg_match('~char|text~',$m[$y]["type"])&&preg_match("~[^ -@]~",$W))$M[]="$d = ".q($W)." COLLATE ".charset($f)."_bin";}foreach((array)$Z["null"]as$y)$M[]=escape_key($y)." IS NULL";return
implode(" AND ",$M);}function
where_check($W,$m=array()){parse_str($W,$Ea);remove_slashes(array(&$Ea));return
where($Ea,$m);}function
where_link($s,$d,$X,$ed="="){return"&where%5B$s%5D%5Bcol%5D=".urlencode($d)."&where%5B$s%5D%5Bop%5D=".urlencode(($X!==null?$ed:"IS NULL"))."&where%5B$s%5D%5Bval%5D=".urlencode($X);}function
convert_fields($e,$m,$P=array()){$M="";foreach($e
as$y=>$W){if($P&&!in_array(idf_escape($y),$P))continue;$oa=convert_field($m[$y]);if($oa)$M.=", $oa AS ".idf_escape($y);}return$M;}function
cookie($E,$X,$Ec=2592000){global$aa;return
header("Set-Cookie: $E=".urlencode($X).($Ec?"; expires=".gmdate("D, d M Y H:i:s",time()+$Ec)." GMT":"")."; path=".preg_replace('~\?.*~','',$_SERVER["REQUEST_URI"]).($aa?"; secure":"")."; HttpOnly; SameSite=lax",false);}function
restart_session(){if(!ini_bool("session.use_cookies"))session_start();}function
stop_session($Mb=false){$Ye=ini_bool("session.use_cookies");if(!$Ye||$Mb){session_write_close();if($Ye&&@ini_set("session.use_cookies",false)===false)session_start();}}function&get_session($y){return$_SESSION[$y][DRIVER][SERVER][$_GET["username"]];}function
set_session($y,$W){$_SESSION[$y][DRIVER][SERVER][$_GET["username"]]=$W;}function
auth_url($Y,$Q,$U,$h=null){global$mb;preg_match('~([^?]*)\??(.*)~',remove_from_uri(implode("|",array_keys($mb))."|username|".($h!==null?"db|":"").session_name()),$B);return"$B[1]?".(sid()?SID."&":"").($Y!="server"||$Q!=""?urlencode($Y)."=".urlencode($Q)."&":"")."username=".urlencode($U).($h!=""?"&db=".urlencode($h):"").($B[2]?"&$B[2]":"");}function
is_ajax(){return($_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest");}function
redirect($A,$C=null){if($C!==null){restart_session();$_SESSION["messages"][preg_replace('~^[^?]*~','',($A!==null?$A:$_SERVER["REQUEST_URI"]))][]=$C;}if($A!==null){if($A=="")$A=".";header("Location: $A");exit;}}function
query_redirect($K,$A,$C,$Fd=true,$Bb=true,$Eb=false,$ze=""){global$f,$k,$b;if($Bb){$he=microtime(true);$Eb=!$f->query($K);$ze=format_time($he);}$fe="";if($K)$fe=$b->messageQuery($K,$ze,$Eb);if($Eb){$k=error().$fe.script("messagesPrint();");return
false;}if($Fd)redirect($A,$C.$fe);return
true;}function
queries($K){global$f;static$Ad=array();static$he;if(!$he)$he=microtime(true);if($K===null)return
array(implode("\n",$Ad),format_time($he));$Ad[]=(preg_match('~;$~',$K)?"DELIMITER ;;\n$K;\nDELIMITER ":$K).";";return$f->query($K);}function
apply_queries($K,$te,$zb='table'){foreach($te
as$S){if(!queries("$K ".$zb($S)))return
false;}return
true;}function
queries_redirect($A,$C,$Fd){list($Ad,$ze)=queries(null);return
query_redirect($Ad,$A,$C,$Fd,false,!$Fd,$ze);}function
format_time($he){return
sprintf('%.3f s',max(0,microtime(true)-$he));}function
remove_from_uri($md=""){return
substr(preg_replace("~(?<=[?&])($md".(SID?"":"|".session_name()).")=[^&]*&~",'',"$_SERVER[REQUEST_URI]&"),0,-1);}function
pagination($H,$ab){return" ".($H==$ab?$H+1:'<a href="'.h(remove_from_uri("page").($H?"&page=$H".($_GET["next"]?"&next=".urlencode($_GET["next"]):""):"")).'">'.($H+1)."</a>");}function
get_file($y,$eb=false){$Hb=$_FILES[$y];if(!$Hb)return
null;foreach($Hb
as$y=>$W)$Hb[$y]=(array)$W;$M='';foreach($Hb["error"]as$y=>$k){if($k)return$k;$E=$Hb["name"][$y];$Ee=$Hb["tmp_name"][$y];$Ta=file_get_contents($eb&&preg_match('~\.gz$~',$E)?"compress.zlib://$Ee":$Ee);if($eb){$he=substr($Ta,0,3);if(function_exists("iconv")&&preg_match("~^\xFE\xFF|^\xFF\xFE~",$he,$Gd))$Ta=iconv("utf-16","utf-8",$Ta);elseif($he=="\xEF\xBB\xBF")$Ta=substr($Ta,3);$M.=$Ta."\n\n";}else$M.=$Ta;}return$M;}function
upload_error($k){$Mc=($k==UPLOAD_ERR_INI_SIZE?ini_get("upload_max_filesize"):0);return($k?'Unable to upload a file.'.($Mc?" ".sprintf('Maximum allowed file size is %sB.',$Mc):""):'File does not exist.');}function
repeat_pattern($J,$Cc){return
str_repeat("$J{0,65535}",$Cc/65535)."$J{0,".($Cc%65535)."}";}function
is_utf8($W){return(preg_match('~~u',$W)&&!preg_match('~[\0-\x8\xB\xC\xE-\x1F]~',$W));}function
shorten_utf8($ke,$Cc=80,$oe=""){if(!preg_match("(^(".repeat_pattern("[\t\r\n -\x{10FFFF}]",$Cc).")($)?)u",$ke,$B))preg_match("(^(".repeat_pattern("[\t\r\n -~]",$Cc).")($)?)",$ke,$B);return
h($B[1]).$oe.(isset($B[2])?"":"<i>‚Ä¶</i>");}function
format_number($W){return
strtr(number_format($W,0,".",','),preg_split('~~u','0123456789',-1,PREG_SPLIT_NO_EMPTY));}function
friendly_url($W){return
preg_replace('~[^a-z0-9_]~i','-',$W);}function
hidden_fields($yd,$kc=array()){$M=false;while(list($y,$W)=each($yd)){if(!in_array($y,$kc)){if(is_array($W)){foreach($W
as$uc=>$V)$yd[$y."[$uc]"]=$V;}else{$M=true;echo'<input type="hidden" name="'.h($y).'" value="'.h($W).'">';}}}return$M;}function
hidden_fields_get(){echo(sid()?'<input type="hidden" name="'.session_name().'" value="'.h(session_id()).'">':''),(SERVER!==null?'<input type="hidden" name="'.DRIVER.'" value="'.h(SERVER).'">':""),'<input type="hidden" name="username" value="'.h($_GET["username"]).'">';}function
table_status1($S,$Fb=false){$M=table_status($S,$Fb);return($M?$M:array("Name"=>$S));}function
column_foreign_keys($S){global$b;$M=array();foreach($b->foreignKeys($S)as$Qb){foreach($Qb["source"]as$W)$M[$W][]=$Qb;}return$M;}function
enum_input($Le,$c,$l,$X,$vb=null){global$b;preg_match_all("~'((?:[^']|'')*)'~",$l["length"],$Jc);$M=($vb!==null?"<label><input type='$Le'$c value='$vb'".((is_array($X)?in_array($vb,$X):$X===0)?" checked":"")."><i>".'empty'."</i></label>":"");foreach($Jc[1]as$s=>$W){$W=stripcslashes(str_replace("''","'",$W));$Fa=(is_int($X)?$X==$s+1:(is_array($X)?in_array($s+1,$X):$X===$W));$M.=" <label><input type='$Le'$c value='".($s+1)."'".($Fa?' checked':'').'>'.h($b->editVal($W,$l)).'</label>';}return$M;}function
input($l,$X,$q){global$Ne,$b,$x;$E=h(bracket_escape($l["field"]));echo"<td class='function'>";if(is_array($X)&&!$q){$na=array($X);if(version_compare(PHP_VERSION,5.4)>=0)$na[]=JSON_PRETTY_PRINT;$X=call_user_func_array('json_encode',$na);$q="json";}$Jd=($x=="mssql"&&$l["auto_increment"]);if($Jd&&!$_POST["save"])$q=null;$Ub=(isset($_GET["select"])||$Jd?array("orig"=>'original'):array())+$b->editFunctions($l);$c=" name='fields[$E]'";if($l["type"]=="enum")echo
h($Ub[""])."<td>".$b->editInput($_GET["edit"],$l,$c,$X);else{$ac=(in_array($q,$Ub)||isset($Ub[$q]));echo(count($Ub)>1?"<select name='function[$E]'>".optionlist($Ub,$q===null||$ac?$q:"")."</select>".on_help("getTarget(event).value.replace(/^SQL\$/, '')",1).script("qsl('select').onchange = functionChange;",""):h(reset($Ub))).'<td>';$pc=$b->editInput($_GET["edit"],$l,$c,$X);if($pc!="")echo$pc;elseif(preg_match('~bool~',$l["type"]))echo"<input type='hidden'$c value='0'>"."<input type='checkbox'".(preg_match('~^(1|t|true|y|yes|on)$~i',$X)?" checked='checked'":"")."$c value='1'>";elseif($l["type"]=="set"){preg_match_all("~'((?:[^']|'')*)'~",$l["length"],$Jc);foreach($Jc[1]as$s=>$W){$W=stripcslashes(str_replace("''","'",$W));$Fa=(is_int($X)?($X>>$s)&1:in_array($W,explode(",",$X),true));echo" <label><input type='checkbox' name='fields[$E][$s]' value='".(1<<$s)."'".($Fa?' checked':'').">".h($b->editVal($W,$l)).'</label>';}}elseif(preg_match('~blob|bytea|raw|file~',$l["type"])&&ini_bool("file_uploads"))echo"<input type='file' name='fields-$E'>";elseif(($we=preg_match('~text|lob|memo~i',$l["type"]))||preg_match("~\n~",$X)){if($we&&$x!="sqlite")$c.=" cols='50' rows='12'";else{$O=min(12,substr_count($X,"\n")+1);$c.=" cols='30' rows='$O'".($O==1?" style='height: 1.2em;'":"");}echo"<textarea$c>".h($X).'</textarea>';}elseif($q=="json"||preg_match('~^jsonb?$~',$l["type"]))echo"<textarea$c cols='50' rows='12' class='jush-js'>".h($X).'</textarea>';else{$Oc=(!preg_match('~int~',$l["type"])&&preg_match('~^(\d+)(,(\d+))?$~',$l["length"],$B)?((preg_match("~binary~",$l["type"])?2:1)*$B[1]+($B[3]?1:0)+($B[2]&&!$l["unsigned"]?1:0)):($Ne[$l["type"]]?$Ne[$l["type"]]+($l["unsigned"]?0:1):0));if($x=='sql'&&min_version(5.6)&&preg_match('~time~',$l["type"]))$Oc+=7;echo"<input".((!$ac||$q==="")&&preg_match('~(?<!o)int(?!er)~',$l["type"])&&!preg_match('~\[\]~',$l["full_type"])?" type='number'":"")." value='".h($X)."'".($Oc?" data-maxlength='$Oc'":"").(preg_match('~char|binary~',$l["type"])&&$Oc>20?" size='40'":"")."$c>";}echo$b->editHint($_GET["edit"],$l,$X);$Kb=0;foreach($Ub
as$y=>$W){if($y===""||!$W)break;$Kb++;}if($Kb)echo
script("mixin(qsl('td'), {onchange: partial(skipOriginal, $Kb), oninput: function () { this.onchange(); }});");}}function
process_input($l){global$b,$i;$u=bracket_escape($l["field"]);$q=$_POST["function"][$u];$X=$_POST["fields"][$u];if($l["type"]=="enum"){if($X==-1)return
false;if($X=="")return"NULL";return+$X;}if($l["auto_increment"]&&$X=="")return
null;if($q=="orig")return(preg_match('~^CURRENT_TIMESTAMP~i',$l["on_update"])?idf_escape($l["field"]):false);if($q=="NULL")return"NULL";if($l["type"]=="set")return
array_sum((array)$X);if($q=="json"){$q="";$X=json_decode($X,true);if(!is_array($X))return
false;return$X;}if(preg_match('~blob|bytea|raw|file~',$l["type"])&&ini_bool("file_uploads")){$Hb=get_file("fields-$u");if(!is_string($Hb))return
false;return$i->quoteBinary($Hb);}return$b->processInput($l,$X,$q);}function
fields_from_edit(){global$i;$M=array();foreach((array)$_POST["field_keys"]as$y=>$W){if($W!=""){$W=bracket_escape($W);$_POST["function"][$W]=$_POST["field_funs"][$y];$_POST["fields"][$W]=$_POST["field_vals"][$y];}}foreach((array)$_POST["fields"]as$y=>$W){$E=bracket_escape($y,1);$M[$E]=array("field"=>$E,"privileges"=>array("insert"=>1,"update"=>1),"null"=>1,"auto_increment"=>($y==$i->primary),);}return$M;}function
search_tables(){global$b,$f;$_GET["where"][0]["val"]=$_POST["query"];$Ud="<ul>\n";foreach(table_status('',true)as$S=>$T){$E=$b->tableName($T);if(isset($T["Engine"])&&$E!=""&&(!$_POST["tables"]||in_array($S,$_POST["tables"]))){$L=$f->query("SELECT".limit("1 FROM ".table($S)," WHERE ".implode(" AND ",$b->selectSearchProcess(fields($S),array())),1));if(!$L||$L->fetch_row()){$wd="<a href='".h(ME."select=".urlencode($S)."&where[0][op]=".urlencode($_GET["where"][0]["op"])."&where[0][val]=".urlencode($_GET["where"][0]["val"]))."'>$E</a>";echo"$Ud<li>".($L?$wd:"<p class='error'>$wd: ".error())."\n";$Ud="";}}}echo($Ud?"<p class='message'>".'No tables.':"</ul>")."\n";}function
dump_headers($ic,$Rc=false){global$b;$M=$b->dumpHeaders($ic,$Rc);$jd=$_POST["output"];if($jd!="text")header("Content-Disposition: attachment; filename=".$b->dumpFilename($ic).".$M".($jd!="file"&&!preg_match('~[^0-9a-z]~',$jd)?".$jd":""));session_write_close();ob_flush();flush();return$M;}function
dump_csv($N){foreach($N
as$y=>$W){if(preg_match("~[\"\n,;\t]~",$W)||$W==="")$N[$y]='"'.str_replace('"','""',$W).'"';}echo
implode(($_POST["format"]=="csv"?",":($_POST["format"]=="tsv"?"\t":";")),$N)."\r\n";}function
apply_sql_function($q,$d){return($q?($q=="unixepoch"?"DATETIME($d, '$q')":($q=="count distinct"?"COUNT(DISTINCT ":strtoupper("$q("))."$d)"):$d);}function
get_temp_dir(){$M=ini_get("upload_tmp_dir");if(!$M){if(function_exists('sys_get_temp_dir'))$M=sys_get_temp_dir();else{$n=@tempnam("","");if(!$n)return
false;$M=dirname($n);unlink($n);}}return$M;}function
file_open_lock($n){$p=@fopen($n,"r+");if(!$p){$p=@fopen($n,"w");if(!$p)return;chmod($n,0660);}flock($p,LOCK_EX);return$p;}function
file_write_unlock($p,$bb){rewind($p);fwrite($p,$bb);ftruncate($p,strlen($bb));flock($p,LOCK_UN);fclose($p);}function
password_file($Va){$n=get_temp_dir()."/adminer.key";$M=@file_get_contents($n);if($M||!$Va)return$M;$p=@fopen($n,"w");if($p){chmod($n,0660);$M=rand_string();fwrite($p,$M);fclose($p);}return$M;}function
rand_string(){return
md5(uniqid(mt_rand(),true));}function
select_value($W,$_,$l,$xe){global$b;if(is_array($W)){$M="";foreach($W
as$uc=>$V)$M.="<tr>".($W!=array_values($W)?"<th>".h($uc):"")."<td>".select_value($V,$_,$l,$xe);return"<table cellspacing='0'>$M</table>";}if(!$_)$_=$b->selectLink($W,$l);if($_===null){if(is_mail($W))$_="mailto:$W";if(is_url($W))$_=$W;}$M=$b->editVal($W,$l);if($M!==null){if(!is_utf8($M))$M="\0";elseif($xe!=""&&is_shortable($l))$M=shorten_utf8($M,max(0,+$xe));else$M=h($M);}return$b->selectVal($M,$_,$l,$W);}function
is_mail($sb){$pa='[-a-z0-9!#$%&\'*+/=?^_`{|}~]';$lb='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';$J="$pa+(\\.$pa+)*@($lb?\\.)+$lb";return
is_string($sb)&&preg_match("(^$J(,\\s*$J)*\$)i",$sb);}function
is_url($ke){$lb='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';return
preg_match("~^(https?)://($lb?\\.)+$lb(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i",$ke);}function
is_shortable($l){return
preg_match('~char|text|json|lob|geometry|point|linestring|polygon|string|bytea~',$l["type"]);}function
count_rows($S,$Z,$w,$r){global$x;$K=" FROM ".table($S).($Z?" WHERE ".implode(" AND ",$Z):"");return($w&&($x=="sql"||count($r)==1)?"SELECT COUNT(DISTINCT ".implode(", ",$r).")$K":"SELECT COUNT(*)".($w?" FROM (SELECT 1$K GROUP BY ".implode(", ",$r).") x":$K));}function
slow_query($K){global$b,$Fe,$i;$h=$b->database();$_e=$b->queryTimeout();$be=$i->slowQuery($K,$_e);if(!$be&&support("kill")&&is_object($g=connect())&&($h==""||$g->select_db($h))){$wc=$g->result(connection_id());echo'<script',nonce(),'>
var timeout = setTimeout(function () {
	ajax(\'',js_escape(ME),'script=kill\', function () {
	}, \'kill=',$wc,'&token=',$Fe,'\');
}, ',1000*$_e,');
</script>
';}else$g=null;ob_flush();flush();$M=@get_key_vals(($be?$be:$K),$g,false);if($g){echo
script("clearTimeout(timeout);");ob_flush();flush();}return$M;}function
get_token(){$Dd=rand(1,1e6);return($Dd^$_SESSION["token"]).":$Dd";}function
verify_token(){list($Fe,$Dd)=explode(":",$_POST["token"]);return($Dd^$_SESSION["token"])==$Fe;}function
lzw_decompress($za){$jb=256;$_a=8;$Ka=array();$Kd=0;$Ld=0;for($s=0;$s<strlen($za);$s++){$Kd=($Kd<<8)+ord($za[$s]);$Ld+=8;if($Ld>=$_a){$Ld-=$_a;$Ka[]=$Kd>>$Ld;$Kd&=(1<<$Ld)-1;$jb++;if($jb>>$_a)$_a++;}}$ib=range("\0","\xFF");$M="";foreach($Ka
as$s=>$Ja){$rb=$ib[$Ja];if(!isset($rb))$rb=$if.$if[0];$M.=$rb;if($s)$ib[]=$if.$rb[0];$if=$rb;}return$M;}function
on_help($Pa,$ae=0){return
script("mixin(qsl('select, input'), {onmouseover: function (event) { helpMouseover.call(this, event, $Pa, $ae) }, onmouseout: helpMouseout});","");}function
edit_form($a,$m,$N,$Ve){global$b,$x,$Fe,$k;$se=$b->tableName(table_status1($a,true));page_header(($Ve?'Edit':'Insert'),$k,array("select"=>array($a,$se)),$se);if($N===false)echo"<p class='error'>".'No rows.'."\n";echo'<form action="" method="post" enctype="multipart/form-data" id="form">
';if(!$m)echo"<p class='error'>".'You have no privileges to update this table.'."\n";else{echo"<table cellspacing='0' class='layout'>".script("qsl('table').onkeydown = editingKeydown;");foreach($m
as$E=>$l){echo"<tr><th>".$b->fieldName($l);$fb=$_GET["set"][bracket_escape($E)];if($fb===null){$fb=$l["default"];if($l["type"]=="bit"&&preg_match("~^b'([01]*)'\$~",$fb,$Gd))$fb=$Gd[1];}$X=($N!==null?($N[$E]!=""&&$x=="sql"&&preg_match("~enum|set~",$l["type"])?(is_array($N[$E])?array_sum($N[$E]):+$N[$E]):$N[$E]):(!$Ve&&$l["auto_increment"]?"":(isset($_GET["select"])?false:$fb)));if(!$_POST["save"]&&is_string($X))$X=$b->editVal($X,$l);$q=($_POST["save"]?(string)$_POST["function"][$E]:($Ve&&preg_match('~^CURRENT_TIMESTAMP~i',$l["on_update"])?"now":($X===false?null:($X!==null?'':'NULL'))));if(preg_match("~time~",$l["type"])&&preg_match('~^CURRENT_TIMESTAMP~i',$X)){$X="";$q="now";}input($l,$X,$q);echo"\n";}if(!support("table"))echo"<tr>"."<th><input name='field_keys[]'>".script("qsl('input').oninput = fieldChange;")."<td class='function'>".html_select("field_funs[]",$b->editFunctions(array("null"=>isset($_GET["select"]))))."<td><input name='field_vals[]'>"."\n";echo"</table>\n";}echo"<p>\n";if($m){echo"<input type='submit' value='".'Save'."'>\n";if(!isset($_GET["select"])){echo"<input type='submit' name='insert' value='".($Ve?'Save and continue edit':'Save and insert next')."' title='Ctrl+Shift+Enter'>\n",($Ve?script("qsl('input').onclick = function () { return !ajaxForm(this.form, '".'Saving'."‚Ä¶', this); };"):"");}}echo($Ve?"<input type='submit' name='delete' value='".'Delete'."'>".confirm()."\n":($_POST||!$m?"":script("focus(qsa('td', qs('#form'))[1].firstChild);")));if(isset($_GET["select"]))hidden_fields(array("check"=>(array)$_POST["check"],"clone"=>$_POST["clone"],"all"=>$_POST["all"]));echo'<input type="hidden" name="referer" value="',h(isset($_POST["referer"])?$_POST["referer"]:$_SERVER["HTTP_REFERER"]),'">
<input type="hidden" name="save" value="1">
<input type="hidden" name="token" value="',$Fe,'">
</form>
';}if(isset($_GET["file"])){if($_SERVER["HTTP_IF_MODIFIED_SINCE"]){header("HTTP/1.1 304 Not Modified");exit;}header("Expires: ".gmdate("D, d M Y H:i:s",time()+365*24*60*60)." GMT");header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");header("Cache-Control: immutable");if($_GET["file"]=="favicon.ico"){header("Content-Type: image/x-icon");echo
lzw_decompress("\0\0\0` \0Ñ\0\n @\0¥CÑË\"\0`E„Q∏‡ˇá?¿tvM'îJd¡d\\åb0\0ƒ\"ô¿f”à§Ós5õœÁ—AùXPaJì0Ñ•ë8Ñ#RäT©ëz`à#.©«cÌX√˛»Ä?¿-\0°Im?†.´M∂Ä\0»Ø(Ãâ˝¿/(%å\0");}elseif($_GET["file"]=="default.css"){header("Content-Type: text/css; charset=utf-8");echo
lzw_decompress("\n1ÃáìŸåﬁl7úáB1Ñ4vb0òÕfsëºÍn2BÃ—±Ÿòﬁn:á#(ºb.\rDc)»»a7EÑë§¬l¶√±îËi1Ãésò¥Á-4ôáf”	»Œi7Ü≥π§»t4Ö¶”yËZf4ù∞iñAT´VVêÈf:œ¶,:1¶Q›ºÒb2`«#˛>:7GÔó1—ÿ“s∞ôLóXD*bv<‹å#£e@÷:4Áß!foê∑∆t:<•‹Âíæôo‚‹\ni√≈',Èªa_§:πiÔÖ¥¡Bv¯|N˚4.5NfÅi¢vp–h∏∞l®Í°÷ö‹O¶ÅâÓ= £OFQ–ƒk\$•”iıô¿¬d2T„°p‡ 6Ñã˛á°-ÿZÄéÉ†ﬁ6Ω£Äh:¨aÃ,é£ÎÓ2ç#8–ê±#íò6n‚ÓÜÒJà¢h´tÖå±ä‰4O42ÙΩokﬁæ*r†©Ä@p@Ü!ƒæœ√Ù˛?–6¿âr[çL¡ã:2Bàjß!HbÛ√P‰=!1Vâ\"à≤0Öø\nS∆∆œD7√ÏD⁄õ√C!Ü!õ‡¶G åß »+í=tCÊ©.C§¿:+» =™™∫≤°±Â%™cÌ1MR/îE»í4Ñ©†2∞‰±†„`¬8(·”π[W‰—=âySÅb∞=÷-‹πBS+…Ø»‹˝•¯@pL4Yd„Ñqä¯„¶Í¢6£3ƒ¨Ø∏Ac‹åËŒ®åkÇ[&>ˆï®Z¡pkm]óu-c:ÿ∏àNtÊŒ¥p“ùåä8Ë=ø#ò·[.‹ﬁØç~†çÅmÀyáPP·|I÷õ˘¿ÏQ™9v[ñQïÑ\nñŸrÙ'gá+ê·T—2Ö≠V¡ız‰4ç£8˜è(	æEy*#j¨2]≠ïR“¡ë•)É¿[N≠R\$ä<>:Û≠>\$;ñ>†Ã\rªÑŒHÕ√T»\nw°N Âwÿ£¶Ï<ÔÀGw‡ˆˆπ\\YÛ_†Rt^å>é\r}åŸS\rzÈ4=µ\nLî%J„ã\",Z†8∏ûôêi˜0u©?®˚—Ù°s3#®Ÿâ†:Û¶˚ç„Ωñ»ﬁE]x›“Ås^8é£K^…˜*0—ﬁwﬁ‡»ﬁ~è„ˆ:Ì—iÿ˛èv2wΩˇ±˚^7ê„Ú7£c›—u+U%é{P‹*4ÃºÈLX./!ºâ1C≈ﬂqx!Hπ„Fd˘≠L®§®ƒ†œ`6ÎË5ÆôfÄ∏ƒÜ®=H¯l åV1ìõ\0a2◊;Å‘6Ü‡ˆ˛_Ÿáƒ\0&ÙZ‹S†d)KE'íÄnµê[X©≥\0Z…ä‘F[Pëﬁò@‡ﬂ!âÒY¬,`…\"⁄∑Å¬0Ee9yF>À‘9b∫ñåÊF5:¸àî\0}ƒ¥äá(\$û”áÎÄ37Hˆ£Ë MæA∞≤6Rï˙{Mq›7G†⁄CôCÍm2¢(åCt>[Ï-t¿/&Cõ]ÍetGÙÃ¨4@r>«¬Â<öSqï/Â˙îQÎçhmçö¿–∆Ù„ÙùL¿‹#ËÙKÀ|ÆôÑ6fKP›\r%t‘”V=\"†SH\$ù} ∏Å)w°,W\0F≥™u@ÿb¶9Ç\rr∞2√#¨DåîXÉ≥⁄yOI˘>ªÖnÅÜ«¢%„˘ê'ã›_¡Ät\rœÑzƒ\\1òhlº]Q5Mp6kÜ–ƒqh√\$£H~Õ|“›!*4åÒÚ€`SÎ˝≤S tÌPP\\g±Ë7á\n-ä:Ë¢™p¥ïîàlãBû¶Óî7”®cÉ(wO0\\:ï–wî¡ùp4àìÚ{T⁄˙jO§6H√ä∂r’•êq\n¶…%%∂y']\$ÇîaëZ”.fc’q*-ÍFW∫˙kçÑzÉ∞µjëé∞lg·å:á\$\"ﬁNº\r#…d‚√Ç¬ˇ–sc·¨Ã†ÑÉ\"j™\r¿∂ñ¶à’íºPhã1/ÇúDA)†≤›[¿kn¡p76¡Y¥âR{·M§P˚∞Ú@\n-∏a∑6˛ﬂ[ªzJH,ñdl†B£hêo≥çÏÚ¨+á#Dr^µ^µŸeöºEΩΩñ ƒúaPâÙıJG£z‡ÒtÒ†2«XŸ¢¥¡øV∂◊ﬂ‡ﬁ»≥â—B_%K=E©∏bÂºæﬂ¬ßkU(.!‹Æ8∏ú¸…I.@éKÕxn˛¨¸:√PÛ32´îmÌH		C*Ï:v‚T≈\nRπÉïµã0u¬ÌÉÊÓ“ß]ŒØòäîP/µJQd•{Lñﬁ≥:Y¡è2bºúT Òù 3”4Üó‰cÍ•V=êøÜL4Œ–rƒ!ﬂBY≥6Õ≠MeLä™‹Áúˆ˘i¿o–9< Gî§∆ï–ôMhm^ØU€N¿å∑ÚTr5HiMî/¨nÉÌù≥T†ç[-<__Ó3/Xr(<áØäÜÆ…ÙìÃu“ñGNX20Â\r\$^áç:'9Ë∂OÖÌ;◊kèºÜµf†ñN'a∂î«≠b≈,ÀV§ÙÖ´1µÔHI!%6@˙œ\$“EG⁄ú¨1ù(mU™ÂÖr’ΩÔﬂÂ`°–iN+√úÒ)öú‰0lÿ“f0√Ω[U‚¯V Ë-:I^†ò\$ÿs´b\reáëug…h™~9€ﬂàùbòµÙ¬»f‰+0¨‘ hXr›¨©!\$óe,±w+Ñ˜åÎå3ÜÃ_‚AÖkö˘\nk√rı õcuWdYˇ\\◊={.Ûƒçòê¢gªâp8út\rRZøvçJ:≤>˛£Y|+≈@¿áÉ€Cêt\rÄÅjtÅΩ6≤%¬?‡Ù«éÒí>˘/•Õ«Œ9F`◊ï‰Úv~K§ê·ˆ—R–WãzëÍlm™wL«9Yï*q¨xƒzÒËSeÆ›õ≥Ë˜£~öD‡Õ·ñ˜ùxòæÎ…üi7ï2ƒ¯—O›ªí˚_{Ò˙53‚˙têòõ_üız‘3˘d)ãCØ¬\$?K”™PÅ%œœT&˛ò&\0P◊NAé^≠~¢É†p∆ ˆœúì‘ı\r\$ﬁÔ–÷Ïb*+D6Í∂¶œàﬁÌJ\$(»olﬁÕh&îÏKBS>∏ãˆ;z∂¶x≈oz>Ìú⁄oƒZ\n ã[œvıÇÀ»úµ∞2ıOxŸêV¯0f˚Ä˙Øﬁ2Bl…bk–6ZkµhXcdÍ0*¬KT‚ØH=≠ïœÄëp0älVÈıË‚\rºå•ném¶Ô)(è ˙");}elseif($_GET["file"]=="functions.js"){header("Content-Type: text/javascript; charset=utf-8");echo
lzw_decompress("f:õågCIº‹\n8ú≈3)∞À7úÖÜ81– x:\nOg#)–Ír7\n\"ÜË¥`¯|2ÃgSiñH)N¶Së‰ß\ráù\"0πƒ@‰)ü`(\$s6O!”ËúV/=ùå' T4Ê=ÑòiSòç6IO†G#“X∑VCç∆s°†Z1.–hp8,≥[¶H‰µ~Czß…Â2πlæc3öÕÈs£ëŸIÜb‚4\nÈF8T‡ÜIò›©U*fzπ‰r0ûE∆Å¿ÿyé∏ÒféY.:ÊÉIå (ÿc∑·Œã!ç_lôÌ^∑^(∂öN{Sñì)rÀq¡YìñlŸ¶3ä3⁄\nò+G•”Íy∫ÌÜÀi∂¬ÓxV3w≥uh„^rÿ¿∫¥a€î˙πçcÿË\rì®Î(.¬à∫ÅCh“<\r)Ë—£°`Ê7£ÌÚ43'm5å£»\nÅP‹:2£Pª™éãq Úˇ≈Cì}ƒ´à˙ ¡Í38ãBÿ0éhRâ»r(ú0•°b\\0åHr44å¡Bç!°p«\$érZZÀ2‹â.…É(\\é5√|\nC(Œ\"èÄPÖ¯.ç–NÃRT Œì¿Ê>ÅHNÖÅ8HP·\\¨7Jp~Ñ‹˚2%°–OC®1„.ÉßC8ŒáH»Ú*àj∞Ö·˜S(π/°Ï¨6KUú á°<2âpOIÑÙ’`ç‘‰‚≥àdOÅH†ﬁ5ç-¸∆4å„pX25-“¢Ú€à∞z7£∏\"(∞P†\\32:]U⁄ËÌ‚ﬂÖ!]∏<∑A€€§í–ﬂi⁄∞ãl\r‘\0v≤Œ#J8´œwmûÌ…§®<ä…†Ê¸%m;p#„`XùDå¯˜iZç¯N0åêï»9¯®Âç†¡Ë`ÖéwJçDøæ2“9tå¢*¯ŒyÏÀNiIh\\9∆’Ë–:ÉÄÊ·xÔ≠µyl*ö»àŒÊY†‹á¯Í8íW≥‚?µéÅﬁõ3Ÿ !\"6Âõn[¨ \r≠*\$∂∆ßænzx∆9\rÏ|*3◊£pﬁÔª∂û:(p\\;‘Àmz¢¸ß9Û–—¬å¸8NÖ¡êj2çΩ´Œ\r…HÓH&å≤(√zÑ¡7i€k£ ãä§Çc§ãeÚû˝ßtúÃÃ2:SHÛ»†√/)ñxﬁ@ÈÂtâri9•ΩıÎú8œ¿ÀÔy“∑Ω∞éVƒ+^W⁄¶≠¨kZÊYól∑ £ÅÅå4÷»∆ã™∂¿¨Ç\\E»{Ó7\0πpÜÄïDÄÑiî-TÊ˛⁄˚0l∞%=¡†–ÀÉ9(Ñ5\n\nÄn,4á\0Ëa}‹É.∞ˆRsÔÇ™\02B\\€b1üS±\0003,‘XPHJspÂdìKÉ CA!∞2*Wü‘Ò⁄2\$‰+¬f^\nÑ1åÅ¥ÚzEÉ Iv§\\‰ú2…†.*A∞ôîE(d±·∞√bÍ¬‹Ñê∆9áÇ‚Ä¡Dhê&≠™?ƒH∞sèQò2íx~n√ÅJãT2˘&„‡eRúΩôG“QéêTwÍ›ëªıPà‚„\\†)6¶Ù‚ú¬Úsh\\3®\0R	¿'\r+*;RH‡.ì!—[Õ'~≠%t< Áp‹K#¬ëÊ!ÒlﬂÃLeå≥úŸ,ƒ¿Æ&·\$	¡Ω`îñCXöâ”Ü0÷≠Âº˚≥ƒ:MÈh	Á⁄úG‰—!&3†DÅ<!Ëê23Ñ√?h§J©e ⁄h·\r°mïòNi∏£¥éíÜ NÿHl7°ÆvÇÍWIÂ.¥¡-”5÷ßeyè\rEJ\ni*º\$@⁄RU0,\$UøEÜ¶‘‘¬™u)@(tŒSJk·p!Ä~≠Ç‡d`Ã>Øï\n√;#\rp9Üj…π‹]&Nc(rÄàïTQU™ΩS∑⁄\08n`´óyïb§≈ûL‹O5ÇÓ,§Úûë>éÇÜx‚‚±f‰¥í‚ÿê+Åñ\"—IÄ{kM»[\r%∆[	§eÙa‘1! ËˇÌ≥‘Æ©F@´b)Rü£72àÓ0°\nW®ô±L≤‹ú“Ætd’+ÅÌ‹0wgl¯0n@ÚÍ…¢’iÌM´É\nAßM5nÏ\$E≥◊±N€·l©›ü◊Ï%™1 A‹˚∫˙˜›kÒrÓiFB˜œ˘ol,muNx-Õ_†÷§C( ÅêfÈl\r1p[9x(i¥B“ñ≤€zQl¸∫8C‘	¥©XU Tb£›I›`ïp+V\0Óã—;ãCbŒ¿XÒ+œíçsÔ¸]H˜“[·kãx¨G*ÙÜè]∑awn˙!≈6ÇÚ‚€–mSÌæìIﬁÕKÀ~/ù”•7ﬁ˘eeN…Úç™S´/;dÂAÜ>}l~ûœÍ ®%^¥fÁÿ¢p⁄úDEÓ√a∑Çt\nx=√k–éÑ*d∫ÍTó∫¸˚j2ü…júù\në†… ,òe=ëÜM84Ù˚‘aïj@ÓT√sè‘‰nf©›\nÓ6™\rdúº0ﬁÌÙYä'%‘ìÌﬁ~	Å“®Ü<÷ÀñAÓãñHøGÇÅ8ÒøùŒÉ\$z´{∂ª≤u2*Ü‡añ¿>ª(wåK.bPÇ{ÖÉo˝î¬¥´zµ#Î2ˆ8=…8>™§≥A,∞e∞¿Ö+ÏCËßxı*√·“-b=máôü,ãaí√lzkùÅÔ\$Wı,êmèJiÊ ß·˜Å+ãË˝0∞[Øˇ.R sK˘«‰XÁ›ZLÀÁ2ê`Ã(ÔC‡vZ°‹›¿∂Ë\$Å◊π,ÂD?H±÷NxXÙÛ)íÓéM®â\$Û,çÕ*\n—£\$<qˇ≈üh!øπSì‚É¿üxsA!ò:¥K•¡}¡≤ì˘¨£úR˛öA2k∑Xép\n<˜˛¶˝ÎlÏßŸ3Ø¯¶»ïVV¨}£g&Y›ç!Ü+Û;<∏Y«ÛüYE3r≥ŸéÒõCÌo5¶≈˘¢’≥œkk˛Ö¯∞÷€£´œt˜íU¯Ö≠)˚[˝ﬂ¡Ó}Ôÿu¥´lÁ¢:Dü¯+œè _o„‰h140÷· 0¯Øb‰Kò„¨í†ˆ˛ÈªlG™Ñ#™ö©ÍéÜ¶©Ï|UdÊ∂IK´Í¬7‡^Ï‡∏@∫ÆO\0H≈Hiä6\rá€©‹\\cg\0ˆ„Î2éBƒ*e‡ê\nÄö	Özrê!ênWz&ê {Hñ'\$X †w@“8ÎDGr*Îƒ›HÂ'p#éƒÆÄ¶‘\nd¸Ä˜,Ù•ó,¸;g~Ø\0–#ÄÃé≤Eè¬\r÷I`úÓ'É%E“.†]` –õÖÓ%&–Óm∞˝\r‚ﬁ%4SÑv#\n†ûfH\$%Î-¬#≠∆—qB‚ÌÊ†¿¬Q-Ùc2äßÇ&¬¿Ã]‡ô Ëqh\rÒl]‡Æs†–—h‰7±n#±ÇÇ⁄-‡jEØFrÁ§l&d¿ÿŸÂzÏF6∏êà¡\"†ûì|øß¢s@ﬂ±ÆÂz)0rp⁄è\0ÇX\0§ŸË|DL<!∞ÙoÑ*áD∂{.B<E™ãã0nB(Ô é|\r\nÏ^©ç‡ç h≥!Ç÷Ír\$ßí(^™~èËﬁ¬/pèq≤ÃB®≈Oöà˙,\\µ®#RRŒè%Î‰Õd–Hjƒ`¬†ÙÆÃ≠ VÂ bSídßiéEÇ¯Ôoh¥r<i/k\$-ü\$oîº+∆≈ãŒ˙l“ﬁO≥&ev∆íºi“jMPA'u'éŒí( M(h/+´ÚWDæSo∑.n∑.n∏ÏÍ(ú(\"≠¿ßhˆ&pÜ®/À/1DÃäÁjÂ®∏EËﬁ&‚¶Äè,'l\$/.,ƒd®ÖÇWÄbbO3ÛB≥sH†:J`!ì.Ä™Çá¿˚•†è,F¿—7(á»‘ø≥˚1älÂs ÷“éë≤ó≈¢q¢X\r¿öÆÉ~RÈ∞±`Æ“ûÛÆY*‰:R®˘rJ¥∑%Lœ+n∏\"à¯\r¶ŒÕáH!qbæ2‚Li±%”ﬁŒ®Wj#9”‘ObE.I:Ö6¡7\0À6+§%∞.»Öﬁ≥a7E8VSÂ?(DG®”≥BÎ%;Ú¨˘‘/<í¥˙•¿\r Ï¥>˚M¿∞@∂æÄH†Ds–∞Z[tH£Enx(å©R†xÒè˚@Ø˛GkjWî>Ã¬⁄#T/8Æc8ÈQ0ÀË_‘IIGIIí!•äYEdÀE¥^ètdÈth¬`DV!CÊ8é•\r≠¥übì3©!3‚@Ÿ33N}‚ZBÛ3	œ3‰30⁄‹M(Í>Ç }‰\\—tÍÇf†fåÀ‚I\rÆÄÛ337 X‘\"tdŒ,\nbtNO`P‚;≠‹ï“≠¿‘Ø\$\nÇûﬂ‰Z—≠5U5WUµ^ho˝‡ÊtŸPM/5K4Ej≥KQ&53GXìXx)“<5DÖè\r˚VÙ\nﬂr¢5b‹Ä\\J\">ßË1S\r[-¶ Du¿\r“‚ß√)00ÛYı»À¢∑k{\nµƒ#µﬁ\r≥^∑ã|Ëu‹ªUÂ_nÔU4…Uä~Yt”\rIö√@‰è≥ôR Û3:“uePMSË0TµwWØX»ÚÚD®Ú§KOU‹‡ïá;Uı\n†OYçÈYÕQ,M[\0˜_™DöÕ»W†æJ*Ï\rg(]‡®\r\"ZCâ©6uÍè+µYÛàY6√¥ê0™qı(ŸÛ8}êÛ3AX3T†h9j∂j‡fıMtÂPJbqMP5>è»¯∂©Yák%&\\Ç1d¢ÿE4¿ µYnê Ì\$<•U]”â1âmb÷∂ê^“ıö†Í\"NVÈﬂp∂Îpı±eM⁄ﬁ◊WÈ‹¢Ó\\‰)\n À\nf7\n◊2¥ır8ãó=Ek7tVöáµû7P¶∂L…Ìa6ÚÚv@'Ç6i‡Ôj&>±‚;≠„`“ˇa	\0p⁄®(µJ—Î)´\\ø™n˚Úƒ¨m\0º®2ÄÙeqJˆ≠PçÙtåÎ±fj¸¬\"[\0®∑Ü¢X,<\\åÓ∂◊‚˜Ê∑+mdÜÂ~‚‡öÖ—s%o∞¥mn◊),◊ÑÊ‘á≤\r4∂¬8\r±Œ∏◊mEÇH]Ç¶ò¸÷HW≠M0DÔﬂÄóÂ~èÀÅòKòÓE}¯∏¥‡|fÿ^ì‹◊\r>‘-z]2sÇxDòd[sátéS¢∂\0Qf-K`≠¢Çt‡ÿÑwTØ9ÄÊZÄ‡	¯\nB£9 Nbñ„<⁄B˛I5o◊oJÒp¿œJNdÂÀ\rçhﬁç√ê2ê\"‡xÊHC‡›çñ:ç¯˝9Yn16∆Ùzr+z±˘˛\\í˜ïúÙm ﬁ±T ˆÚ†˜@Y2lQ<2O+•%ìÕ.”Éh˘0AﬁÒ∏ä√Zãè2R¶¿1£ä/ØhH\r®XÖ»aNB&ß ƒM@÷[xåá Æ•Íñ‚8&L⁄VÕúv‡±*öj§€öGHÂ»\\ŸÆ	ô≤∂&s€\0Qö†\\\"Ëb†∞	‡ƒ\rBsõ…wùÇ	ùŸ·ûBN`ö7ßCo(Ÿ√‡®\n√®ùì®1ö9Ã*Eò ÒSÖ”Uê0U∫ tö'|îmô∞ﬁ?h[¢\$.#…5	 Â	pÑ‡yB‡@RÙ]£ÖÍ@|Ñß{ô¿ P\0xÙ/¶ w¢%§EsBdøßöCUö~O◊∑‡P‡@X‚]‘Öç®Z3®•1¶•{©eLYâ°å⁄ê¢\\í(*R`†	‡¶\nÖä‡é∫ÃQCF»*éππê‡Èú¨⁄pÜX|`N®Çæ\$Ä[Üâí@ÕU¢‡¶∂‡Z•`Zd\"\\\"ÖÇ¢£)´áIà:ËtöÏoDÊ\0[≤®‡±Ç-©ì†gÌ≥âôÆ*`hu%£,Äî¨„Iµ7ƒ´≤HÛµm§6ﬁ}Æ∫N÷Õ≥\$ªMµUYf&1˘é¿õe]pz•ß⁄I§≈m∂G/£ ∫w ‹!ï\\#5•4I•dπE¬hqÄÂ¶˜—¨kÁx|⁄k•qDöbÖz?ß∫â>˙Éæ:Üì[ËL“∆¨Z∞XöÆ:ûπÑ∑⁄ç«jﬂw5	∂YÅæ0 ©¬ì≠Ø\$\0C¢ÜdSg∏ÎÇ†{ù@î\n`û	¿√¸C ¢∑ªM∫µ‚ª≤# t}xŒNÑ˜∫á{∫€∞)Í˚CÉ FKZﬁjô¬\0PFYîB‰pFkñõ0<⁄> D<JEôög\rı.ì2ñ¸8ÈU@*Œ5fk™ÃJDÏ»…4çïTDU76…/¥ËØ@∑ÇK+Ñ√ˆJÆ∫√¬Ì@”=å‹WIOD≥85MöçN∫\$RÙ\0¯5®\r‡˘_™úÏEúÒœI´œ≥NÁl£“Ây\\Ùëà«qUÄ–Q˚†™\n@í®Ä€∫√pö¨®P€±´7‘ΩN\r˝R{*çqm›\$\0Rî◊‘ìä≈Âq–√à+U@ﬁB§ÁOf*ÜCÀ¨∫MCé‰`_ Ë¸ÚΩÀµNÍÊT‚5Ÿ¶C◊ª© ∏‡\\W√e&_Xå_ÿçhÂó¬∆Bú3¿å€%‹FW£˚Å|ôGﬁõ'≈[Ø≈Ç¿∞Ÿ’V†–#^\rÁ¶GRÄæòÄP±›FgÅ¢˚ÓØ¿Yi ˚•«z\n‚®ﬁ+ﬂ^/ì®ÄÇº•Ω\\ï6Ëﬂbºdmh◊‚@qÌç’Ah÷),J≠◊Wñ«cm˜em]é”èeœkZb0ﬂÂ˛ûÅYÒ]ymäËáfÿeπB;π”ÍO…¿wüapDW˚å…‹”{õ\0ò¿-2/bN¨s÷ΩﬁæRaìœÆh&qt\n\"’iˆRm¸hzœe¯Ü‡‹FS7µ–PPÚ‰ñ§‚‹:Bßà‚’sm∂≠Y d¸ﬁÚ7}3?*Çt˙ÚÈœlT⁄}ò~ÄÑèÄ‰=cû˝¨÷ﬁ«	û⁄3Ö;T≤Lﬁ5*	Ò~#µAïæÉëséx-7˜éf5`ÿ#\"N”b˜ØGòüãı@‹e¸[Ô¯Å§ÃsëòÄ∏-ßòM6ß£qqö hÄe5Ö\0“¢¿±˙*‡b¯IS‹…‹FŒÆ9}˝p”-¯˝`{˝±…ñkPò0T<Ñ©Z9‰0<’ö\r≠Ä;!√àg∫\r\nK‘\nïá\0¡∞*Ω\nb7(¿_∏@,Óe2\r¿]ñKÖ+\0…ˇp C\\—¢,0¨^ÓM–ßö∫©ì@ä;X\rï?\$\rájí+ˆ/¥¨BˆÊP†Ωâ˘®J{\"aÕ6ò‰âúπ|Â£\n\0ª‡\\5ìÅ–	156ˇÜ .›[¬UÿØ\0dË≤8YÁ:!—≤ë=∫¿X.≤uC™äåˆ!S∫∏áoÖp”B›¸€7∏≠≈Ø°Rh≠\\hãE=˙y:< :u≥Û2µ80ìsi¶üTsB€@\$ ÕÈ@«u	»Q∫ê¶.ÙÇT0M\\/ÍÄd+∆É\në°=‘∞då≈ÎA¢∏¢)\r@@¬h3ÄñŸ8.eZa|.‚7ùYk–c¿òÒñ'D#á®YÚ@Xçqñ=M°Ô44öB AM§ØdU\"ãHw4Ó(>Ç¨8®≤√C∏?e_`–≈X:ƒA9√∏ôÅÙp´G–‰áGy6Ω√FìXrâ°l˜1°ΩÿªêB¢√Ö9Rz©ıhBÑ{çûÄô\0ÎÂ^Ç√-‚0©%Dú5F\"\"‡⁄‹ ¬ô˙iƒ`ÀŸnAf® \"tDZ\"_‡V\$ü™!/ÖDÄ·öÜøµã¥àŸ¶°ÃÄF,25…jõTÎ·óy\0ÖNºx\rÁYl¶è#ë∆Eq\nÕ»B2ú\nÏ‡6∑Öƒ4”◊î!/¬\nÛÉâQ∏Ω*Æ;)bR∏Z0\0ƒCDoåÀûé48¿ï¥µá–eë\n„¶S%\\˙PIkêá(0¡åu/ôãG≤∆πäåº\\À}†4FpëûG˚_˜G?)g»otÅ∫[vû÷\0∞∏?b¿;™À`(ï€å‡∂NS)\n„x=Ë–+@Í‹7Éèj˙0èó,1√Özôì≠ç>0àâGc„LÖVXÙÉ±€ %¿Ö¡ÑQ+¯éÈo∆Fı»È‹∂–>Q-„cë⁄«lâ°≥§w‡Ãz5GëÍÇ@(hëc”Hı«r?àöNb˛@…®ˆ«¯∞Ólx3ãU`Ñrw™©‘U√‘Ùtÿ8‘=¿l#Úıèlˇ‰®â8•E\"åÉòôO6\nò¬1e£`\\hKfóV/–∑PaYKÁOÃ˝ Èè‡xë	âOjÑÛèr7•F;¥ÍÅBªëÍ£ÌÃíáº>Ê–¶≤V\rƒñƒ|©'Jµz´ºöî#íPB‰íY5\0NC§^\n~LrRí‘[ÃüR√¨Òg¿eZ\0xõ^ªi<Q„/)”%@ êíôfB≤Hf {%P‡\"\"Ωç¯@™˛ç)ÚíëìDE(iM2ÇSí*ÉyÚS¡\"‚Ò eÃí1å´◊ò\n4` ©>¶èQ*¶‹y∞nîíû•T‰u‘ù‚‰î—~%Å+WÅ≤XKãå£Q°[ îû‡lêPYy#DŸ¨D<´FL˙≥’@¡6']∆ãá˚\rFƒ`±!ï%\nè0êc–Ù¿À©%c8WrpGÉ.TúDoæUL2ÿ*È|\$¨:ËúròΩ@ÊÒË&“4ÅãHä> ë†ç%0*åZc(@‹]ÛÃQ:*¨ì‚(&\"xç'JO≥1π∫`>7	#Ÿ\"O4PX¸±î|B4ªÈâ[ òÈŸò\$nÔà1`ÙÍGSAı÷ÀAHª†\"Ü)‡©„S®˚fî…¶¡∫-\"ÀW˙+…ñ∫\0s-[îfoŸßÕD´êxÛÊ∏ıæ=Cö.ıì9≥≠ŒfÔ¿c¡\0¬ã7°?√ì95¥÷¶Z«0≠ÓfÏ≠®‡¯ÎH?R'q>o⁄ @aDüç˘G[;G¥DπBBdƒ°óq†ñ•2§|1πÏqô≤‰¿ŒÂ≤w<‹#™ßEYΩ^öß†≠Q\\Î[XÒÂË≈>?vÔû[ áÊäI…Õ— ÑôúÃg\0«)¥ÖÆgÖuå–g42j√∫'ÛéT‰êÑãÕvy,uí€DÜ=péH\\Éî^b‰ÏqÿÑƒit√≈XÖ¿£FP…@P˙•Täæi2#∞gÄóD·ÆôÒ%9ô@Ç");}elseif($_GET["file"]=="jush.js"){header("Content-Type: text/javascript; charset=utf-8");echo
lzw_decompress('');}else{header("Content-Type: image/gif");switch($_GET["file"]){case"plus.gif":echo'';break;case"cross.gif":echo'';break;case"up.gif":echo'';break;case"down.gif":echo'';break;case"arrow.gif":echo'';break;}}exit;}if($_GET["script"]=="version"){$p=file_open_lock(get_temp_dir()."/adminer.version");if($p)file_write_unlock($p,serialize(array("signature"=>$_POST["signature"],"version"=>$_POST["version"])));exit;}global$b,$f,$i,$mb,$pb,$xb,$k,$Ub,$Xb,$aa,$oc,$x,$ba,$zc,$ad,$pd,$le,$bc,$Fe,$Je,$Ne,$Ue,$ca;if(!$_SERVER["REQUEST_URI"])$_SERVER["REQUEST_URI"]=$_SERVER["ORIG_PATH_INFO"];if(!strpos($_SERVER["REQUEST_URI"],'?')&&$_SERVER["QUERY_STRING"]!="")$_SERVER["REQUEST_URI"].="?$_SERVER[QUERY_STRING]";if($_SERVER["HTTP_X_FORWARDED_PREFIX"])$_SERVER["REQUEST_URI"]=$_SERVER["HTTP_X_FORWARDED_PREFIX"].$_SERVER["REQUEST_URI"];$aa=($_SERVER["HTTPS"]&&strcasecmp($_SERVER["HTTPS"],"off"))||ini_bool("session.cookie_secure");@ini_set("session.use_trans_sid",false);if(!defined("SID")){session_cache_limiter("");session_name("adminer_sid");$nd=array(0,preg_replace('~\?.*~','',$_SERVER["REQUEST_URI"]),"",$aa);if(version_compare(PHP_VERSION,'5.2.0')>=0)$nd[]=true;call_user_func_array('session_set_cookie_params',$nd);session_start();}remove_slashes(array(&$_GET,&$_POST,&$_COOKIE),$Jb);if(get_magic_quotes_runtime())set_magic_quotes_runtime(false);@set_time_limit(0);@ini_set("zend.ze1_compatibility_mode",false);@ini_set("precision",15);function
get_lang(){return'en';}function
lang($Ie,$Xc=null){if(is_array($Ie)){$sd=($Xc==1?0:1);$Ie=$Ie[$sd];}$Ie=str_replace("%d","%s",$Ie);$Xc=format_number($Xc);return
sprintf($Ie,$Xc);}if(extension_loaded('pdo')){class
Min_PDO
extends
PDO{var$_result,$server_info,$affected_rows,$errno,$error;function
__construct(){global$b;$sd=array_search("SQL",$b->operators);if($sd!==false)unset($b->operators[$sd]);}function
dsn($nb,$U,$I,$F=array()){try{parent::__construct($nb,$U,$I,$F);}catch(Exception$_b){auth_error(h($_b->getMessage()));}$this->setAttribute(13,array('Min_PDOStatement'));$this->server_info=@$this->getAttribute(4);}function
query($K,$Oe=false){$L=parent::query($K);$this->error="";if(!$L){list(,$this->errno,$this->error)=$this->errorInfo();if(!$this->error)$this->error='Unknown error.';return
false;}$this->store_result($L);return$L;}function
multi_query($K){return$this->_result=$this->query($K);}function
store_result($L=null){if(!$L){$L=$this->_result;if(!$L)return
false;}if($L->columnCount()){$L->num_rows=$L->rowCount();return$L;}$this->affected_rows=$L->rowCount();return
true;}function
next_result(){if(!$this->_result)return
false;$this->_result->_offset=0;return@$this->_result->nextRowset();}function
result($K,$l=0){$L=$this->query($K);if(!$L)return
false;$N=$L->fetch();return$N[$l];}}class
Min_PDOStatement
extends
PDOStatement{var$_offset=0,$num_rows;function
fetch_assoc(){return$this->fetch(2);}function
fetch_row(){return$this->fetch(3);}function
fetch_field(){$N=(object)$this->getColumnMeta($this->_offset++);$N->orgtable=$N->table;$N->orgname=$N->name;$N->charsetnr=(in_array("blob",(array)$N->flags)?63:0);return$N;}}}$mb=array();class
Min_SQL{var$_conn;function
__construct($f){$this->_conn=$f;}function
select($S,$P,$Z,$r,$G=array(),$z=1,$H=0,$wd=false){global$b,$x;$w=(count($r)<count($P));$K=$b->selectQueryBuild($P,$Z,$r,$G,$z,$H);if(!$K)$K="SELECT".limit(($_GET["page"]!="last"&&$z!=""&&$r&&$w&&$x=="sql"?"SQL_CALC_FOUND_ROWS ":"").implode(", ",$P)."\nFROM ".table($S),($Z?"\nWHERE ".implode(" AND ",$Z):"").($r&&$w?"\nGROUP BY ".implode(", ",$r):"").($G?"\nORDER BY ".implode(", ",$G):""),($z!=""?+$z:null),($H?$z*$H:0),"\n");$he=microtime(true);$M=$this->_conn->query($K);if($wd)echo$b->selectQuery($K,$he,!$M);return$M;}function
delete($S,$Bd,$z=0){$K="FROM ".table($S);return
queries("DELETE".($z?limit1($S,$K,$Bd):" $K$Bd"));}function
update($S,$R,$Bd,$z=0,$Vd="\n"){$af=array();foreach($R
as$y=>$W)$af[]="$y = $W";$K=table($S)." SET$Vd".implode(",$Vd",$af);return
queries("UPDATE".($z?limit1($S,$K,$Bd,$Vd):" $K$Bd"));}function
insert($S,$R){return
queries("INSERT INTO ".table($S).($R?" (".implode(", ",array_keys($R)).")\nVALUES (".implode(", ",$R).")":" DEFAULT VALUES"));}function
insertUpdate($S,$O,$vd){return
false;}function
begin(){return
queries("BEGIN");}function
commit(){return
queries("COMMIT");}function
rollback(){return
queries("ROLLBACK");}function
slowQuery($K,$_e){}function
convertSearch($u,$W,$l){return$u;}function
value($W,$l){return(method_exists($this->_conn,'value')?$this->_conn->value($W,$l):(is_resource($W)?stream_get_contents($W):$W));}function
quoteBinary($Nd){return
q($Nd);}function
warnings(){return'';}function
tableHelp($E){}}$mb=array("server"=>"MySQL")+$mb;if(!defined("DRIVER")){$td=array("MySQLi","MySQL","PDO_MySQL");define("DRIVER","server");if(extension_loaded("mysqli")){class
Min_DB
extends
MySQLi{var$extension="MySQLi";function
__construct(){parent::init();}function
connect($Q="",$U="",$I="",$cb=null,$rd=null,$ce=null){global$b;mysqli_report(MYSQLI_REPORT_OFF);list($gc,$rd)=explode(":",$Q,2);$ge=$b->connectSsl();if($ge)$this->ssl_set($ge['key'],$ge['cert'],$ge['ca'],'','');$M=@$this->real_connect(($Q!=""?$gc:ini_get("mysqli.default_host")),($Q.$U!=""?$U:ini_get("mysqli.default_user")),($Q.$U.$I!=""?$I:ini_get("mysqli.default_pw")),$cb,(is_numeric($rd)?$rd:ini_get("mysqli.default_port")),(!is_numeric($rd)?$rd:$ce),($ge?64:0));$this->options(MYSQLI_OPT_LOCAL_INFILE,false);return$M;}function
set_charset($Da){if(parent::set_charset($Da))return
true;parent::set_charset('utf8');return$this->query("SET NAMES $Da");}function
result($K,$l=0){$L=$this->query($K);if(!$L)return
false;$N=$L->fetch_array();return$N[$l];}function
quote($ke){return"'".$this->escape_string($ke)."'";}}}elseif(extension_loaded("mysql")&&!((ini_bool("sql.safe_mode")||ini_bool("mysql.allow_local_infile"))&&extension_loaded("pdo_mysql"))){class
Min_DB{var$extension="MySQL",$server_info,$affected_rows,$errno,$error,$_link,$_result;function
connect($Q,$U,$I){if(ini_bool("mysql.allow_local_infile")){$this->error=sprintf('Disable %s or enable %s or %s extensions.',"'mysql.allow_local_infile'","MySQLi","PDO_MySQL");return
false;}$this->_link=@mysql_connect(($Q!=""?$Q:ini_get("mysql.default_host")),("$Q$U"!=""?$U:ini_get("mysql.default_user")),("$Q$U$I"!=""?$I:ini_get("mysql.default_password")),true,131072);if($this->_link)$this->server_info=mysql_get_server_info($this->_link);else$this->error=mysql_error();return(bool)$this->_link;}function
set_charset($Da){if(function_exists('mysql_set_charset')){if(mysql_set_charset($Da,$this->_link))return
true;mysql_set_charset('utf8',$this->_link);}return$this->query("SET NAMES $Da");}function
quote($ke){return"'".mysql_real_escape_string($ke,$this->_link)."'";}function
select_db($cb){return
mysql_select_db($cb,$this->_link);}function
query($K,$Oe=false){$L=@($Oe?mysql_unbuffered_query($K,$this->_link):mysql_query($K,$this->_link));$this->error="";if(!$L){$this->errno=mysql_errno($this->_link);$this->error=mysql_error($this->_link);return
false;}if($L===true){$this->affected_rows=mysql_affected_rows($this->_link);$this->info=mysql_info($this->_link);return
true;}return
new
Min_Result($L);}function
multi_query($K){return$this->_result=$this->query($K);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($K,$l=0){$L=$this->query($K);if(!$L||!$L->num_rows)return
false;return
mysql_result($L->_result,0,$l);}}class
Min_Result{var$num_rows,$_result,$_offset=0;function
__construct($L){$this->_result=$L;$this->num_rows=mysql_num_rows($L);}function
fetch_assoc(){return
mysql_fetch_assoc($this->_result);}function
fetch_row(){return
mysql_fetch_row($this->_result);}function
fetch_field(){$M=mysql_fetch_field($this->_result,$this->_offset++);$M->orgtable=$M->table;$M->orgname=$M->name;$M->charsetnr=($M->blob?63:0);return$M;}function
__destruct(){mysql_free_result($this->_result);}}}elseif(extension_loaded("pdo_mysql")){class
Min_DB
extends
Min_PDO{var$extension="PDO_MySQL";function
connect($Q,$U,$I){global$b;$F=array(PDO::MYSQL_ATTR_LOCAL_INFILE=>false);$ge=$b->connectSsl();if($ge){if(!empty($ge['key']))$F[PDO::MYSQL_ATTR_SSL_KEY]=$ge['key'];if(!empty($ge['cert']))$F[PDO::MYSQL_ATTR_SSL_CERT]=$ge['cert'];if(!empty($ge['ca']))$F[PDO::MYSQL_ATTR_SSL_CA]=$ge['ca'];}$this->dsn("mysql:charset=utf8;host=".str_replace(":",";unix_socket=",preg_replace('~:(\d)~',';port=\1',$Q)),$U,$I,$F);return
true;}function
set_charset($Da){$this->query("SET NAMES $Da");}function
select_db($cb){return$this->query("USE ".idf_escape($cb));}function
query($K,$Oe=false){$this->setAttribute(1000,!$Oe);return
parent::query($K,$Oe);}}}class
Min_Driver
extends
Min_SQL{function
insert($S,$R){return($R?parent::insert($S,$R):queries("INSERT INTO ".table($S)." ()\nVALUES ()"));}function
insertUpdate($S,$O,$vd){$e=array_keys(reset($O));$ud="INSERT INTO ".table($S)." (".implode(", ",$e).") VALUES\n";$af=array();foreach($e
as$y)$af[$y]="$y = VALUES($y)";$oe="\nON DUPLICATE KEY UPDATE ".implode(", ",$af);$af=array();$Cc=0;foreach($O
as$R){$X="(".implode(", ",$R).")";if($af&&(strlen($ud)+$Cc+strlen($X)+strlen($oe)>1e6)){if(!queries($ud.implode(",\n",$af).$oe))return
false;$af=array();$Cc=0;}$af[]=$X;$Cc+=strlen($X)+2;}return
queries($ud.implode(",\n",$af).$oe);}function
slowQuery($K,$_e){if(min_version('5.7.8','10.1.2')){if(preg_match('~MariaDB~',$this->_conn->server_info))return"SET STATEMENT max_statement_time=$_e FOR $K";elseif(preg_match('~^(SELECT\b)(.+)~is',$K,$B))return"$B[1] /*+ MAX_EXECUTION_TIME(".($_e*1000).") */ $B[2]";}}function
convertSearch($u,$W,$l){return(preg_match('~char|text|enum|set~',$l["type"])&&!preg_match("~^utf8~",$l["collation"])&&preg_match('~[\x80-\xFF]~',$W['val'])?"CONVERT($u USING ".charset($this->_conn).")":$u);}function
warnings(){$L=$this->_conn->query("SHOW WARNINGS");if($L&&$L->num_rows){ob_start();select($L);return
ob_get_clean();}}function
tableHelp($E){$Hc=preg_match('~MariaDB~',$this->_conn->server_info);if(information_schema(DB))return
strtolower(($Hc?"information-schema-$E-table/":str_replace("_","-",$E)."-table.html"));if(DB=="mysql")return($Hc?"mysql$E-table/":"system-database.html");}}function
idf_escape($u){return"`".str_replace("`","``",$u)."`";}function
table($u){return
idf_escape($u);}function
connect(){global$b,$Ne,$le;$f=new
Min_DB;$Xa=$b->credentials();if($f->connect($Xa[0],$Xa[1],$Xa[2])){$f->set_charset(charset($f));$f->query("SET sql_quote_show_create = 1, autocommit = 1");if(min_version('5.7.8',10.2,$f)){$le['Strings'][]="json";$Ne["json"]=4294967295;}return$f;}$M=$f->error;if(function_exists('iconv')&&!is_utf8($M)&&strlen($Nd=iconv("windows-1250","utf-8",$M))>strlen($M))$M=$Nd;return$M;}function
get_databases($Lb){$M=get_session("dbs");if($M===null){$K=(min_version(5)?"SELECT SCHEMA_NAME FROM information_schema.SCHEMATA ORDER BY SCHEMA_NAME":"SHOW DATABASES");$M=($Lb?slow_query($K):get_vals($K));restart_session();set_session("dbs",$M);stop_session();}return$M;}function
limit($K,$Z,$z,$Yc=0,$Vd=" "){return" $K$Z".($z!==null?$Vd."LIMIT $z".($Yc?" OFFSET $Yc":""):"");}function
limit1($S,$K,$Z,$Vd="\n"){return
limit($K,$Z,1,0,$Vd);}function
db_collation($h,$Na){global$f;$M=null;$Va=$f->result("SHOW CREATE DATABASE ".idf_escape($h),1);if(preg_match('~ COLLATE ([^ ]+)~',$Va,$B))$M=$B[1];elseif(preg_match('~ CHARACTER SET ([^ ]+)~',$Va,$B))$M=$Na[$B[1]][-1];return$M;}function
engines(){$M=array();foreach(get_rows("SHOW ENGINES")as$N){if(preg_match("~YES|DEFAULT~",$N["Support"]))$M[]=$N["Engine"];}return$M;}function
logged_user(){global$f;return$f->result("SELECT USER()");}function
tables_list(){return
get_key_vals(min_version(5)?"SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME":"SHOW TABLES");}function
count_tables($db){$M=array();foreach($db
as$h)$M[$h]=count(get_vals("SHOW TABLES IN ".idf_escape($h)));return$M;}function
table_status($E="",$Fb=false){$M=array();foreach(get_rows($Fb&&min_version(5)?"SELECT TABLE_NAME AS Name, ENGINE AS Engine, TABLE_COMMENT AS Comment FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ".($E!=""?"AND TABLE_NAME = ".q($E):"ORDER BY Name"):"SHOW TABLE STATUS".($E!=""?" LIKE ".q(addcslashes($E,"%_\\")):""))as$N){if($N["Engine"]=="InnoDB")$N["Comment"]=preg_replace('~(?:(.+); )?InnoDB free: .*~','\1',$N["Comment"]);if(!isset($N["Engine"]))$N["Comment"]="";if($E!="")return$N;$M[$N["Name"]]=$N;}return$M;}function
is_view($T){return$T["Engine"]===null;}function
fk_support($T){return
preg_match('~InnoDB|IBMDB2I~i',$T["Engine"])||(preg_match('~NDB~i',$T["Engine"])&&min_version(5.6));}function
fields($S){$M=array();foreach(get_rows("SHOW FULL COLUMNS FROM ".table($S))as$N){preg_match('~^([^( ]+)(?:\((.+)\))?( unsigned)?( zerofill)?$~',$N["Type"],$B);$M[$N["Field"]]=array("field"=>$N["Field"],"full_type"=>$N["Type"],"type"=>$B[1],"length"=>$B[2],"unsigned"=>ltrim($B[3].$B[4]),"default"=>($N["Default"]!=""||preg_match("~char|set~",$B[1])?$N["Default"]:null),"null"=>($N["Null"]=="YES"),"auto_increment"=>($N["Extra"]=="auto_increment"),"on_update"=>(preg_match('~^on update (.+)~i',$N["Extra"],$B)?$B[1]:""),"collation"=>$N["Collation"],"privileges"=>array_flip(preg_split('~, *~',$N["Privileges"])),"comment"=>$N["Comment"],"primary"=>($N["Key"]=="PRI"),"generated"=>preg_match('~^(VIRTUAL|PERSISTENT|STORED)~',$N["Extra"]),);}return$M;}function
indexes($S,$g=null){$M=array();foreach(get_rows("SHOW INDEX FROM ".table($S),$g)as$N){$E=$N["Key_name"];$M[$E]["type"]=($E=="PRIMARY"?"PRIMARY":($N["Index_type"]=="FULLTEXT"?"FULLTEXT":($N["Non_unique"]?($N["Index_type"]=="SPATIAL"?"SPATIAL":"INDEX"):"UNIQUE")));$M[$E]["columns"][]=$N["Column_name"];$M[$E]["lengths"][]=($N["Index_type"]=="SPATIAL"?null:$N["Sub_part"]);$M[$E]["descs"][]=null;}return$M;}function
foreign_keys($S){global$f,$ad;static$J='(?:`(?:[^`]|``)+`|"(?:[^"]|"")+")';$M=array();$Wa=$f->result("SHOW CREATE TABLE ".table($S),1);if($Wa){preg_match_all("~CONSTRAINT ($J) FOREIGN KEY ?\\(((?:$J,? ?)+)\\) REFERENCES ($J)(?:\\.($J))? \\(((?:$J,? ?)+)\\)(?: ON DELETE ($ad))?(?: ON UPDATE ($ad))?~",$Wa,$Jc,PREG_SET_ORDER);foreach($Jc
as$B){preg_match_all("~$J~",$B[2],$de);preg_match_all("~$J~",$B[5],$ve);$M[idf_unescape($B[1])]=array("db"=>idf_unescape($B[4]!=""?$B[3]:$B[4]),"table"=>idf_unescape($B[4]!=""?$B[4]:$B[3]),"source"=>array_map('idf_unescape',$de[0]),"target"=>array_map('idf_unescape',$ve[0]),"on_delete"=>($B[6]?$B[6]:"RESTRICT"),"on_update"=>($B[7]?$B[7]:"RESTRICT"),);}}return$M;}function
view($E){global$f;return
array("select"=>preg_replace('~^(?:[^`]|`[^`]*`)*\s+AS\s+~isU','',$f->result("SHOW CREATE VIEW ".table($E),1)));}function
collations(){$M=array();foreach(get_rows("SHOW COLLATION")as$N){if($N["Default"])$M[$N["Charset"]][-1]=$N["Collation"];else$M[$N["Charset"]][]=$N["Collation"];}ksort($M);foreach($M
as$y=>$W)asort($M[$y]);return$M;}function
information_schema($h){return(min_version(5)&&$h=="information_schema")||(min_version(5.5)&&$h=="performance_schema");}function
error(){global$f;return
h(preg_replace('~^You have an error.*syntax to use~U',"Syntax error",$f->error));}function
create_database($h,$Ma){return
queries("CREATE DATABASE ".idf_escape($h).($Ma?" COLLATE ".q($Ma):""));}function
drop_databases($db){$M=apply_queries("DROP DATABASE",$db,'idf_escape');restart_session();set_session("dbs",null);return$M;}function
rename_database($E,$Ma){$M=false;if(create_database($E,$Ma)){$Hd=array();foreach(tables_list()as$S=>$Le)$Hd[]=table($S)." TO ".idf_escape($E).".".table($S);$M=(!$Hd||queries("RENAME TABLE ".implode(", ",$Hd)));if($M)queries("DROP DATABASE ".idf_escape(DB));restart_session();set_session("dbs",null);}return$M;}function
auto_increment(){$ta=" PRIMARY KEY";if($_GET["create"]!=""&&$_POST["auto_increment_col"]){foreach(indexes($_GET["create"])as$lc){if(in_array($_POST["fields"][$_POST["auto_increment_col"]]["orig"],$lc["columns"],true)){$ta="";break;}if($lc["type"]=="PRIMARY")$ta=" UNIQUE";}}return" AUTO_INCREMENT$ta";}function
alter_table($S,$E,$m,$Nb,$Qa,$wb,$Ma,$sa,$od){$ma=array();foreach($m
as$l)$ma[]=($l[1]?($S!=""?($l[0]!=""?"CHANGE ".idf_escape($l[0]):"ADD"):" ")." ".implode($l[1]).($S!=""?$l[2]:""):"DROP ".idf_escape($l[0]));$ma=array_merge($ma,$Nb);$ie=($Qa!==null?" COMMENT=".q($Qa):"").($wb?" ENGINE=".q($wb):"").($Ma?" COLLATE ".q($Ma):"").($sa!=""?" AUTO_INCREMENT=$sa":"");if($S=="")return
queries("CREATE TABLE ".table($E)." (\n".implode(",\n",$ma)."\n)$ie$od");if($S!=$E)$ma[]="RENAME TO ".table($E);if($ie)$ma[]=ltrim($ie);return($ma||$od?queries("ALTER TABLE ".table($S)."\n".implode(",\n",$ma).$od):true);}function
alter_indexes($S,$ma){foreach($ma
as$y=>$W)$ma[$y]=($W[2]=="DROP"?"\nDROP INDEX ".idf_escape($W[1]):"\nADD $W[0] ".($W[0]=="PRIMARY"?"KEY ":"").($W[1]!=""?idf_escape($W[1])." ":"")."(".implode(", ",$W[2]).")");return
queries("ALTER TABLE ".table($S).implode(",",$ma));}function
truncate_tables($te){return
apply_queries("TRUNCATE TABLE",$te);}function
drop_views($df){return
queries("DROP VIEW ".implode(", ",array_map('table',$df)));}function
drop_tables($te){return
queries("DROP TABLE ".implode(", ",array_map('table',$te)));}function
move_tables($te,$df,$ve){$Hd=array();foreach(array_merge($te,$df)as$S)$Hd[]=table($S)." TO ".idf_escape($ve).".".table($S);return
queries("RENAME TABLE ".implode(", ",$Hd));}function
copy_tables($te,$df,$ve){queries("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");foreach($te
as$S){$E=($ve==DB?table("copy_$S"):idf_escape($ve).".".table($S));if(($_POST["overwrite"]&&!queries("\nDROP TABLE IF EXISTS $E"))||!queries("CREATE TABLE $E LIKE ".table($S))||!queries("INSERT INTO $E SELECT * FROM ".table($S)))return
false;foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($S,"%_\\")))as$N){$Ke=$N["Trigger"];if(!queries("CREATE TRIGGER ".($ve==DB?idf_escape("copy_$Ke"):idf_escape($ve).".".idf_escape($Ke))." $N[Timing] $N[Event] ON $E FOR EACH ROW\n$N[Statement];"))return
false;}}foreach($df
as$S){$E=($ve==DB?table("copy_$S"):idf_escape($ve).".".table($S));$cf=view($S);if(($_POST["overwrite"]&&!queries("DROP VIEW IF EXISTS $E"))||!queries("CREATE VIEW $E AS $cf[select]"))return
false;}return
true;}function
trigger($E){if($E=="")return
array();$O=get_rows("SHOW TRIGGERS WHERE `Trigger` = ".q($E));return
reset($O);}function
triggers($S){$M=array();foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($S,"%_\\")))as$N)$M[$N["Trigger"]]=array($N["Timing"],$N["Event"]);return$M;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>array("INSERT","UPDATE","DELETE"),"Type"=>array("FOR EACH ROW"),);}function
routine($E,$Le){global$f,$xb,$oc,$Ne;$la=array("bool","boolean","integer","double precision","real","dec","numeric","fixed","national char","national varchar");$ee="(?:\\s|/\\*[\s\S]*?\\*/|(?:#|-- )[^\n]*\n?|--\r?\n)";$Me="((".implode("|",array_merge(array_keys($Ne),$la)).")\\b(?:\\s*\\(((?:[^'\")]|$xb)++)\\))?\\s*(zerofill\\s*)?(unsigned(?:\\s+zerofill)?)?)(?:\\s*(?:CHARSET|CHARACTER\\s+SET)\\s*['\"]?([^'\"\\s,]+)['\"]?)?";$J="$ee*(".($Le=="FUNCTION"?"":$oc).")?\\s*(?:`((?:[^`]|``)*)`\\s*|\\b(\\S+)\\s+)$Me";$Va=$f->result("SHOW CREATE $Le ".idf_escape($E),2);preg_match("~\\(((?:$J\\s*,?)*)\\)\\s*".($Le=="FUNCTION"?"RETURNS\\s+$Me\\s+":"")."(.*)~is",$Va,$B);$m=array();preg_match_all("~$J\\s*,?~is",$B[1],$Jc,PREG_SET_ORDER);foreach($Jc
as$md)$m[]=array("field"=>str_replace("``","`",$md[2]).$md[3],"type"=>strtolower($md[5]),"length"=>preg_replace_callback("~$xb~s",'normalize_enum',$md[6]),"unsigned"=>strtolower(preg_replace('~\s+~',' ',trim("$md[8] $md[7]"))),"null"=>1,"full_type"=>$md[4],"inout"=>strtoupper($md[1]),"collation"=>strtolower($md[9]),);if($Le!="FUNCTION")return
array("fields"=>$m,"definition"=>$B[11]);return
array("fields"=>$m,"returns"=>array("type"=>$B[12],"length"=>$B[13],"unsigned"=>$B[15],"collation"=>$B[16]),"definition"=>$B[17],"language"=>"SQL",);}function
routines(){return
get_rows("SELECT ROUTINE_NAME AS SPECIFIC_NAME, ROUTINE_NAME, ROUTINE_TYPE, DTD_IDENTIFIER FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = ".q(DB));}function
routine_languages(){return
array();}function
routine_id($E,$N){return
idf_escape($E);}function
last_id(){global$f;return$f->result("SELECT LAST_INSERT_ID()");}function
explain($f,$K){return$f->query("EXPLAIN ".(min_version(5.1)?"PARTITIONS ":"").$K);}function
found_rows($T,$Z){return($Z||$T["Engine"]!="InnoDB"?null:$T["Rows"]);}function
types(){return
array();}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($Od,$g=null){return
true;}function
create_sql($S,$sa,$me){global$f;$M=$f->result("SHOW CREATE TABLE ".table($S),1);if(!$sa)$M=preg_replace('~ AUTO_INCREMENT=\d+~','',$M);return$M;}function
truncate_sql($S){return"TRUNCATE ".table($S);}function
use_sql($cb){return"USE ".idf_escape($cb);}function
trigger_sql($S){$M="";foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($S,"%_\\")),null,"-- ")as$N)$M.="\nCREATE TRIGGER ".idf_escape($N["Trigger"])." $N[Timing] $N[Event] ON ".table($N["Table"])." FOR EACH ROW\n$N[Statement];;\n";return$M;}function
show_variables(){return
get_key_vals("SHOW VARIABLES");}function
process_list(){return
get_rows("SHOW FULL PROCESSLIST");}function
show_status(){return
get_key_vals("SHOW STATUS");}function
convert_field($l){if(preg_match("~binary~",$l["type"]))return"HEX(".idf_escape($l["field"]).")";if($l["type"]=="bit")return"BIN(".idf_escape($l["field"])." + 0)";if(preg_match("~geometry|point|linestring|polygon~",$l["type"]))return(min_version(8)?"ST_":"")."AsWKT(".idf_escape($l["field"]).")";}function
unconvert_field($l,$M){if(preg_match("~binary~",$l["type"]))$M="UNHEX($M)";if($l["type"]=="bit")$M="CONV($M, 2, 10) + 0";if(preg_match("~geometry|point|linestring|polygon~",$l["type"]))$M=(min_version(8)?"ST_":"")."GeomFromText($M, SRID($l[field]))";return$M;}function
support($Gb){return!preg_match("~scheme|sequence|type|view_trigger|materializedview".(min_version(8)?"":"|descidx".(min_version(5.1)?"":"|event|partitioning".(min_version(5)?"":"|routine|trigger|view")))."~",$Gb);}function
kill_process($W){return
queries("KILL ".number($W));}function
connection_id(){return"SELECT CONNECTION_ID()";}function
max_connections(){global$f;return$f->result("SELECT @@max_connections");}$x="sql";$Ne=array();$le=array();foreach(array('Numbers'=>array("tinyint"=>3,"smallint"=>5,"mediumint"=>8,"int"=>10,"bigint"=>20,"decimal"=>66,"float"=>12,"double"=>21),'Date and time'=>array("date"=>10,"datetime"=>19,"timestamp"=>19,"time"=>10,"year"=>4),'Strings'=>array("char"=>255,"varchar"=>65535,"tinytext"=>255,"text"=>65535,"mediumtext"=>16777215,"longtext"=>4294967295),'Lists'=>array("enum"=>65535,"set"=>64),'Binary'=>array("bit"=>20,"binary"=>255,"varbinary"=>65535,"tinyblob"=>255,"blob"=>65535,"mediumblob"=>16777215,"longblob"=>4294967295),'Geometry'=>array("geometry"=>0,"point"=>0,"linestring"=>0,"polygon"=>0,"multipoint"=>0,"multilinestring"=>0,"multipolygon"=>0,"geometrycollection"=>0),)as$y=>$W){$Ne+=$W;$le[$y]=array_keys($W);}$Ue=array("unsigned","zerofill","unsigned zerofill");$fd=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","REGEXP","IN","FIND_IN_SET","IS NULL","NOT LIKE","NOT REGEXP","NOT IN","IS NOT NULL","SQL");$Ub=array("char_length","date","from_unixtime","lower","round","floor","ceil","sec_to_time","time_to_sec","upper");$Xb=array("avg","count","count distinct","group_concat","max","min","sum");$pb=array(array("char"=>"md5/sha1/password/encrypt/uuid","binary"=>"md5/sha1","date|time"=>"now",),array(number_type()=>"+/-","date"=>"+ interval/- interval","time"=>"addtime/subtime","char|text"=>"concat",));}define("SERVER",$_GET[DRIVER]);define("DB",$_GET["db"]);define("ME",str_replace(":","%3a",preg_replace('~^[^?]*/([^?]*).*~','\1',$_SERVER["REQUEST_URI"])).'?'.(sid()?SID.'&':'').(SERVER!==null?DRIVER."=".urlencode(SERVER).'&':'').(isset($_GET["username"])?"username=".urlencode($_GET["username"]).'&':'').(DB!=""?'db='.urlencode(DB).'&'.(isset($_GET["ns"])?"ns=".urlencode($_GET["ns"])."&":""):''));$ca="4.7.6";class
Adminer{var$operators=array("<=",">=");var$_values=array();function
name(){return"<a href='https://www.adminer.org/editor/'".target_blank()." id='h1'>".'Editor'."</a>";}function
credentials(){return
array(SERVER,$_GET["username"],get_password());}function
connectSsl(){}function
permanentLogin($Va=false){return
password_file($Va);}function
bruteForceKey(){return$_SERVER["REMOTE_ADDR"];}function
serverName($Q){}function
database(){global$f;if($f){$db=$this->databases(false);return(!$db?$f->result("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1)"):$db[(information_schema($db[0])?1:0)]);}}function
schemas(){return
schemas();}function
databases($Lb=true){return
get_databases($Lb);}function
queryTimeout(){return
5;}function
headers(){}function
csp(){return
csp();}function
head(){return
true;}function
css(){$M=array();$n="adminer.css";if(file_exists($n))$M[]=$n;return$M;}function
loginForm(){echo"<table cellspacing='0' class='layout'>\n",$this->loginFormField('username','<tr><th>'.'Username'.'<td>','<input type="hidden" name="auth[driver]" value="server"><input name="auth[username]" id="username" value="'.h($_GET["username"]).'" autocomplete="username" autocapitalize="off">'.script("focus(qs('#username'));")),$this->loginFormField('password','<tr><th>'.'Password'.'<td>','<input type="password" name="auth[password]" autocomplete="current-password">'."\n"),"</table>\n","<p><input type='submit' value='".'Login'."'>\n",checkbox("auth[permanent]",1,$_COOKIE["adminer_permanent"],'Permanent login')."\n";}function
loginFormField($E,$ec,$X){return$ec.$X;}function
login($Fc,$I){return
true;}function
tableName($re){return
h($re["Comment"]!=""?$re["Comment"]:$re["Name"]);}function
fieldName($l,$G=0){return
h(preg_replace('~\s+\[.*\]$~','',($l["comment"]!=""?$l["comment"]:$l["field"])));}function
selectLinks($re,$R=""){$a=$re["Name"];if($R!==null)echo'<p class="tabs"><a href="'.h(ME.'edit='.urlencode($a).$R).'">'.'New item'."</a>\n";}function
foreignKeys($S){return
foreign_keys($S);}function
backwardKeys($S,$qe){$M=array();foreach(get_rows("SELECT TABLE_NAME, CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = ".q($this->database())."
AND REFERENCED_TABLE_SCHEMA = ".q($this->database())."
AND REFERENCED_TABLE_NAME = ".q($S)."
ORDER BY ORDINAL_POSITION",null,"")as$N)$M[$N["TABLE_NAME"]]["keys"][$N["CONSTRAINT_NAME"]][$N["COLUMN_NAME"]]=$N["REFERENCED_COLUMN_NAME"];foreach($M
as$y=>$W){$E=$this->tableName(table_status($y,true));if($E!=""){$Pd=preg_quote($qe);$Vd="(:|\\s*-)?\\s+";$M[$y]["name"]=(preg_match("(^$Pd$Vd(.+)|^(.+?)$Vd$Pd\$)iu",$E,$B)?$B[2].$B[3]:$E);}else
unset($M[$y]);}return$M;}function
backwardKeysPrint($wa,$N){foreach($wa
as$S=>$va){foreach($va["keys"]as$Oa){$_=ME.'select='.urlencode($S);$s=0;foreach($Oa
as$d=>$W)$_.=where_link($s++,$d,$N[$W]);echo"<a href='".h($_)."'>".h($va["name"])."</a>";$_=ME.'edit='.urlencode($S);foreach($Oa
as$d=>$W)$_.="&set".urlencode("[".bracket_escape($d)."]")."=".urlencode($N[$W]);echo"<a href='".h($_)."' title='".'New item'."'>+</a> ";}}}function
selectQuery($K,$he,$Eb=false){return"<!--\n".str_replace("--","--><!-- ",$K)."\n(".format_time($he).")\n-->\n";}function
rowDescription($S){foreach(fields($S)as$l){if(preg_match("~varchar|character varying~",$l["type"]))return
idf_escape($l["field"]);}return"";}function
rowDescriptions($O,$Pb){$M=$O;foreach($O[0]as$y=>$W){if(list($S,$t,$E)=$this->_foreignColumn($Pb,$y)){$jc=array();foreach($O
as$N)$jc[$N[$y]]=q($N[$y]);$hb=$this->_values[$S];if(!$hb)$hb=get_key_vals("SELECT $t, $E FROM ".table($S)." WHERE $t IN (".implode(", ",$jc).")");foreach($O
as$D=>$N){if(isset($N[$y]))$M[$D][$y]=(string)$hb[$N[$y]];}}}return$M;}function
selectLink($W,$l){}function
selectVal($W,$_,$l,$id){$M=$W;$_=h($_);if(preg_match('~blob|bytea~',$l["type"])&&!is_utf8($W)){$M=lang(array('%d byte','%d bytes'),strlen($id));if(preg_match("~^(GIF|\xFF\xD8\xFF|\x89PNG\x0D\x0A\x1A\x0A)~",$id))$M="<img src='$_' alt='$M'>";}if(like_bool($l)&&$M!="")$M=(preg_match('~^(1|t|true|y|yes|on)$~i',$W)?'yes':'no');if($_)$M="<a href='$_'".(is_url($_)?target_blank():"").">$M</a>";if(!$_&&!like_bool($l)&&preg_match(number_type(),$l["type"]))$M="<div class='number'>$M</div>";elseif(preg_match('~date~',$l["type"]))$M="<div class='datetime'>$M</div>";return$M;}function
editVal($W,$l){if(preg_match('~date|timestamp~',$l["type"])&&$W!==null)return
preg_replace('~^(\d{2}(\d+))-(0?(\d+))-(0?(\d+))~','$1-$3-$5',$W);return$W;}function
selectColumnsPrint($P,$e){}function
selectSearchPrint($Z,$e,$v){$Z=(array)$_GET["where"];echo'<fieldset id="fieldset-search"><legend>'.'Search'."</legend><div>\n";$vc=array();foreach($Z
as$y=>$W)$vc[$W["col"]]=$y;$s=0;$m=fields($_GET["select"]);foreach($e
as$E=>$gb){$l=$m[$E];if(preg_match("~enum~",$l["type"])||like_bool($l)){$y=$vc[$E];$s--;echo"<div>".h($gb)."<input type='hidden' name='where[$s][col]' value='".h($E)."'>:",(like_bool($l)?" <select name='where[$s][val]'>".optionlist(array(""=>"",'no','yes'),$Z[$y]["val"],true)."</select>":enum_input("checkbox"," name='where[$s][val][]'",$l,(array)$Z[$y]["val"],($l["null"]?0:null))),"</div>\n";unset($e[$E]);}elseif(is_array($F=$this->_foreignKeyOptions($_GET["select"],$E))){if($m[$E]["null"])$F[0]='('.'empty'.')';$y=$vc[$E];$s--;echo"<div>".h($gb)."<input type='hidden' name='where[$s][col]' value='".h($E)."'><input type='hidden' name='where[$s][op]' value='='>: <select name='where[$s][val]'>".optionlist($F,$Z[$y]["val"],true)."</select></div>\n";unset($e[$E]);}}$s=0;foreach($Z
as$W){if(($W["col"]==""||$e[$W["col"]])&&"$W[col]$W[val]"!=""){echo"<div><select name='where[$s][col]'><option value=''>(".'anywhere'.")".optionlist($e,$W["col"],true)."</select>",html_select("where[$s][op]",array(-1=>"")+$this->operators,$W["op"]),"<input type='search' name='where[$s][val]' value='".h($W["val"])."'>".script("mixin(qsl('input'), {onkeydown: selectSearchKeydown, onsearch: selectSearchSearch});","")."</div>\n";$s++;}}echo"<div><select name='where[$s][col]'><option value=''>(".'anywhere'.")".optionlist($e,null,true)."</select>",script("qsl('select').onchange = selectAddRow;",""),html_select("where[$s][op]",array(-1=>"")+$this->operators),"<input type='search' name='where[$s][val]'></div>",script("mixin(qsl('input'), {onchange: function () { this.parentNode.firstChild.onchange(); }, onsearch: selectSearchSearch});"),"</div></fieldset>\n";}function
selectOrderPrint($G,$e,$v){$hd=array();foreach($v
as$y=>$lc){$G=array();foreach($lc["columns"]as$W)$G[]=$e[$W];if(count(array_filter($G,'strlen'))>1&&$y!="PRIMARY")$hd[$y]=implode(", ",$G);}if($hd){echo'<fieldset><legend>'.'Sort'."</legend><div>","<select name='index_order'>".optionlist(array(""=>"")+$hd,($_GET["order"][0]!=""?"":$_GET["index_order"]),true)."</select>","</div></fieldset>\n";}if($_GET["order"])echo"<div style='display: none;'>".hidden_fields(array("order"=>array(1=>reset($_GET["order"])),"desc"=>($_GET["desc"]?array(1=>1):array()),))."</div>\n";}function
selectLimitPrint($z){echo"<fieldset><legend>".'Limit'."</legend><div>";echo
html_select("limit",array("","50","100"),$z),"</div></fieldset>\n";}function
selectLengthPrint($xe){}function
selectActionPrint($v){echo"<fieldset><legend>".'Action'."</legend><div>","<input type='submit' value='".'Select'."'>","</div></fieldset>\n";}function
selectCommandPrint(){return
true;}function
selectImportPrint(){return
true;}function
selectEmailPrint($tb,$e){if($tb){print_fieldset("email",'E-mail',$_POST["email_append"]);echo"<div>",script("qsl('div').onkeydown = partialArg(bodyKeydown, 'email');"),"<p>".'From'.": <input name='email_from' value='".h($_POST?$_POST["email_from"]:$_COOKIE["adminer_email"])."'>\n",'Subject'.": <input name='email_subject' value='".h($_POST["email_subject"])."'>\n","<p><textarea name='email_message' rows='15' cols='75'>".h($_POST["email_message"].($_POST["email_append"]?'{$'."$_POST[email_addition]}":""))."</textarea>\n","<p>".script("qsl('p').onkeydown = partialArg(bodyKeydown, 'email_append');","").html_select("email_addition",$e,$_POST["email_addition"])."<input type='submit' name='email_append' value='".'Insert'."'>\n";echo"<p>".'Attachments'.": <input type='file' name='email_files[]'>".script("qsl('input').onchange = emailFileChange;"),"<p>".(count($tb)==1?'<input type="hidden" name="email_field" value="'.h(key($tb)).'">':html_select("email_field",$tb)),"<input type='submit' name='email' value='".'Send'."'>".confirm(),"</div>\n","</div></fieldset>\n";}}function
selectColumnsProcess($e,$v){return
array(array(),array());}function
selectSearchProcess($m,$v){$M=array();foreach((array)$_GET["where"]as$y=>$Z){$La=$Z["col"];$dd=$Z["op"];$W=$Z["val"];if(($y<0?"":$La).$W!=""){$Ra=array();foreach(($La!=""?array($La=>$m[$La]):$m)as$E=>$l){if($La!=""||is_numeric($W)||!preg_match(number_type(),$l["type"])){$E=idf_escape($E);if($La!=""&&$l["type"]=="enum")$Ra[]=(in_array(0,$W)?"$E IS NULL OR ":"")."$E IN (".implode(", ",array_map('intval',$W)).")";else{$ye=preg_match('~char|text|enum|set~',$l["type"]);$X=$this->processInput($l,(!$dd&&$ye&&preg_match('~^[^%]+$~',$W)?"%$W%":$W));$Ra[]=$E.($X=="NULL"?" IS".($dd==">="?" NOT":"")." $X":(in_array($dd,$this->operators)||$dd=="="?" $dd $X":($ye?" LIKE $X":" IN (".str_replace(",","', '",$X).")")));if($y<0&&$W=="0")$Ra[]="$E IS NULL";}}}$M[]=($Ra?"(".implode(" OR ",$Ra).")":"1 = 0");}}return$M;}function
selectOrderProcess($m,$v){$mc=$_GET["index_order"];if($mc!="")unset($_GET["order"][1]);if($_GET["order"])return
array(idf_escape(reset($_GET["order"])).($_GET["desc"]?" DESC":""));foreach(($mc!=""?array($v[$mc]):$v)as$lc){if($mc!=""||$lc["type"]=="INDEX"){$Zb=array_filter($lc["descs"]);$gb=false;foreach($lc["columns"]as$W){if(preg_match('~date|timestamp~',$m[$W]["type"])){$gb=true;break;}}$M=array();foreach($lc["columns"]as$y=>$W)$M[]=idf_escape($W).(($Zb?$lc["descs"][$y]:$gb)?" DESC":"");return$M;}}return
array();}function
selectLimitProcess(){return(isset($_GET["limit"])?$_GET["limit"]:"50");}function
selectLengthProcess(){return"100";}function
selectEmailProcess($Z,$Pb){if($_POST["email_append"])return
true;if($_POST["email"]){$Td=0;if($_POST["all"]||$_POST["check"]){$l=idf_escape($_POST["email_field"]);$ne=$_POST["email_subject"];$C=$_POST["email_message"];preg_match_all('~\{\$([a-z0-9_]+)\}~i',"$ne.$C",$Jc);$O=get_rows("SELECT DISTINCT $l".($Jc[1]?", ".implode(", ",array_map('idf_escape',array_unique($Jc[1]))):"")." FROM ".table($_GET["select"])." WHERE $l IS NOT NULL AND $l != ''".($Z?" AND ".implode(" AND ",$Z):"").($_POST["all"]?"":" AND ((".implode(") OR (",array_map('where_check',(array)$_POST["check"]))."))"));$m=fields($_GET["select"]);foreach($this->rowDescriptions($O,$Pb)as$N){$Id=array('{\\'=>'{');foreach($Jc[1]as$W)$Id['{$'."$W}"]=$this->editVal($N[$W],$m[$W]);$sb=$N[$_POST["email_field"]];if(is_mail($sb)&&send_mail($sb,strtr($ne,$Id),strtr($C,$Id),$_POST["email_from"],$_FILES["email_files"]))$Td++;}}cookie("adminer_email",$_POST["email_from"]);redirect(remove_from_uri(),lang(array('%d e-mail has been sent.','%d e-mails have been sent.'),$Td));}return
false;}function
selectQueryBuild($P,$Z,$r,$G,$z,$H){return"";}function
messageQuery($K,$ze,$Eb=false){return" <span class='time'>".@date("H:i:s")."</span><!--\n".str_replace("--","--><!-- ",$K)."\n".($ze?"($ze)\n":"")."-->";}function
editFunctions($l){$M=array();if($l["null"]&&preg_match('~blob~',$l["type"]))$M["NULL"]='empty';$M[""]=($l["null"]||$l["auto_increment"]||like_bool($l)?"":"*");if(preg_match('~date|time~',$l["type"]))$M["now"]='now';if(preg_match('~_(md5|sha1)$~i',$l["field"],$B))$M[]=strtolower($B[1]);return$M;}function
editInput($S,$l,$c,$X){if($l["type"]=="enum")return(isset($_GET["select"])?"<label><input type='radio'$c value='-1' checked><i>".'original'."</i></label> ":"").enum_input("radio",$c,$l,($X||isset($_GET["select"])?$X:0),($l["null"]?"":null));$F=$this->_foreignKeyOptions($S,$l["field"],$X);if($F!==null)return(is_array($F)?"<select$c>".optionlist($F,$X,true)."</select>":"<input value='".h($X)."'$c class='hidden'>"."<input value='".h($F)."' class='jsonly'>"."<div></div>".script("qsl('input').oninput = partial(whisper, '".ME."script=complete&source=".urlencode($S)."&field=".urlencode($l["field"])."&value=');
qsl('div').onclick = whisperClick;",""));if(like_bool($l))return'<input type="checkbox" value="1"'.(preg_match('~^(1|t|true|y|yes|on)$~i',$X)?' checked':'')."$c>";$fc="";if(preg_match('~time~',$l["type"]))$fc='HH:MM:SS';if(preg_match('~date|timestamp~',$l["type"]))$fc='[yyyy]-mm-dd'.($fc?" [$fc]":"");if($fc)return"<input value='".h($X)."'$c> ($fc)";if(preg_match('~_(md5|sha1)$~i',$l["field"]))return"<input type='password' value='".h($X)."'$c>";return'';}function
editHint($S,$l,$X){return(preg_match('~\s+(\[.*\])$~',($l["comment"]!=""?$l["comment"]:$l["field"]),$B)?h(" $B[1]"):'');}function
processInput($l,$X,$q=""){if($q=="now")return"$q()";$M=$X;if(preg_match('~date|timestamp~',$l["type"])&&preg_match('(^'.str_replace('\$1','(?P<p1>\d*)',preg_replace('~(\\\\\\$([2-6]))~','(?P<p\2>\d{1,2})',preg_quote('$1-$3-$5'))).'(.*))',$X,$B))$M=($B["p1"]!=""?$B["p1"]:($B["p2"]!=""?($B["p2"]<70?20:19).$B["p2"]:gmdate("Y")))."-$B[p3]$B[p4]-$B[p5]$B[p6]".end($B);$M=($l["type"]=="bit"&&preg_match('~^[0-9]+$~',$X)?$M:q($M));if($X==""&&like_bool($l))$M="'0'";elseif($X==""&&($l["null"]||!preg_match('~char|text~',$l["type"])))$M="NULL";elseif(preg_match('~^(md5|sha1)$~',$q))$M="$q($M)";return
unconvert_field($l,$M);}function
dumpOutput(){return
array();}function
dumpFormat(){return
array('csv'=>'CSV,','csv;'=>'CSV;','tsv'=>'TSV');}function
dumpDatabase($h){}function
dumpTable($S,$me,$tc=0){echo"\xef\xbb\xbf";}function
dumpData($S,$me,$K){global$f;$L=$f->query($K,1);if($L){while($N=$L->fetch_assoc()){if($me=="table"){dump_csv(array_keys($N));$me="INSERT";}dump_csv($N);}}}function
dumpFilename($ic){return
friendly_url($ic);}function
dumpHeaders($ic,$Rc=false){$Cb="csv";header("Content-Type: text/csv; charset=utf-8");return$Cb;}function
importServerPath(){}function
homepage(){return
true;}function
navigation($Qc){global$ca;echo'<h1>
',$this->name(),' <span class="version">',$ca,'</span>
<a href="https://www.adminer.org/editor/#download"',target_blank(),' id="version">',(version_compare($ca,$_COOKIE["adminer_version"])<0?h($_COOKIE["adminer_version"]):""),'</a>
</h1>
';if($Qc=="auth"){$Kb=true;foreach((array)$_SESSION["pwds"]as$Y=>$Xd){foreach($Xd[""]as$U=>$I){if($I!==null){if($Kb){echo"<ul id='logins'>",script("mixin(qs('#logins'), {onmouseover: menuOver, onmouseout: menuOut});");$Kb=false;}echo"<li><a href='".h(auth_url($Y,"",$U))."'>".($U!=""?h($U):"<i>".'empty'."</i>")."</a>\n";}}}}else{$this->databasesPrint($Qc);if($Qc!="db"&&$Qc!="ns"){$T=table_status('',true);if(!$T)echo"<p class='message'>".'No tables.'."\n";else$this->tablesPrint($T);}}}function
databasesPrint($Qc){}function
tablesPrint($te){echo"<ul id='tables'>",script("mixin(qs('#tables'), {onmouseover: menuOver, onmouseout: menuOut});");foreach($te
as$N){echo'<li>';$E=$this->tableName($N);if(isset($N["Engine"])&&$E!="")echo"<a href='".h(ME).'select='.urlencode($N["Name"])."'".bold($_GET["select"]==$N["Name"]||$_GET["edit"]==$N["Name"],"select")." title='".'Select data'."'>$E</a>\n";}echo"</ul>\n";}function
_foreignColumn($Pb,$d){foreach((array)$Pb[$d]as$Ob){if(count($Ob["source"])==1){$E=$this->rowDescription($Ob["table"]);if($E!=""){$t=idf_escape($Ob["target"][0]);return
array($Ob["table"],$t,$E);}}}}function
_foreignKeyOptions($S,$d,$X=null){global$f;if(list($ve,$t,$E)=$this->_foreignColumn(column_foreign_keys($S),$d)){$M=&$this->_values[$ve];if($M===null){$T=table_status($ve);$M=($T["Rows"]>1000?"":array(""=>"")+get_key_vals("SELECT $t, $E FROM ".table($ve)." ORDER BY 2"));}if(!$M&&$X!==null)return$f->result("SELECT $E FROM ".table($ve)." WHERE $t = ".q($X));return$M;}}}$b=(function_exists('adminer_object')?adminer_object():new
Adminer);function
page_header($Ae,$k="",$Ca=array(),$Be=""){global$ba,$ca,$b,$mb,$x;page_headers();if(is_ajax()&&$k){page_messages($k);exit;}$Ce=$Ae.($Be!=""?": $Be":"");$De=strip_tags($Ce.(SERVER!=""&&SERVER!="localhost"?h(" - ".SERVER):"")." - ".$b->name());echo'<!DOCTYPE html>
<html lang="en" dir="ltr">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex">
<title>',$De,'</title>
<link rel="stylesheet" type="text/css" href="',h(preg_replace("~\\?.*~","",ME)."?file=default.css&version=4.7.6"),'">
',script_src(preg_replace("~\\?.*~","",ME)."?file=functions.js&version=4.7.6");if($b->head()){echo'<link rel="shortcut icon" type="image/x-icon" href="',h(preg_replace("~\\?.*~","",ME)."?file=favicon.ico&version=4.7.6"),'">
<link rel="apple-touch-icon" href="',h(preg_replace("~\\?.*~","",ME)."?file=favicon.ico&version=4.7.6"),'">
';foreach($b->css()as$Za){echo'<link rel="stylesheet" type="text/css" href="',h($Za),'">
';}}echo'
<body class="ltr nojs">
';$n=get_temp_dir()."/adminer.version";if(!$_COOKIE["adminer_version"]&&function_exists('openssl_verify')&&file_exists($n)&&filemtime($n)+86400>time()){$bf=unserialize(file_get_contents($n));$zd="-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwqWOVuF5uw7/+Z70djoK
RlHIZFZPO0uYRezq90+7Amk+FDNd7KkL5eDve+vHRJBLAszF/7XKXe11xwliIsFs
DFWQlsABVZB3oisKCBEuI71J4kPH8dKGEWR9jDHFw3cWmoH3PmqImX6FISWbG3B8
h7FIx3jEaw5ckVPVTeo5JRm/1DZzJxjyDenXvBQ/6o9DgZKeNDgxwKzH+sw9/YCO
jHnq1cFpOIISzARlrHMa/43YfeNRAm/tsBXjSxembBPo7aQZLAWHmaj5+K19H10B
nCpz9Y++cipkVEiKRGih4ZEvjoFysEOdRLj6WiD/uUNky4xGeA6LaJqh5XpkFkcQ
fQIDAQAB
-----END PUBLIC KEY-----
";if(openssl_verify($bf["version"],base64_decode($bf["signature"]),$zd)==1)$_COOKIE["adminer_version"]=$bf["version"];}echo'<script',nonce(),'>
mixin(document.body, {onkeydown: bodyKeydown, onclick: bodyClick',(isset($_COOKIE["adminer_version"])?"":", onload: partial(verifyVersion, '$ca', '".js_escape(ME)."', '".get_token()."')");?>});
document.body.className = document.body.className.replace(/ nojs/, ' js');
var offlineMessage = '<?php echo
js_escape('You are offline.'),'\';
var thousandsSeparator = \'',js_escape(','),'\';
</script>

<div id="help" class="jush-',$x,' jsonly hidden"></div>
',script("mixin(qs('#help'), {onmouseover: function () { helpOpen = 1; }, onmouseout: helpMouseout});"),'
<div id="content">
';if($Ca!==null){$_=substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1);echo'<p id="breadcrumb"><a href="'.h($_?$_:".").'">'.$mb[DRIVER].'</a> &raquo; ';$_=substr(preg_replace('~\b(db|ns)=[^&]*&~','',ME),0,-1);$Q=$b->serverName(SERVER);$Q=($Q!=""?$Q:'Server');if($Ca===false)echo"$Q\n";else{echo"<a href='".($_?h($_):".")."' accesskey='1' title='Alt+Shift+1'>$Q</a> &raquo; ";if($_GET["ns"]!=""||(DB!=""&&is_array($Ca)))echo'<a href="'.h($_."&db=".urlencode(DB).(support("scheme")?"&ns=":"")).'">'.h(DB).'</a> &raquo; ';if(is_array($Ca)){if($_GET["ns"]!="")echo'<a href="'.h(substr(ME,0,-1)).'">'.h($_GET["ns"]).'</a> &raquo; ';foreach($Ca
as$y=>$W){$gb=(is_array($W)?$W[1]:h($W));if($gb!="")echo"<a href='".h(ME."$y=").urlencode(is_array($W)?$W[0]:$W)."'>$gb</a> &raquo; ";}}echo"$Ae\n";}}echo"<h2>$Ce</h2>\n","<div id='ajaxstatus' class='jsonly hidden'></div>\n";restart_session();page_messages($k);$db=&get_session("dbs");if(DB!=""&&$db&&!in_array(DB,$db,true))$db=null;stop_session();define("PAGE_HEADER",1);}function
page_headers(){global$b;header("Content-Type: text/html; charset=utf-8");header("Cache-Control: no-cache");header("X-Frame-Options: deny");header("X-XSS-Protection: 0");header("X-Content-Type-Options: nosniff");header("Referrer-Policy: origin-when-cross-origin");foreach($b->csp()as$Ya){$cc=array();foreach($Ya
as$y=>$W)$cc[]="$y $W";header("Content-Security-Policy: ".implode("; ",$cc));}$b->headers();}function
csp(){return
array(array("script-src"=>"'self' 'unsafe-inline' 'nonce-".get_nonce()."' 'strict-dynamic'","connect-src"=>"'self'","frame-src"=>"https://www.adminer.org","object-src"=>"'none'","base-uri"=>"'none'","form-action"=>"'self'",),);}function
get_nonce(){static$Vc;if(!$Vc)$Vc=base64_encode(rand_string());return$Vc;}function
page_messages($k){$We=preg_replace('~^[^?]*~','',$_SERVER["REQUEST_URI"]);$Pc=$_SESSION["messages"][$We];if($Pc){echo"<div class='message'>".implode("</div>\n<div class='message'>",$Pc)."</div>".script("messagesPrint();");unset($_SESSION["messages"][$We]);}if($k)echo"<div class='error'>$k</div>\n";}function
page_footer($Qc=""){global$b,$Fe;echo'</div>

';if($Qc!="auth"){echo'<form action="" method="post">
<p class="logout">
<input type="submit" name="logout" value="Logout" id="logout">
<input type="hidden" name="token" value="',$Fe,'">
</p>
</form>
';}echo'<div id="menu">
';$b->navigation($Qc);echo'</div>
',script("setupSubmitHighlight(document);");}function
int32($D){while($D>=2147483648)$D-=4294967296;while($D<=-2147483649)$D+=4294967296;return(int)$D;}function
long2str($V,$ff){$Nd='';foreach($V
as$W)$Nd.=pack('V',$W);if($ff)return
substr($Nd,0,end($V));return$Nd;}function
str2long($Nd,$ff){$V=array_values(unpack('V*',str_pad($Nd,4*ceil(strlen($Nd)/4),"\0")));if($ff)$V[]=strlen($Nd);return$V;}function
xxtea_mx($kf,$jf,$pe,$uc){return
int32((($kf>>5&0x7FFFFFF)^$jf<<2)+(($jf>>3&0x1FFFFFFF)^$kf<<4))^int32(($pe^$jf)+($uc^$kf));}function
encrypt_string($je,$y){if($je=="")return"";$y=array_values(unpack("V*",pack("H*",md5($y))));$V=str2long($je,true);$D=count($V)-1;$kf=$V[$D];$jf=$V[0];$_d=floor(6+52/($D+1));$pe=0;while($_d-->0){$pe=int32($pe+0x9E3779B9);$ob=$pe>>2&3;for($kd=0;$kd<$D;$kd++){$jf=$V[$kd+1];$Sc=xxtea_mx($kf,$jf,$pe,$y[$kd&3^$ob]);$kf=int32($V[$kd]+$Sc);$V[$kd]=$kf;}$jf=$V[0];$Sc=xxtea_mx($kf,$jf,$pe,$y[$kd&3^$ob]);$kf=int32($V[$D]+$Sc);$V[$D]=$kf;}return
long2str($V,false);}function
decrypt_string($je,$y){if($je=="")return"";if(!$y)return
false;$y=array_values(unpack("V*",pack("H*",md5($y))));$V=str2long($je,false);$D=count($V)-1;$kf=$V[$D];$jf=$V[0];$_d=floor(6+52/($D+1));$pe=int32($_d*0x9E3779B9);while($pe){$ob=$pe>>2&3;for($kd=$D;$kd>0;$kd--){$kf=$V[$kd-1];$Sc=xxtea_mx($kf,$jf,$pe,$y[$kd&3^$ob]);$jf=int32($V[$kd]-$Sc);$V[$kd]=$jf;}$kf=$V[$D];$Sc=xxtea_mx($kf,$jf,$pe,$y[$kd&3^$ob]);$jf=int32($V[0]-$Sc);$V[0]=$jf;$pe=int32($pe-0x9E3779B9);}return
long2str($V,true);}$f='';$bc=$_SESSION["token"];if(!$bc)$_SESSION["token"]=rand(1,1e6);$Fe=get_token();$pd=array();if($_COOKIE["adminer_permanent"]){foreach(explode(" ",$_COOKIE["adminer_permanent"])as$W){list($y)=explode(":",$W);$pd[$y]=$W;}}function
add_invalid_login(){global$b;$p=file_open_lock(get_temp_dir()."/adminer.invalid");if(!$p)return;$rc=unserialize(stream_get_contents($p));$ze=time();if($rc){foreach($rc
as$sc=>$W){if($W[0]<$ze)unset($rc[$sc]);}}$qc=&$rc[$b->bruteForceKey()];if(!$qc)$qc=array($ze+30*60,0);$qc[1]++;file_write_unlock($p,serialize($rc));}function
check_invalid_login(){global$b;$rc=unserialize(@file_get_contents(get_temp_dir()."/adminer.invalid"));$qc=$rc[$b->bruteForceKey()];$Uc=($qc[1]>29?$qc[0]-time():0);if($Uc>0)auth_error(lang(array('Too many unsuccessful logins, try again in %d minute.','Too many unsuccessful logins, try again in %d minutes.'),ceil($Uc/60)));}$ra=$_POST["auth"];if($ra){session_regenerate_id();$Y=$ra["driver"];$Q=$ra["server"];$U=$ra["username"];$I=(string)$ra["password"];$h=$ra["db"];set_password($Y,$Q,$U,$I);$_SESSION["db"][$Y][$Q][$U][$h]=true;if($ra["permanent"]){$y=base64_encode($Y)."-".base64_encode($Q)."-".base64_encode($U)."-".base64_encode($h);$xd=$b->permanentLogin(true);$pd[$y]="$y:".base64_encode($xd?encrypt_string($I,$xd):"");cookie("adminer_permanent",implode(" ",$pd));}if(count($_POST)==1||DRIVER!=$Y||SERVER!=$Q||$_GET["username"]!==$U||DB!=$h)redirect(auth_url($Y,$Q,$U,$h));}elseif($_POST["logout"]){if($bc&&!verify_token()){page_header('Logout','Invalid CSRF token. Send the form again.');page_footer("db");exit;}else{foreach(array("pwds","db","dbs","queries")as$y)set_session($y,null);unset_permanent();redirect(substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1),'Logout successful.'.' '.'Thanks for using Adminer, consider <a href="https://www.adminer.org/en/donation/">donating</a>.');}}elseif($pd&&!$_SESSION["pwds"]){session_regenerate_id();$xd=$b->permanentLogin();foreach($pd
as$y=>$W){list(,$Ha)=explode(":",$W);list($Y,$Q,$U,$h)=array_map('base64_decode',explode("-",$y));set_password($Y,$Q,$U,decrypt_string(base64_decode($Ha),$xd));$_SESSION["db"][$Y][$Q][$U][$h]=true;}}function
unset_permanent(){global$pd;foreach($pd
as$y=>$W){list($Y,$Q,$U,$h)=array_map('base64_decode',explode("-",$y));if($Y==DRIVER&&$Q==SERVER&&$U==$_GET["username"]&&$h==DB)unset($pd[$y]);}cookie("adminer_permanent",implode(" ",$pd));}function
auth_error($k){global$b,$bc;$Yd=session_name();if(isset($_GET["username"])){header("HTTP/1.1 403 Forbidden");if(($_COOKIE[$Yd]||$_GET[$Yd])&&!$bc)$k='Session expired, please login again.';else{restart_session();add_invalid_login();$I=get_password();if($I!==null){if($I===false)$k.='<br>'.sprintf('Master password expired. <a href="https://www.adminer.org/en/extension/"%s>Implement</a> %s method to make it permanent.',target_blank(),'<code>permanentLogin()</code>');set_password(DRIVER,SERVER,$_GET["username"],null);}unset_permanent();}}if(!$_COOKIE[$Yd]&&$_GET[$Yd]&&ini_bool("session.use_only_cookies"))$k='Session support must be enabled.';$nd=session_get_cookie_params();cookie("adminer_key",($_COOKIE["adminer_key"]?$_COOKIE["adminer_key"]:rand_string()),$nd["lifetime"]);page_header('Login',$k,null);echo"<form action='' method='post'>\n","<div>";if(hidden_fields($_POST,array("auth")))echo"<p class='message'>".'The action will be performed after successful login with the same credentials.'."\n";echo"</div>\n";$b->loginForm();echo"</form>\n";page_footer("auth");exit;}if(isset($_GET["username"])&&!class_exists("Min_DB")){unset($_SESSION["pwds"][DRIVER]);unset_permanent();page_header('No extension',sprintf('None of the supported PHP extensions (%s) are available.',implode(", ",$td)),false);page_footer("auth");exit;}stop_session(true);if(isset($_GET["username"])&&is_string(get_password())){list($gc,$rd)=explode(":",SERVER,2);if(is_numeric($rd)&&($rd<1024||$rd>65535))auth_error('Connecting to privileged ports is not allowed.');check_invalid_login();$f=connect();$i=new
Min_Driver($f);}$Fc=null;if(!is_object($f)||($Fc=$b->login($_GET["username"],get_password()))!==true){$k=(is_string($f)?h($f):(is_string($Fc)?$Fc:'Invalid credentials.'));auth_error($k.(preg_match('~^ | $~',get_password())?'<br>'.'There is a space in the input password which might be the cause.':''));}if($ra&&$_POST["token"])$_POST["token"]=$Fe;$k='';if($_POST){if(!verify_token()){$nc="max_input_vars";$Nc=ini_get($nc);if(extension_loaded("suhosin")){foreach(array("suhosin.request.max_vars","suhosin.post.max_vars")as$y){$W=ini_get($y);if($W&&(!$Nc||$W<$Nc)){$nc=$y;$Nc=$W;}}}$k=(!$_POST["token"]&&$Nc?sprintf('Maximum number of allowed fields exceeded. Please increase %s.',"'$nc'"):'Invalid CSRF token. Send the form again.'.' '.'If you did not send this request from Adminer then close this page.');}}elseif($_SERVER["REQUEST_METHOD"]=="POST"){$k=sprintf('Too big POST data. Reduce the data or increase the %s configuration directive.',"'post_max_size'");if(isset($_GET["sql"]))$k.=' '.'You can upload a big SQL file via FTP and import it from server.';}function
email_header($cc){return"=?UTF-8?B?".base64_encode($cc)."?=";}function
send_mail($sb,$ne,$C,$Tb="",$Ib=array()){$j=(DIRECTORY_SEPARATOR=="/"?"\n":"\r\n");$C=str_replace("\n",$j,wordwrap(str_replace("\r","","$C\n")));$Ba=uniqid("boundary");$qa="";foreach((array)$Ib["error"]as$y=>$W){if(!$W)$qa.="--$Ba$j"."Content-Type: ".str_replace("\n","",$Ib["type"][$y]).$j."Content-Disposition: attachment; filename=\"".preg_replace('~["\n]~','',$Ib["name"][$y])."\"$j"."Content-Transfer-Encoding: base64$j$j".chunk_split(base64_encode(file_get_contents($Ib["tmp_name"][$y])),76,$j).$j;}$ya="";$dc="Content-Type: text/plain; charset=utf-8$j"."Content-Transfer-Encoding: 8bit";if($qa){$qa.="--$Ba--$j";$ya="--$Ba$j$dc$j$j";$dc="Content-Type: multipart/mixed; boundary=\"$Ba\"";}$dc.=$j."MIME-Version: 1.0$j"."X-Mailer: Adminer Editor".($Tb?$j."From: ".str_replace("\n","",$Tb):"");return
mail($sb,email_header($ne),$ya.$C.$qa,$dc);}function
like_bool($l){return
preg_match("~bool|(tinyint|bit)\\(1\\)~",$l["full_type"]);}$f->select_db($b->database());$ad="RESTRICT|NO ACTION|CASCADE|SET NULL|SET DEFAULT";$mb[DRIVER]='Login';if(isset($_GET["select"])&&($_POST["edit"]||$_POST["clone"])&&!$_POST["save"])$_GET["edit"]=$_GET["select"];if(isset($_GET["download"])){$a=$_GET["download"];$m=fields($a);header("Content-Type: application/octet-stream");header("Content-Disposition: attachment; filename=".friendly_url("$a-".implode("_",$_GET["where"])).".".friendly_url($_GET["field"]));$P=array(idf_escape($_GET["field"]));$L=$i->select($a,$P,array(where($_GET,$m)),$P);$N=($L?$L->fetch_row():array());echo$i->value($N[0],$m[$_GET["field"]]);exit;}elseif(isset($_GET["edit"])){$a=$_GET["edit"];$m=fields($a);$Z=(isset($_GET["select"])?($_POST["check"]&&count($_POST["check"])==1?where_check($_POST["check"][0],$m):""):where($_GET,$m));$Ve=(isset($_GET["select"])?$_POST["edit"]:$Z);foreach($m
as$E=>$l){if(!isset($l["privileges"][$Ve?"update":"insert"])||$b->fieldName($l)==""||$l["generated"])unset($m[$E]);}if($_POST&&!$k&&!isset($_GET["select"])){$A=$_POST["referer"];if($_POST["insert"])$A=($Ve?null:$_SERVER["REQUEST_URI"]);elseif(!preg_match('~^.+&select=.+$~',$A))$A=ME."select=".urlencode($a);$v=indexes($a);$Qe=unique_array($_GET["where"],$v);$Cd="\nWHERE $Z";if(isset($_POST["delete"]))queries_redirect($A,'Item has been deleted.',$i->delete($a,$Cd,!$Qe));else{$R=array();foreach($m
as$E=>$l){$W=process_input($l);if($W!==false&&$W!==null)$R[idf_escape($E)]=$W;}if($Ve){if(!$R)redirect($A);queries_redirect($A,'Item has been updated.',$i->update($a,$R,$Cd,!$Qe));if(is_ajax()){page_headers();page_messages($k);exit;}}else{$L=$i->insert($a,$R);$Ac=($L?last_id():0);queries_redirect($A,sprintf('Item%s has been inserted.',($Ac?" $Ac":"")),$L);}}}$N=null;if($_POST["save"])$N=(array)$_POST["fields"];elseif($Z){$P=array();foreach($m
as$E=>$l){if(isset($l["privileges"]["select"])){$oa=convert_field($l);if($_POST["clone"]&&$l["auto_increment"])$oa="''";if($x=="sql"&&preg_match("~enum|set~",$l["type"]))$oa="1*".idf_escape($E);$P[]=($oa?"$oa AS ":"").idf_escape($E);}}$N=array();if(!support("table"))$P=array("*");if($P){$L=$i->select($a,$P,array($Z),$P,array(),(isset($_GET["select"])?2:1));if(!$L)$k=error();else{$N=$L->fetch_assoc();if(!$N)$N=false;}if(isset($_GET["select"])&&(!$N||$L->fetch_assoc()))$N=null;}}if(!support("table")&&!$m){if(!$Z){$L=$i->select($a,array("*"),$Z,array("*"));$N=($L?$L->fetch_assoc():false);if(!$N)$N=array($i->primary=>"");}if($N){foreach($N
as$y=>$W){if(!$Z)$N[$y]=null;$m[$y]=array("field"=>$y,"null"=>($y!=$i->primary),"auto_increment"=>($y==$i->primary));}}}edit_form($a,$m,$N,$Ve);}elseif(isset($_GET["select"])){$a=$_GET["select"];$T=table_status1($a);$v=indexes($a);$m=fields($a);$Rb=column_foreign_keys($a);$Zc=$T["Oid"];parse_str($_COOKIE["adminer_import"],$ia);$Md=array();$e=array();$xe=null;foreach($m
as$y=>$l){$E=$b->fieldName($l);if(isset($l["privileges"]["select"])&&$E!=""){$e[$y]=html_entity_decode(strip_tags($E),ENT_QUOTES);if(is_shortable($l))$xe=$b->selectLengthProcess();}$Md+=$l["privileges"];}list($P,$r)=$b->selectColumnsProcess($e,$v);$w=count($r)<count($P);$Z=$b->selectSearchProcess($m,$v);$G=$b->selectOrderProcess($m,$v);$z=$b->selectLimitProcess();if($_GET["val"]&&is_ajax()){header("Content-Type: text/plain; charset=utf-8");foreach($_GET["val"]as$Re=>$N){$oa=convert_field($m[key($N)]);$P=array($oa?$oa:idf_escape(key($N)));$Z[]=where_check($Re,$m);$M=$i->select($a,$P,$Z,$P);if($M)echo
reset($M->fetch_row());}exit;}$vd=$Te=null;foreach($v
as$lc){if($lc["type"]=="PRIMARY"){$vd=array_flip($lc["columns"]);$Te=($P?$vd:array());foreach($Te
as$y=>$W){if(in_array(idf_escape($y),$P))unset($Te[$y]);}break;}}if($Zc&&!$vd){$vd=$Te=array($Zc=>0);$v[]=array("type"=>"PRIMARY","columns"=>array($Zc));}if($_POST&&!$k){$hf=$Z;if(!$_POST["all"]&&is_array($_POST["check"])){$Ga=array();foreach($_POST["check"]as$Ea)$Ga[]=where_check($Ea,$m);$hf[]="((".implode(") OR (",$Ga)."))";}$hf=($hf?"\nWHERE ".implode(" AND ",$hf):"");if($_POST["export"]){cookie("adminer_import","output=".urlencode($_POST["output"])."&format=".urlencode($_POST["format"]));dump_headers($a);$b->dumpTable($a,"");$Tb=($P?implode(", ",$P):"*").convert_fields($e,$m,$P)."\nFROM ".table($a);$Wb=($r&&$w?"\nGROUP BY ".implode(", ",$r):"").($G?"\nORDER BY ".implode(", ",$G):"");if(!is_array($_POST["check"])||$vd)$K="SELECT $Tb$hf$Wb";else{$Pe=array();foreach($_POST["check"]as$W)$Pe[]="(SELECT".limit($Tb,"\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($W,$m).$Wb,1).")";$K=implode(" UNION ALL ",$Pe);}$b->dumpData($a,"table",$K);exit;}if(!$b->selectEmailProcess($Z,$Rb)){if($_POST["save"]||$_POST["delete"]){$L=true;$ja=0;$R=array();if(!$_POST["delete"]){foreach($e
as$E=>$W){$W=process_input($m[$E]);if($W!==null&&($_POST["clone"]||$W!==false))$R[idf_escape($E)]=($W!==false?$W:idf_escape($E));}}if($_POST["delete"]||$R){if($_POST["clone"])$K="INTO ".table($a)." (".implode(", ",array_keys($R)).")\nSELECT ".implode(", ",$R)."\nFROM ".table($a);if($_POST["all"]||($vd&&is_array($_POST["check"]))||$w){$L=($_POST["delete"]?$i->delete($a,$hf):($_POST["clone"]?queries("INSERT $K$hf"):$i->update($a,$R,$hf)));$ja=$f->affected_rows;}else{foreach((array)$_POST["check"]as$W){$gf="\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($W,$m);$L=($_POST["delete"]?$i->delete($a,$gf,1):($_POST["clone"]?queries("INSERT".limit1($a,$K,$gf)):$i->update($a,$R,$gf,1)));if(!$L)break;$ja+=$f->affected_rows;}}}$C=lang(array('%d item has been affected.','%d items have been affected.'),$ja);if($_POST["clone"]&&$L&&$ja==1){$Ac=last_id();if($Ac)$C=sprintf('Item%s has been inserted.'," $Ac");}queries_redirect(remove_from_uri($_POST["all"]&&$_POST["delete"]?"page":""),$C,$L);if(!$_POST["delete"]){edit_form($a,$m,(array)$_POST["fields"],!$_POST["clone"]);page_footer();exit;}}elseif(!$_POST["import"]){if(!$_POST["val"])$k='Ctrl+click on a value to modify it.';else{$L=true;$ja=0;foreach($_POST["val"]as$Re=>$N){$R=array();foreach($N
as$y=>$W){$y=bracket_escape($y,1);$R[idf_escape($y)]=(preg_match('~char|text~',$m[$y]["type"])||$W!=""?$b->processInput($m[$y],$W):"NULL");}$L=$i->update($a,$R," WHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($Re,$m),!$w&&!$vd," ");if(!$L)break;$ja+=$f->affected_rows;}queries_redirect(remove_from_uri(),lang(array('%d item has been affected.','%d items have been affected.'),$ja),$L);}}elseif(!is_string($Hb=get_file("csv_file",true)))$k=upload_error($Hb);elseif(!preg_match('~~u',$Hb))$k='File must be in UTF-8 encoding.';else{cookie("adminer_import","output=".urlencode($ia["output"])."&format=".urlencode($_POST["separator"]));$L=true;$Oa=array_keys($m);preg_match_all('~(?>"[^"]*"|[^"\r\n]+)+~',$Hb,$Jc);$ja=count($Jc[0]);$i->begin();$Vd=($_POST["separator"]=="csv"?",":($_POST["separator"]=="tsv"?"\t":";"));$O=array();foreach($Jc[0]as$y=>$W){preg_match_all("~((?>\"[^\"]*\")+|[^$Vd]*)$Vd~",$W.$Vd,$Kc);if(!$y&&!array_diff($Kc[1],$Oa)){$Oa=$Kc[1];$ja--;}else{$R=array();foreach($Kc[1]as$s=>$La)$R[idf_escape($Oa[$s])]=($La==""&&$m[$Oa[$s]]["null"]?"NULL":q(str_replace('""','"',preg_replace('~^"|"$~','',$La))));$O[]=$R;}}$L=(!$O||$i->insertUpdate($a,$O,$vd));if($L)$L=$i->commit();queries_redirect(remove_from_uri("page"),lang(array('%d row has been imported.','%d rows have been imported.'),$ja),$L);$i->rollback();}}}$se=$b->tableName($T);if(is_ajax()){page_headers();ob_start();}else
page_header('Select'.": $se",$k);$R=null;if(isset($Md["insert"])||!support("table")){$R="";foreach((array)$_GET["where"]as$W){if($Rb[$W["col"]]&&count($Rb[$W["col"]])==1&&($W["op"]=="="||(!$W["op"]&&!preg_match('~[_%]~',$W["val"]))))$R.="&set".urlencode("[".bracket_escape($W["col"])."]")."=".urlencode($W["val"]);}}$b->selectLinks($T,$R);if(!$e&&support("table"))echo"<p class='error'>".'Unable to select the table'.($m?".":": ".error())."\n";else{echo"<form action='' id='form'>\n","<div style='display: none;'>";hidden_fields_get();echo(DB!=""?'<input type="hidden" name="db" value="'.h(DB).'">'.(isset($_GET["ns"])?'<input type="hidden" name="ns" value="'.h($_GET["ns"]).'">':""):"");echo'<input type="hidden" name="select" value="'.h($a).'">',"</div>\n";$b->selectColumnsPrint($P,$e);$b->selectSearchPrint($Z,$e,$v);$b->selectOrderPrint($G,$e,$v);$b->selectLimitPrint($z);$b->selectLengthPrint($xe);$b->selectActionPrint($v);echo"</form>\n";$H=$_GET["page"];if($H=="last"){$o=$f->result(count_rows($a,$Z,$w,$r));$H=floor(max(0,$o-1)/$z);}$Qd=$P;$Vb=$r;if(!$Qd){$Qd[]="*";$Ua=convert_fields($e,$m,$P);if($Ua)$Qd[]=substr($Ua,2);}foreach($P
as$y=>$W){$l=$m[idf_unescape($W)];if($l&&($oa=convert_field($l)))$Qd[$y]="$oa AS $W";}if(!$w&&$Te){foreach($Te
as$y=>$W){$Qd[]=idf_escape($y);if($Vb)$Vb[]=idf_escape($y);}}$L=$i->select($a,$Qd,$Z,$Vb,$G,$z,$H,true);if(!$L)echo"<p class='error'>".error()."\n";else{if($x=="mssql"&&$H)$L->seek($z*$H);$ub=array();echo"<form action='' method='post' enctype='multipart/form-data'>\n";$O=array();while($N=$L->fetch_assoc()){if($H&&$x=="oracle")unset($N["RNUM"]);$O[]=$N;}if($_GET["page"]!="last"&&$z!=""&&$r&&$w&&$x=="sql")$o=$f->result(" SELECT FOUND_ROWS()");if(!$O)echo"<p class='message'>".'No rows.'."\n";else{$xa=$b->backwardKeys($a,$se);echo"<div class='scrollable'>","<table id='table' cellspacing='0' class='nowrap checkable'>",script("mixin(qs('#table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true), onkeydown: editingKeydown});"),"<thead><tr>".(!$r&&$P?"":"<td><input type='checkbox' id='all-page' class='jsonly'>".script("qs('#all-page').onclick = partial(formCheck, /check/);","")." <a href='".h($_GET["modify"]?remove_from_uri("modify"):$_SERVER["REQUEST_URI"]."&modify=1")."'>".'Modify'."</a>");$Tc=array();$Ub=array();reset($P);$Ed=1;foreach($O[0]as$y=>$W){if(!isset($Te[$y])){$W=$_GET["columns"][key($P)];$l=$m[$P?($W?$W["col"]:current($P)):$y];$E=($l?$b->fieldName($l,$Ed):($W["fun"]?"*":$y));if($E!=""){$Ed++;$Tc[$y]=$E;$d=idf_escape($y);$hc=remove_from_uri('(order|desc)[^=]*|page').'&order%5B0%5D='.urlencode($y);$gb="&desc%5B0%5D=1";echo"<th>".script("mixin(qsl('th'), {onmouseover: partial(columnMouse), onmouseout: partial(columnMouse, ' hidden')});",""),'<a href="'.h($hc.($G[0]==$d||$G[0]==$y||(!$G&&$w&&$r[0]==$d)?$gb:'')).'">';echo
apply_sql_function($W["fun"],$E)."</a>";echo"<span class='column hidden'>","<a href='".h($hc.$gb)."' title='".'descending'."' class='text'> ‚Üì</a>";if(!$W["fun"]){echo'<a href="#fieldset-search" title="'.'Search'.'" class="text jsonly"> =</a>',script("qsl('a').onclick = partial(selectSearch, '".js_escape($y)."');");}echo"</span>";}$Ub[$y]=$W["fun"];next($P);}}$Dc=array();if($_GET["modify"]){foreach($O
as$N){foreach($N
as$y=>$W)$Dc[$y]=max($Dc[$y],min(40,strlen(utf8_decode($W))));}}echo($xa?"<th>".'Relations':"")."</thead>\n";if(is_ajax()){if($z%2==1&&$H%2==1)odd();ob_end_clean();}foreach($b->rowDescriptions($O,$Rb)as$D=>$N){$Qe=unique_array($O[$D],$v);if(!$Qe){$Qe=array();foreach($O[$D]as$y=>$W){if(!preg_match('~^(COUNT\((\*|(DISTINCT )?`(?:[^`]|``)+`)\)|(AVG|GROUP_CONCAT|MAX|MIN|SUM)\(`(?:[^`]|``)+`\))$~',$y))$Qe[$y]=$W;}}$Re="";foreach($Qe
as$y=>$W){if(($x=="sql"||$x=="pgsql")&&preg_match('~char|text|enum|set~',$m[$y]["type"])&&strlen($W)>64){$y=(strpos($y,'(')?$y:idf_escape($y));$y="MD5(".($x!='sql'||preg_match("~^utf8~",$m[$y]["collation"])?$y:"CONVERT($y USING ".charset($f).")").")";$W=md5($W);}$Re.="&".($W!==null?urlencode("where[".bracket_escape($y)."]")."=".urlencode($W):"null%5B%5D=".urlencode($y));}echo"<tr".odd().">".(!$r&&$P?"":"<td>".checkbox("check[]",substr($Re,1),in_array(substr($Re,1),(array)$_POST["check"])).($w||information_schema(DB)?"":" <a href='".h(ME."edit=".urlencode($a).$Re)."' class='edit'>".'edit'."</a>"));foreach($N
as$y=>$W){if(isset($Tc[$y])){$l=$m[$y];$W=$i->value($W,$l);if($W!=""&&(!isset($ub[$y])||$ub[$y]!=""))$ub[$y]=(is_mail($W)?$Tc[$y]:"");$_="";if(preg_match('~blob|bytea|raw|file~',$l["type"])&&$W!="")$_=ME.'download='.urlencode($a).'&field='.urlencode($y).$Re;if(!$_&&$W!==null){foreach((array)$Rb[$y]as$Qb){if(count($Rb[$y])==1||end($Qb["source"])==$y){$_="";foreach($Qb["source"]as$s=>$de)$_.=where_link($s,$Qb["target"][$s],$O[$D][$de]);$_=($Qb["db"]!=""?preg_replace('~([?&]db=)[^&]+~','\1'.urlencode($Qb["db"]),ME):ME).'select='.urlencode($Qb["table"]).$_;if($Qb["ns"])$_=preg_replace('~([?&]ns=)[^&]+~','\1'.urlencode($Qb["ns"]),$_);if(count($Qb["source"])==1)break;}}}if($y=="COUNT(*)"){$_=ME."select=".urlencode($a);$s=0;foreach((array)$_GET["where"]as$V){if(!array_key_exists($V["col"],$Qe))$_.=where_link($s++,$V["col"],$V["val"],$V["op"]);}foreach($Qe
as$uc=>$V)$_.=where_link($s++,$uc,$V);}$W=select_value($W,$_,$l,$xe);$t=h("val[$Re][".bracket_escape($y)."]");$X=$_POST["val"][$Re][bracket_escape($y)];$qb=!is_array($N[$y])&&is_utf8($W)&&$O[$D][$y]==$N[$y]&&!$Ub[$y];$we=preg_match('~text|lob~',$l["type"]);echo"<td id='$t'";if(($_GET["modify"]&&$qb)||$X!==null){$Yb=h($X!==null?$X:$N[$y]);echo">".($we?"<textarea name='$t' cols='30' rows='".(substr_count($N[$y],"\n")+1)."'>$Yb</textarea>":"<input name='$t' value='$Yb' size='$Dc[$y]'>");}else{$Gc=strpos($W,"<i>‚Ä¶</i>");echo" data-text='".($Gc?2:($we?1:0))."'".($qb?"":" data-warning='".h('Use edit link to modify this value.')."'").">$W</td>";}}}if($xa)echo"<td>";$b->backwardKeysPrint($xa,$O[$D]);echo"</tr>\n";}if(is_ajax())exit;echo"</table>\n","</div>\n";}if(!is_ajax()){if($O||$H){$Ab=true;if($_GET["page"]!="last"){if($z==""||(count($O)<$z&&($O||!$H)))$o=($H?$H*$z:0)+count($O);elseif($x!="sql"||!$w){$o=($w?false:found_rows($T,$Z));if($o<max(1e4,2*($H+1)*$z))$o=reset(slow_query(count_rows($a,$Z,$w,$r)));else$Ab=false;}}$ld=($z!=""&&($o===false||$o>$z||$H));if($ld){echo(($o===false?count($O)+1:$o-$H*$z)>$z?'<p><a href="'.h(remove_from_uri("page")."&page=".($H+1)).'" class="loadmore">'.'Load more data'.'</a>'.script("qsl('a').onclick = partial(selectLoadMore, ".(+$z).", '".'Loading'."‚Ä¶');",""):''),"\n";}}echo"<div class='footer'><div>\n";if($O||$H){if($ld){$Lc=($o===false?$H+(count($O)>=$z?2:1):floor(($o-1)/$z));echo"<fieldset>";if($x!="simpledb"){echo"<legend><a href='".h(remove_from_uri("page"))."'>".'Page'."</a></legend>",script("qsl('a').onclick = function () { pageClick(this.href, +prompt('".'Page'."', '".($H+1)."')); return false; };"),pagination(0,$H).($H>5?" ‚Ä¶":"");for($s=max(1,$H-4);$s<min($Lc,$H+5);$s++)echo
pagination($s,$H);if($Lc>0){echo($H+5<$Lc?" ‚Ä¶":""),($Ab&&$o!==false?pagination($Lc,$H):" <a href='".h(remove_from_uri("page")."&page=last")."' title='~$Lc'>".'last'."</a>");}}else{echo"<legend>".'Page'."</legend>",pagination(0,$H).($H>1?" ‚Ä¶":""),($H?pagination($H,$H):""),($Lc>$H?pagination($H+1,$H).($Lc>$H+1?" ‚Ä¶":""):"");}echo"</fieldset>\n";}echo"<fieldset>","<legend>".'Whole result'."</legend>";$kb=($Ab?"":"~ ").$o;echo
checkbox("all",1,0,($o!==false?($Ab?"":"~ ").lang(array('%d row','%d rows'),$o):""),"var checked = formChecked(this, /check/); selectCount('selected', this.checked ? '$kb' : checked); selectCount('selected2', this.checked || !checked ? '$kb' : checked);")."\n","</fieldset>\n";if($b->selectCommandPrint()){echo'<fieldset',($_GET["modify"]?'':' class="jsonly"'),'><legend>Modify</legend><div>
<input type="submit" value="Save"',($_GET["modify"]?'':' title="'.'Ctrl+click on a value to modify it.'.'"'),'>
</div></fieldset>
<fieldset><legend>Selected <span id="selected"></span></legend><div>
<input type="submit" name="edit" value="Edit">
<input type="submit" name="clone" value="Clone">
<input type="submit" name="delete" value="Delete">',confirm(),'</div></fieldset>
';}$Sb=$b->dumpFormat();foreach((array)$_GET["columns"]as$d){if($d["fun"]){unset($Sb['sql']);break;}}if($Sb){print_fieldset("export",'Export'." <span id='selected2'></span>");$jd=$b->dumpOutput();echo($jd?html_select("output",$jd,$ia["output"])." ":""),html_select("format",$Sb,$ia["format"])," <input type='submit' name='export' value='".'Export'."'>\n","</div></fieldset>\n";}$b->selectEmailPrint(array_filter($ub,'strlen'),$e);}echo"</div></div>\n";if($b->selectImportPrint()){echo"<div>","<a href='#import'>".'Import'."</a>",script("qsl('a').onclick = partial(toggle, 'import');",""),"<span id='import' class='hidden'>: ","<input type='file' name='csv_file'> ",html_select("separator",array("csv"=>"CSV,","csv;"=>"CSV;","tsv"=>"TSV"),$ia["format"],1);echo" <input type='submit' name='import' value='".'Import'."'>","</span>","</div>";}echo"<input type='hidden' name='token' value='$Fe'>\n","</form>\n",(!$r&&$P?"":script("tableCheck();"));}}}if(is_ajax()){ob_end_clean();exit;}}elseif(isset($_GET["script"])){if($_GET["script"]=="kill")$f->query("KILL ".number($_POST["kill"]));elseif(list($S,$t,$E)=$b->_foreignColumn(column_foreign_keys($_GET["source"]),$_GET["field"])){$z=11;$L=$f->query("SELECT $t, $E FROM ".table($S)." WHERE ".(preg_match('~^[0-9]+$~',$_GET["value"])?"$t = $_GET[value] OR ":"")."$E LIKE ".q("$_GET[value]%")." ORDER BY 2 LIMIT $z");for($s=1;($N=$L->fetch_row())&&$s<$z;$s++)echo"<a href='".h(ME."edit=".urlencode($S)."&where".urlencode("[".bracket_escape(idf_unescape($t))."]")."=".urlencode($N[0]))."'>".h($N[1])."</a><br>\n";if($N)echo"...\n";}exit;}else{page_header('Server',"",false);if($b->homepage()){echo"<form action='' method='post'>\n","<p>".'Search data in tables'.": <input type='search' name='query' value='".h($_POST["query"])."'> <input type='submit' value='".'Search'."'>\n";if($_POST["query"]!="")search_tables();echo"<div class='scrollable'>\n","<table cellspacing='0' class='nowrap checkable'>\n",script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"),'<thead><tr class="wrap">','<td><input id="check-all" type="checkbox" class="jsonly">'.script("qs('#check-all').onclick = partial(formCheck, /^tables\[/);",""),'<th>'.'Table','<td>'.'Rows',"</thead>\n";foreach(table_status()as$S=>$N){$E=$b->tableName($N);if(isset($N["Engine"])&&$E!=""){echo'<tr'.odd().'><td>'.checkbox("tables[]",$S,in_array($S,(array)$_POST["tables"],true)),"<th><a href='".h(ME).'select='.urlencode($S)."'>$E</a>";$W=format_number($N["Rows"]);echo"<td align='right'><a href='".h(ME."edit=").urlencode($S)."'>".($N["Engine"]=="InnoDB"&&$W?"~ $W":$W)."</a>";}}echo"</table>\n","</div>\n","</form>\n",script("tableCheck();");}}page_footer();