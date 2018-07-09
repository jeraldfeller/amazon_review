<?php include('./header.php');
$select ='<select name="marketplace">
				   <option value="">Please Select marketplace</option>
				   <option value="co.uk">UK</option>
				   <option value="it">Italy</option>
				   <option value="de">DE</option>
				   <option value="fr">France</option>
				</select>';
				?>
<div class="container">
		
		<div id="formPosition">  
		
		<div id="textFormPosition"> 
			 
			<br><h1>SCRAPER For Inorgainc Adds From AMAZON</h1> 
			<br>
			<form  role="form" target="iframe_a"  action= "sellerDataToFile.php" method="get"  style="text-align:center;" enctype="multipart/form-data">  
			<?php echo $select ; ?>
			<button type="submit" class="btn btn-success">Just Click to get the CSV file with ASIN</button>
			</form>
			
			<br>
			<form  role="form" target="iframe_a" action="deleteData.php" method="GET">			
			<input type="hidden" class="form-control" id="ASIN" name="purge" value="targetProduct">		
				<select name="marketplace">
				   <option value="all">Delete ALL input Data</option>
				   <option value="co.uk">"UK" input data</option>
				   <option value="it">"Italy"  input data</option>
				   <option value="de">"DE"  input data</option>
				   <option value="fr">"France" input data</option>
				</select>
			<div class="form-group">
				<button type="submit" class="btn btn-danger">Delete input Old data</button>
			</div>
		</form><br> 
		<form  role="form" target="iframe_a" action="deleteData.php" method="GET">			
			<input type="hidden" class="form-control" id="ASIN" name="purge" value="targetProduct">		
				<select name="marketplace">
				   <option value="all_out">Delete ALL output Data</option>
				   <option value="co.uk_out">"UK" output data</option>
				   <option value="it_out">"Italy"  output data</option>
				   <option value="de_out">"DE"  output data</option>
				    <option value="fr_out">"France" output data</option>
				</select>
			<div class="form-group">
				<button type="submit" class="btn btn-danger">Delete output Old data</button>
			</div>
		</form>
			<form class="form-inline" role="form" target="iframe_a" action= "sellerToTable.php" method="post"  style="text-align:center;" enctype="multipart/form-data">
				<div class="form-group">
				<input type="file" name="file" id="file">
				<?php echo $select ; ?>
				</div>
			  <button type="submit" class="btn btn-success">Submit File with   ASIN codes </button>
			</form>	
		</div></div>
		<br>
		
		<iframe id="iframePosition" src="" name="iframe_a" width="50%" height="60%"></iframe>
	</div>
</body>
</html>