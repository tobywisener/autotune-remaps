<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/*

Template Name: API Checker

*/

/*
    Toby Wisener: Helper class for Autotune to contact the tuning-api br-performance API

    -manufacturer/brands (Audi, VW, etc)
    -model (Golf, Bora, Jetta etc)
    -build year (2001, 2002, 2003-2005 etc)
    -motor (1.4, 1.6TSI. 1.9TDI etc)
    -stages (stage 1 - etc)
*/

/* Logo mode */
    if(isset($_GET['manufacturer_logo'])) {
        header('Content-type:image/png');
        echo AutotuneAPI::getManufacturerLogo($_GET['manufacturer_logo']);
    }
    
    if(isset($_GET['model_icon'])) {
    	header('Content-type:image/*');
        echo AutotuneAPI::getModelIcon($_GET['manufacturer'], $_GET['model']);
        exit;
    }

class AutotuneAPI {

    public static $API_URL = "https://tuning-api.bcconsulting.lu/api/vehicles/1";
    // When using the API, ensure to use a static IP and save it on the br-performance control panel (217.78.10.23)
    public static $API_TOKEN = "sGOu41jy10ZeGozwbgAkHTYlQytZZaYIhn2becQWSp0ZUpLvLL";

    public static $DISPLAYED_LOGOS = ["Alfa Romeo","Audi","BMW","Chevrolet","Chrysler","Citroën","Dacia","DS","Fiat","Ford","Honda","Hyundai","Isuzu","Jaguar","Jeep","Kia","Landrover","Lexus","Mazda","Mercedes","MG","Mini","Mitsubishi","Nissan","Opel/Vauxhall","Peugeot","Renault","Saab","Seat","Skoda","Smart","SsangYong","Subaru","Suzuki","Toyota","Volkswagen","Volvo"];
    public static $OUTPUT_PREFIX = "<script type='text/javascript'>";
    public static $OUTPUT_SUFFIX = "</script>";
    public static $DISPLAY_LOGOS = false; // Display logos?

    public static $manufacturers = [];
    public static $models = [];
    public static $build_years = [];
    public static $motors = [];
    public static $stages = [];
    
    public static function displayWidget() {
        if(!self::$DISPLAY_LOGOS) {
            return;
        }
        
    echo '<ul class="manufacturers">';

        // Loop through all of the AutotuneAPI manufacturers
        
        if(!isset($_GET['manufacturer'])) {
            foreach (AutotuneAPI::$manufacturers as $manufacturer) {

                if(!in_array($manufacturer->name, AutotuneAPI::$DISPLAYED_LOGOS)) {
                    continue;
                }

                echo "<li style='background-image: url(/wp-content/themes/sydney/images/autotune/".$manufacturer->id.".png)' tooltip='" . $manufacturer->name . "' tooltip-position='buttom' onclick='window.location = \"?manufacturer=".$manufacturer->id."\";'></li>";
            }
        }

        echo '</ul>';
     }
    // Process and return the JavaScript given a number of GET input fields
    public static function process() {
        // Get the list of brands available

        // User is trying to view stages
        $stages = (isset($_GET['manufacturer'], $_GET['model'], $_GET['build_year'], $_GET['motor'], $_GET['motor_power']) ? 
            self::getStages($_GET['manufacturer'], $_GET['model'], $_GET['build_year'], $_GET['motor'], $_GET['motor_power']) 
            : '[]');
        if($stages != '[]') {
         
            echo $stages;
            return;
        }
        
        // User is trying to view motors
        $motors = (isset($_GET['manufacturer'], $_GET['model'], $_GET['build_year']) ? 
            self::getMotors($_GET['manufacturer'], $_GET['model'], $_GET['build_year']) : '[]');
        if($motors != '[]') {
            
            echo $motors;
            return;
        }

        // User is trying to view build years
        $build_years = (isset($_GET['manufacturer'], $_GET['model']) ? 
            self::getBuildYears($_GET['manufacturer'], $_GET['model']) 
            : '[]');
        if($build_years != '[]') {
            
            echo $build_years;
            return;
        }
        
        // User is trying to view models
        $models = (isset($_GET['manufacturer']) ? 
            self::getModels($_GET['manufacturer']) 
            : '[]');
        if($models != '[]') {
            
            echo $models;
            return;
        }
        
        // Default request is manufacturers
        $manufacturers = self::getManufacturers();
        echo $manufacturers;

    }

