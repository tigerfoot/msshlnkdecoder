<?php
/**
 * @desc simple test script for lnkdecoder.class.php
 * 
 */
require 'lnkdecoder.class.php';

function giveme_files($store=array()){


    foreach ($store as $key => $f){
        
        $f = mb_convert_encoding($f, 'UTF-8');
        try{

            echo "        ===================            " . PHP_EOL;
            echo "  Working $f " . PHP_EOL;
            echo "        ===================            " . PHP_EOL;
            //                  var_dump($of->__toString());
            $of = new SplFileInfo("$f");
            if ($of->isFile()){
                echo 'fichier ';
                if ($of->isReadable()){
                    echo "lisible";
                }else{
                    echo "non lisible";
                }
                echo PHP_EOL;
            }elseif ($of->isDir()){
                echo 'répertoire ';
                if ($of->isReadable()){
                    echo "lisible";
                }else{
                    echo "non lisible";
                }
                echo PHP_EOL;
            }elseif (!$of->isReadable()){
                echo " No target available ".PHP_EOL;
            }else{
                echo " --- Ceci est un bug --- ".PHP_EOL;
        // Debug
//         print "************************** Debug **************";
//         print $of->getType() . '
//         ' . $of->getPath() .  ' ' . $of->getPathname() . '
//         ' . $of->getFilename() . ' ' .$of->getBasename() . ' ' . $of->getExtension() . '
//         ' . $of->getATime() . ' ' . $of->getCTime() . ' ' . $of->getMTime() . '
//         ' . $of->getOwner() . ':' . $of->getGroup() . ' ' . $of->getPerms() . '
//         ' . $of->getInode() . ' ' . $of->getSize() . PHP_EOL;
        }

        }catch ( RuntimeException $r){

            echo sprintf("%s : %s",$r->getCode(), $r->getMessage()).PHP_EOL;
            echo $r->getTraceAsString() . PHP_EOL;


        }catch ( Exception $e){

            echo sprintf("%s : %s",$e->getCode(), $e->getMessage()).PHP_EOL;
            echo $e->getTraceAsString() . PHP_EOL;
        // die();

        }
    }
}
// http://www.php.net/manual/en/function.chr.php#89488
function echocolor($text,$color="normal",$back=0)
{
  $colors = array('light_red'  => "[1;31m", 'light_green' => "[1;32m", 'yellow'     => "[1;33m",
                  'light_blue' => "[1;34m", 'magenta'     => "[1;35m", 'light_cyan' => "[1;36m",
                  'white'      => "[1;37m", 'normal'      => "[0m",    'black'      => "[0;30m",
                  'red'        => "[0;31m", 'green'       => "[0;32m", 'brown'      => "[0;33m",
                  'blue'       => "[0;34m", 'cyan'        => "[0;36m", 'bold'       => "[1m",
                  'underscore' => "[4m",    'reverse'     => "[7m" );
  $out = $colors["$color"];
  $ech = chr(27)."$out"."$text".chr(27)."[0m";
  if($back)
  {
    return $ech;
  }
    else
  {
    echo $ech . PHP_EOL;
  }
}

function failed () 
{
  echocolor(" FAILED",$color="red",$back=0);
}

function passed () 
{
  echocolor(" PASSED",$color="green",$back=0);
}

function separator () 
{
  echocolor ("          =====", $color="magenta",$back=0);
}


$msshlnk = array();
$FOLDER = 'samples';
$test_files = new DirectoryIterator($FOLDER);

echo "=======================================" . PHP_EOL;
echo "======== Test Suite for MSSHLNK =======" . PHP_EOL;
echo "=======================================" . PHP_EOL;
echo "             Opening Files" . PHP_EOL;
echo "=======================================" . PHP_EOL;

foreach ($test_files as $file) 
{ 
  if (!$test_files->isDot()) {
    $f = $file->getFilename();
    echo 'Opening lnk file "' . $f . '"... ' ;
    //var_dump($file);
    $msshlnk[$f] = new MSshlnk();
    if (!$msshlnk[$f]->open($file->getPathname())) {
        failed() . PHP_EOL;
        echo '      errno='
        . $msshlnk[$f]->errno
        . ' errstring="'
        . $msshlnk[$f]->errstring 
        . '"' . PHP_EOL;
        unset($msshlnk[$f]);
      } else {
         passed() . PHP_EOL;
      }
      echo PHP_EOL;
  }
}
unset ($test_files);
unset ($file);
clearstatcache();
$tomove=array();
// $file_store = "/tmp/store_lnk.csv";
// try{
//     $fs = new SplFileObject($file_store, 'wb');
// }catch(Exception $e){
//     die($e->getCode . ' ' . $e->getMessage());
// }

foreach($msshlnk as $key => $lnk) {
  echo PHP_EOL;
  echo PHP_EOL;
  echo PHP_EOL;
  echo "=======================================" . PHP_EOL;
  echo "        ===================            " . PHP_EOL;
  echo "  parse_LinkInfo for $key" . PHP_EOL;
  echo "        ===================            " . PHP_EOL;
  $lnk->parse();
//  echo "LinkFlags= "; print_r($lnk->LinkFlags);
//  echo "StructSize= "; print_r($lnk->StructSize);
//  echo "ParsedInfo= "; @print_r($lnk->ParsedInfo);
//        echo "Path source to move = " . str_replace('\\', '/', $lnk->ParsedInfo['LinkInfo']['CommonPathSuffix']).PHP_EOL;
  // $tomove[] = rtrim('/sigeom/install/'.str_replace('\\', '/', $lnk->ParsedInfo['LinkInfo']['CommonPathSuffix']));
  $tomove[] = '/sigeom/install/'.str_replace('\\', '/', $lnk->ParsedInfo['LinkInfo']['CommonPathSuffix']);
  unset($lnk);
//  $fs->fwrite(rtrim($str));
  // echo mb_convert_encoding(rtrim($str),'UTF-8');
//  echo "debug" . PHP_EOL;
//  foreach (str_split(rtrim($str)) as $char){
//      printf ("%s ", ord($char));
       
echo "=======================================" . PHP_EOL;
}
  unset ($msshlnk);
  clearstatcache();
    
  echo PHP_EOL;
  echo PHP_EOL;
  echo "=======================================" . PHP_EOL;

  // $store = serialize($tomove);

  giveme_files($tomove);


//print_r( str_split($msshlnk[$key]->lnk_bin));

echo "========== End of test suite ==========" . PHP_EOL;
