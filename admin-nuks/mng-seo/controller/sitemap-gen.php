<?php

include "../../../vendor/autoload.php";

require_once '../../php/table.class.php';
require_once '../../php/connection.class.php';
require_once '../../mng-product/model/porduct.class.php';
require_once '../../mng-category/model/category.class.php';

$DB = new DBConnection;
$PROD = new Product($DB);
$CAT = new Category($DB);


if (count($PROD->getMostClicked()) < 20000) {
    $allProducts = $PROD->getAll();
} else {
    $allProducts = $PROD->getMostClicked();
}
//var_dump($allProducts);
//die();
$allCAts = $CAT->getAll();

$imgUrl = "/img/product/";
$cateUrl = "/categoria/";
$prodUrl = "/producto/";

$yourSiteUrl = 'https://artpromos.com.mx';
$robotsfile = file_get_contents("../../../robots.txt");


// Setting the current working directory to be output directory
// for generated sitemaps (and, if needed, robots.txt)
// The output directory setting is optional and provided for demonstration purposes.
// The generator writes output to the current directory by default.
//$outputDir = getcwd();
$outputDir = "../../../";

$generator = new \Icamys\SitemapGenerator\SitemapGenerator($yourSiteUrl, $outputDir);

// Create a compressed sitemap
//$generator->enableCompression();

// Determine how many urls should be put into one file;
// this feature is useful in case if you have too large urls
// and your sitemap is out of allowed size (50Mb)
// according to the standard protocol 50000 urls per sitemap
// is the maximum allowed value (see http://www.sitemaps.org/protocol.html)
$generator->setMaxUrlsPerSitemap(50000);

// Set the sitemap file name
$generator->setSitemapFileName("sitemap.xml");

// Set the sitemap index file name
$generator->setSitemapIndexFileName("sitemap-index.xml");

// Add alternate languages if needed
//$alternates = [
//    ['hreflang' => 'de', 'href' => "http://www.example.com/de"],
//    ['hreflang' => 'fr', 'href' => "http://www.example.com/fr"],
//];

// Add url components: `path`, `lastmodified`, `changefreq`, `priority`, `alternates`
// Instead of storing all urls in the memory, the generator will flush sets of added urls
// to the temporary files created on your disk.
// The file format is 'sm-{index}-{timestamp}.xml'


//PRODUCTS URLS
for ($i = 0; $i < count($allProducts); $i++) {
    $generator->addURL($prodUrl . $allProducts[$i]['url'], new DateTime(), 'daily', 1.0);
}

//PRODUCTS IMG URLS

for ($i = 0; $i < count($allProducts); $i++) {
    $imgLoc = $yourSiteUrl . $imgUrl . $allProducts[$i]['img'];
    $imgTitle = $allProducts[$i]['name'];
    $imgCaption = $allProducts[$i]['description'];

    $imageTags = [
        'loc' => "$imgLoc",
        'title' => "$imgTitle",
        'caption' => "$imgCaption",
        'geo_location' => 'CDMX, Mexico',
//        'license' => 'https://example.com/image-license',
    ];
    $extensions = [
        'google_image' => $imageTags
    ];
    $generator->addURL($imgUrl . $allProducts[$i]['img'], new DateTime(), 'daily', 0.9, null, $extensions);
}



//CATES

for ($i = 0; $i < count($allCAts); $i++) {
    $generator->addURL($cateUrl . $allCAts[$i]["cat_url"], new DateTime(), 'daily', 0.5);
}

//Main URLS

$generator->addURL("/sobre-nosotros/", new DateTime(), 'daily', 1.0);
$generator->addURL("/cotizar/", new DateTime(), 'daily', 1.0);
$generator->addURL("/catalogo/", new DateTime(), 'daily', 1.0);
$generator->addURL("/metodos-de-impresion/", new DateTime(), 'daily', 1.0);
$generator->addURL("/contacto/", new DateTime(), 'daily', 1.0);


// Flush all stored urls from memory to the disk and close all necessary tags.
$generator->flush();

// Move flushed files to their final location. Compress if the option is enabled.
$generator->finalize();


if (!strpos($robotsfile, "Sitemap: https://artpromos.com.mx/sitemap.xml")) {

// Update robots.txt file in output directory or create a new one
    $generator->updateRobots();
}

echo json_encode(array("title" => "Éxito!", "msg" => "Mapa Generado correctamente", "class" => "success"));
