<?php


namespace App\Traits;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\SearchClient;
use App\Jobs\StoreElasticModel;

trait HashtagFactory
{
    protected $time = null;
    public function createHashtag($hashtags, $modelName, $modelId,$updated = null,$created = null)
    {
        $hashtags = array_unique($hashtags);
         foreach($hashtags as $hashtag) {
             if(strlen($hashtag)<=51) {
                $hash = $this->hashtagExist(strtolower($hashtag));
            if(!$hash['hits']['total']) {
                $model = new \App\Hashtag();  
                $model->id =mt_rand(1000000000, 9999999999);          
                $model->tag=strtolower($hashtag);
                $model->public_use = [$modelName.'\''.$modelId];
                $model->updated= $updated == null ? Carbon::now()->timestamp : Carbon::parse($updated)->timestamp; 
                $model->created= $created == null ? Carbon::now()->timestamp : Carbon::parse($created)->timestamp;
            } else {
                $hashDocument = $hash['hits']['hits'][0]['_source'];
                $public_use = $hashDocument['public_use'];
                array_unshift($public_use,$modelName.'\''.$modelId);
                $hashDocument['public_use'] = $public_use;
                $hashDocument['updated'] = Carbon::now()->timestamp; 
                $model = new \App\Hashtag($hashDocument);
            }
            $job = (new StoreElasticModel($model));
            dispatch($job);
            // \App\Documents\Hashtag::create($model);
            // sleep(3);
             }
        }
    }
    public function deleteExistingHashtag($modelName, $modelId)
    {
        $document = $this->getDocumentContainingModel($modelName.'\''.$modelId);
        if($document['hits']['total']) {
            foreach($document['hits']['hits'] as $hit) {
                if(count($hit['_source']['public_use']) == 1) {
                    $model = new \App\Hashtag($hit['_source']);
                    \App\Documents\Hashtag::delete($model);
                }  else {
                    $doc = $hit['_source'];
                    $index = array_search($modelName.'\''.$modelId,$doc['public_use']);
                    unset($doc['public_use'][$index]);
                    $doc['public_use'] = array_values($doc['public_use']);
                    $model = new \App\Hashtag($doc);
                    $job = (new StoreElasticModel($model));
                    dispatch($job);
                    // \App\Documents\Hashtag::create($model);
                    // sleep(5);
                }
            }
        }
    }

    protected function hashtagExist($hashtag) {
        $params = [
            'index' => "api",
            'body' => [
                "from" => 0, "size" => 1000,
                'query' => [
                    'query_string' => [
                        'query' => $hashtag,
                        'fields' => ['tag']
                         ]
                    ]
                ]
            ];
            $params['type'] = 'hashtag';
        $client = SearchClient::get();
        $response = $client->search($params);
        return $response;
    }
        
    protected function getDocumentContainingModel($model)
    {
        $params = [
            'index' => "api",
            'body' => [
                "from" => 0, "size" => 1000,
                'query' => [
                    'query_string' => [
                        'query' => $model
                         ]
                    ]
            ]];
        $params['type'] = 'hashtag';
        $client = SearchClient::get();
        $response = $client->search($params);
        return $response;
    }
    
