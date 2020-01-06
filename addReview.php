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
        <title> Customer Review </title>
        <link rel="stylesheet" href="site.css">
    </head>
    <body>
        <div class="header">
            <img src="images/logo3.png">
            <h1>
             Customer Review Tool
            </h1> 
        </div>
        <div class="label_body">
            
				<?php
				//$sql = "select  title, pages, year, author, language from books";
				$selectedRestaurant = filter_input(INPUT_GET, "name");
				if ($selectedRestaurant === false && strlen(trim($selectedRestaurant)) === 0) {
					unset($selectedRestaurant);
				}
			
				$operation = filter_input(INPUT_POST, 'operation');
	
				if ($operation == 'insert') {
					$name = filter_input(INPUT_POST, 'name');
					$rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
					$reviewer_name = filter_input(INPUT_POST, 'reviewer_name');
					$coment = filter_input(INPUT_POST, 'coment');

					
					$sql = "INSERT INTO restaurants
							(namer, reviewer_name,rating,coment) 
							VALUES 
							(:rnamer, :rreviewer_name, :rrating, :rcoment)";
					$stmt = $db->prepare($sql);
					
					$stmt->bindValue(':rnamer', $name, PDO::PARAM_STR);
					$stmt->bindValue(':rreviewer_name', $reviewer_name, PDO::PARAM_STR);
					$stmt->bindValue(':rrating', $rating, PDO::PARAM_INT);
					$stmt->bindValue(':rcoment', $coment, PDO::PARAM_STR);
					
					
					if ($stmt->execute() == false) {
						echo "WARNING: error inserting new book<br>";
					}
					
				}
				
				?>
	
	<div id="formArea">
		<h2>
				Add
			
			Review
		</h2>
		
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			
			<input type="hidden" name="operation" value="insert">
			<label>Name of Restaurant:</label>
			<select name="name" required="required">
				
				<option> select a restaurant</option>
					<?php
					$sql = "select distinct name from business where name !=  '' ";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$restaurants= $stmt->fetchAll();
					$stmt->closeCursor();
					
					foreach ($restaurants as $restaurant) {
						echo "<option value='$restaurant[name]' ";
						if ($restaurant['name'] === $selectedRestaurant) {
							echo "selected='selected'";
						}
						echo ">$restaurant[name]</option>\n";
					}
					?>
				
			</select> 
			<br>
			<label>Reviewer's name:</label>
			<input type="text" id="reviewer_name" name="reviewer_name" 
					
					required="required">
			<br>
			<label>Desired rating(1 to 5):</label>
			<input type="number" id="rating" name="rating" 
					
					required="required">
			<br>
			<label>Comment:</label>
			<input type="text" id="coment" name="coment" 
					
					required="required">
			<br>
			
			<input type="submit" value="Add Review">
			
			
			<input type="reset">
			
			
			
		</form><br>
		<a href="index.html">Go back to Homepage</a><br>
	</div>
	</div>
	<div class="footer">
            <p>@footer</p>
        </div>
</body>
</html>


