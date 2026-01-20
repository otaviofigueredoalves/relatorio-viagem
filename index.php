<?php
session_start();
if(!empty($_SESSION['cpf']) && !empty($_SESSION['nome_completo'])){
    $cpf_value = $_SESSION['cpf'];
    $nome = $_SESSION['nome_completo'];
}
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once __DIR__ . '/GeradorRelatorio.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'cidade' => $_POST['cidade'],
        'data_i' => $_POST['data_i'],
        'data_f' => $_POST['data_f'],
        'historico' => $_POST['historico'],
        'motorista' => $_POST['motorista'],
        'veiculo' => $_POST['veiculo'],
        'saida' => $_POST['saida'],
        'chegada' => $_POST['chegada'],
        'cpf' => $_POST['cpf'],
        'extra' => $_POST['extra']
    ];

    $_SESSION['cpf'] = $_POST['cpf'] ?? null;
    $_SESSION['nome_completo'] = $_POST['motorista'] ?? null;


    echo $cpf_value;
    echo $nome;


    $gerador = new GeradorRelatorio();
    $pdfBinario = $gerador->renderizar($dados);

    ob_clean();

    $data = date('d-m-Y');
    $nomeArquivo = "relatorio_viagem-$data.pdf";
    header('Content-Type: application/pdf');
    header("Content-Disposition: attachment; filename=\"{$nomeArquivo}\"");
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    echo $pdfBinario;
    exit;

}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Viagem</title>
    <link rel="stylesheet" href="/src/css/style.css">
</head>
<body>

<div class="container">
    <h1>Preencher Relatório de Viagem</h1>
    <p>Os dados abaixo serão sobrepostos ao PDF oficial.</p>

    <form action="" method="POST">
        <div class="form-group">
            <label for="motorista">Motorista: </label>
            <input type="text" name="motorista" id="motorista" value="<?=$nome?>" required>
        </div>
        <div class="form-group">
            <label for="cpf">CPF: </label>
            <input type="text" name="cpf" id="cpf" value="<?php echo $cpf_value?>" required>
        </div>
        
        <div class="form-group">
            <label for="cidade">Cidade: </label>
            <input type="text" name="cidade" id="cidade" required>
        </div>

        <div class="form-group">
            <label for="veiculo">Veiculo: </label>
            <input type="text" name="veiculo" id="veiculo" required>
        </div>

        <div class="form-group">
            <label for="data_i">Data inicial: </label>
            <input type="date" name="data_i" id="data_i" required>
        </div>
        
        <div class="form-group">
            <label for="data_f">Data final: </label>
            <input type="date" name="data_f" id="data_f" required>
        </div>

        <div class="form-group">
            <label for="historico">Historico </label>
            <textarea type="text" rows='3' name="historico" id="historico" required></textarea>
        </div>

        <div class="form-group">
            <label for="saida">Saída: </label>
            <input type="text" name="saida" id="saida" required>
        </div>

        <div class="form-group">
            <label for="chegada">Chegada: </label>
            <input type="text" name="chegada" id="chegada" required >
        </div>
    
        <label for="extra" id="label-extra">Viagem extra </label>
        <input type="checkbox" name="extra" id="extra">

        <button type="submit" class="btn-gerar">Gerar e Enviar PDF</button>
    </form>
  
</div>

</body>
</html>