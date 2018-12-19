<?php

namespace App\Utils;

use GuzzleHttp\Client;
use RenanBr\BibTexParser\Listener;
use RenanBr\BibTexParser\Parser;

class Orcid
{
    /**
     * @var string
     */
    protected $orcid_id;
    
    /**
     * @var string
     */
    protected $base_url;

    /**
     * @var string
     */
    protected $orcid_url;
    
    /**
     * @var string
     */
    protected $orcid_token;
    
    public function __construct(string $orcid_id, string $orcid_token)
    {
        $this->orcid_id = $orcid_id;
        $this->base_url = "https://pub.orcid.org/v2.1";
        $this->orcid_url = $this->base_url . '/' . $orcid_id;
        $this->orcid_token = $orcid_token;
    }
    
    public function getWorks() : array
    {
        $final_works = array();
        
        // get works summary
        
        $url = $this->orcid_url . '/works';
        $xml = $this->getUrl($url);
        
        $work_ids = array();        
        $works = $xml->xpath("//work:work-summary");
        
        foreach ($works as $work) {
            $work_ids[] = (string) $work['put-code'];
        }
        
        // fetch back full records for the works (up to 100)
        
        $url = $this->orcid_url . '/works/' . implode(',', $work_ids);
        $xml = $this->getUrl($url);
        
        // extract bibtex citations
        
        $citations = $xml->xpath('//work:citation-value');
        
        foreach ($citations as $citation) {
            $final_works[] = (string) $citation;
        }
        
        return $final_works;
    }
    
    /**
     * Retrieve public access token
     * 
     * @param string $client_id
     * @param string $client_secret
     * @return string
     */
    public static function getToken(string $client_id, string $client_secret) : string
    {
        $url = 'https://orcid.org/oauth/token';
        
        $options = [
            'headers' => [
                'Accept' => 'application/json'
            ],
            'form_params' => [
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'grant_type' => 'client_credentials',
                'scope' => '/read-public'
            ]
        ];
        
        $client = new Client();
        $response = $client->post($url, $options);
        return $response->getBody()->getContents();
    }
    
    /**
     * Get content for URL
     *
     * @param string $url
     * @return \SimpleXMLElement
     */
    protected function getUrl(string $url) : \SimpleXMLElement
    {
        $options = [
            'headers' => [
                'Accept' => 'application/vnd.orcid+xml',
                'Authorization' => 'Bearer ' . $this->orcid_token
            ]
        ];
        
        // get the content from orcid
        
        $client = new Client();
        $response = $client->get($url, $options);
        $contents = $response->getBody()->getContents();
        
        // load it into xml and register namespaces so we can query this stuff
        
        $xml = simplexml_load_string($contents);
        $xml->registerXPathNamespace('internal', 'http://www.orcid.org/ns/internal');
        $xml->registerXPathNamespace('funding', 'http://www.orcid.org/ns/funding');
        $xml->registerXPathNamespace('preferences', 'http://www.orcid.org/ns/preferences');
        $xml->registerXPathNamespace('address', 'http://www.orcid.org/ns/address');
        $xml->registerXPathNamespace('education', 'http://www.orcid.org/ns/education');
        $xml->registerXPathNamespace('work', 'http://www.orcid.org/ns/work');
        $xml->registerXPathNamespace('deprecated', 'http://www.orcid.org/ns/deprecated');
        $xml->registerXPathNamespace('other-name', 'http://www.orcid.org/ns/other-name');
        $xml->registerXPathNamespace('history', 'http://www.orcid.org/ns/history');
        $xml->registerXPathNamespace('employment', 'http://www.orcid.org/ns/employment');
        $xml->registerXPathNamespace('error', 'http://www.orcid.org/ns/error');
        $xml->registerXPathNamespace('common', 'http://www.orcid.org/ns/common');
        $xml->registerXPathNamespace('person', 'http://www.orcid.org/ns/person');
        $xml->registerXPathNamespace('activities', 'http://www.orcid.org/ns/activities');
        $xml->registerXPathNamespace('record', 'http://www.orcid.org/ns/record');
        $xml->registerXPathNamespace('researcher-url', 'http://www.orcid.org/ns/researcher-url');
        $xml->registerXPathNamespace('peer-review', 'http://www.orcid.org/ns/peer-review');
        $xml->registerXPathNamespace('personal-details', 'http://www.orcid.org/ns/personal-details');
        $xml->registerXPathNamespace('bulk', 'http://www.orcid.org/ns/bulk');
        $xml->registerXPathNamespace('keyword', 'http://www.orcid.org/ns/keyword');
        $xml->registerXPathNamespace('email', 'http://www.orcid.org/ns/email');
        $xml->registerXPathNamespace('external-identifier', 'http://www.orcid.org/ns/external-identifier');
        
        return $xml;
    }
}
