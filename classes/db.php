<?php
// Query databases using PDO
class DB
{
public$pdo;static$q=array();
function __construct($dns,$u,$p,$a=NULL){$this->pdo=new PDO($dns,$u,$p,$a);}
function row($q,$p=NULL){return(($s=$this->query($q,$p))?$s->fetch(PDO::FETCH_OBJ):NULL);}
function fetch($q,$p=NULL){return(($s=$this->query($q,$p))?$s->fetchAll(PDO::FETCH_OBJ):NULL);}
function query($q,$p=NULL){self::$q[]=$q;$s=$this->pdo->prepare($q);$s->execute($p);return$s;}
function insert($t,$d){$q="INSERT INTO `$t` (`".implode('`,`',array_keys($d)).'`)VALUES('.rtrim(str_repeat('?,',count($d)),',').')';return $this->query($q,array_values($d))?$this->pdo->lastInsertId():0;}
function update($t,$d,$w=NULL){$q="UPDATE `$t` SET `".implode('`=?,`',array_keys($d)).'`=? WHERE ';list($a,$b)=self::where($w);return(($s=$this->query($q.$a,array_merge(array_values($d),$b)))?$s->rowCount():NULL);}
static function where($w=0){$a=$s=array();if($w){foreach($w as$c=>$v){if(is_int($c))$s[]=$v;else{$s[]="`$c`=?";$a[]=$v;}}}return array(join(' AND ',$s),$a);}
}