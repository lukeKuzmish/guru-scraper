<?php
error_reporting(E_ALL);

$data = array(
    'default_email' => 'blahblah@website.com',
    'previous_jobs_file_location' => __DIR__ . '/previous_jobs.json',
    'queries' => array(
            array(
                'url'               =>  'http://www.guru.com/rss/jobs/c/web-software-it/',
                'keywords'          =>  array(),
                'email'             =>  '', // leave blank for default
                'budget_type'       =>  '', // fixed, hourly
                'budget'            =>  '', // leave blank for any budget (experimental)
                'location'          =>  '', // leave blank for all (experimental)
            ),
            array(
                'url'               =>  'http://www.guru.com/rss/jobs/c/other/',
                'keywords'          =>  array('php', 'javascript', 'js', 'json', 'xml'),
                'email'             =>  'blahblah2@website.com',
                'budget_type'       =>  'hourly',
                'budget'            =>  '10',
                'location'          =>  array('United States', 'Worldwide'),
            ),
    ),
);

file_put_contents(__DIR__ . '/config.json', json_encode($data));
