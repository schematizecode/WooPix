<?php
/**
 * @package WOO Pix
 * @version 1.0.3
 */
/*
Plugin Name: WOO Pix
Plugin URI: https://www.Schematize.com.br/
Author: Schematize
Version: 1.0.3
Author URI: https://www.Schematize.com.br/
*/
if (!defined("ABSPATH")) { 
    exit; // Exit if accessed directly
}

//function para plugins_loaded
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) return;
add_action('plugins_loaded', 'pix_payment_init', 11);

//function para pix_payment_init
function pix_payment_init() {
    if( class_exists( 'WC_Payment_Gateway' ) ) {
        class WC_PIX_pay_Gateway extends WC_Payment_Gateway {
            public function __construct() {
                $this->id   = 'pix_payment';
                $this->icon = apply_filters( 'woocommerce_pix_icon', plugins_url('/assets/icon.png', __FILE__ ) );
                $this->has_fields = false;
                $this->method_title = __( 'Woo Pix', 'pix-pay-woo');
                $this->method_description = __( 'Permitir que os seus clientes paguem com PIX (Sistema de pagamento Woo Pix)', 'pix-pay-woo');

                $this->title = $this->get_option( 'title' );
                $this->description = $this->get_option( 'description' );
                $this->instructions = $this->get_option( 'instructions', $this->description );
                $this->chave = $this->get_option('chave');
                $this->beneficiario = $this->get_option('beneficiario');
                $this->cidade = $this->get_option('cidade');
                $this->descricao = $this->get_option('descricao');
                $this->desconto = $this->get_option('desconto');

           
                $this->init_form_fields();
                $this->init_settings();

                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
                // add_action( 'woocommerce_thank_you_' . $this->id, array( $this, 'thank_you_page' ) );
            }

            public function init_form_fields() {
                $this->form_fields = apply_filters( 'woo_pix_pay_fields', array(
                    'enabled' => array(
                        'title' => __( 'Enable/Disable', 'pix-pay-woo'),
                        'type' => 'checkbox',
                        'label' => __( 'Ativar ou desativar pagamentos Woo Pix', 'pix-pay-woo'),
                        'default' => 'no'
                    ),

                    'title' => array(
                        'title' => __( 'Woo Pix', 'pix-pay-woo'),
                        'type' => 'text',
                        'default' => __( 'Gateway de pagamentos Woo Pix', 'pix-pay-woo'),
                        'desc_tip' => true,
                        'description' => __( 'Adicione um novo título para o Woo Pix que os clientes verão quando estiverem na página de checkout.', 'pix-pay-woo')
                    ),

                    'description' => array(
                        'title' => __( 'Descrição do gateway de pagamentos Woo Pix', 'pix-pay-woo'),
                        'type' => 'textarea',
                        'default' => __( 'Por favor, envie seu pagamento para a loja para permitir que a entrega seja feita.', 'pix-pay-woo'),
                        'desc_tip' => true,
                        'description' => __( 'Adicione um novo título para o Woo Pix que os clientes verão quando estiverem na página de checkout.', 'pix-pay-woo')
                    ),

                    'instructions' => array(
                        'title' => __( 'Instruções', 'pix-pay-woo'),
                        'type' => 'textarea',
                        'default' => __( 'Instruções padrões', 'pix-pay-woo'),
                        'desc_tip' => true,
                        'description' => __( 'Instruções que serão adicionadas à página de agradecimento e ao e-mail do odrer.', 'pix-pay-woo')
                    ),


                    'chave' => array(
                        'title' => __( 'Chave Pix', 'pix-pay-woo'),
                        'type' => 'text',
                        'default' => __( 'Chave Pix', 'pix-pay-woo'),
                        'desc_tip' => true,
                        'description' => __( 'Digite sua Chave Pix', 'pix-pay-woo')
                    ),


                    'beneficiario' => array(
                        'title' => __( 'Nome do Beneficiario', 'pix-pay-woo'),
                        'type' => 'text',
                        'default' => __( 'Nome do Beneficiario', 'pix-pay-woo'),
                        'desc_tip' => true,
                        'description' => __( 'Digite o nome do Beneficiario', 'pix-pay-woo')
                    ),

                    'cidade' => array(
                        'title' => __( 'Nome da Cidade', 'pix-pay-woo'),
                        'type' => 'text',
                        'default' => __( 'Nome da Cidade', 'pix-pay-woo'),
                        'desc_tip' => true,
                        'description' => __( 'Digite o nome da Cidade', 'pix-pay-woo')
                    ),


                    'descricao' => array(
                        'title' => __( 'Descrição (Título)', 'pix-pay-woo'),
                        'type' => 'text',
                        'default' => __( 'Descrição (Título)', 'pix-pay-woo'),
                        'desc_tip' => true,
                        'description' => __( 'Digite uma descrição (Título)', 'pix-pay-woo')
                    ),

                    'desconto' => array(
                        'title' => __( 'Desconto (Porcentagem)', 'pix-pay-woo'),
                        'type' => 'number',
                        'default' => __( '0', 'pix-pay-woo'),
                        'desc_tip' => true,
                        'description' => __( 'Digite um valor para desconto (Porcentagem)', 'pix-pay-woo')
                    ),


                ));
            }

            function process_payment( $order_id ) {
                global $woocommerce;
                $order = new WC_Order( $order_id );

                // Mark as on-hold (we're awaiting the cheque)
                $order->update_status('on-hold', __( 'Aguardando pagamento. ', 'woocommerce' ));

                // Remove cart
                $woocommerce->cart->empty_cart();

                // Return thankyou redirect
                return array(
                    'result' => 'success',
                    'redirect' => $this->get_return_url( $order )
                );
            }

        }
    }
}



