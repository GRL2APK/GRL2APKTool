

<html>
<head>
	<title></title>
</head>
<body>
	<div class="custom-file-upload">
	  <h1>Upload and Download NFR catalogue from FireBase<span style="font-size: 1em; margin-left: 2%"class="glyphicon glyphicon-fire"></span></h1>
		<input type="file" onchange="previewFile()" id="files" name="files[]" multiple />
		<input type="Button" onclick="download()" id="btndown" value="Download"/>
		<img src="" id="testImg" />
		<span id="sp"></span>
	  <div>
</body>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.5.8/firebase.js"></script>
<script src="js/FileSaver.js"></script>
<script>
	
	var config = {
    apiKey: "AIzaSyB6CwYAWYU29Ouf5syufS08gV1qRB1cvMI",
    authDomain: "phpproject-af5fc.firebaseapp.com",
    databaseURL: "https://phpproject-af5fc.firebaseio.com",
    projectId: "phpproject-af5fc",
    storageBucket: "phpproject-af5fc.appspot.com",
    messagingSenderId: "558325460273"
  };
  firebase.initializeApp(config);

//function to save file
function previewFile(){
  var storage = firebase.storage();

  var file = document.getElementById("files").files[0];
    console.log(file);
  
  var storageRef = firebase.storage().ref();
  
  //dynamically set reference to the file name
  var thisRef = storageRef.child(file.name);

  //put request upload file to firebase storage
  thisRef.put(file).then(function(snapshot) {
    console.log('Uploaded a blob or file!');
});
  
  //get request to get URL for uploaded file
  thisRef.getDownloadURL().then(function(url) {
  console.log(url);
  })

  }

  function download()
  {
  	var storageRef = firebase.storage().ref();
  	storageRef.child('nfrs.txt').getDownloadURL().then(function(url) {
  // `url` is the download URL for 'images/stars.jpg'

  // This can be downloaded directly:
  var xhr = new XMLHttpRequest();
  xhr.responseType = 'blob';
  xhr.onload = function(event) {
    var blob = xhr.response;
    //saveAs(blob, "test.txt");
  };
  xhr.open('GET', url);
  xhr.send();

  // Or inserted into an <img> element:
  //var img = document.getElementById('testImg');
  //img.src = url;
  document.getElementById('sp').innerHTML=url;
  	saveAs(url, "textfile.txt");
	}).catch(function(error) {
	  // Handle any errors
	});
//var blob = new Blob(["test text"], {type: "text/plain;charset=utf-8"});
  //saveAs(blob, "testfile1.txt");
	//var file = new File(["Hello, world!"], "hello world.txt", {type: "text/plain;charset=utf-8"});
	//saveAs(file);
	var storageRef = firebase.storage().ref();
  	storageRef.child('storagenfr.txt').getDownloadURL().then(function(url) {
  // `url` is the download URL for 'images/stars.jpg'

  // This can be downloaded directly:
  var xhr = new XMLHttpRequest();
  xhr.responseType = 'blob';
  xhr.onload = function(event) {
    var blob = xhr.response;
    //saveAs(blob, "test.txt");
  };
  xhr.open('GET', url);
  xhr.send();

  // Or inserted into an <img> element:
  //var img = document.getElementById('testImg');
  //img.src = url;
  document.getElementById('sp').innerHTML=url;
  	saveAs(url, "textfile.txt");
	}).catch(function(error) {
	  // Handle any errors
	});
  }
</script>
</html>
