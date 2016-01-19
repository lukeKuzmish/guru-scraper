<?php
error_reporting(E_ALL);
require_once __DIR__ . DIRECTORY_SEPARATOR . 'class.guruscraperconfig.php';

$config =  new GuruScraperConfig(__DIR__ . DIRECTORY_SEPARATOR . 'config.json');

foreach($config->queries as $i => $toQuery) {
    
    echo "\nPulling {$toQuery['url']}";
    $guid = null;
    $currEmail = (!empty($toQuery['email'])) ? $toQuery['email'] : $config->defaultEmail;
    $keywordsToSearch = (!empty($toQuery['keywords'])) ? $toQuery['keywords'] : false;
    $xmlRaw = file_get_contents($toQuery['url']);
    $feedData = json_decode(json_encode(simplexml_load_string($xmlRaw, null, LIBXML_NOCDATA)), true);
    if ( (!isset($feedData['channel'])) or (!isset($feedData['channel']['item'])) or (empty($feedData['channel']['item'])) ) {
        // no items to parse!
        echo "\nNo items to parse for this feed\nContinuing...\n";
        continue;
    }
    foreach($feedData['channel']['item'] as $item) {
        /*
        // don't think this is necessary
        if ($guid !== null) {
            $config->previousJobs[] = $guid;
        }
        */
        $title = $item['title'];
        $url = $item['link'];
        $desc = $item['description'];
        $guid = basename($url);
        $emailBody = $title . "\n" . strip_tags(str_replace(array('<br>', '<br/>', '<br />', '<b>', '</b>'), '', $desc)) . "\n" . $url;
        
        if (in_array($guid, $config->previousJobs)) {
            echo "\nThis guid ({$guid}) was already parsed previously.\nContinuing...\n";
            continue;
        }
        if ($keywordsToSearch === false) {
            // don't need to both checking, just send alert
            echo "\nNo keywords to search!  Sending alert to {$currEmail} now\n";
            sendAlert($currEmail, $title, $emailBody);
            $config->previousJobs[] = $guid;
            continue;
        }
        else {
            $foundKeyword = false;
            foreach($keywordsToSearch as $keyword) {
                
                if (stripos($title . ' ' . $desc, $keyword) !== false) {
                    // TODO implement budget
                    // TODO implement location
                    echo "\nKeyword {$keyword} found!";
                    $foundKeyword = true;
                    break;
                    
                }
                
            }
            if ($foundKeyword) {
                echo "\nWill alert {$currEmail} now\n";
                sendAlert($currEmail, $title, $emailBody);
                continue;
            }
        }
        $config->previousJobs[] = $guid;
        
    } // foreach item
    break;
} // foreach queries

$config->updatePreviousJobs();

function sendAlert($emailAddress, $subjectLine, $jobInfoStr) {
    echo <<< EOC

Would mail
TO:         $emailAddress
Subject:    $subjectLine
Body:       $jobInfoStr

EOC;
    //return mail($emailAddress, $subjectLine, $jobInfoStr);
    
}