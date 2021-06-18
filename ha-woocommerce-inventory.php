<?php
/*
 * Plugin Name:       HA Woocommerce Inventory
 * Description:       Simple Plugin to manage woocommerce product Inventory
 * Version:           1.0
 * Author:            Hassin Albadry
 
 */


function ha_styles() {
            $plugin_url = plugin_dir_url( __FILE__ );

        wp_enqueue_style( 'style',  $plugin_url . "/css/styles.css");
    }

    add_action( 'admin_print_styles', 'ha_styles' );


function list_titles(){
 
    $args = array(
      'post_type' => 'product',
      'posts_per_page' => 12
      );
    $loop = new WP_Query( $args );
    if ( $loop->have_posts() ) {

?>

<?php $ha_update_status=array(); ?> 

 <table>
  <caption>Manage Woocommerce Inventory</caption>
  <thead>
    <tr>
      <th scope="col">Item Title</th>
      <th scope="col">Item SKU</th>
      <th scope="col">Price</th>
      <th scope="col">Current Status</th>
      <th scope="col">Change Status</th>
    </tr>
  </thead>
<?php include 'ha_bulk.php'; ?>
<?php 
  // loop thru products
      while ( $loop->have_posts() ) : $loop->the_post();
        
        global $product;
       

        $ha_update_status['ID'] =  $product->get_id(); // declare array with product ids
        

?>
         <tbody>
              <tr> 
                <!-- list all product variables name,sku, price, status and change status in a table --> 
                <td data-label="Period"> <?php echo $product->get_name(); ?></td> 
                <td data-label="Account"> <?php echo $product->get_sku(); ?></td>
                <td data-label="Due Date"> <?php echo '$'.$product->get_price();?></td>
                <td data-label="status"> <?php echo $product->get_stock_status();?></td>
                <td>
             <form method="post" id="ha_Inventory"> <!-- Form inserted to chose among stock options for each product--> 
               <label class="stock_status_field">
                      <span class="title"><?php esc_html_e( 'In stock?', 'woocommerce' ); ?></span>
                      <span class="input-text-wrap">
                        <select class="stock_status" name="_stock_status">
                          <?php


                          echo '<option value="" id="stock_status_no_change">' . esc_html__( '— No Change —', 'woocommerce' ) . '</option>';
                          foreach ( wc_get_product_stock_status_options() as $key => $value ) {
                            echo '<option value="' . esc_attr( $key ) . '">' . esc_html( $value ) . '</option>';

                          }
                          ?>
                        </select>
                        <div class="wc-quick-edit-warning" style="display:none">
                          <?php echo esc_html__( 'This will change the stock status of all variations.', 'woocommerce' ); ?></p>
                        </div>
                      </span>
              </label>

                      <input type="hidden" name="ids" value="<?php echo $product->get_id(); ?>">

                      <input type="submit" name="button1" value="Update"/>
  
               </form>


              </td>
              </tr>
    
          </tbody>


     <?php 
 
    // handle passed data selection from form 
 if(isset($_POST['button1'])) {
                
                $ha_selection=$_POST['_stock_status'];
                $ha_ids=$_POST['ids'];
                 wc_update_product_stock_status($ha_ids, $ha_selection); // update product status from form selection and product id
                 $product->save();
                   echo "<meta http-equiv='refresh' content='0'>";    // refresh page to make sure stock status column is refreshed with new status

  
        }
         
        


    

      endwhile; // end of product loop 
    } else {
      echo __( 'No products found' );
    }
    wp_reset_postdata();
  

}
?>


<?php
do_action('titless','list_titles'); // add function to hook

// menu to add to admin page for plugin
 
add_action('admin_menu', 'custom_menu'); 

 function custom_menu() { 

  add_menu_page( 
      'Page Title',  // page title
      'Manage Woocommerce Inventory',  // name of plugin displayed on admin page
      'edit_posts', 
      'ha-pages.php', 
      'list_titles', // function used to populate plugin page
      'dashicons-media-spreadsheet',
      '20'

     );
}
 


?>