add_action( 'woocommerce_review_order_before_payment', 'ts_refresh_payment_method' );
function ts_refresh_payment_method(){ ?>
    <script type="text/javascript">
        (function($){
            $( 'form.checkout' ).on( 'change', 'input[name^="payment_method"]', function() {
                $('body').trigger('update_checkout');
            });
        })(jQuery);
    </script>
<?php }


add_action( 'woocommerce_cart_calculate_fees','cod_fee' );
function cod_fee() {
    global $woocommerce;

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;
        // get your payment method
        $chosen_gateway = WC()->session->chosen_payment_method;
        $item = get_option('woocommerce_pix_payment_settings', 'chave', array());
        $desconto_pix = $item['desconto'];


        if ($desconto_pix == null) { } else {
            $cart_total = WC()->cart->cart_contents_total;
            $amount = ($cart_total * $desconto_pix)/100;
            $amount = '-'.$amount;

            if ( $chosen_gateway == 'pix_payment' ) { //test with cash on delivery method
            WC()->cart->add_fee( 'Desconto via Pix', $amount, false, '' );
        }
    }


}

//function para woocommerce_payment_gateways
add_filter( 'woocommerce_payment_gateways', 'add_to_woo_noob_payment_gateway');
function add_to_woo_noob_payment_gateway( $gateways ) {
    $gateways[] = 'WC_PIX_pay_Gateway';
    return $gateways;
}


//function para woocommerce_thankyou
add_action( 'woocommerce_thankyou', 'custom_content_thankyou', 10, 1 );
function custom_content_thankyou($order_id) {

    //array options plugins
    $item = get_option('woocommerce_pix_payment_settings', 'chave', array());
    $order = wc_get_order($order_id);

    //payment values plugin
    $order_total = $order->get_total();
    $payment_method = $order->get_payment_method();
    $chave_pix = $item['chave'];
    $beneficiario_pix = $item['beneficiario'];
    $cidade_pix = $item['cidade'];
    $descricao_pix = $item['descricao'];
    $desconto_pix = $item['desconto'];

    if ($payment_method == "pix_payment") {
        //require viewn
        $file = "viewn/pix.php";
        include($file);
    }
}
