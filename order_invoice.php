<?php
$currDir = dirname(__FILE__);
include("$currDir/defaultLang.php");
include("$currDir/language.php");
include("$currDir/lib.php");

include_once("$currDir/header.php");

/* grant access to all logged users */
/*$mi = getMemberInfo();
if(!$mi['username'] || $mi['username'] == 'guest'){
	echo "Access denied";
	exit;
}
*/

/* grant access to all users who have access to the orders table*/
$order_from = get_sql_from('orders'); //AppGini function to select data from orders table
if (!$order_from) exit(error_message('Access denied!', false));

/* get invoice*/
$order_id = intval($_REQUEST['OrderID']);
if (!$order_id) exit(error_message('Invalid Order ID!', false)); 

/* retrieve order info */
$order_fields = get_sql_fields('orders');
$res = sql("select {$order_fields} from {$order_from} and OrderID = {$order_id}", $eo);
if (!($order = db_fetch_assoc($res))) exit(error_message('Order not found', false));

//var_dump($order);

/* retrieve order items */
$items = array();
$order_total = 0;
$item_fields = get_sql_fields('order_details');
$item_from = get_sql_from('order_details');
$res = sql("select {$item_fields} from {$item_from} and order_details.OrderID={$order_id}", $eo);
while ($row = db_fetch_assoc($res)) {
	$row['LineTotal'] = str_replace('$', '', $row['UnitPrice']) * $row['Quantity'];
	$items[] = $row;
	$order_total += $row['LineTotal'];
}

//var_dump($items);

?>

<div class="row">
	<div class="col-sm-6">
		<!-- company info -->
		<h1>VirtualDev Software Soln.</h1>
		<h5>South B,<br>Nairobi, 2532-00100<br>Kenya</h5>
	</div>
	<div class="col-sm-6 text-right">
		<!-- invoice info -->
		<h1>INVOICE</h1>
		<h5>Date: <?php echo  $order['OrderDate']; ?></h5>
		<h5>Invoice No. <?php echo $order_id; ?></h5>
	</div>
</div>

<hr>

<!-- Order items -->

<table class="table table-striped table-bordered">
  <thead>
    <th class="text-center">#</th>
    <th>Item</th>
    <th class="text-center">Unit Price</th>
    <th class="text-center">Quantity</th>
    <th class="text-center">LineTotal</th>
  </thead>
  
  <tbody>
    <?php foreach ($items as $i => $item){?>
    	<tr>
    		<td class="text-center"><?php echo ($i + 1); ?></td>
    		<td><?php echo $item['ProductID']; ?></td>
    		<td class="text-right"><?php echo $item['UnitPrice']; ?></td>
    		<td class="text-right"><?php echo $item['Quantity']; ?></td>
    		<td class="text-right"><?php echo number_format($item['LineTotal'], 2); ?></td>
    	</tr>
    <?php } ?>
  </tbody>
  
  <tfoot>
  		<tr>
  			<th colspan="4" class="text-right">Subtotal</th>
  			<th class="text-right">$<?php  echo number_format($order_total, 2); ?></th>
  		</tr>
  		<tr>
  			<th colspan="4" class="text-right">Shipping</th>
  			<th class="text-right">$<?php  echo number_format($order['Freight'], 2); ?></th>
  		</tr>
  		<tr>
  			<th colspan="4" class="text-right">Subtotal</th>
  			<th class="text-right">$<?php  echo number_format($order_total + $order['Freight'] , 2); ?></th>
  		</tr>
  </tfoot>
</table>


<?php


include_once("$currDir/footer.php");
?>
