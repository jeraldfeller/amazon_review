<?php include('./header.php'); 
$select ='<select name="marketplace">
				   <option value="com">Please Select marketplace</option>
				   <option value="com">US</option>
				   <option value="co.uk">UK</option>
				   <option value="de">Germany</option>
				   <option value="co.jp">JAPAN</option>
				   <option value="cn">CHINA</option>
				   <option value="it">Italy</option>
				   <option value="ca">CANADA</option>
				   <option value="es">Spain</option>
				   <option value="fr">France</option>
				   <option value="in">INDIA</option>
				   <option value="com.br">Brazil</option>
				   <option value="com.mx">Mexico</option>
				</select>';?>
<div class="container">
		
		<div id="formPosition">  
		
		<div id="textFormPosition"> 
			 
			<br><h1>SCRAPER For my SERVER Adds From AMAZON</h1> 
			<br>
			<form  role="form" target="iframe_a"  action= "sellerDataToFile12.php" method="get"  style="text-align:center;" enctype="multipart/form-data">  
			<?php echo $select ; ?>
			<button type="submit" class="btn btn-success">Just Click to get the CSV file with ASIN</button>
			</form>
			 <br><br>
			<form class="form-inline" role="form" target="iframe_a" action= "sellerToTable1.php" method="post"  style="text-align:center;" enctype="multipart/form-data">
				<div class="form-group">
				<input type="file" name="file" id="file">
				</div>
				<?php echo $select ; ?>
			  <button type="submit" class="btn btn-success">Submit File with   ASIN codes </button>
			</form>	
		</div></div>
		<br>
		
		<iframe id="iframePosition" src="" name="iframe_a" width="50%" height="60%"></iframe>
	</div>
</body>
</html>