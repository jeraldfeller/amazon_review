<!DOCTYPE html>
<html lang="en">
<head>
	<title>scraper</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<LINK href="style.css" rel="stylesheet" type="text/css">
	
	<style>
	table tr:nth-child(even) {
		background-color: #eee;
		
		
	}
	table tr:nth-child(odd) {
	   background-color:#fff;
	}
	table th	{
		background-color: black;
		color: white;
	}
	#formPosition {
		top: 10%; 
		left: 8%; 
		position: absolute;
	}
	#textFormPosition {
		top: 5%; 
		left: 8%; 
		position: absolute;
	}
	#iframePosition {
		top: 30%; 
		left: 30%; 
		position: absolute;
	}
	.btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}
	</style>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>
      <a class="navbar-brand" href="#"></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="index.php">Home</a></li> 
      </ul>
      
    </div>
  </div>
</nav>		
</head>
<body>