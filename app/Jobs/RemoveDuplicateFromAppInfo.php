<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

use function GuzzleHttp\json_decode;

class RemoveDuplicateFromAppInfo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $idToStore = '';
        $idFromIos = self::getIdForIos(); 
        $idFromAndroid = self::getIdForAndroid();

        if(strlen($idFromAndroid) == 0 && strle($idFromIos) == 0){
          return;
        }else if(strlen($idFromIos) == 0){
          $idToStore = $idFromAndroid;
        }else if(strlen($idFromAndroid) == 0){
          $idToStore = $idFromIos;
        }else{
          $idToStore = $idFromIos.','.$idFromAndroid;
        }
        $idToStoreArr = explode(',',$idToStore);
        // delete all the token except these supplied ids.
        DB::table('app_info')->whereNotIn('id', $idToStoreArr)->delete();
        
        //delete all the tokens for simulator or emultor
        DB::table('app_info')->where('device_info->deviceType','like','%Simulator%')->orWhere('device_info->SERIAL','like','%EMULATOR%')->delete();
      }
    
    function getIdForIos(){
       $data = \DB::table("app_info")->select('id','device_info->identifierForVendor as deviceId')->groupBy('id','deviceId')->get();
       $decodedData = json_decode($data); 
       $idToStore = self::getIdsFromData($decodedData);
       return $idToStore;
    }
    
    function getIdForAndroid(){
      $data = \DB::table("app_info")->select('id','device_info->ID as deviceId')->groupBy('id','deviceId')->get();
      $decodedData = json_decode($data); 
      $idToStore = self::getIdsFromData($decodedData);
      return $idToStore;
    }

    function getIdsFromData($data){
      $newArray = array();
      foreach($data as $entity)
      {   
        if(!isset($newArray[$entity->deviceId])){
          $newArray[$entity->deviceId][] = $entity;  
        }else{
          $storedObj = $newArray[$entity->deviceId];
          if($entity->id > $storedObj[0]->id){
            unset($newArray[$entity->deviceId]);
            $newArray[$entity->deviceId][] = $entity; 
          }
        }
      }
      $idToStore = '';
      foreach($newArray as $element){
        $idToStore .= $element[0]->id.',';
      }
      if(strlen($idToStore) > 0){
        $idToStore = substr($idToStore, 0, strlen($idToStore) - 1);
      }
      return $idToStore;
    }

}
