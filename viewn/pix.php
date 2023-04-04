<?php 
global $wpdb;

$value_1_checked = get_post_meta('1', 'chave', true);
if ($value_1_checked == null) { $value_print_1 = ""; } else { $value_print_1 = $value_1_checked; }

$value_2_checked = get_post_meta('1', 'beneficiario', true);
if ($value_2_checked == null) { $value_print_2 = ""; } else { $value_print_2 = $value_2_checked; }

$value_3_checked = get_post_meta('1', 'titulo', true);
if ($value_3_checked == null) { $value_print_3 = ""; } else { $value_print_3 = $value_3_checked; }

// $value_4_checked = get_post_meta('1', 'valor', true);
// if ($value_4_checked == null) { $value_print_4 = ""; } else { $value_print_4 = $value_4_checked; }

$value_5_checked = get_post_meta('1', 'cidade', true);
if ($value_5_checked == null) { $value_print_5 = ""; } else { $value_print_5 = $value_5_checked; }


//valores para o QRCODE PHP
$chave_pix = $chave_pix;
$beneficiario_pix = $beneficiario_pix;
$cidade_pix = $cidade_pix;
$identificador = "***";
$descricao = "{$descricao_pix} - (#{$order_id})";
$gerar_qrcode = true;
$valor_pix = $order_total;
$QR_BASEDIR = dirname(__FILE__).DIRECTORY_SEPARATOR;


if ($gerar_qrcode){

	include require "".$QR_BASEDIR."vendor/phpqrcode/qrlib.php";
	
	$px[00]="01"; //Payload Format Indicator, Obrigatório, valor fixo: 01

	$px[26][00]="br.gov.bcb.pix"; 
	$px[26][01]=$chave_pix;
	if (!empty($descricao)) {
		$tam_max_descr=99-(4+4+4+14+strlen($chave_pix));
		if (strlen($descricao) > $tam_max_descr) {
			$descricao=substr($descricao,0,$tam_max_descr);
		}
		$px[26][02]=$descricao;
	}

	$px[52]="0000"; //Merchant Category Code “0000” ou MCC ISO18245
    $px[53]="986"; //Moeda, “986” = BRL: real brasileiro - ISO4217
    if ($valor_pix > 0) {
    	$px[54]=$valor_pix;
    }

    $px[58]="BR"; //“BR” – Código de país ISO3166-1 alpha 2
    $px[59]=$beneficiario_pix; //Nome do beneficiário/recebedor. Máximo: 25 caracteres.
    $px[60]=$cidade_pix; //Nome cidade onde é efetuada a transação. Máximo 15 caracteres.
    $px[62][05]=$identificador;

    $pix=montaPix($px);
    $pix.="6304"; //Adiciona o campo do CRC no fim da linha do pix.
    $pix.=crcChecksum($pix); //Calcula o checksum CRC16 e acrescenta ao final.
    $linhas=round(strlen($pix)/120)+1;

    //gero o pix
    ob_start();
	QRCode::png($pix, null,'M',5);
	$imageString = base64_encode( ob_get_contents() );
	ob_end_clean();
	$pix_img = '<img style="width: 20em" src="data:image/png;base64,' . $imageString . '"></p>';

} else {
	$pix_img = '<img style="width: 20em" src="data:image/png;base64,"></p>';
} ?>
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.16.2/dist/css/uikit.min.css" />
<script src="https://cdn.jsdelivr.net/npm/uikit@3.16.2/dist/js/uikit.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.16.2/dist/js/uikit-icons.min.js"></script> -->
<style>
	.item-manager {
		background-color: #fff; color: #666; box-shadow: 0 5px 15px rgb(0 0 0 / 8%);
		border-radius: 5px; padding: 1em 2em;
	}

	.uk-input, .uk-select, .uk-textarea {
	    background: #fff; color: #666; border: 1px solid #e5e5e5; transition: .2s ease-in-out; 
	    transition-property: color,background-color,border;
	}
</style>
<center>
	<div class="item-manager">
	    <?php echo $pix_img; ?>
	    <div class="card card-pix-edit">
	      <div class="card-body">
	      	<form class="uk-grid-small" uk-grid>
			    <div class="uk-width-1-1">
			    	<textarea class="uk-textarea" style="text-align: center; height: 6em; width: 100%;" id="brcodepix" rows="<?= $linhas; ?>" onclick="copiar()"><?= $pix;?></textarea>
			    </div>
			    <div class="uk-width-1-1">
			    	 <button style="background-color: #00ae25!important; color: #fff; border: solid 0px #00ae25; padding: 1em 2em; margin-top: 1em; border-radius: 25px; cursor: pointer; font-weight: bold;" type="button" id="clip_btn" data-toggle="tooltip" data-placement="top" title="Copiar código pix" onclick="copiar()">COPIAR PIX</button>
			    </div>
			</form>
	      </div>
	    </div>
	</div>
</center>
<script type="text/javascript">
	function copiar() {
	  var copyText = document.getElementById("brcodepix");
	  copyText.select();
	  copyText.setSelectionRange(0, 99999); /* For mobile devices */
	  document.execCommand("copy");
	  document.getElementById("clip_btn").innerHTML='PIX COPIADO';
	}
</script>
