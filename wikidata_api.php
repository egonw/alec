<?php

// API to Wikidata


error_reporting(E_ALL ^ E_DEPRECATED);
error_reporting(0);

require_once(dirname(__FILE__) .  '/config.inc.php');
require_once(dirname(__FILE__) .  '/lib.php');

require_once(dirname(__FILE__) .  '/vendor/digitalbazaar/json-ld/jsonld.php');




//----------------------------------------------------------------------------------------
// Get one item from Wikidata
// Use SPARQL CONSTRUCT then convert to CSL 
function get_item($qid, $debug = false)
{
	global $config;
	

	$uri = 'http://www.wikidata.org/entity/' . $qid;

	$sparql = 'PREFIX schema: <http://schema.org/>
PREFIX identifiers: <https://registry.identifiers.org/registry/>
PREFIX bibo: <http://purl.org/ontology/bibo/>
PREFIX skos: <http://www.w3.org/2004/02/skos/core#>


CONSTRUCT
{
 ?item a ?type . 
  
 ?item schema:name ?title .
 ?item schema:name ?label .
 ?item schema:image ?image .
 
 ?item schema:url ?url .
  
 # scholarly article
 ?item schema:name ?title .
 ?item schema:volumeNumber ?volume .
 ?item schema:issueNumber ?issue .
 ?item schema:pagination ?page .
 ?item schema:datePublished ?datePublished .
  
 # author(s)
  ?item schema:author ?author .
  ?author schema:name ?author_name .
  ?author schema:position ?author_order .
  
  ?author schema:identifier ?orcid_author_identifier .
 ?orcid_author_identifier a <http://schema.org/PropertyValue> .
 ?orcid_author_identifier <http://schema.org/propertyID> "orcid" .
 ?orcid_author_identifier <http://schema.org/value> ?orcid .
  
  
  
 # container
  ?item schema:isPartOf ?container .
  ?container schema:name ?container_title .
  ?container schema:issn ?issn .
  
  
  # person
  ?item schema:description ?description .
  ?item schema:alternateName ?alternateName .
  ?item schema:birthDate ?birthDate .
  ?item schema:deathDate ?deathDate .
 # identifiers as property values
 
 # bhl
 ?item schema:identifier ?bhl_page_identifier .
 ?bhl_page_identifier a <http://schema.org/PropertyValue> .
 ?bhl_page_identifier <http://schema.org/propertyID> "bhl page" .
 ?bhl_page_identifier <http://schema.org/value> ?bhl_page .

 # biostor
 ?item schema:identifier ?biostor_identifier .
 ?biostor_identifier a <http://schema.org/PropertyValue> .
 ?biostor_identifier <http://schema.org/propertyID> "biostor" .
 ?biostor_identifier <http://schema.org/value> ?biostor .

 # doi
 ?item schema:identifier ?doi_identifier .
 ?doi_identifier a <http://schema.org/PropertyValue> .
 ?doi_identifier <http://schema.org/propertyID> "doi" .
 ?doi_identifier <http://schema.org/value> ?doi .

 # handle
 ?item schema:identifier ?handle_identifier .
 ?handle_identifier a <http://schema.org/PropertyValue> .
 ?handle_identifier <http://schema.org/propertyID> "handle" .
 ?handle_identifier <http://schema.org/value> ?handle .

 # internetarchive
 ?item schema:identifier ?internetarchive_identifier .
 ?internetarchive_identifier a <http://schema.org/PropertyValue> .
 ?internetarchive_identifier <http://schema.org/propertyID> "internetarchive" .
 ?internetarchive_identifier <http://schema.org/value> ?internetarchive .
 
 # IPNI author
 ?item schema:identifier ?ipni_author_identifier .
 ?ipni_author_identifier a <http://schema.org/PropertyValue> .
 ?ipni_author_identifier <http://schema.org/propertyID> "ipni_author" .
 ?ipni_author_identifier <http://schema.org/value> ?ipni_author .
 

 # jstor
 ?item schema:identifier ?jstor_identifier .
 ?jstor_identifier a <http://schema.org/PropertyValue> .
 ?jstor_identifier <http://schema.org/propertyID> "jstor" .
 ?jstor_identifier <http://schema.org/value> ?jstor .
 
 # ORCID
 ?item schema:identifier ?orcid_identifier .
 ?orcid_identifier a <http://schema.org/PropertyValue> .
 ?orcid_identifier <http://schema.org/propertyID> "orcid" .
 ?orcid_identifier <http://schema.org/value> ?orcid .
 
 # pmc
 ?item schema:identifier ?pmc_identifier .
 ?pmc_identifier a <http://schema.org/PropertyValue> .
 ?pmc_identifier <http://schema.org/propertyID> "pmc" .
 ?pmc_identifier <http://schema.org/value> ?pmc .

 # pmid
 ?item schema:identifier ?pmid_identifier .
 ?pmid_identifier a <http://schema.org/PropertyValue> .
 ?pmid_identifier <http://schema.org/propertyID> "pmid" .
 ?pmid_identifier <http://schema.org/value> ?pmid .
 
 # ResearchGate
 ?item schema:identifier ?rg_author_identifier .
 ?rg_author_identifier a <http://schema.org/PropertyValue> .
 ?rg_author_identifier <http://schema.org/propertyID> "researchgate author" .
 ?rg_author_identifier <http://schema.org/value> ?rg_author .
 
 # Twitter
 ?item schema:identifier ?twitter_identifier .
 ?twitter_identifier a <http://schema.org/PropertyValue> .
 ?twitter_identifier <http://schema.org/propertyID> "twitter" .
 ?twitter_identifier <http://schema.org/value> ?twitter .

 # VIAF
 ?item schema:identifier ?viaf_identifier .
 ?viaf_identifier a <http://schema.org/PropertyValue> .
 ?viaf_identifier <http://schema.org/propertyID> "viaf" .
 ?viaf_identifier <http://schema.org/value> ?viaf .
 

 # zoobank author
 ?item schema:identifier ?zoobank_author_identifier .
 ?zoobank_author_identifier a <http://schema.org/PropertyValue> .
 ?zoobank_author_identifier <http://schema.org/propertyID> "zoobank_author" .
 ?zoobank_author_identifier <http://schema.org/value> ?zoobank_author_uuid .


 # zoobank publication
 ?item schema:identifier ?zoobank_pub_identifier .
 ?zoobank_pub_identifier a <http://schema.org/PropertyValue> .
 ?zoobank_pub_identifier <http://schema.org/propertyID> "zoobank_pub" .
 ?zoobank_pub_identifier <http://schema.org/value> ?zoobank_pub_uuid .

 # bloodhound
 ?item schema:identifier ?bloodhound_identifier .
 ?bloodhound_identifier a <http://schema.org/PropertyValue> .
 ?bloodhound_identifier <http://schema.org/propertyID> "bloodhound" .
 ?bloodhound_identifier <http://schema.org/value> ?bloodhound .

 # twitter
 ?item schema:identifier ?twitter_identifier .
 ?twitter_identifier a <http://schema.org/PropertyValue> .
 ?twitter_identifier <http://schema.org/propertyID> "twitter" .
 ?twitter_identifier <http://schema.org/value> ?twitter .

 # flickr
 ?item schema:identifier ?flickr_identifier .
 ?flickr_identifier a <http://schema.org/PropertyValue> .
 ?flickr_identifier <http://schema.org/propertyID> "flickr" .
 ?flickr_identifier <http://schema.org/value> ?flickr .

 # taxa
 # GBIF
 ?item schema:identifier ?gbif_identifier .
 ?gbif_identifier a <http://schema.org/PropertyValue> .
 ?gbif_identifier <http://schema.org/propertyID> "gbif" .
 ?gbif_identifier <http://schema.org/value> ?gbif .
 
 ?item schema:sameAs ?gbif_url .
 
 # EOL
 ?item schema:identifier ?eol_identifier .
 ?eol_identifier a <http://schema.org/PropertyValue> .
 ?eol_identifier <http://schema.org/propertyID> "eol" .
 ?eol_identifier <http://schema.org/value> ?eol .
 
 ?item schema:sameAs ?eol_url .

 # NCBI
 ?item schema:identifier ?ncbi_identifier .
 ?ncbi_identifier a <http://schema.org/PropertyValue> .
 ?ncbi_identifier <http://schema.org/propertyID> "ncbi" .
 ?ncbi_identifier <http://schema.org/value> ?ncbi .
 
 ?item schema:sameAs ?ncbi_url .
 
 
  # worms
 ?item schema:identifier ?worms_identifier .
 ?worms_identifier a <http://schema.org/PropertyValue> .
 ?worms_identifier <http://schema.org/propertyID> "worms" .
 ?worms_identifier <http://schema.org/value> ?worms .

 # wsc
 ?item schema:identifier ?wsc_identifier .
 ?wsc_identifier a <http://schema.org/PropertyValue> .
 ?wsc_identifier <http://schema.org/propertyID> "wsc" .
 ?wsc_identifier <http://schema.org/value> ?wsc .

 # ipni_name
 ?item schema:identifier ?ipni_name_identifier .
 ?ipni_name_identifier a <http://schema.org/PropertyValue> .
 ?ipni_name_identifier <http://schema.org/propertyID> "ipni_name" .
 ?ipni_name_identifier <http://schema.org/value> ?ipni_name .

 # zoobank_name
 ?item schema:identifier ?zoobank_name_identifier .
 ?zoobank_name_identifier a <http://schema.org/PropertyValue> .
 ?zoobank_name_identifier <http://schema.org/propertyID> "zoobank_name" .
 ?zoobank_name_identifier <http://schema.org/value> ?zoobank_name .

 # fossilworks
 ?item schema:identifier ?fossilworks_identifier .
 ?fossilworks_identifier a <http://schema.org/PropertyValue> .
 ?fossilworks_identifier <http://schema.org/propertyID> "fossilworks" .
 ?fossilworks_identifier <http://schema.org/value> ?fossilworks .

 # irmng
 ?item schema:identifier ?irmng_identifier .
 ?irmng_identifier a <http://schema.org/PropertyValue> .
 ?irmng_identifier <http://schema.org/propertyID> "irmng" .
 ?irmng_identifier <http://schema.org/value> ?irmng .


 
# subjects
 ?item schema:about ?subject .
 
# licensing
 ?item schema:license ?license_url .
 
 # taxon
 ?item schema:hasMap ?map .
 ?item schema:scientificName ?scientificName .
 ?item schema:taxonRank ?taxonRank .
 
 ?item schema:parentTaxon ?parentTaxon .
 ?parentTaxon schema:scientificName ?parentTaxonName .

  # periodical
  ?item schema:issn ?issn .
  ?item schema:startDate ?startDate .
  ?item schema:endDate ?endDate .
  ?item schema:logo ?logo .
  
  # publisher
  ?item schema:publisher ?publisher .
  ?publisher schema:name ?publisher_name .
  
  # Wikis
  ?item schema:sameAs ?en_wikipedia .
  ?item schema:sameAs ?species_wiki .
  

}
WHERE
{
   VALUES ?item { wd:' . $qid . ' }
  
  #?item ?p ?o .
  
  ?item wdt:P31 ?type .
  
  OPTIONAL {
   ?item wdt:P1476 ?title .
  }    
  
  # Some entities such as Q21337383 have lots of labels and this can cause queries to take too long
  OPTIONAL {
   ?item rdfs:label ?label .
   FILTER (lang(?label) = "en")
  }    
  
  OPTIONAL {
   ?item wdt:P18 ?image .
  }    
  
  
  # authors
 OPTIONAL {
   {
   ?item p:P2093 ?author .
    ?author ps:P2093 ?author_name .
    ?author pq:P1545 ?author_order. 
     
   }
   UNION
   {
     ?item p:P50 ?statement .
     OPTIONAL
     {
         ?statement pq:P1545 ?author_order. 
     }
     ?statement ps:P50 ?author. 
     ?author rdfs:label ?author_name .  
     FILTER (lang(?author_name) = "en")

       
     OPTIONAL
     {
       ?author wdt:P496 ?orcid .
  		 BIND( IRI (CONCAT (STR(?author), "#orcid")) as ?orcid_author_identifier)
     }
    }
  }    
    
  
  # container
  OPTIONAL {
   ?item wdt:P1433 ?container .
   ?container wdt:P1476 ?container_title .
   OPTIONAL {
     ?container wdt:P236 ?issn .
    }    
  }
  
  # date
   OPTIONAL {
   ?item wdt:P577 ?date .
   BIND(STR(?date) as ?datePublished) 
  }
  
  # people -------------------------------------------------------------------------------
  
  OPTIONAL {
   ?item schema:description ?description .
   # filter languages otherwise we can be inundated
  FILTER(
       LANG(?description) = "en" 
  	|| LANG(?description) = "fr" 
  	|| LANG(?description) = "de" 
  	|| LANG(?description) = "es" 
  	|| LANG(?description) = "zh"
  	)
   }  
  
   OPTIONAL {
   ?item skos:altLabel ?alternateName .
  # filter languages otherwise we can be inundated
  FILTER(
       LANG(?alternateName) = "en" 
  	|| LANG(?alternateName) = "fr" 
  	|| LANG(?alternateName) = "de" 
  	|| LANG(?alternateName) = "es" 
  	|| LANG(?alternateName) = "zh"
  	)   
   }   
  
  OPTIONAL {
   ?item wdt:P569 ?date_of_birth .
   BIND(STR(?date_of_birth) as ?birthDate) 
  }  
  
  OPTIONAL {
   ?item wdt:P570 ?date_of_death .
   BIND(STR(?date_of_death) as ?deathDate) 
  }     
  
  # scholarly articles -------------------------------------------------------------------
  OPTIONAL {
   ?item wdt:P478 ?volume .
  }   
  
  OPTIONAL {
   ?item wdt:P433 ?issue .
  }  
  
  OPTIONAL {
   ?item wdt:P304 ?page .
  }
  
  # taxa ---------------------------------------------------------------------------------
  OPTIONAL {
   ?item wdt:P181 ?map .
  }   
  
  OPTIONAL {
   ?item wdt:P225 ?scientificName .
  }  
  
  OPTIONAL {
   ?item wdt:P105 ?taxonRankProperty .
   ?taxonRankProperty rdfs:label ?taxonRank
   FILTER (LANG(?taxonRank) = "en" )
  } 
  
  OPTIONAL {
   ?item wdt:P171 ?parentTaxon .
   ?parentTaxon wdt:P225 ?parentTaxonName .
  }    
  
  # identifiers --------------------------------------------------------------------------
    
  
  # identifiers as property value pairs
  
 OPTIONAL {
   ?item wdt:P687 ?bhl_page .   
   BIND( IRI (CONCAT (STR(?item), "#bhl_page")) as ?bhl_page_identifier)
  }    
  
 OPTIONAL {
   ?item wdt:P5315 ?biostor .   
   BIND( IRI (CONCAT (STR(?item), "#biostor")) as ?biostor_identifier)
  }   
  
  # Make DOI lowercase
 OPTIONAL {
   ?item wdt:P356 ?doi_string .   
   BIND( IRI (CONCAT (STR(?item), "#doi")) as ?doi_identifier)
   BIND( LCASE(?doi_string) as ?doi)
  }    
  
 OPTIONAL {
   ?item wdt:P1184 ?handle .   
   BIND( IRI (CONCAT (STR(?item), "#handle")) as ?handle_identifier)
  } 

 OPTIONAL {
   ?item wdt:P888 ?jstor .   
   BIND( IRI (CONCAT (STR(?item), "#jstor")) as ?jstor_identifier)
  } 
  

  OPTIONAL {
   ?item wdt:P724 ?internetarchive .   
   BIND( IRI (CONCAT (STR(?item), "#internetarchive")) as ?internetarchive_identifier)
  }  
  
 OPTIONAL {
   ?item wdt:P586 ?ipni_author .   
   BIND( IRI (CONCAT (STR(?item), "#ipni_author")) as ?ipni_author_identifier)
  } 
  
 OPTIONAL {
   ?item wdt:P496 ?orcid .   
   BIND( IRI (CONCAT (STR(?item), "#orcid")) as ?orcid_identifier)
  }   
  
  OPTIONAL {
   ?item wdt:P698 ?pmid .   
   BIND( IRI (CONCAT (STR(?item), "#pmid")) as ?pmid_identifier)
  } 
  
  OPTIONAL {
   ?item wdt:P932 ?pmc .   
   BIND( IRI (CONCAT (STR(?item), "#pmc")) as ?pmc_identifier)
  }        
  
 OPTIONAL {
   ?item wdt:P2038 ?rg_author .   
   BIND( IRI (CONCAT (STR(?item), "#rg_author")) as ?rg_author_identifier)
  }   
  
OPTIONAL {
   ?item wdt:P2002 ?twitter .   
   BIND( IRI (CONCAT (STR(?item), "#twitter")) as ?twitter_identifier)
  }    
  
 OPTIONAL {
   ?item wdt:P214 ?viaf .   
   BIND( IRI (CONCAT (STR(?item), "#viaf")) as ?viaf_identifier)
  }   
  

 OPTIONAL {
   ?item wdt:P2006 ?zoobank_author_uuid .   
   BIND( IRI (CONCAT (STR(?item), "#zoobank_author")) as ?zoobank_author_identifier)
  }   

  
 OPTIONAL {
   ?item wdt:P2007 ?zoobank_pub_uuid .   
   BIND( IRI (CONCAT (STR(?item), "#zoobank_pub")) as ?zoobank_pub_identifier)
  }  
  
 OPTIONAL {
   ?item wdt:P6944 ?bloodhound .   
   BIND( IRI (CONCAT (STR(?item), "#bloodhound")) as ?bloodhound_identifier)
  }   
  
 OPTIONAL {
   ?item wdt:P2002 ?twitter .   
   BIND( IRI (CONCAT (STR(?item), "#twitter")) as ?twitter_identifier)
  }   
  
 OPTIONAL {
   ?item wdt:P3267 ?flickr .   
   BIND( IRI (CONCAT (STR(?item), "#flickr")) as ?flickr_identifier)
  }        
  
  # taxon identifiers
 OPTIONAL {
   ?item wdt:P846 ?gbif .   
   BIND( IRI (CONCAT (STR(?item), "#gbif")) as ?gbif_identifier)
  BIND( IRI (CONCAT ("https://www.gbif.org/species/", ?gbif)) as ?gbif_url)
  }
   
 OPTIONAL {
   ?item wdt:P830 ?eol .   
   BIND( IRI (CONCAT (STR(?item), "#eol")) as ?eol_identifier)
   BIND( IRI (CONCAT ("https://eol.org/pages/", ?eol)) as ?eol_url)
  }  
  
 OPTIONAL {
   ?item wdt:P685 ?ncbi .   
   BIND( IRI (CONCAT (STR(?item), "#ncbi")) as ?ncbi_identifier)
   BIND( IRI (CONCAT ("https://www.ncbi.nlm.nih.gov/Taxonomy/wwwtax.cgi%3Fmode%3DInfo%26id=", ?ncbi)) as ?ncbi_url)
  }       

 OPTIONAL {
   ?item wdt:P850 ?worms .   
   BIND( IRI (CONCAT (STR(?item), "#worms")) as ?worms_identifier)
  }  
       
 OPTIONAL {
   ?item wdt:P3288 ?wsc .   
   BIND( IRI (CONCAT (STR(?item), "#wsc")) as ?wsc_identifier)
  }  

 OPTIONAL {
   ?item wdt:P961 ?ipni_name .   
   BIND( IRI (CONCAT (STR(?item), "#ipni_name")) as ?ipni_name_identifier)
  }   
          
 OPTIONAL {
   ?item wdt:P1746 ?zoobank_name .   
   BIND( IRI (CONCAT (STR(?item), "#zoobank_name")) as ?zoobank_name_identifier)
  } 
  
 OPTIONAL {
   ?item wdt:P842 ?fossilworks .   
   BIND( IRI (CONCAT (STR(?item), "#fossilworks")) as ?fossilworks_identifier)
  } 
  
 OPTIONAL {
   ?item wdt:P5055 ?irmng .   
   BIND( IRI (CONCAT (STR(?item), "#irmng")) as ?irmng_identifier)
  }   
    
  
  # license
  OPTIONAL {
   ?item wdt:P275 ?license .  
   ?license wdt:P856 ?license_url .  
  }   
  
    # periodical
  OPTIONAL {
   ?item wdt:P236 ?issn .  
  }   
  OPTIONAL {
   ?item wdt:P154 ?logo .  
  }   
  # inception
  OPTIONAL {
   ?item wdt:P571 ?startDateValue . 
   BIND(STR(?startDateValue) as ?startDate)  
  }   
  # dissolved, abolished or demolished
  OPTIONAL {
   ?item wdt:P576 ?endDateValue .  
   BIND(STR(?endDateValue) as ?endDate) 
  }   
  
  OPTIONAL {
   ?item wdt:P123 ?publisher .  
   ?publisher rdfs:label  ?publisher_name .
   # filter languages otherwise we can be inundated
  FILTER(
       LANG(?publisher_name) = "en" 
  	|| LANG(?publisher_name) = "fr" 
  	|| LANG(?publisher_name) = "de" 
  	|| LANG(?publisher_name) = "es" 
  	|| LANG(?publisher_name) = "zh"
  	)
  }  
    
 OPTIONAL {
   ?item wdt:P856 ?url .  
  }     
  
  # Wiki projects
  
 OPTIONAL {
   ?en_wikipedia schema:about ?item; 
   	schema:isPartOf <https://en.wikipedia.org/>;
  }  
     
 OPTIONAL {
   ?species_wiki schema:about ?item; 
   	schema:isPartOf <https://species.wikimedia.org/>;
   
  } 
}   
';
	
	// Get item
	$url = 'https://query.wikidata.org/bigdata/namespace/wdq/sparql?query=' . urlencode($sparql);
	
	if (0)
	{
		$json = get(
			$config['sparql_endpoint']. '?query=' . urlencode($sparql), 
			'application/ld+json'
		);
	}
	else
	{
		$json = post(
			$config['sparql_endpoint'], 
			'application/ld+json',
			'query=' . $sparql
		);
	}	
		
	/*
	//$json = get($url, 'application/ld+json');
	$json = post(
		'https://query.wikidata.org/bigdata/namespace/wdq/sparql', 
		'application/ld+json',
		'query=' . $sparql
		);
		*/
	
	if (0)
	{
		echo '<pre>';
		echo $json;
		echo '</pre>';
	}
	
	// Frame the JSON-LD to make it easier to parse
	$doc = json_decode($json);
		
	$context = (object)array(
		'@vocab' 	 	=> 'http://schema.org/'			
		//'bibo' 			=> 'http://purl.org/ontology/bibo/',			
		//'identifiers' 	=> 'https://registry.identifiers.org/registry/'
		//'wd' 			=> 'http://www.wikidata.org/entity/'
	);
		
	// author is always an array
	$author = new stdclass;
	$author->{'@id'} = "author";
	//$author->{'@author'} = "@set";
	$author->{'@author'} = "@list"; 

	$context->{'author'} = $author;

	// ISSN is always an array
	$issn = new stdclass;
	$issn->{'@id'} = "issn";
	$issn->{'@container'} = "@set";

	$context->{'issn'} = $issn;
	
	// identifier
	$identifier = new stdclass;
	$identifier->{'@id'} = "identifier";
	$identifier->{'@container'} = "@set";
	
	$context->{'identifier'} = $identifier;

	// ISSN
	$issn = new stdclass;
	$issn->{'@id'} = "issn";
	$issn->{'@container'} = "@set";
	
	$context->{'issn'} = $issn;

	
	// about
	$about = new stdclass;
	$about->{'@id'} = "about";
	$about->{'@container'} = "@set";

	$context->{'about'} = $about;
	
	// image
	$image = new stdclass;
	$image->{'@id'} = "image";
	$image->{'@type'} = "@id";
	
	$context->{'image'} = $image;
	
	// license
	$license = new stdclass;
	$license->{'@id'} = "license";
	$license->{'@type'} = "@id";
	
	$context->{'license'} = $license;
	

	// map
	$hasMap = new stdclass;
	$hasMap->{'@id'} = "hasMap";
	$hasMap->{'@type'} = "@id";
	
	$context->{'hasMap'} = $hasMap;

	// sameAs
	$sameas = new stdclass;
	$sameas->{'@id'} = "sameAs";
	$sameas->{'@type'} = "@id";
	
	$context->{'sameAs'} = $sameas;
	
	// logo
	$logo = new stdclass;
	$logo->{'@id'} = "logo";
	$logo->{'@type'} = "@id";
	
	$context->{'logo'} = $logo;
	
	

	// Find work type
	$n = count($doc);
	$type = '';
	$i = 0;
	while ($i < $n && $type == '')
	{
		if ($doc[$i]->{'@id'} == $uri)
		{
			$type =  $doc[$i]->{'@type'}[0];
		}
		$i++;
	}


	if (0)
	{
		$data = jsonld_compact($doc, $context);
	}
	else
	{

		$frame = (object)array(
				'@context' => $context,
				'@type' => $type
			);
		
		$data = jsonld_frame($doc, $frame);
		
		/*
		echo '<pre>';
		print_r($data);
		echo '</pre>';
		*/
		
		
		// OK, RDF doesn't have a notion of order,
		// and for this app I want authors in the correct order. So, SPARQL stores 
		// order in "schema:positoon" so we use that to build an ordered array.
		
		if (isset($data->{'@graph'}[0]->author))
		{
			$author_list = array();
			
			if (is_object($data->{'@graph'}[0]->author))
			{
				// one author
				unset($author->position);
				$author_list[] = $data->{'@graph'}[0]->author;			
			}
			else
			{
				// array of authors
				foreach ($data->{'@graph'}[0]->author as $author_item)
				{
					if (isset($author_item->position))
					{
						$index = (Integer)$author_item->position;
						unset($author_item->position);
						$author_list[$index] = $author_item;
					}
			
				}
				ksort($author_list, SORT_NUMERIC);
			}
	
			$data->{'@graph'}[0]->author = array_values($author_list);
		}
		
	}
	
	if ($debug)
	{
		echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";	
	}
	
	return $data;
}

