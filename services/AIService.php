<?php



namespace App\services;


class AIService
{
    private string $apiKey;
    private string $apiEndpoint = 'https://api.openai.com/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = $_ENV['OPENAI_API_KEY'] ?? '';

        if(empty($this->apiKey))
        {
            throw new \Exception('openAI API key is not set. please add it to your .env file');
            
        }
    }

    private function buildDescriptionPrompt(string $productName, string $category)
    {
        $prompt = "Create an engaging and SEO-friendly product description for an e-commerce website.";
        $prompt .= "The product is: $productName.";

        if(!empty($category))
        {
            $prompt .= "it belongs to the category: $category";

        }
        $prompt .= "The description should be 2-3 paragraphs, highlighting the product's features and benefits ";
        $prompt .= "Include relevant keywords for SEO, but make it sound natural. ";
        
        return $prompt;

    }

    private function makeOpenAIRequest(array $data): array
    {
        $ch = curl_init($this->apiEndpoint);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        
        if ($error) {
            throw new \Exception('cURL Error: ' . $error);
        }
        
        $responseData = json_decode($response, true);
        
        if ($statusCode !== 200) {
            $errorMessage = isset($responseData['error']['message']) 
                ? $responseData['error']['message'] 
                : 'Unknown error from OpenAI API';
            
            throw new \Exception('API Error (' . $statusCode . '): ' . $errorMessage);
        }
        
        return $responseData;
    }



    public function generateProductDescription(string $productName, string $category = '')
    {
        $prompt = $this->buildDescriptionPrompt($productName, $category);

        $response = $this->makeOpenAIRequest([
            'model' => 'gpt-3.5-turbo',
            'messages' =>[
                ['role' => 'system', 'content' => 'You are an expert e-commerce copywriter specializing in Moroccan products. Your task is to create compelling, SEO-friendly product descriptions.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => 500, //text size
            'temperature' => 0.7 //Controls the randomness and creativity of AI responses.
            
        ]);
        if (isset($response['choices'][0]['message']['content'])) {
            return trim($response['choices'][0]['message']['content']);
        }
        
        throw new \Exception('Failed to generate description: ' . json_encode($response));
    }



    



}