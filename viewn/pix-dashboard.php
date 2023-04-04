<?php 
$log_mensagens = "";
global $wpdb;

if (isset($_POST['enviar'])) {

	$value_1 = get_post_meta('1', 'chave');
	if ($value_1 == null) { 
		add_post_meta('1', 'chave', $_POST['chave']); 
    } else { 
    	update_post_meta('1', 'chave', $_POST['chave']); 
    }

    $value_2 = get_post_meta('1', 'beneficiario');
	if ($value_2 == null) { 
		add_post_meta('1', 'beneficiario', $_POST['beneficiario']); 
    } else { 
    	update_post_meta('1', 'beneficiario', $_POST['beneficiario']); 
    }

    $value_3 = get_post_meta('1', 'titulo');
	if ($value_3 == null) { 
		add_post_meta('1', 'titulo', $_POST['titulo']); 
    } else { 
    	update_post_meta('1', 'titulo', $_POST['titulo']); 
    }

 //    $value_4 = get_post_meta('1', 'valor');
	// if ($value_4 == null) { 
	// 	add_post_meta('1', 'valor', $_POST['valor']); 
 //    } else { 
 //    	update_post_meta('1', 'valor', $_POST['valor']); 
 //    }

    $value_5 = get_post_meta('1', 'cidade');
	if ($value_5 == null) { 
		add_post_meta('1', 'cidade', $_POST['cidade']); 
    } else { 
    	update_post_meta('1', 'cidade', $_POST['cidade']); 
    }

    $log_mensagens = '
    <div class="uk-alert-success" uk-alert>
	    <a class="uk-alert-close" uk-close></a>
	    <p>Os dados para o PIX foram atualizados com sucesso..</p>
	</div>';

}


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

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.16.2/dist/css/uikit.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.16.2/dist/js/uikit.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.16.2/dist/js/uikit-icons.min.js"></script>
<style type="text/css">
	.uk-button { width: 100%; border-radius: 4px; }
</style>
<div class="uk-container">
	<form class="uk-grid-small" uk-grid method="POST" action="" style="margin-top: 3em;">
		<div class="uk-width-1-1@s">
			<center>
				<h2 style="margin-bottom: 0em; margin-top: 0em; text-transform: uppercase; font-size: 1.7em; color: #333; font-weight: bold;">Configurações para PIX</h2>
				<p style="margin-bottom: 0em; margin-top: 0em; font-size: 1em; color: #333;">Forneça os seguintes dados a seguir para atualizar as informações do seu QR:</p>
			</center>
			<?php echo $log_mensagens; ?>
			<hr>
		</div>
	    <div class="uk-width-1-2@s">
	    	<label>Chave pix</label>
	        <input class="uk-input" type="text" placeholder="Chave pix" name="chave" required value="<?php echo $value_print_1; ?>">
	    </div>
	    <div class="uk-width-1-2@s">
	    	<label>Nome do Beneficiario</label>
	        <input class="uk-input" type="text" placeholder="Beneficiario" name="beneficiario" required value="<?php echo $value_print_2; ?>">
	    </div>
	    <div class="uk-width-1-2@s">
	    	<label>Cidade</label>
	        <input class="uk-input" type="text" placeholder="Cidade" name="cidade" required value="<?php echo $value_print_5; ?>">
	    </div>
	    <div class="uk-width-1-2@s">
	    	<label>Descrição (Título)</label>
	        <input class="uk-input" type="text" placeholder="Descrição (Título)" name="titulo" required value="<?php echo $value_print_3; ?>">
	    </div>
<!-- 	    <div class="uk-width-1-1@s">
	    	<label>Valor</label>
	        <input class="uk-input" type="text" placeholder="Valor" name="valor" required value="<?php echo $value_print_4; ?>">
	    </div> -->
	    <div class="uk-width-1-1@s">
	    	<button type="submit" class="uk-button uk-button-primary" name="enviar">ATUALIZAR DADOS</button>
	    </div>
	</form>
</div>