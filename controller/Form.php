<?php
class Form
{
  private $message = "";
  public function controller()
  {
    $form = new Template("view/form.html");
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
        $resultado = $computador->insert("marca,configuracao,valor", "$marca,$configuracao,$valor");
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }
  }
  public function getMessage()
  {
    return $this->message;
  }
}
