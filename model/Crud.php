<?php
class Crud
{
  private $tabela;
  public function __construct($tabela)
  {
    $this->tabela = $tabela;
  }
  public function select($campos = "*", $condicao = NULL)
  {
    $conexao = Transaction::get();
  }
}