//----------------------------------------------------------------------------------------
// Item as JSON-LD object
function item_to_csl($data, $debug = true)
{
	
	$id = $data->{'@graph'}[0]->{'@id'};
	$qid = str_replace('http://www.wikidata.org/entity/', '', $id);
	
	$type = $data->{'@graph'}[0]->{'@type'};
	
	
	// convert to CSL-JSON

	$citeproc_obj = array();
	
	$citeproc_obj['type']['id'] = $qid;

	// classify the work type
	switch ($type)
	{
		case 'http://www.wikidata.org/entity/Q13442814':
			$citeproc_obj['type'] = 'article-journal';
			break;
		
		default:
			break;		
	}

	// set default language
	$default_language = 'en';

	foreach ($data->{'@graph'}[0] as $k => $v )
	{
		switch ($k)
		{
	
			# title
			case 'name':
				if (is_array($v))
				{
					$title = '';
					if (!isset($citeproc_obj['multi']))
					{
						$citeproc_obj['multi'] = new stdclass;
						$citeproc_obj['multi']->_key = new stdclass;							
					}
					$citeproc_obj['multi']->_key->title = new stdclass;
					foreach ($v as $literal)
					{
						if ($title == '')
						{
							$title = $literal->{'@value'};
						}
					
						if ($literal->{'@language'} == $default_language)
						{
							$title = $literal->{'@value'};							
						}
					
						$citeproc_obj['multi']->_key->title->{$literal->{'@language'}} = $literal->{'@value'};
					}
				
					$citeproc_obj['title'] = $title;
				}
				else
				{
					$citeproc_obj['title'] = $v->{'@value'};
				}					
				break;
	
			# authors
			case 'author':
				$authors = array();
				
				$counter = 1;
			
				foreach ($v as $a)
				{
					if (isset($a->position))
					{
						$index = (Integer)$a->position;
					}
					else
					{
						$index = $counter;
					}
					$authors[$index] = new stdclass;
					
					if (is_object($a->name))
					{
						$authors[$index]->literal = $a->name->{'@value'};
					}
					else
					{
						$authors[$index]->literal = $a->name;
					}
					
					// ORCID?
					if (isset($a->{'identifiers:orcid'}))
					{
						$authors[$index]->ORCID = 'https://orcid.org/' . $a->{'identifiers:orcid'};
					}
					
					// Wikidata?
					if (preg_match('/https?:\/\/www.wikidata.org\/entity\/(?<id>Q\d+)/', $a->{'@id'}, $m))
					{
						$authors[$index]->WIKIDATA = $m['id'];
					}
					
					$counter++;
				}
			
				// Just want a simple array of author objects
				ksort($authors);
			
				$citeproc_obj['author'] = array_values($authors);
		
				break;
			
			# container
			case 'isPartOf':
				if (isset($v->issn))
				{
					$citeproc_obj['ISSN'] = $v->issn;
				}			
			
				if (is_array($v->name))
				{
					$title = '';
					if (!isset($citeproc_obj['multi']))
					{
						$citeproc_obj['multi'] = new stdclass;
						$citeproc_obj['multi']->_key = new stdclass;							
					}
					$citeproc_obj['multi']->_key->{'container-title'} = new stdclass;
					foreach ($v->name as $literal)
					{
						if ($title == '')
						{
							$title = $literal->{'@value'};
						}
					
						if ($literal->{'@language'} == $default_language)
						{
							$title = $literal->{'@value'};							
						}
					
						$citeproc_obj['multi']->_key->{'container-title'}->{$literal->{'@language'}} = $literal->{'@value'};
					}
				
					$citeproc_obj['container-title'] = $title;
				}
				else
				{
					$citeproc_obj['container-title'] = $v->name->{'@value'};
				}					
				break;					
	
			# date
			case 'datePublished':
				$v = preg_replace('/^\+/', '', $v);
				$v = preg_replace('/T.*$/', '', $v);
			
				$parts = explode('-', $v);
			
				$citeproc_obj['issued'] = new stdclass;
				$citeproc_obj['issued']->{'date-parts'} = array();
				$citeproc_obj['issued']->{'date-parts'}[0] = array();

				$citeproc_obj['issued']->{'date-parts'}[0][] = (Integer)$parts[0];

				if ($parts[1] != '00')
				{		
					$citeproc_obj['issued']->{'date-parts'}[0][] = (Integer)$parts[1];
				}

				if ($parts[2] != '00')
				{		
					$citeproc_obj['issued']->{'date-parts'}[0][] = (Integer)$parts[2];
				}
				break;
					
			# location
			case 'volumeNumber':
				$citeproc_obj['volume'] = $v;
				break;
			
			case 'issueNumber':
				$citeproc_obj['issue'] = $v;
				break;
			
			case 'pagination':
				$citeproc_obj['page'] = $v;
				break;

			# identifiers
			case 'identifiers:doi':
				$citeproc_obj['DOI'] = $v;
				break;

			case 'identifiers:pubmed':
				$citeproc_obj['PMID'] = $v;
				break;			

			case 'identifiers:pmc':
				$citeproc_obj['PMCID'] = $v;
				break;	
				
			case 'identifier':
				$citeproc_obj['alternative-id'] = array();
				foreach ($v as $identifier)
				{
					$citeproc_obj['alternative-id'][] = $identifier;
				}
				break;
				
			case 'about':
				$citeproc_obj['subject'] = array();
				foreach ($v as $about)
				{
					$citeproc_obj['subject'][] = $about->{'@value'};
				}
				break;
				
			
			# URL(s)	
		
			# PDF	
	
			default:
				break;
		}
	}


	return $citeproc_obj;


}		

