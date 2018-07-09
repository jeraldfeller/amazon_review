<?php include('./header.php'); ?>
<div class="container">
		
		<div id="formPosition">  
		
		<div id="textFormPosition"> 
			 
			<br><h1>SCRAPER For reviews From AMAZON</h1>
			<form  role="form" target="blank"  action= "Amazon_Review_Product.php" method="get"  style="text-align:center;" enctype="multipart/form-data"> 
			<button type="submit" class="btn btn-success">Click for Updation of  Data from Database </button>
			</form>
			</form>
			<br>
			<form  role="form" target="iframe_a"  action= "sellerDataToFile.php" method="get"  style="text-align:center;" enctype="multipart/form-data">  
			<button type="submit" class="btn btn-success">Just Click to get the CSV file with ASIN</button>
			</form>
			
			<br>
			<form  role="form" target="iframe_a" action="deleteData.php" method="POST">			
			<input type="hidden" class="form-control" id="ASIN" name="purge" value="targetProduct">			
			<div class="form-group">
				<button type="submit" class="btn btn-danger">Delete Old data</button>
			</div>
		</form>
			<form class="form-inline" role="form" target="blank" action= "sellerToTable.php" method="post"  style="text-align:center;" enctype="multipart/form-data">
				<div class="form-group">
				<input type="file" name="file" id="file">
				</div>
			  <button type="submit" class="btn btn-success">Submit File with   ASIN codes </button>
			</form>	
		</div></div>
		<br>
		
		<iframe id="iframePosition" src="" name="iframe_a" width="50%" height="60%"></iframe>
	</div>
</body>
</html>