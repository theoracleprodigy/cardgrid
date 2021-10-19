<?php

namespace Drupal\cardgrid\Controller;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
* Class CustomRestController.
*/
class CardRestController extends ControllerBase {


 /**
 * Return the 10 most recently updated nodes in a formatted JSON response.
 *
 * @return \Symfony\Component\HttpFoundation\JsonResponse
 * The formatted JSON response.
 */
  public function getCardGrid() {
    // get url query paramaters make sure they exist

    if(!isset($_GET["rows"]) || !isset($_GET["columns"])) {
      // I wanted to create a function for the return value but Drupal... couldn't figure it out with the time
      //  $result= \Drupal::service(CardRestController::class)->cardResponse();
      $response_array['cardlist'] = array(
        'meta' => array(
            'sucess' => 'false',
            'cardCount' => 0,
            'uniqueCardCount' => 0,
          ),
        );


      $response = new CacheableJsonResponse($response_array);
      $response->addCacheableDependency($cache_metadata);

      return $response;
    }

    $rows = $_GET["rows"];
    $columns = $_GET["columns"];


    // test that the rows * columns are even
    if((($rows*$columns) % 2) == 1 || ($rows*$columns) > 52){
      //  $result= \Drupal::service(CardRestController::class)->cardResponse();
      $response_array['cardlist'] = array(
        'meta' => array(
            'sucess' => 'false',
            'cardCount' => 0,
            'uniqueCardCount' => 0,
          ),
        );


      $response = new CacheableJsonResponse($response_array);
      $response->addCacheableDependency($cache_metadata);

      return $response;
    }



    /**
    * all requirements complete in success return response
    */
    $cardListArray = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','x','y','z');
    $halfList = array_slice($cardListArray, 0, (($rows*$columns)/2));
    $shuffledArray = array_merge($halfList, $halfList);
    shuffle($shuffledArray);
    $thisCount = 0;

    $printArray = array();



    //take the total number of the shuffled list split by rows then loop.
    // the issue with this solution is if rows are greater than columns / or uneven could fix
    for($thisRow=0;$thisRow<$rows;$thisRow++){
        $printArray[$thisRow] = array();
        for($thisColumn=0;$thisColumn<(count($shuffledArray)/$rows);$thisColumn++){
            array_push($printArray[$thisRow],$shuffledArray[$thisCount]);
            $thisCount++;
        }
    }




    $response_array['cardlist'] = array(
      'meta' => array(
          'sucess' => 'true',
          'cardCount' => $rows*$columns,
          'uniqueCardCount' => (($rows*$columns)/2),
          'uniqueCards' => $halfList,
        ),
        'data' => array(
          'cards' => $printArray,
        ),
      );


    $response = new CacheableJsonResponse($response_array);
    $response->addCacheableDependency($cache_metadata);

    return $response;

  }


  public function cardResponse(){
    $response_array['cardlist'] = array(
      'meta' => array(
          'sucess' => 'false',
          'cardCount' => 0,
          'uniqueCardCount' => 0,
        ),
      );


    $response = new CacheableJsonResponse($response_array);
    $response->addCacheableDependency($cache_metadata);

    return $response;
  }
}
