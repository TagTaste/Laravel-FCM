<?php

namespace App\Similar;

use App\Company as BaseCompany;

class Company extends BaseCompany
{
    //protected $visible = ['id'];
    
    public function similar($skip,$take)
    {
        /*
            select distinct companies.id from companies
            where companies.id not in (select distinct channels.company_id from channels
            join subscribers on subscribers.channel_name = channels.name
            where subscribers.channel_name like 'network.6' or subscribers.company_id = 6)
        */
        $distinctCompanies = \DB::table('companies')->selectRaw(\DB::raw('distinct companies.id'))
            ->whereRaw(
                \DB::raw(
                    'companies.id not in (select distinct channels.company_id from channels join subscribers on subscribers.channel_name = channels.name where subscribers.channel_name like \'company.network.' . $this->id .'\')'
                )
            )
            ->get();
        
        if($distinctCompanies->count()){
            $dist = $distinctCompanies->pluck('id')->toArray();
            return self::whereIn('id',$dist)->where('id','!=',$this->id)->whereNull('deleted_at')->skip($skip)
                ->take($take)
                ->get();
        }
        return false;
    }
}