    // Return true if on test site
    public static function isTesting() {
        //die($_SERVER['HTTP_HOST']);
        return $_SERVER['HTTP_HOST'] == "wordpress.test";
    }
    
    // Return all available manufacturers on the system
    public static function getManufacturers() {
        if(self::isTesting()) {
            return json_encode([
                ["id" => 1, "name" => "name one"],
                ["id" => 2, "name" => "name"]
            ]);
        }

        return self::GET("/brands");
    }
    
    // Return a logo from a given manufacturer
    public static function getManufacturerLogo($manufacturer) {
        return self::GET("/brands/" . $manufacturer . "/logo");
    }

    // Return all models of a particular manufacturer
    public static function getModels($manufacturer) {
        if(self::isTesting()) {
            return json_encode([
                ["id" => 1, "name" => "name one"],
                ["id" => 2, "name" => "name"]
            ]);
        }

        return self::GET("/brands/" . $manufacturer . "/models");
    }
    
    // Return all build years of a particular manufacturer and model
    public static function getModelIcon($manufacturer, $model) {
    return self::GET("/brands/" . $manufacturer . "/models/" . $model . "/logo/dark/small");
    
        return self::GET("/brands/" . $manufacturer . "/models/" . $model . "/miniature");
    }
    
    // Return all build years of a particular manufacturer and model
    public static function getBuildYears($manufacturer, $model) {
        if(self::isTesting()) {
            return json_encode([
                ["id" => 1, "long_name" => "1995"],
                ["id" => 2, "long_name" => "1996"]
            ]);
        }

        return self::GET("/brands/" . $manufacturer . "/models/" . $model . "/years");
    }

    // Return all motors of a particular manufacturer, model and build year combination
    public static function getMotors($manufacturer, $model, $build_year) {
        if(self::isTesting()) {
            return json_encode([
                ["id" => 1, "engine" => ["name" => "name one", "power" => "300bhp", "stages" => [1,2,3] ] ],
                ["id" => 2, "engine" => ["name" => "engine two", "power" => "500bhp", "stages" => [1,2,3] ]]
            ]);
        }

        return self::GET("/brands/" . $manufacturer . "/models/" . $model . "/years/" . $build_year . "/powertrains");
    }

    // Return all remap stages of a particular motor, motor power, model and build year combination
    public static function getStages($manufacturer, $model, $build_year, $motor, $motor_power) {
        if(self::isTesting()) {
            return json_encode([
                "engine" => [ "name" => "boo", "stages" => [1,2,3] ]
            ]);
        }

        return self::GET("/brands/" . $manufacturer . "/models/" . $model . "/years/" . $build_year . "/powertrains/" . $motor );
    }

    // Perform a cURL GET request including error handling, to the specified URI
    private static function GET($uri) {
        $ch = curl_init();
        
        $verbose = fopen('php://temp', 'w+');

        $options = array(
            CURLOPT_URL            => self::$API_URL . $uri,
            CURLOPT_RETURNTRANSFER => true,   // return web page
            CURLOPT_HEADER         => false,  // return headers?
            CURLOPT_HTTPHEADER     => array('Authorization: Bearer ' . self::$API_TOKEN),  // api-token inclusion
            CURLOPT_FOLLOWLOCATION => true,   // follow redirects
            CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
            CURLOPT_ENCODING       => "",     // handle compressed
            CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
            CURLOPT_TIMEOUT        => 120,    // time-out on response
            //CURLOPT_VERBOSE       => true,
            //CURLOPT_STDERR => $verbose,
        ); 

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        /* Verbose cURL error reporting:
          printf("cUrl error (#%d): %s<br>\n", curl_errno($ch),
           htmlspecialchars(curl_error($ch)));
          */ 
        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            die("cURL error ({$errno}):\n {$error_message}");
        } 
        
        curl_close($ch);
        
        return $response;

    }

}

AutotuneAPI::process();