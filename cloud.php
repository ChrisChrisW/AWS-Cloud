<?php
// DOC
// https://docs.aws.amazon.com/aws-sdk-php/v2/guide/service-s3.html

require __DIR__.'/vendor/autoload.php';
//require __DIR__.'/aws.phar';
//require __DIR__.'/aws/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\ObjectUploader;

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$host= $_ENV['Host'];
$idKey= $_ENV['Key_ID'];
$privateKey= $_ENV['Key_Secret'];

var_dump($_SERVER['Host']);
// Clever Cellar access
$credentials = new Aws\Credentials\Credentials($idKey, $privateKey);

$config = [
'version' => 'latest', //'version' => '2006-03-01',
'region' => 'FR', //'region' => 'US',
'endpoint' => 'https://'.$host,
'credentials' => $credentials
];

// Create an SDK class used to share configuration across clients.
$sdk = new Aws\Sdk($config);

// Use an Aws\Sdk class to create the S3Client object.
$s3Client = $sdk->createS3();

$pathToFile = "data.txt";

// Access our cellar bucket (name, creation, ...)
$buckets = $s3Client->listBuckets();
foreach ($buckets['Buckets'] as $bucket) {
    // Each Bucket value will contain a Name and CreationDate
    echo "<p>Bucket name is : <b>{$bucket['Name']}</b> - created at : <b>{$bucket['CreationDate']}</b></p>";
}

$bucket = "hamster";
/*$iterator = $s3Client->getIterator('ListObjects', ['Bucket' => $bucket]);
foreach ($iterator as $object) {
	$link = "https:\/\/hamster.cellar-c2.services.clever-cloud.com/".$object["Key"];
    //print($object['Key']. "/  \n");
    print("<img src=".$link." />");
}*/

$listObjects= $s3Client->listObjects(['Bucket'=>$bucket]);
foreach ($listObjects as $object) {
	$size =sizeof($object);
	for ($i=0; $i < $size ; $i++) {
		$link = "https:\/\/hamster.cellar-c2.services.clever-cloud.com/".$object[$i]["Key"];
    	print("<img src=".$link." />");
    	print($object[$i]["Key"]);
	}
}

	/*// Send a PutObject request and get the result object.
	$result = $s3Client->putObject([
	    'Bucket'     => "hamster",
	    'Key'        => "discours",
	    'SourceFile' => $pathToFile,
	    'ACL'        => 'public-read'
	    //'Body'		 => fopen($pathToFile, 'r+')
	]);
	var_dump($result);

	// Download the contents of the object.
	$result = $s3Client->getObject([
	    'Bucket' => "hamster",
	    'Key' => "discours",
	    'SaveAs' => 'tmp/'.$pathToFile // local save
	]);

	/*$result['Body']->rewind();
	while ($data = $result['Body']->read(1024)) {
	    print("<p>".$data."</p>");
	}*/
	
	/*// Upload file to cellar (clever cloud)
	$uploader = new ObjectUploader(
	    $s3Client,
	    $bucket['Name'],
	    $pathToFile,
	    fopen($pathToFile, 'r+')
	);
	// Read the body off of the underlying stream in chunks
	$result['Body']->rewind();
	while ($data = $result['Body']->read(1024)) {
	    print("<p>Content of file (path : tmp/".$pathToFile.") :</p><p>".$data."</p>");
	}
	do {
	    try {
	        $result = $uploader->upload();
	        if ($result["@metadata"]["statusCode"] == '200') {
	            print('<p>File successfully uploaded to ' . $result["ObjectURL"] . '.</p>');
	        }
	        print($result."<br>");
	    } catch (MultipartUploadException $e) {
	        rewind($source);
	        $uploader = new MultipartUploader($s3Client, $source, [
	            'state' => $e->getState(),
	        ]);
	    }
	} while (!isset($result));*/


/*
echo get_class($result['Body']);
echo "<br>";
// > Guzzle\Http\EntityBody

// The 'Body' value can be cast to a string
echo $result['Body'];
echo "<br>";*/
?>