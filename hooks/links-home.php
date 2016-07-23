<?php
	/*
	 * You can add custom links in the home page by appending them here ...
	 * The format for each link is:
		$homeLinks[] = array(
			'url' => 'path/to/link', 
			'title' => 'Link title', 
			'description' => 'Link text',
			'groups' => array('group1', 'group2'), // groups allowed to see this link, use '*' if you want to show the link to all groups
			'grid_column_classes' => '', // optional CSS classes to apply to link block. See: http://getbootstrap.com/css/#grid
			'panel_classes' => '', // optional CSS classes to apply to panel. See: http://getbootstrap.com/components/#panels
			'link_classes' => '', // optional CSS classes to apply to link. See: http://getbootstrap.com/css/#buttons
			'icon' => 'path/to/icon' // optional icon to use with the link
		);
	 */
		$homeLinks[] = array(
         'url' => 'reports" target="blank"',
         'title' => 'Reports',
         'description' => '',
         'groups' => array('*')
      );

	/*	$homeLinks[] = array(
			'url' => 'http://bigprof.com/appgini/', 
			'icon' => 'http://bigprof.com/appgini3D.png', 
			'title' => 'AppGini homepage', 
			'description' => 'You can add your own links to the homepage like this one by adding an entry for each link in the generated "hooks/links-home.php" file.',
			'groups' => array('*'), // groups allowed to see this link
			'grid_column_classes' => 'col-sm-6 col-md-4 col-lg-3',
			'panel_classes' => 'panel-success',
			'link_classes' => 'btn-success'
		);

		$homeLinks[] = array(
			'url' => 'nwdemo.zip', 
			'title' => 'Want to try this demo on your own server? Download it now!', 
			'description' => 'Download all the files of this demo application, including the AppGini project file, the generated files and the SQL dump of data. To set up the demo either on your own PC or on a web server, please refer to the included README.txt file.',
			'groups' => array('*'), // groups allowed to see this link
			'grid_column_classes' => 'col-sm-8 col-md-8 col-lg-6',
			'panel_classes' => 'panel-primary',
			'link_classes' => 'btn-primary'
		);
		*/

		/*http://localhost:8080/northwind/orders_view.php?SortField=&SortDirection=&FilterAnd%5B1%5D=&FilterField%5B1%5D=6&FilterOperator%5B1%5D=is-empty&FilterValue%5B1%5D=*/

		//sql query using AppGini
		$res = sql("select * from orders order by OrderDate desc limit 1 ", $eo);
		if ($row = db_fetch_assoc($res)){
			$last_order_id = $row['OrderID'];
			$last_order_ts = $row['OrderDate'];
			$last_order_date = date('j/n/Y', $last_order_ts);
		}

		$homeLinks[] = array(
				'url' => 'http://localhost:8080/northwind/orders_view.php?SortField=&SortDirection=&FilterAnd%5B1%5D=&FilterField%5B1%5D=6&FilterOperator%5B1%5D=is-empty&FilterValue%5B1%5D=',
				'icon' => '',
				'title' => 'Unshipped Orders',
				'description' => 'Show all orders that are not shipped yet. <br><br>'.
				                  '<a href="orders_view.php?SelectedID='.urlencode($last_order_id).'">Most recent order</a> was placed on'. $last_order_date,
				'groups' => array('*'), // groups allowed to see this link
				'grid_column_classes' => 'col-md-4 col-lg-3',
				'panel_classes' => 'panel-danger',
				'link_classes' => 'btn-danger'
		);
		
		/* get the count of orders */
		//$orders_count = sqlValue("select count(*) from orders");
		$orders_count = sqlValue("select count(*) from ".get_sql_from('orders')); //AppGini function
		
		//echo "Orders Count : {$orders_count}";
		?>
		
		<script>
			$j(function(){
				$j('a.btn[href="orders_view.php"]').html('<img src="resources/table_icons/cash_register.png"><strong>Orders(<?php echo $orders_count;?>)</strong>');
				});
		</script>


