<?php

use Illuminate\Foundation\Inspiring;
use Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

\Artisan::command("recipe:delete {recipeId}",function($recipeId){
    $recipe = \App\Recipe::find($recipeId);
    $recipe->delete();
});

\Artisan::command("job:delete {jobId}",function($jobId){
    $job = \App\Job::find($jobId);
    $job->delete();
});

\Artisan::command("config:generate {path} {prefix} {host}",function($path,$prefix,$host){
    $file = fopen($path,"ab");
    $count = 0;
    $host = "http://$host/v1/kv/";
    echo $host . "\n";
    foreach($_ENV as $key => $value){
        if(trim($value) == null){
           continue;
       }
       if(substr($value,0,1) == '@'){
          $value = substr($value,1);
          echo "$key has @\n : new value $value";
       }
        //write the template
        fwrite($file,$key . '={{ key "' . $prefix . $key . "\"}}\n");
        echo "running:\n";
        $cmd = "curl -s --request PUT --data '$value' $host" . $prefix . $key;
        echo $cmd ."\n";
        $status = shell_exec($cmd);
        if($status){
            echo $status . "\n";
            $count++;
        }else{
            echo "Couldnt write $key : $value\n";
        }
    }
    echo "wrote: " . $count;
    fclose($file);
});

\Artisan::command("deleteFilters:expired",function(){
    
    $collabs = \App\Collaborate::with([])->whereNotNull('deleted_at')->where('state',\App\Collaborate::$state[2])->get();
    if($collabs->count()){
        $collabs->each(function($model){
            \App\Filter\Collaborate::removeModel($model->id);
        });
    }
    
    $jobs = \App\Job::with([])->whereNotNull('deleted_at')->where('state',\App\Job::$state[2])->get();
    if($jobs->count()){
        $jobs->each(function($model){
            \App\Filter\Job::removeModel($model->id);
        });
    }
    
    $recipes = \App\Recipe::with([])->whereNotNull('deleted_at')->get();
    if($recipes->count()){
        $recipes->each(function($model){
            \App\Filter\Recipe::removeModel($model->id);
        });
    }
    
    $profiles = \App\Profile::with([])->whereNotNull('deleted_at')->get();
    if($profiles->count()){
        $profiles->each(function($model){
            \App\Filter\Profile::removeModel($model->id);
        });
    }
    
    $companies = \App\Company::with([])->whereNotNull('deleted_at')->get();
    if($companies->count()){
        $companies->each(function($model){
            \App\Filter\Company::removeModel($model->id);
        });
    }
    
});

\Artisan::command("reopen:collab {id}",function($id){
    $status = \DB::table('collaborates')->where('id',$id)
        ->update(['state'=>\App\Collaborate::$state[0],'deleted_at'=>null,
            'expires_on'=>\Carbon\Carbon::now()->addMonth()->toDateTimeString()]);
    \App\Filter\Collaborate::addModel(\App\Collaborate::find($id));
    echo $status;
});

\Artisan::command("reopen:job {id}",function($id){
    $status = \DB::table('jobs')
        ->where('id',$id)->update(['state'=>\App\Job::$state[0],
            'deleted_at'=>null,'expires_on'=>Carbon::now()->addMonth()->toDateTimeString()]);
    
    \App\Filter\Job::addModel(\App\Job::find($id));
    echo $status;
});

\Artisan::command("expire:collab {id}",function($id){
    $status = \DB::table('collaborates')->where('id',$id)->update(['state'=>\App\Collaborate::$state[2]]);
    
    \App\Filter\Collaborate::removeModel($id);
    
    echo $status;
});

\Artisan::command("expire:job {id}",function($id){
    $status = \DB::table('jobs')->where('id',$id)->update(['state'=>\App\Job::$state[2]]);
    
    \App\Filter\Job::removeModel($id);
    echo $status;
});

\Artisan::command("inviteall",function(){
    $when = \Carbon\Carbon::createFromTime(10,00,00);

    \DB::table('newsletters')->where('created_at','>','2018-01-18')->orderBy('id')->chunk(50,function ($users) use ($when)
    {
        $users->each(function($user) use($when) {
            $email = $user->email;
            echo "Sending invite mail to " . $email . "\n";

            $mail = (new \App\Mail\Launch())->onQueue('emails');
            \Mail::to($email)->send($mail);
        });
    });
});

\Artisan::command("email:test {view} {emails}",function($view,$emails){
    $subject = "TEST";
    $emails = explode(",",$emails);
    foreach($emails as $email){
        $mail = (new \App\Mail\Test($view,$subject))->onQueue('emails');
        \Mail::to($email)->send($mail);
    }
});

\Artisan::command("sendCollabTest",function(){

    $when = \Carbon\Carbon::now();

    $count = 0;
    $users = \DB::table('users')->whereNull('deleted_at')->where('email','ashok@tagtaste.com')->get();
    foreach ($users as $user)
    {
        $count++;

        $email = $user->email;
        echo "Sending collab mail to " . $email . "\n";

        $mail = (new \App\Mail\CollabSuggestions())->onQueue('emails');
//        \Mail::to($email)->bcc('aman@tagtaste.com')->bcc('amitabh@tagtaste.com')->send($mail);
    };
    echo "\nsent $count mails";

});

