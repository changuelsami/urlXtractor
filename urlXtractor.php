<?php
# recursively extract URLs from an XML sitemap.
#
# Display only :
# $ php urlXtractor.php 
#
# Write results in a text file :
# $ php urlXtractor.php > out.csv

function urlXtractor($url)
{
    // Ignore invalid SSL
    $opts = [
        "ssl" => [
            "verify_peer"       => false,
            "verify_peer_name"  => false,
        ],
    ];

    $ext = substr($url, -3);

    if ($ext != "xml") {
        echo $url . PHP_EOL;
    } else {
        $xml_filename = $url;

        if (fopen($xml_filename, 'r', false, stream_context_create($opts))) {
            $xml = simplexml_load_file($xml_filename);

            foreach ($xml as $node) {
                $loc = $node->loc;
                $ext = substr($loc, -3);
                if ($ext == "xml") {
                    echo "-------------------------------" . $loc . "---------" . PHP_EOL;
                    urlXtractor($loc);
                }
                echo $node->loc . PHP_EOL;
            }
        }

    }
}

if (count($argv) < 2) {
    exit("Error: Invalid number of arguments. Specify an input XML URL.");
}

urlXtractor($argv[1]);
