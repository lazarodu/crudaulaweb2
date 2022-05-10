<?php
class Form
{
  private $message = "";
  private $error = "";
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template("view/form.html");
    $form->set("id", "");
    $form->set("marca", "");
    $form->set("configuracao", "");
    $form->set("valor", "");
    $this->message = $form->saida();
  }
  public function salvar()
  {
    if (isset($_POST['marca']) && isset($_POST['configuracao']) && isset($_POST['valor'])) {
      try {
        $conexao = Transaction::get();
        $computador = new Crud('computador');
        $marca = $conexao->quote($_POST['marca']);
        $configuracao = $conexao->quote($_POST['configuracao']);
        $valor = $conexao->quote($_POST['valor']);
        if (empty($_POST["id"])) {
          $computador->insert("marca,configuracao,valor", "$marca,$configuracao,$valor");
        } else {
          $id = $conexao->quote($_POST['id']);
          $computador->update("marca=$marca,configuracao=$configuracao,valor=$valor", "id=$id");
        }
        $this->message = $computador->getMessage();
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }
  }
  public function editar()
  {
    if (isset($_GET['id'])) {
      try {
        $conexao = Transaction::get();
        $id = $conexao->quote($_GET['id']);
        $computador = new Crud('computador');
        $resultado = $computador->select("*", "id=$id");
        $form = new Template("view/form.html");
        foreach ($resultado[0] as $cod => $valor) {
          $form->set($cod, $valor);
        }
        $this->message = $form->saida();
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }
  }
  public function getMessage()
  {
    return $this->message;
  }
  public function __destruct()
  {
    Transaction::close();
  }
}