\Artisan::command("sendCollab",function(){
    
    $count = 0;
    $users = \DB::table('users')->whereNull('deleted_at')->get();
    foreach ($users as $user)
    {
        $count++;

        $email = $user->email;
        echo "Sending collab mail to " . $email . "\n";

        $mail = (new \App\Mail\CollabSuggestions($user->name))->onQueue('emails');
        \Mail::to($email)->send($mail);
    };
    echo "\nsent $count mails";
});

\Artisan::command("fixFollowers",function(){
    $now = \Carbon\Carbon::now()->toDateTimeString();
    \DB::table("channels")->orderBy('channels.id')->join('profiles','profiles.id','=','channels.profile_id')
        ->whereNotNull("profiles.deleted_at")
        ->chunk(25,function($deletedProfileChannels) use (&$now) {
            $deletedProfileChannels->each(function($channel) use (&$now) {
                echo $channel->name . " ";
                echo \DB::table("subscribers")->where("channel_name",'like',$channel->name)->update(['deleted_at'=>$now]) . " ";
                echo \DB::table('subscribers')->where('profile_id','=',$channel->profile_id)->update(['deleted_at'=>$now]) . " ";
//                echo \DB::table("channels")->where('profile_id','=',$channel->profile_id)->update(['deleted_at'=>$now]) . "\n";
            });
    });

});

\Artisan::command("billFollow",function(){

    $profileIds = \App\Recipe\Profile::whereNull('deleted_at')->where('id','!=',2245)->get()->pluck('id');
    foreach ($profileIds as $profileId)
    {
        echo 'profile id is'.$profileId ."\n";
        $x = \Redis::sIsMember("followers:profile:".$profileId,2245);
        echo 'following is '.$x ."\n";
        if($x)
        {
            continue;
        }

        $channelOwner = App\Profile::find($profileId);
        if(!$channelOwner){
            throw new ModelNotFoundException();
        }
        $user = \App\Profile::where('id',2245)->first();
        $this->model = $user->subscribeNetworkOf($channelOwner);
        $id = $user->id;

        //profiles the logged in user is following
        \Redis::sAdd("following:profile:" . $id, $profileId);

        //profiles that are following $channelOwner
        \Redis::sAdd("followers:profile:" . $profileId, $id);

        echo 'profile id is'.$profileId ."\n";

        if(!$this->model){
            continue;
        }
        echo 'profile id is'.$profileId ."\n";

        event(new \App\Events\Actions\Follow($channelOwner, $user->profile));
    }

});

\Artisan::command("tagtasteFollow",function(){

    $profileIds = \App\Recipe\Profile::whereNull('deleted_at')->get()->pluck('id');
    foreach ($profileIds as $profileId)
    {
        echo 'profile id is'.$profileId ."\n";
        $x = \Redis::sIsMember("followers:company:111",$profileId);
        echo 'following is '.$x ."\n";
        if($x)
        {
            continue;
        }

        $channelOwner = App\Company::find(111);
        if(!$channelOwner){
            throw new ModelNotFoundException();
        }
        $user = \App\Profile::where('id',$profileId)->first();
        $this->model = $user->subscribeNetworkOf($channelOwner);
        $id = $user->id;

        //companies the logged in user is following
        \Redis::sAdd("following:profile:" . $profileId, "company.111");

        //profiles that are following $channelOwner
        \Redis::sAdd("followers:company:111", $profileId);

        echo 'profile id is'.$profileId ."\n";

        if(!$this->model){
            continue;
        }
        echo 'profile id is'.$profileId ."\n";

    }

});
\Artisan::command("tagtasteInsightFollow",function(){

    $profileIds = \App\Recipe\Profile::whereNull('deleted_at')->get()->pluck('id');
    foreach ($profileIds as $profileId)
    {
        echo 'profile id is'.$profileId ."\n";
        $x = \Redis::sIsMember("followers:company:137",$profileId);
        echo 'following is '.$x ."\n";
        if($x)
        {
            continue;
        }

        $channelOwner = App\Company::find(137);
        if(!$channelOwner){
            throw new ModelNotFoundException();
        }
        $user = \App\Profile::where('id',$profileId)->first();
        $this->model = $user->subscribeNetworkOf($channelOwner);
        $id = $user->id;

        //companies the logged in user is following
        \Redis::sAdd("following:profile:" . $profileId, "company.137");

        //profiles that are following $channelOwner
        \Redis::sAdd("followers:company:137", $profileId);

        echo 'profile id is'.$profileId ."\n";

        if(!$this->model){
            continue;
        }
        echo 'profile id is'.$profileId ."\n";

    }

});

\Artisan::command("iOS-App",function(){

    \App\User::with([])->whereNull('deleted_at')
        ->orderBy('id')->chunk(100,function($users) {
            $users->each(function($user){
                $email = $user->email;
                echo "Sending invite mail to " . $email . "\n";

                $mail = (new \App\Jobs\iOSAppLink($email,$user->name))->onQueue('emails');
                \Log::info('Queueing send invitation...');
                dispatch($mail);
            });
        });
});


