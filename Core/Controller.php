<?php
namespace Core;

// Assurez-vous que cette classe existe si elle est utilisée dans le constructeur
// use Core\Request; // Si vous avez une classe Core\Request

class Controller
{
    // Propriété pour stocker l'objet Request (si votre framework l'utilise)
    protected $request;

    /**
     * Controller constructor.
     * Initializes the Request object.
     */
    public function __construct()
    {
        // Initialisation de l'objet Request
        // Note : La méthode getJsonInput() utilise directement php://input,
        // ce qui est standard pour lire le corps JSON. Si votre classe Request
        // est censée encapsuler cette logique, vous pourriez ajuster getJsonInput
        // pour utiliser $this->request plus tard.
         // Si votre framework n'a pas de classe Request ou si vous n'en avez pas besoin ici,
         // vous pourriez commenter ou supprimer cette ligne.
        // $this->request = new Request();
    }

    /**
     * Sends a JSON response.
     * Handles CORS headers and sets the HTTP status code.
     *
     * @param mixed $data The data to encode as JSON.
     * @param int $status The HTTP status code (default is 200).
     */
    public function json($data, $status = 200)
    {
        // Handle CORS Preflight Request (OPTIONS method)
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            // Allow requests from any origin (consider restricting this in production)
            header("Access-Control-Allow-Origin: *");
            // Specify which headers are allowed in the actual request (add Content-Type, Authorization, etc.)
            header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin"); // Exemple de headers courants
            // Specify which methods are allowed
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
            // Optional: Specify how long the preflight response can be cached
            // header("Access-Control-Max-Age: 86400"); // 24 hours
            header("HTTP/1.1 200 OK");
            exit(); // Terminate script after handling preflight request
        }

        // Set standard CORS headers for actual requests
        header("Access-Control-Allow-Origin: *"); // Consider restricting this in production
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin"); // Répéter pour les requêtes normales
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Répéter pour les requêtes normales

        header('Content-Type: application/json');
        http_response_code($status);

        // Encode the data to JSON and output it
        echo json_encode($data);

        // It's generally a good practice to exit after sending the final response
        // to prevent any other part of the script from outputting unexpected content.
        // exit();
    }

    /**
     * Reads the raw POST data from php://input and decodes it as JSON.
     * Useful for handling incoming JSON payloads in API requests.
     *
     * @return array Associative array of the decoded JSON data. Returns an empty array
     *               if no data is provided or if the data is not a JSON object or array after decoding.
     * @throws \InvalidArgumentException If the raw input string is malformed JSON.
     */
    protected function getJsonInput(): array
    {
        // Read the raw request body. This is where JSON data is typically sent.
        $json_data = file_get_contents('php://input');

        // If no data was sent in the body, return an empty array.
        // This allows validation logic in the service/controller to easily check if data is missing.
        if ($json_data === false || $json_data === '') {
            return [];
        }

        // Decode the JSON string into a PHP associative array.
        // The 'true' parameter is crucial for getting an associative array instead of standard objects.
        $data = json_decode($json_data, true);

        // Check if any JSON decoding error occurred (e.g., syntax error in the JSON string).
        if (json_last_error() !== JSON_ERROR_NONE) {
            // If the JSON is invalid, throw an exception. The controller's catch block
            // should ideally translate this into a 400 Bad Request HTTP response.
            throw new \InvalidArgumentException('Invalid JSON format received: ' . json_last_error_msg());
        }

        // After decoding, if the result is null (e.g., the input was the string 'null')
        // or not an array (e.g., the input was '"some string"', '123', 'true'),
        // which are not typical structures for a request body containing data fields,
        // we treat it as effectively no valid structured data provided.
        // If you strictly require the body to be a JSON object or array, you might
        // throw an exception here instead of returning an empty array.
         if ($data === null || !is_array($data)) {
             // Returning empty array is a common pattern allowing downstream validation
             // to check for required fields.
             return [];
         }

        // Return the decoded associative array containing the request data.
        return $data;
    }

    // Ajoutez d'autres méthodes communes à tous les contrôleurs ici si nécessaire...
}