    public function trendingHashtags()
    {
        $removeFromTrending = 'abbey,brooks,abused,accidental,anal,creampie,orgasm,addison,rose,adriana,nicole,sage,adrianna,adult,porn,tube,tubes,you,youtube,african,agent,aladdin,alanah,rae,alaura,eden,albino,alektra,blue,aletta,ocean,alexa,may,alexis,amor,amore,breeze,love,silver,texas,alice,in,wonderland,alicia,angel,rhodes,tyler,alien,allie,sin,allysin,chaynes,amateur,allure,blowjob,bukkake,couple,cuckold,cumshots,gangbang,handjob,lesbian,milf,orgy,porno,sex,videos,swingers,teen,threesome,wife,amateurs,gone,wild,amatoriale,amatuer,amature,amazing,ass,deepthroat,tits,amazon,amber,lynn,michaels,rayne,american,pussy,amputee,amy,reid,ana,nova,accident,asian,beads,blonde,bondage,casting,compilation,cream,eating,cum,cumshot,destruction,dildo,extreme,fingering,fisting,fuck,fucking,gape,granny,hardcore,hd,hentai,interracial,lesbians,licking,massage,mature,pain,party,pov,punishment,queen,squirt,stretching,surprise,teens,torture,training,video,virgin,anastasia,christ,andi,anderson,android,anetta,keys,anette,dawn,dark,eyes,long,angelica,angelina,crow,valentine,animal,animated,anime,anita,ann,marie,anna,malle,annette,schwarz,annie,cruz,april,flowers,arab,cock,arabic,argentina,aria,giovanni,ariana,jollee,art,asa,akira,ashley,ashli,orion,ashlynn,brooke,asia,carrera,bbw,street,meat,to,mouth,xxx,aubrey,addams,audition,audrey,bitoni,hollander,aunt,aurora,jolie,snow,austin,kincaid,ava,devine,lauren,avena,lee,avy,scott,ayana,babe,babes,babysitter,backroom,couch,facials,bang,bus,my,bangbros,bangbros.com,bangbus,barbie,bathroom,batman,pissing,bdsm,beach,beautiful,beauty,dior,beeg,behind,the,scenes,belladonna,best,blow,job,ever,free,sites,beurette,bi,bianca,pureheart,big,asses,black,booty,dick,boobies,boobs,brother,butts,clit,cocks,dicks,natural,nipples,lips,tit,at,school,titties,bigboobs,biggest,bigtits,bikini,bisexual,bizarre,cunt,gang,gf,girl,milfs,blackmail,blacks,on,blondes,bleach,jobs,blowjobs,bobbi,starr,bollywood,boner,boss,brandy,talore,taylor,brazil,brazilian,brazzers,brea,bennett,bree,olsen,briana,banks,brianna,frost,bride,british,britney,spears,stevens,brittany,andrews,brittney,skye,broke,straight,boys,banner,haven,hun,hunter,and,sister,bruna,ferraz,brunette,brutal,brynn,bubble,butt,bulma,busty,russians,cam,camera,inside,vagina,can,he,score,candace,von,candy,manson,car,carly,parker,carmel,moore,carmella,bing,carmen,hayes,kinsley,luvana,carton,cartoon,network,cash,for,cassandra,cassie,young,catwoman,caught,masturbating,celeb,tapes,celebrity,nudes,tape,cfnm,charley,chase,charlie,laine,charlotte,stokely,charmane,star,chasey,lain,chat,chatroulette,chav,chayse,evans,cheat,cheating,cheerleader,cherokee,chicks,with,china,chinese,chloe,lamb,christina,bella,christmas,christy,canyon,chubby,chyanne,jacobs,cindy,crawford,hope,claire,dames,classic,classy,claudia,rossi,close,up,club,docking,hero,sounding,stuffing,sucking,cody,lane,college,dorm,fest,girls,rules,sluts,colombian,colombiana,comic,comics,condom,cory,everson,cosplay,cougar,courtney,cummz,simpson,crazy,angels,cleanup,thais,creampies,creamy,crempie,crissy,moran,crying,crystal,clear,control,down,throat,fiesta,fountain,shot,shots,swallow,swap,twice,cum+inside,cumpilation,cunnilingus,cute,cytheria,czech,daisy,dana,dearmond,dance,dancing,bear,dani,woodward,daniella,rush,danish,daphne,rosen,dare,daria,glover,daughter,dbz,dd,deauxma,deep,deepthroating,defloration,delilah,strong,delotta,brown,demon,desi,deutsch,devil,devon,dexter,diamond,foxxx,diaper,bike,dillan,dirty,latina,maids,masseur,talk,doctor,dog,dogging,doggy,doggystyle,domination,dominatrix,dominican,donkey,dont,me,dora,venter,invasion,double,penetration,vaginal,syndrome,downblouse,dp,dragon,ball,z,dragonball,drawn,dream,drinking,dripping,wet,drunk,fucked,dry,humping,dutch,dwarf,dyanna,dylan,ryder,eat,ebony,sexy,squirting,ecg,egypt,egyptian,eight,elbow,electro,elegant,elena,grimaldi,elf,ellen,saint,emma,(mae),heart,emo,encouragement,enema,england,english,dubbed,enormous,erotic,cartoons,erotica,women,escort,ethiopia,ethiopian,euro,eva,eve,lawrence,evelyn,lin,ex,girlfriend,revenge,exgf,exgirlfriend,exotic,explicit,exploited,babysitters,extra,gagging,face,facefuck,facesitting,facial,fairy,tail,fake,fakeagent,family,famous,toons,fantasy,fart,fat,father,faye,reagan,feet,female,ejaculation,friendly,masturbation,femaleagent,femdom,fetish,ffm,filipina,filipino,film,films,final,finnish,first,time,movies,fist,fitness,fleshlight,flexible,flick,shagwell,flower,tucci,foot,footjob,force,foursome,francaise,francesca,le,freaky,download,hot,internet,mobile,online,clips,games,stream,vids,websites,pornography,pron,streaming,freeballing,frei,french,friend,friends,mom,frottage,ftm,money,hard,machine,from,18,public,fuckfest,machines,full,movie,fun,funny,furry,fursuit,futanari,futanaria,gag,game,gangbanged,gaping,gauge,gay,gdp,geek,gen,padova,georgia,peach,german,ggw,ghana,ghetto,gia,paloma,gianna,giant,gigantic,gilf,gina,ginger,cumming,having,masterbating,next,door,action,do,hunting,stockings,peeing,scissoring,girlsdoporn,giselle,glamour,glasses,glory,hole,gloryhole,gloryholes,gold,gonzo,good,goth,gothic,grandma,grandpa,great,greek,group,guy,gym,gyno,exam,gypsy,hairy,cunts,haley,paige,halloween,hand,handjobs,hands,handsfree,hanjob,hanna,hilton,hannah,harper,happy,tugs,core,junky,harmony,(bliss),havana,passion,heather,hegre-art,3d,manga,tentacle,her,sweet,hermaphrodite,hidden,high,quality,hijab,hillary,hipster,hitomi,tanaka,holly,body,halston,wellin,hollywood,home,made,homemade,hood,hooker,horny,moms,horse,mean,indian,naked,hotel,hottest,housewife,how,make,a,huge,hulk,hogan,humiliation,i,teacher,have,know,that,icelandic,impregnation,crack,inari,vachs,incredible,india,summer,uncovered,actress,aunty,mms,maid,stars,pornstars,real,scandals,village,indie,indonesia,indonesian,inflatable,plug,innocent,insane,insertion,instruction,instructional,intense,interactive,interesting,internal,interview,ipad,iran,iranian,iraq,irish,isabel,ice,isabella,soprano,isis,israel,israeli,italian,italiana,J,jack,napier,off,jacking,jaclyn,case,jacuzzi,jada,fire,jail,jailbait,jamaica,jamaican,jamie,elle,jana,cova,jane,darling,janine,lindemulder,jap,japan,japanese,av,beauties,daddy,show,son,mother,nurse,schoolgirl,student,uncensored,jasmin,st.,jasmine,byrne,rouge,tame,jayden,jaymes,jayna,oso,jazmine,cashmere,jeanna,fine,jeans,jelena,jensen,jenaveve,jenna,haze,jameson,presley,jenni,jennifer,luv,stone,jenny,hendrix,jerk,instructions,jerking,jerkoff,jerky,jessi,summers,jessica,bangkok,drake,rabbit,jeune,jew,jewish,jill,kelly,jizz,jock,jogging,john,holmes,johnni,jr,carrington,juggalette,juggs,juicy,kacey,jordan,kagney,linn,karter,kama,sutra,kamasutra,kapri,styles,kardashian,karen,lancaume,karina,kay,kat,kathleen,kruz,katie,morgan,katja,kassin,katrina,kraven,katsumi,kayden,kross,kayla,kaylani,lei,kaylee,b,divine,kline,trump,wells,kenyan,keri,sable,windsor,kianna,kidnap,kidnapped,kids,kiki,daire,kim,kind,kink,kink.com,kinky,kinzie,kenner,kira,kener,kiss,kissing,kitchen,kitty,kobe,tai,korea,korean,webcam,kream,kristal,kristina,krystal,steal,kylie,ireland,lacey,duvalle,lacie,lactating,ladies,lady,lana,croft,lani,lanny,lap,lapdance,large,breasts,latest,latex,latin,latinas,latino,laura,lion,phoenix,leah,jaye,leanna,legal,leggings,lela,lelu,69,breastfeeding,cheerleaders,grannies,grinding,lovers,piss,seduce,seduction,sisters,slave,spanking,strap,strapon,tribbing,twins,wrestling,making,out,lesbo,lex,steele,lexi,belle,lexington,lez,lezbo,lezley,zen,lichelle,lick,lily,thai,lindsey,meadows,lingerie,lisa,lipps,sparxxx,little,lolita,london,longest,loni,loona,lux,loose,lorena,sanchez,lucie,theodorova,lucy,luna,luscious,lopez,macho,fucker,mackenzee,pierce,madison,ivy,malay,male,man,mandingo,maria,bellucci,ozawa,mariah,milano,mark,marquetta,jewel,marry,mary,anne,mason,storm,creep,parlor,penis,massagesex,massive,masterbation,maya,hills,mckenzie,miles,medical,voyeur,medieval,melissa,melrose,memphis,monroe,men,play,mercedez,messy,mexican,mia,bangg,micah,michelle,maylene,midget,mika,tan,miko,military,milk,milking,milky,millian,blu,mindy,main,vega,missionary,missy,misti,mistress,misty,mmf,model,mofos,fucks,mommy,loves,mompov,mone,talks,monica,mattos,mayhem,santhiago,sweetheart,monique,alexander,monster,curves,law,mr,marcus,multiple,mum,muscle,mushroom,music,muslim,mutual,wifes,mya,g,nichole,nadia,celebrities,housewives,celebs,news,twerking,twister,whores,workout,yoga,naomi,russell,naruto,nasty,natalia,natasha,nice,native,naughty,america,office,nautica,thorn,nazi,neighbor,neighbour,nerd,nessa,net,netvideogirls,new,graves,ray,sheridan,nigerian,night,nikita,denise,nikki,benz,n,nina,hartley,ninja,nipple,norway,norwegian,nubile,nubiles,nude,aerobics,redheads,nudist,nun,nuru,nvg,nylon,nympho,octomom,odd,oil,overload,oiled,oily,old,farts,tarts,people,woman,older,olivia,del,rio,omegle,one,piece,onion,oops,open,oral,queens,contractions,denial,orgies,oriental,outdoor,nudity,over,60,painful,pakistani,pamela,panties,panty,pee,pantyhose,paris,parody,passed,passionate,patricia,petite,paulina,james,pawg,peaches,pegging,penny,flame,perfect,perky,persia,decarlo,persian,peter,north,petra,phat,pick,pickup,pierre,woodman,pinay,pink,pinky,pinoy,pirates,playboy,please,pokemon,police,polish,pool,bloopers,pornstar,pregnant,priva,private,priya,rai,prostate,prostitute,disgrace,pickups,wank,publicagent,puerto,rican,puffy,puma,swede,punish,punk,pure,puremature,pump,quad,quadriplegic,quadruple,quarterback,quebec,quebecoise,queef,queefing,diva,sheba,queening,quick,bj,cummer,head,quickest,quickie,quicksand,quicky,quiet,quirky,quivering,rachel,roxxx,rare,rave,raven,riley,raw,rayveness,life,slut,realistic,reality,kings,rebeca,linares,rebecca,red,hair,redhead,redneck,redtube,regina,renae,renata,retro,Return,top,20,reverse,cowgirl,ricki,white,rico,ridiculous,riding,shy,rim,rimjob,rimming,rita,faltoyano,robot,rocco,siffredi,role,playing,roleplay,roman,romance,romanian,romantic,ron,jeremy,rough,round,roxy,deville,jezel,reynolds,rubbing,ruined,russian,rusty,trombone,ryan,conner,sabrine,maui,sadie,west,sahara,knight,sakura,sena,samantha,sammie,samoan,sandra,romain,sandy,sapphic,sara,jay,st,sarah,blake,twain,vandella,sasha,grey,knox,sativa,sauna,savannah,stern,scandal,scarlett,scene,scouse,screw,sean,secretary,self,sensual,submission,moves,pornos,positions,underwater,sg4ge,sharing,sharka,sharon,wi,shaved,shaving,shawna,lenee,sheila,shemale,short,your,shower,shyla,stylez,sienna,sierra,sinn,silvia,simone,simony,sindee,jennings,sinnamon,skinny,skyy,sleep,sleeping,sloppy,slow,roulette,small,smoking,soapy,soft,softcore,solo,some,ho,sondra,hall,sonia,sophia,castello,sophie,dee,moone,spanish,spank,spiderman,spring,break,spy,squirters,stacy,step,dad,stepdad,stephanie,cane,swift,stepmom,stickam,stocking,stormy,daniels,story,stoya,stranger,strip,stripper,striptease,sunny,sunrise,adams,sunshine,super,superhero,suzie,carina,swedish,tee,swinger,sybian,sydnee,capri,tabitha,taboo,taiwan,tall,tango,tanya,tarra,taryn,thomas,tarzan,tasia,tasteful,tatoo,tattoo,tawny,roberts,rain,cla,teagan,teanna,kai,tease,titans,teenage,teenager,teenporn,tera,patrick,terri,thailand,avengers,thick,thong,throbbing,tia,ling,sweets,tiana,tied,tiffany,holiday,hopkins,mynx,preston,tight,tiny,titfuck,titjob,titts,titty,toilet,tokyo,tomb,raider,tommy,tonights,too,toon,tori,tory,toy,train,tranny,tricked,trina,trinidad,trinity,trios,triple,tron,truth,or,tube8,tugjob,turk,turkish,twerk,twin,twistys,tyla,wynn,tyra,misoux,ugandan,ugly,americans,uk,flashers,ukraine,ukrainian,ultimate,surrender,umemaro,unbelievable,uncircumcised,uncle,uncontrollable,uncut,under,table,underground,underwear,underworld,undress,undressed,undressing,unexpected,uniform,university,unnatural,unreal,untouched,unused,pussies,unusual,unwanted,upper,floor,upside,upskirt,no,urban,urethra,urethral,urinal,using,vibrator,vampire,van,vanessa,leon,velicity,venezuela,veronica,l,vanoza,veronique,very,vicky,vett,vette,victoria,victorian,victorious,vietnam,vietnamese,vintage,violation,violent,vip,virginity,virtual,viv,vivian,schmitt,vivid,volleyball,wake,wasteland,watch,watching,wedding,weed,weird,welsh,werewolf,western,wetting,whipped,whitezilla,whitney,whore,wicked,bbc,breeding,bucket,flashing,share,wifey,wifeys,world,wives,work,wonder,worlds,wow,wowgirls,wrong,wwe,x,mas,xart,x-art,xhamster,xl,xmas,x-men,xnxx,xtube,xvideos,xxl,france,hindi,proposal,rated,xxxmas,pants,yorkshire,boy,fatties,harlots,hotties,parties,throats,youporn,yummy,mama,zelda,zimbabwe,zimbabwean,zombie,zumba,4r5e,5h1t,5hit,a55,anus,ar5e,arrse,arse,ass-fucker,assfucker,assfukka,asshole,assholes,asswhole,a_s_s,b!tch,b00bs,b17ch,b1tch,ballbag,balls,ballsack,bastard,beastial,beastiality,bellend,bestial,bestiality,bi+ch,biatch,bitch,bitcher,bitchers,bitches,bitchin,bitching,bloody,boiolas,bollock,bollok,boob,booobs,boooobs,booooobs,booooooobs,buceta,bugger,bum,bunny,butthole,buttmuch,buttplug,c0ck,c0cksucker,carpet,muncher,cawk,chink,cipa,cl1t,clitoris,clits,cnut,cock-sucker,cockface,cockhead,cockmunch,cockmuncher,cocksuck,cocksucked,cocksucker,cocksucking,cocksucks,cocksuka,cocksukka,cok,cokmuncher,coksucka,coon,cox,crap,cums,cunilingus,cunillingus,cuntlick,cuntlicker,cuntlicking,cyalis,cyberfuc,cyberfuck,cyberfucked,cyberfucker,cyberfuckers,cyberfucking,d1ck,damn,dickhead,dildos,dink,dinks,dirsa,dlck,dog-fucker,doggin,donkeyribber,doosh,duche,dyke,ejaculate,ejaculated,ejaculates,ejaculating,ejaculatings,ejakulate,f,u,c,k,e,r,f4nny,fag,fagging,faggitt,faggot,faggs,fagot,fagots,fags,fanny,fannyflaps,fannyfucker,fanyy,fatass,fcuk,fcuker,fcuking,feck,fecker,felching,fellate,fellatio,fingerfuck,fingerfucked,fingerfucker,fingerfuckers,fingerfucking,fingerfucks,fistfuck,fistfucked,fistfucker,fistfuckers,fistfucking,fistfuckings,fistfucks,flange,fook,fooker,fucka,fuckers,fuckhead,fuckheads,fuckin,fuckings,fuckingshitmotherfucker,fuckme,fuckwhit,fuckwit,fudge,packer,fudgepacker,fuk,fuker,fukker,fukkin,fuks,fukwhit,fukwit,fux,fux0r,f_u_c_k,gangbangs,gaylord,gaysex,goatse,God,god-dam,god-damned,goddamn,goddamned,hardcoresex,hell,heshe,hoar,hoare,hoer,homo,hore,horniest,hotsex,jack-off,jackoff,jerk-off,jism,jiz,jizm,kawk,knob,knobead,knobed,knobend,knobhead,knobjocky,knobjokey,kock,kondum,kondums,kum,kummer,kumming,kums,kunilingus,l3i+ch,l3itch,labia,lmfao,lust,lusting,m0f0,m0fo,m45terbate,ma5terb8,ma5terbate,masochist,master-bate,masterb8,masterbat*,masterbat3,masterbate,masterbations,masturbate,mo-fo,mof0,mofo,mothafuck,mothafucka,mothafuckas,mothafuckaz,mothafucked,mothafucker,mothafuckers,mothafuckin,mothafucking,mothafuckings,mothafucks,motherfuck,motherfucked,motherfucker,motherfuckers,motherfuckin,motherfucking,motherfuckings,motherfuckka,motherfucks,muff,mutha,muthafecker,muthafuckker,muther,mutherfucker,n1gga,n1gger,nigg3r,nigg4h,nigga,niggah,niggas,niggaz,nigger,niggers,nob,jokey,nobhead,nobjocky,nobjokey,numbnuts,nutsack,orgasim,orgasims,orgasms,p0rn,pawn,pecker,penisfucker,phonesex,phuck,phuk,phuked,phuking,phukked,phukking,phuks,phuq,pigfucker,pimpis,pissed,pisser,pissers,pisses,pissflaps,pissin,pissoff,poop,prick,pricks,pube,pusse,pussi,pussys,rectum,retard,rimjaw,s,hit,s.o.b.,sadist,schlong,screwing,scroat,scrote,scrotum,semen,sh!+,sh!t,sh1t,shag,shagger,shaggin,shagging,shi+,shit,shitdick,shite,shited,shitey,shitfuck,shitfull,shithead,shiting,shitings,shits,shitted,shitter,shitters,shitting,shittings,shitty,skank,smegma,smut,snatch,son-of-a-bitch,spac,spunk,s_h_i_t,t1tt1e5,t1tties,teets,teez,testical,testicle,titt,tittie5,tittiefucker,tittyfuck,tittywank,titwank,tosser,turd,tw4t,twat,twathead,twatty,twunt,twunter,v14gra,v1gra,viagra,vulva,w00se,wang,wanker,wanky,whoar,willies,willy,xrated';
        $timestampGt = Carbon::now()->subDays(7)->timestamp;
        $timestampLt = Carbon::now()->timestamp;
        $params = [
            'index' => "api",
            'body' => [
                "from" => 0, "size" => 20,
                    'query'=> [
                        'range' => [
                            'updated' => [
                                'gte' => $timestampGt,
                                'lte' => $timestampLt
                                ]
                            ],
                      ] ,  
                    'sort'=> [
                        ['updated'=>['order'=>'desc', 'unmapped_type' => 'long']]
                      ]
            ]];
        $params['type'] = 'hashtag';
        $client = SearchClient::get();
        $response = $client->search($params);
        if($response['hits']['total']== 0){
            return [];
        } else {
            $response = $response['hits']['hits'];
            $tag = [];
            $bannedWordsArray = explode(',',$removeFromTrending);
            foreach($response as $tags) {
                if(!in_array(trim($tags['_source']['tag'],' #'),$bannedWordsArray)) {
                    $tag[] = [
                        'tag'=>$tags['_source']['tag'],
                        'count'=>count($tags['_source']['public_use']),
                        'updated_at'=>Carbon::createFromTimestamp($tags['_source']['updated'])
                    ];
                }
            }
            return $tag;
        }
        
    }