//----------------------------------------------------------------------------------------
// CONSTRUCT a stream, by default return as JSON-LD
function sparql_construct_stream($sparql_endpoint, $query, $format='application/ld+json')
{
	if (1)
	{
		$response = get(
			$sparql_endpoint . '?query=' . urlencode($query), 
			$format,
			'query=' . $query
		);
	}
	else
	{
		$response = post(
			$sparql_endpoint, 
			$format,
			'query=' . $query
		);
	}
		
	//$json = get($url, 'application/ld+json');
	
		
	//echo $response;
	
	//echo $sparql_endpoint;
	//echo $query;

	
	$obj = json_decode($response);
	if (is_array($obj))
	{
		$doc = $obj;
		
		//echo '<pre>' . print_r($obj) . '<pre>';
		
		
		$context = (object)array(
			'@vocab' => 'http://schema.org/'	,
			'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',			
		);
		
			// dataFeedElement is always an array
			$dataFeedElement = new stdclass;
			$dataFeedElement->{'@id'} = "dataFeedElement";
			$dataFeedElement->{'@container'} = "@set";
			
			$context->{'dataFeedElement'} = $dataFeedElement;
			
	// identifier
	$identifier = new stdclass;
	$identifier->{'@id'} = "identifier";
	$identifier->{'@container'} = "@set";
	
	$context->{'identifier'} = $identifier;

	// image
	$image = new stdclass;
	$image->{'@id'} = "image";
	$image->{'@type'} = "@id";
	$context->{'image'} = $image;

	
	
		$frame = (object)array(
			'@context' => $context,
			'@type' => 'http://schema.org/DataFeed'
		);
			
		$data = jsonld_frame($doc, $frame);
	
		
		$response = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);		
	}
	

	return $response;
}


