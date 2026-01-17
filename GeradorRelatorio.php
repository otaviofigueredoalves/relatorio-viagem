<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class GeradorRelatorio {
    private $dompdf;

    public function __construct() {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', realpath(__DIR__)); 

        $this->dompdf = new Dompdf($options);
    }

    public function renderizar($dados) {
        ob_start();
        ?>
        <style>
            #cidade {
                top: 87.5mm;
                left: 73mm;
                background-color: #fff;
                width: 47mm
            }

            #data_i {
                text-align: center;
                top: 92mm;
                left: 35mm;
                background-color: #fff;
                width: 27mm;
                font-size: 15px;
            }

            #data_f {
                text-align: center;
                top: 92mm;
                left: 65.5mm;
                background-color: #fff;
                width: 27mm;
                font-size: 15px;
            }

            #historico {
                top: 130mm;
                left: 28.5mm;
                width: 148mm;
                line-height: 5.8mm;
                word-wrap: break-word;
            }

            #motorista {
                top: 68mm;
                left: 97mm;
            
            }
            #nome_motorista{
                top: 202mm;
                left: 50mm;
            }
            #cpf {
                top: 73mm;
                left: 30mm;
            }

            #veiculo {
                top: 210mm;
                left: 58mm;
            }

            #saida {
                top: 217mm;
                left: 43mm;
            }

            #chegada {
                top: 224.5mm;
                left: 49mm;
            }

            <?php 
            if(!empty($dados['extra'])){
                echo "@import url(/src/css/extra.css)";
            }

            ?>
            @page { margin: 0; }
            body {
                margin: 0;
                padding: 0;
                width: 210mm;
                height: 297mm;
                font-family: Arial, sans-serif;
            }
            .background {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
            }
            

            .campo {
                position: absolute;
                color: #000;
                font-size: 14px;
                font-weight: bold;
                /* border: 1px solid red; */
            }

            #cpf{
                /* font-size: 15px; */
                background: #fff;
                width: 40mm
            }

            #cidade {
                background: none;
            }

            
        </style>
        
     
        <img src="<?php
        if(!empty($dados['extra'])){
            echo realpath(__DIR__ . '/template/RELATORIO-VIAGEM-EXTRA.jpg'); 
        } else {
            echo realpath(__DIR__ . '/template/RELATORIO-VIAGEM.jpg'); 
        }

        ?>" class="background">

        <div id="cidade" class="campo"><?php echo $dados['cidade'] . '-CE'; ?></div>
        <div id="data_i" class="campo"><?php echo date('d/m/Y', strtotime($dados['data_i'])) ; ?></div>
        <div id="data_f" class="campo"><?php echo date('d/m/Y', strtotime($dados['data_f'])); ?></div>
        <div id="historico" class="campo"><?php echo nl2br(htmlspecialchars($dados['historico'])); ?></div>
        <div id="motorista" class="campo"><?php echo ucwords($dados['motorista']); ?></div>
        <div id="nome_motorista" class="campo"><?php echo ucwords($dados['motorista']); ?></div>
        <div id="cpf" class="campo"><?php 

        echo $dados['cpf'][0] .  $dados['cpf'][1] .  $dados['cpf'][2] . '.' .  $dados['cpf'][3] .  $dados['cpf'][4] .  $dados['cpf'][5] . '.' .  $dados['cpf'][6] .  $dados['cpf'][7] .  $dados['cpf'][8] . '-' .  $dados['cpf'][9] .  $dados['cpf'][10]; 
        
        
        ?></div>
        <div id="veiculo" class="campo"><?php echo $dados['veiculo']; ?></div>
        <div id="saida" class="campo"><?php echo $dados['saida']; ?></div>
        <div id="chegada" class="campo"><?php echo $dados['chegada']; ?></div>
        
        <?php
        $html = ob_get_clean();

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();

        return $this->dompdf->output();
    }
}