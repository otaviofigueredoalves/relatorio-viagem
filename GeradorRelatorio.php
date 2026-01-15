<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class GeradorRelatorio {
    private $dompdf;

    public function __construct() {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        // IMPORTANTE: O chroot deve apontar para a pasta pai se o template estiver numa subpasta
        $options->set('chroot', realpath(__DIR__)); 

        $this->dompdf = new Dompdf($options);
    }

    public function renderizar($dados) {
        // Usamos o Buffer de saída para não misturar HTML com PHP
        ob_start();
        ?>
        <style>
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
                color: #000080; /* Azul caneta */
                font-size: 14px;
                font-weight: bold;
            }
            /* Aqui você mapeia cada campo do seu formulário físico */
            #cidade { top: 106mm; left: 137mm; background-color: #fff; width: 47mm}
            #data_i { top: 116mm; left: 15mm; background-color: #fff; width: 22mm}
            #data_f { top: 116mm; left: 42mm; background-color: #fff; width: 22mm}
            #historico { top: 168mm; left: 17mm; width: 173mm; line-height: 5.6mm; }
            #motorista { top: 238mm; left: 36mm; }
            #veiculo { top: 245mm; left: 45mm; }
            #saida { top: 253mm; left: 30mm; }
            #chegada { top: 260mm; left: 35mm; }

            .campo {
                position: absolute;
                color: #000;
                font-size: 14px;
                font-weight: bold;
                /* border: 0.1mm solid rgba(255, 0, 0, 0.5);  */
                /* background-color: rgba(255, 255, 0, 0.1); */
            }

            
        </style>

       <img src="<?php echo realpath(__DIR__ . '/template/relatorio_osvaldo.jpg'); ?>" class="background">

        <div id="cidade" class="campo"><?php echo $dados['cidade']; ?></div>
        <div id="data_i" class="campo"><?php echo date('d/m/Y', strtotime($dados['data_i'])) ; ?></div>
        <div id="data_f" class="campo"><?php echo date('d/m/Y', strtotime($dados['data_f'])); ?></div>
        <div id="historico" class="campo"><?php echo nl2br(htmlspecialchars($dados['historico'])); ?></div>
        <div id="motorista" class="campo"><?php echo $dados['motorista']; ?></div>
        <div id="veiculo" class="campo"><?php echo $dados['veiculo']; ?></div>
        <div id="saida" class="campo"><?php echo $dados['saida']; ?></div>
        <div id="chegada" class="campo"><?php echo $dados['chegada']; ?></div>
        
        <?php
        $html = ob_get_clean();

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();

        return $this->dompdf->output(); // Retorna o binário do PDF
    }
}