if (0)
{
	// works by author
	$query = 'PREFIX schema: <http://schema.org/>
	PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
	CONSTRUCT 
	{
		<http://example.rss>
		rdf:type schema:DataFeed;
		schema:name "Publications";
		schema:dataFeedElement ?item .

		?item 
			rdf:type schema:DataFeedItem;
			rdf:type ?item_type;
			schema:name ?title;
			schema:datePublished ?datePublished;
			schema:description ?description;					
	}
	WHERE
	{
	VALUES ?author { wd:Q19002284 }

	?item wdt:P50 ?author .
	?item wdt:P31 ?type .

	?item wdt:P1476 ?title .

	OPTIONAL {
	?item wdt:P577 ?date .
	BIND(STR(?date) as ?datePublished) 
	}  			
			}';
			
	// cited by 
	
			
			//echo $query;
	
	$feed = sparql_construct_stream(
		'https://query.wikidata.org/bigdata/namespace/wdq/sparql',
		$query);
	
	
	print_r($feed);

}



if (0)
{
	$qid = 'Q56097590';

	//$qid = 'Q47164672'; // Chinese

	$qid = 'Q21283951'; // DOI, PMID, PMC
	
	//$qid = 'Q19689597';
	
	// $qid = 'Q21189593';
	
	$qid = 'Q58677102';
	
	//$qid = 'Q19689597';
	
	$item = get_item($qid, true);
	
	
	
	$citeproc_obj = item_to_csl($item);  


	//print_r($citeproc_obj);

	$csljson = json_encode($citeproc_obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);	
	echo $csljson;


}

		
?>
