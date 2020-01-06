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
        <title> Admin  Review Tool</title>
        <link rel="stylesheet" href="site.css">
    </head>
    <body>
        <div class="header">
            <img src="images/logo3.png">
            <h1>
             Lola's Rec Admin Tool
            </h1> 
        </div>
        <div class="label_body">
            
				<?php
				//$sql = "select  title, pages, year, author, language from books";
				
			
				$operation = filter_input(INPUT_POST, 'operation');	
				if ($operation == "delete") {
					$restaurant_id = filter_input(INPUT_POST, 'restaurants_id', FILTER_VALIDATE_INT);
					
					$sql = "delete  from restaurants where  restaurants_id = :restaurantsId";
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':restaurantsId', $restaurant_id, PDO::PARAM_INT);
					
					if ($stmt->execute() == false) {
						echo "WARNING: error deleting restaurant<br>";
					}
					
				}
				#
				# Get all the data for the TABLE that shows all the products
				#
				$sortOrder = filter_input(INPUT_GET, 'sort_order');
				if (empty($sortOrder)) {
					$sortOrder = filter_input(INPUT_POST, 'sort_order');
				}
				
				$sql = "select restaurants_id, name, reviewer_name, rating, coment, time, menu,  price from restaurants  inner join business on  restaurants.namer = business.name ";
				
				if ($sortOrder === 'name') {
					$sql .= "order by name";
				} else if ($sortOrder === 'reviewer_name') {
					$sql .= "order by reviewer_name";
				}else if ($sortOrder === 'rating') {
					$sql .= "order by rating";
				} else if ($sortOrder === 'coment') {
					$sql .= "order by coment";
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
		<div id="">
		<h2> Restaurant Infromation and Reviews Available</h2>
		<table  class="table_width">
			<tr>
				<th><a href="?sort_order=name">Name of  Restaurant</a></th>
				<th><a href="?sort_order=reviewer_name">Reviewer's name</a></th>			
				<th><a href="?sort_order=time">Time of operation</a></th>
				<th><a href="?sort_order=rating">Rating</a></th>
				<th><a href="?sort_order=price">Food price range</a></th>
				<th><a href="?sort_order=menu">Menu available</a></th>
				<th><a href="?sort_order=coment">Comment</a></th>
			</tr>
			<?php
				foreach ($results as $row) {
					echo "<tr>";
					echo "<td>", htmlspecialchars($row['name']), "</td>\n";
					echo "<td>", htmlspecialchars($row['reviewer_name']), "</td>\n";
					echo "<td>", htmlspecialchars($row['time']), "</td>\n";
					echo "<td>", htmlspecialchars($row['rating']), "</td>\n";
					echo "<td>", htmlspecialchars($row['price']), "</td>\n";
					echo "<td>", htmlspecialchars($row['menu']), "</td>\n";
					echo "<td>", htmlspecialchars($row['coment']), "</td>\n";
					?>
					<td>
						<form method="post" 
							  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" 
						      class="tableForm">
							<input type="hidden" name="sort_order" 
								   value="<?php echo htmlspecialchars($sortOrder); ?>" >
							<input type="hidden" name="operation" 
								   value="delete" >
							<input type="hidden" name="restaurants_id" 
								   value="<?php echo $row['restaurants_id']; ?>" >
							<input type="submit" 
								   value="Delete">
						</form>
					</td>
					<?php
					echo "</tr>";
				}
			?>
		</table>
		
	</div>
	
	
	
		<br>
		<a href="index.html">Go back to Homepage</a><br>
	
	</div>
	<div class="footer">
            <p>@footer</p>
        </div>
</body>
</html>

