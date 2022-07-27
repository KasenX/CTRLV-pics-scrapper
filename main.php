<?php declare(strict_types=1);

use Goutte\Client;

require __DIR__.'/vendor/autoload.php';

const CTRLV = "https://ctrlv.cz/";
const DIR = "./images/";
const DELAY = 100;

/**
 * Downloads the image from the specified uri.
 * @param string $uri
 * @param string $fileName
 */
function downloadImage(string $uri, string $fileName): void
{
    // Debug
    // echo "PATH: " . $fileName . "URL: " . CTRLV . $uri . PHP_EOL; 

    // There is no image on given uri
    if ($uri === "/images/notexists.png")
    {
        return;
    }

    // Download image
    file_put_contents(DIR . $fileName . ".png", fopen(CTRLV . $uri, 'r'));
}

/**
 * Randomly selects an url and downloads the image if it exists.
 */
function scrape(): void
{
    $chars = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m",
              "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z",
              "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M",
              "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
              "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];

    $client = new Client();

    while (true)
    {
        // Possible uri
        $path = $chars[rand(0, count($chars) - 1)] . $chars[rand(0, count($chars) - 1)] . $chars[rand(0, count($chars) - 1)] . $chars[rand(0, count($chars) - 1)];
        $url = CTRLV . "/" . $path;

        // Get the page
        $crawler = $client->request("GET", $url);

        // Get the img element
        $result = $crawler->filter('#preview')->extract(array('src'));
        
        downloadImage($result[0], $path);

        usleep(DELAY);
    }
}

// MAIN

// Create the directory for images
if (!file_exists(DIR))
    mkdir(DIR);

// Start scraping
scrape();