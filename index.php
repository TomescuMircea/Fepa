<?php
const BASE_URL = 'http://localhost/tw-project';
const BASE_PATH = __DIR__;
const SECRET_KEY = '8YKEu9Of1hmVdGkahXLH7YcOQ3QhPOAm';
require __DIR__ . '/vendor/autoload.php';

use Src\Controllers\AuthController;
use Src\Controllers\CoordinatesController;
use Src\Controllers\HomeController;
use Src\Controllers\HelpController;
use Src\Controllers\ContactController;
use Src\Controllers\AboutController;
use Src\Controllers\DashboardController;
use Src\Controllers\ReportAnimalController;
use Src\Controllers\RssController;
use Src\Controllers\ReportsController;
use Src\Controllers\StatsController;
use Src\Controllers\UserController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$requestMethod = $_SERVER["REQUEST_METHOD"];

if (sizeof($uri) == 3) {
    $controller = new HomeController();
} else {
    switch ($uri[3]) {
        case 'stats':
            $param = $uri[4] ?? "";
            $controller = new StatsController($requestMethod, $param);
            $controller->processRequest();
            break;
        case 'reportAnimal':
            $param = $uri[4] ?? "";
            $controller = new ReportAnimalController($requestMethod, $param);
            $data = $_POST;
            $images = $_FILES['upload-images'] ?? null;

            $controller->processRequest($data, $images);
            break;
        case (bool)str_contains($uri[3], 'reports'):
            $controller = new ReportsController();
            $param = $uri[4] ?? "";
            switch ($param) {
                case 'myReports':
                    $controller->processRequest($requestMethod, 'myReports');
                    break;
                case (bool)str_contains($uri[3], 'orderBy'):
                    $controller->processRequest($requestMethod, 'reports', null, $_GET["orderBy"]);
                    break;
                case 'editReport':
                    $id = $uri[5] ?? "";
                    $controller->processRequest($requestMethod, 'editReport', $id);
                    break;
                default:
                    $controller->processRequest($requestMethod, 'report', $param);
            }
            break;
        case 'about':
            $controller = new AboutController();
            break;
        case 'help':
            $controller = new HelpController();
            break;
        case 'contact':
            $requestMethod = $_SERVER["REQUEST_METHOD"];

            $controller = new ContactController($requestMethod);
            $data = isset($_POST) ? $_POST : null;

            $controller->processRequest($data);
            break;
        case 'login':
            $controller = new AuthController();
            $controller->displayLoginPage();
            break;
        case 'logout':
            $controller = new AuthController();
            $controller->logout();
            break;
        case 'register':
            $controller = new AuthController();
            $controller->displayRegisterPage();
            break;
        case (bool)str_contains($uri[3], 'changePassword'):
            $controller = new AuthController();
            $controller->displayChangePasswordPage();
            break;
        case 'credentials-recover':
            $controller = new AuthController();
            $controller->displayRecoverCredentialsPage();
            break;
        case 'confirmationNotice':
            $controller = new AuthController();
            $controller->displayConfirmationNotice();
            break;
        case 'failedConfirmation':
            $controller = new AuthController();
            $controller->displayConfirmationFail();
            break;
        case (bool)str_contains($uri[3], 'confirm') :
            $controller = new AuthController();
            $controller->confirm();
            break;
        case 'coordinates':
            $controller = new CoordinatesController();
            $param = $uri[4] ?? "";
            $controller->processRequest($param);
            break;
        case 'dashboard':
            $controller = new DashboardController();
            $controller->processRequest();
            break;
        case 'users':
            $controller = new UserController();
            $param = $uri[4] ?? "";
            $controller->processRequest($param);
            break;
        case 'auth':
            if (sizeof($uri) == 5) {
                $controller = new AuthController();
                switch ($uri[4]) {
                    case 'register':
                        $controller->register();
                        break;
                    case 'confirm':
                        $controller->confirm();
                        break;
                    case 'login':
                        $controller->login();
                        break;
                    case 'logout':
                        $controller->logout();
                        break;
                    case 'recoverUsername':
                        $controller->recoverUsername();
                        break;
                    case 'recoverPassword':
                        $controller->recoverPassword();
                        break;
                    case (bool)str_contains($uri[4], 'changePassword') :
                        $controller->changePassword();
                        break;
                    default:
                        http_response_code(404);
                        echo json_encode(['message' => 'Not Found']);
                        break;
                }
            }
            break;
        case 'rss':
            $param = isset($uri[4]) ? $uri[4] : "";
            $controller = new RssController($requestMethod, $param);
            $controller->processRequest();
            break;
        default:
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
            break;
    }
}
function readInput()
{
//    $input = file_get_contents('php://input');
//    echo $input;
//    $data = $input ? json_decode($input, true) : [];
    $data = $_POST;
    return $data;
}
