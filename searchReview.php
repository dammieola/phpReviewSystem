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
        <title>  Search Search</title>
        <link rel="stylesheet" href="site.css">
    </head>
    <body>
        <div class="header">
            <img src="images/logo3.png">
            <h1>
              Restaurant Search Tool
            </h1> 
        </div>
    <div class="label_body">
            
				<?php
				//$sql = "select  title, pages, year, author, language from books";
				$terms = filter_input(INPUT_POST, 'word', FILTER_SANITIZE_SPECIAL_CHARS);
				$terms = '%' . $terms . '%';
				if ($terms === false && strlen(trim($terms)) === 0) {
					unset($terms);
				}
				
				$selectedSerchBy=filter_input(INPUT_POST, 'searchBy');
				if ($selectedSerchBy === false && strlen(trim($selectedSerchBy)) === 0) {
					unset($selectedSerchBy);
				}
						
				
				?>
		<div id="items">
			<h2> Restaurant Search Result</h2>
			<table>
				
				<tr>
						<?php
						if(isset($terms) && isset($selectedSerchBy)){
							$sql = "select namer, reviewer_name, rating, coment, price, time, menu from restaurants join  business 
							on  restaurants.namer = business.name  where $selectedSerchBy LIKE :rsearchTerms  order by :rsearchTerms";
							//echo "DEBUG: $sql<br>\n";
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':rsearchTerms',$terms,PDO::PARAM_STR);
							//$stmt->bindValue(':searchBy',$selectedSerchBy);
							$stmt->execute();
							if ($stmt->rowCount() > 0) {
								$results = $stmt->fetchAll(); ?>
								<tr>
									<th>Name of  res</th>
									<th>Rewiewer</th>
									<th>Time of operation</th>
									<th>Rating</th>
									<th>Price</th>
									<th>Menu</th>
									<th>Comment</th>
								</tr>
								<tr>
								
								<?php
								foreach ($results as $row) {
								echo "<tr>";
								echo "<td>", htmlspecialchars($row['namer']), "</td>\n";
								echo "<td>", htmlspecialchars($row['reviewer_name']), "</td>\n";
								echo "<td>", htmlspecialchars($row['time']), "</td>\n";
								echo "<td>", htmlspecialchars($row['rating']), "</td>\n";
								echo "<td>", htmlspecialchars($row['price']), "</td>\n";
								echo "<td>", htmlspecialchars($row['menu']), "</td>\n";
								echo "<td>", htmlspecialchars($row['coment']), "</td>\n";
							}
							}
							else{
								echo "Sorry, nothing matches your search, please try again";
							}
							$stmt->closeCursor(); 
		
						}	
							?>
					</tr>
			</table>
		</div>
		<div id="formArea">
			<h2> Restaurant Search Tool</h2>
			
			<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				
				
				<label>Search Word</label>
				<input type="text" id="word" name="word" required="required">
				<br>
				<label>Search by:</label>
				<select name="searchBy" id="searchBy" required>
					<option value="">Select a search by</option>
					<option value="rating" <?php  if (isset($selectedSerchBy) && $selectedSerchBy==="rating"){
						echo 'selected';}?>>Rating</option>
					<option value="coment" <?php  if (isset($selectedSerchBy)  &&   $selectedSerchBy==="coment"){
						echo 'selected';}?>>Comment</option>
					<option value="name" <?php  if (isset($selectedSerchBy)  &&   $selectedSerchBy==="name"){
						echo 'selected';}?>>Name</option>
				</select>
				<br>
				<input type=submit value=submit>
				
			</form>
			<a href="index.html">Go back to Homepage</a><br>
		</div>
		</div>
		<div class="footer">
            <p>@footer</p>
        </div>
	</body>
</html>


