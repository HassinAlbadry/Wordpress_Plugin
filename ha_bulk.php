<?php
// this page used for bulk inventory manage
$ha_status_arr_bulk=array();
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => 12
			);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();  // loop thru products 

        
        		global $product;

        		array_push($ha_status_arr_bulk, $product->get_id());  // push all ids into an array to bulk modify status
        		?>
        		<!DOCTYPE html>





<?php
			endwhile;
		} else {
			echo __( 'No products found' );
		}
		wp_reset_postdata();





         
	
	?>
<!-- add form with selections to user to choose from --> 

<form method="post" id="ha_Inventory_bulk">
  <span class="title">Bulk Manage?  </span>
 <select id="ha_products" name="bulk_stock_status" form="ha_Inventory_bulk">
  <option value="instock">in stock</option>
  <option value="outofstock">out of stock</option>
  <option value="onbackorder">on back order</option>
</select>

<input type="hidden" name="ids" value="<?php print_r($ha_status_arr_bulk) ?>">
<input type="submit" name="button2" value="Update"/>

</form>



<?php 

// handle selection and Ids from form and array and update status
if(isset($_POST['button2'])) {
                
                $ha_selection=$_POST['bulk_stock_status'];
                $ha_ids=$ha_status_arr_bulk;
                //loop thru array with ids to update all product ids 
                foreach ($ha_status_arr_bulk as $value) {
				  wc_update_product_stock_status($value, $ha_selection);
				}
                 
                 $product->save(); //save after update 
                   echo "<meta http-equiv='refresh' content='0'>"; //refresh page

  
        }


?>







