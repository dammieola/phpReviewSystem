<?php
	require('database.php');
?>
<!DOCTYPE html>
 <!--
Damilola Olaleye
CSCI 5060
Final Project-Lola's Rec
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title> Business Owner Tool</title>
        <link rel="stylesheet" href="site.css">
    </head>
    <body>
        <div class="header">
            <img src="images/logo3.png">
            <h1>
             Business Owner Tool
            </h1> 
        </div>
        <div class="label_body">
            
				<?php
				//$sql = "select  title, pages, year, author, language from books";
				
			
				$operation = filter_input(INPUT_POST, 'operation');
	
				if ($operation == 'insert') {
					$name = filter_input(INPUT_POST, 'name');
					$time = filter_input(INPUT_POST, 'time');
					$menu = filter_input(INPUT_POST, 'menu');
					$price = filter_input(INPUT_POST, 'price');
					$reviewer_name = filter_input(INPUT_POST, 'reviewer_name');
					$rating = filter_input(INPUT_POST, 'rating');
					$coment = filter_input(INPUT_POST, 'coment');
					
					$sql = "INSERT INTO business
							(name, time, menu, price) 
							VALUES 
							(:bname, :btime, :bmenu, :bprice)";
					$stmt = $db->prepare($sql);
					
					$stmt->bindValue(':bname', $name, PDO::PARAM_STR);
					$stmt->bindValue(':btime', $time, PDO::PARAM_STR);
					$stmt->bindValue(':bmenu', $menu, PDO::PARAM_STR);
					$stmt->bindValue(':bprice', $price, PDO::PARAM_STR);
					if ($stmt->execute() == false) {
						echo "WARNING: error inserting new restaurant<br>";
					}
					
				}else if ($operation == "delete") {
					$business_id = filter_input(INPUT_POST, 'business_id', FILTER_VALIDATE_INT);
					
					$sql = "delete from business where business_id = :businessId";
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':businessId', $business_id, PDO::PARAM_INT);
					
					if ($stmt->execute() == false) {
						echo "WARNING: error deleting restaurant<br>";
					}					
					
				}
				else if ($operation == "update_form") {
					$business_id = filter_input(INPUT_POST, 'business_id', FILTER_VALIDATE_INT);		
				
					$sql = "select business_id, name, time, menu, price
							from business 
							where business_id = :businessId";
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':businessId', $business_id, PDO::PARAM_INT);
					
					if ($stmt->execute() == false) {
						echo "WARNING: error deleting restaurant<br>";
					} else {
						
						if ($stmt->rowCount() === 1) {
							$record = $stmt->fetch();
							
							$business_id = $record['business_id'];
							$name = $record['name'];
							$time = $record['time'];
							$menu = $record['menu'];
							$price = $record['price'];							
						} else {
							# cancels the update
							$operation = "";
						}
						
						$stmt->closeCursor();
					}
		
				}
				else if ($operation == 'update_database') {
					$name = filter_input(INPUT_POST, 'name');
					$time = filter_input(INPUT_POST, 'time');
					$menu = filter_input(INPUT_POST, 'menu');
					$price = filter_input(INPUT_POST, 'price');
					$business_id=filter_input(INPUT_POST, 'business_id');
					$sql = "update  business
							set name=:bname,
								time=:btime,
								menu=:bmenu,
								price=:bprice								
							where business_id = :businessId";
					
					$stmt = $db->prepare($sql);
					
					$stmt->bindValue(':businessId', $business_id, PDO::PARAM_INT);
					$stmt->bindValue(':bname', $name, PDO::PARAM_STR);
					$stmt->bindValue(':btime', $time, PDO::PARAM_STR);
					$stmt->bindValue(':bmenu', $menu, PDO::PARAM_STR);
					$stmt->bindValue(':bprice', $price, PDO::PARAM_STR);
					if ($stmt->execute() == false) {
						echo "WARNING: error updating  restaurant<br>";
					}
				}
				#
				# Get all the data for the TABLE that shows all the products
				#
				$sortOrder = filter_input(INPUT_GET, 'sort_order');
				if (empty($sortOrder)) {
					$sortOrder = filter_input(INPUT_POST, 'sort_order');
				}
				
				$sql = "select business_id, name, time, menu,  price from business ";
				
				if ($sortOrder === 'name') {
					$sql .= "order by name";
				} else if ($sortOrder === 'time') {
					$sql .= "order by time";
				}
				else if ($sortOrder === 'menu') {
					$sql .= "order by menu";
				}else if ($sortOrder === 'price') {
					$sql .= "order by price";
				} 
				else {
					$sql .= "order by name";
				}
				
				$stmt = $db->prepare($sql);
				$stmt->execute();
				
				$results = $stmt->fetchAll();
				$stmt->closeCursor();
				
				?>
		<div id="items">
		<h2> Business Owner Tool</h2>
		<table>
			<tr>
				<th><a href="?sort_order=name">Name of  Restaurant</a></th>			
				<th><a href="?sort_order=time">Time of operation</a></th>
				<th><a href="?sort_order=price">Price</a></th>
				<th><a href="?sort_order=menu">Menu</a></th>
			</tr>
			<?php
				foreach ($results as $row) {
					echo "<tr>";
					echo "<td>", htmlspecialchars($row['name']), "</td>\n";
					echo "<td>", htmlspecialchars($row['time']), "</td>\n";
					echo "<td>", htmlspecialchars($row['price']), "</td>\n";
					echo "<td>", htmlspecialchars($row['menu']), "</td>\n";
					?>
					<td>
						<form method="post" 
							  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" 
						      class="tableForm">
							<input type="hidden" name="sort_order" 
								   value="<?php echo htmlspecialchars($sortOrder); ?>" >
							<input type="hidden" name="operation" 
								   value="delete" >
							<input type="hidden" name="business_id" 
								   value="<?php echo $row['business_id']; ?>" >
							<input type="submit" 
								   value="Delete">
						</form>
					</td>
					<td>
						<form method="post"
							  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" 
						      class="tableForm">
							<input type="hidden" name="sort_order" 
								   value="<?php echo htmlspecialchars($sortOrder); ?>" >
							<input type="hidden" name="operation" 
								   value="update_form" >
							<input type="hidden" name="business_id" 
								   value="<?php echo $row['business_id']; ?>" >
							<input type="submit" 
								   value="Update">
						</form>
					</td>
					<?php
					echo "</tr>";
				}
			?>
		</table>
		
	</div>
	
	
	<div id="formArea">
		<h2>
			<?php if ($operation == "update_form") { ?>
				Update
			<?php } else { ?>
				Add
			<?php } ?>
			Restaurtant
		</h2>
		
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<?php if ($operation == "update_form") { ?>
				<input type="hidden" name="operation" value="update_database">
			<?php } else { ?>
				<input type="hidden" name="operation" value="insert">
			<?php } ?>
			
			<input type="hidden" name="sort_order" 
			       value="<?php echo htmlspecialchars($sortOrder); ?>">
			
			<?php if ($operation == "update_form") : ?>
				<input type="hidden" name="business_id" 
				       value="<?php echo $business_id; ?>">
			<?php endif ?>
			
			<label>Restaurant's name:</label>
			<input type="text" id="name" name="name" 
					<?php if ($operation == "update_form") : ?>
						value="<?php echo htmlspecialchars($name); ?>" 
					<?php endif ?>
					required="required">
			<br>
			<label>Time  of  Operation:</label>
			<input type="text" id="time" name="time" 
					<?php if ($operation == "update_form") : ?>
						value="<?php echo htmlspecialchars($time); ?>" 
					<?php endif ?>
					required="required">
			<br>
			<label>Menu:</label>
			<input type="text" id="menu" name="menu" 
					<?php if ($operation == "update_form") : ?>
						value="<?php echo htmlspecialchars($menu); ?>" 
					<?php endif ?>
					required="required">
			<br>
			<label>Price Range:</label>
			<input type="text" id="price" name="price" 
					<?php if ($operation == "update_form") : ?>
						value="<?php echo htmlspecialchars($price); ?>" 
					<?php endif ?>
					required="required">
			<br>
			<?php if ($operation == "update_form") { ?>
				<input type="submit" value="Update Restaurant">
			<?php } else { ?>
				<input type="submit" value="Add Restaurant">
			<?php } ?>
			
			<input type="reset">
			
			<?php if ($operation == "update_form"): ?>
				<input type="button" value="Cancel update"
						onclick="location.href='?sort_order=<?php echo $sortOrder; ?>'">
			<?php endif ?>
			
		</form><br>
		<a href="index.html">Go back to Homepage</a><br>
	</div>
	</div>
	<div class="footer">
            <p>@footer</p>
        </div>
</body>
</html>


