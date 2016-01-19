<?php
class GuruScraperConfig {
    
    private $configData         =   null;
    public  $defaultEmail       =   null;
    private $previousJobsFile   =   null;
    public  $previousJobs       =   array();
    public  $queries            =   array();
    
    public function __construct($file_loc = null) {
        if (($file_loc == null) or (!file_exists($file_loc))) {
            throw new Exception('Please enter a valid config path!');
            return false;
        }
        
        $jsonStr = file_get_contents($file_loc);
        $configData = json_decode($jsonStr, true);
        if ($configData == null) {
            throw new Exception('config JSON is not valid!');
        }
        
        $this->configData = $configData;
        $this->defaultEmail = $configData['default_email'];
        $this->previousJobsFile = $configData['previous_jobs_file_location'];
        $this->setPreviousJobs();
        $this->queries = $configData['queries'];
        
        
    } // __construct
    
    private function setPreviousJobs() {
        if (!file_exists($this->previousJobsFile)) {
            if (!file_put_contents($this->previousJobsFile, json_encode(array()))) {
                throw new Exception('Previous jobs file does not exist and cannot create!');
                return false;
            }
            $this->previousJobs = array();
            return true;
        }
        else {
            $jsonStr = file_get_contents($this->previousJobsFile);
            $previousJobs = json_decode($jsonStr,true);
            if ($previousJobs === null) {
                throw new Exception('Malformed previous jobs JSON!');
                return false;
            }
            else {
                $this->previousJobs = $previousJobs;
                return true;
            }
        }
    } // setPreviousJobs
    
} // GuruScraperConfig