    public function hashtagSuggestions($key) 
    {
        $key = str_replace('#','',$key);
        $params = [
            'index' => "api",
            'body' => [
                "from" => 0, "size" => 1000,
                'query'=>[
                    'query_string' => [
                        'query' => $key.'*',
                            'fields'=>['tag']
                         ]
                ],
                'suggest' => [
                    'my-suggestion-1'=> [
                            'text'=> $key.'*',
                            'term'=> [
                                 'field'=> 'tag'
                            ]
                    ]
                            ]
            ]];
            $params['type'] = 'hashtag';
            $client = SearchClient::get();
            $response = $client->search($params);
            $tag = [];
            if($response['hits']['total'] != 0){
                $response = $response['hits']['hits'];
                foreach($response as $tags) {
                $tag[] = [
                    'tag'=>$tags['_source']['tag']
                ];
            }   
            } else if(isset($response['suggest']['my-suggestion-1'][0])) {
                $suggestions = $response['suggest']['my-suggestion-1'][0]['options'];
                if(count($suggestions) != 0){
                    foreach($suggestions as $tags) {
                        $tag[] = [
                            'tag'=>'#'.$tags['text']
                        ];
                    }
                }
            } 
            return $tag;
    }

    public function getModelsForFeed($key)
    {
        $key = str_replace('#','',$key);
        $params = [
            'index' => "api",
            'body' => [
                "from" => 0, "size" => 1000,
                'query' => [
                    'query_string' => [
                        'query' => $key,
                            'fields'=>['tag']
                         ]
                    ]
            ]];
        $params['type'] = 'hashtag';
        $client = SearchClient::get();
        $response = $client->search($params);
        if($response['hits']['total'] == 0){
            return null;
        }
        $response = $response['hits']['hits'][0]['_source']['public_use'];
        return $response;
    }

}